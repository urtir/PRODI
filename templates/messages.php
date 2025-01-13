<?php
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "informatics_db");
$user_id = $_SESSION['user_id'];

// Get all conversations
$conversations_query = "
    SELECT DISTINCT 
        u.id,
        u.firstname,
        u.lastname,
        (SELECT message FROM private_messages 
         WHERE (sender_id = u.id AND receiver_id = ?) 
            OR (sender_id = ? AND receiver_id = u.id) 
         ORDER BY created_at DESC LIMIT 1) as last_message,
        (SELECT created_at FROM private_messages 
         WHERE (sender_id = u.id AND receiver_id = ?) 
            OR (sender_id = ? AND receiver_id = u.id) 
         ORDER BY created_at DESC LIMIT 1) as last_message_time
    FROM users u
    WHERE u.id IN (
        SELECT sender_id FROM private_messages WHERE receiver_id = ?
        UNION
        SELECT receiver_id FROM private_messages WHERE sender_id = ?
    )
    ORDER BY last_message_time DESC";

$stmt = $conn->prepare($conversations_query);
$stmt->bind_param("iiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
$stmt->execute();
$conversations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get messages for selected conversation
$selected_user = isset($_GET['user']) ? $_GET['user'] : null;
if ($selected_user) {
    $messages_query = "
        SELECT pm.*, u.firstname, u.lastname 
        FROM private_messages pm
        JOIN users u ON pm.sender_id = u.id
        WHERE (sender_id = ? AND receiver_id = ?) 
           OR (sender_id = ? AND receiver_id = ?)
        ORDER BY created_at ASC";
    
    $stmt = $conn->prepare($messages_query);
    $stmt->bind_param("iiii", $user_id, $selected_user, $selected_user, $user_id);
    $stmt->execute();
    $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<div class="container-fluid mt-5 pt-5">
    <div class="row">
        <!-- Contacts Sidebar -->
        <div class="col-md-4 col-lg-3 px-0 border-end">
            <div class="messages-sidebar">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Messages</h5>
                </div>
                <div class="contacts-list">
                    <?php foreach ($conversations as $conv): ?>
                        <a href="?user=<?php echo $conv['id']; ?>" 
                           class="contact-item p-3 d-flex align-items-center border-bottom text-decoration-none <?php echo ($selected_user == $conv['id']) ? 'active bg-light' : ''; ?>">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo htmlspecialchars($conv['firstname'] . ' ' . $conv['lastname']); ?></h6>
                                <p class="mb-0 small text-muted text-truncate"><?php echo htmlspecialchars($conv['last_message']); ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Add after sidebar header -->
        <div class="p-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Messages</h5>
                <button type="button" class="btn btn-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                    <i class="fa fa-edit"></i>
                </button>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-8 col-lg-9 px-0">
            <?php if ($selected_user): ?>
                <div class="chat-container d-flex flex-column h-100">
                    <!-- Chat Header -->
                    <div class="chat-header p-3 border-bottom">
                        <h6 class="mb-0">
                            <?php 
                            $selected_name = array_filter($conversations, function($conv) use ($selected_user) {
                                return $conv['id'] == $selected_user;
                            });
                            $selected_name = reset($selected_name);
                            echo htmlspecialchars($selected_name['firstname'] . ' ' . $selected_name['lastname']);
                            ?>
                        </h6>
                    </div>

                    <!-- Messages Area -->
                    <div class="chat-messages p-3 flex-grow-1 overflow-auto" style="height: calc(100vh - 300px);">
                        <?php foreach ($messages as $msg): ?>
                            <div class="message mb-3 <?php echo ($msg['sender_id'] == $user_id) ? 'sent' : 'received'; ?>">
                                <div class="message-content p-3 rounded">
                                    <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                                    <small class="d-block text-muted mt-1">
                                        <?php echo date('g:i A', strtotime($msg['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Message Input -->
                    <div class="chat-input p-3 border-top">
                        <form method="POST" action="send_message.php" class="d-flex">
                            <input type="hidden" name="receiver_id" value="<?php echo $selected_user; ?>">
                            <input type="text" name="message" class="form-control me-2" placeholder="Type a message..." required>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="h-100 d-flex align-items-center justify-content-center">
                    <p class="text-muted">Select a conversation to start messaging</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add before closing body tag -->
<div class="modal fade" id="newMessageModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="send_message.php" method="POST">
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label class="form-label">To:</label>
                                <select name="receiver_id" class="form-select" required>
                                    <option value="">Select recipient...</option>
                                    <?php
                                    $users = $conn->query("SELECT id, firstname, lastname FROM users WHERE id != $user_id ORDER BY firstname");
                                    while($user = $users->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $user['id']; ?>">
                                        <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Message:</label>
                                <textarea name="message" class="form-control" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<style>
.messages-sidebar {
    height: calc(100vh - 100px);
    overflow-y: auto;
}

.contact-item {
    color: #333;
    transition: background-color 0.2s;
}

.contact-item:hover {
    background-color: rgba(0,0,0,0.05);
}

.chat-messages {
    scrollbar-width: thin;
    padding: 20px;
}

.message {
    max-width: 70%;
    margin-bottom: 20px;
    clear: both;
}

.message.sent {
    float: right;
}

.message.received {
    float: left;
}

.message-content {
    padding: 12px 18px;
    border-radius: 18px;
    position: relative;
}

.message.sent .message-content {
    background-color: #3366CC;
    color: white;
    border-bottom-right-radius: 4px;
}

.message.received .message-content {
    background-color: #f1f1f1;
    color: black;
    border-bottom-left-radius: 4px;
}

.message.sent small {
    color: #ffffff;
    text-align: right;
}

.message.received small {
    color: rgba(255,255,255,0.8);
    text-align: left;
}

.chat-container {
    background: #fff;
    border-radius: 8px;
    height: calc(100vh - 100px);
}

.chat-input {
    background: #fff;
    border-top: 1px solid #eee;
    padding: 15px 20px;
}

.chat-input form {
    display: flex;
    gap: 10px;
}

.chat-input input {
    border-radius: 20px;
    padding: 8px 15px;
}

.chat-input button {
    border-radius: 20px;
    padding: 8px 20px;
}

.btn-primary.rounded-circle {
    width: 40px;
    height: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.modal-content {
    border-radius: 12px;
}

.modal-header {
    border-bottom: 1px solid #eee;
    padding: 15px 20px;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    border-top: 1px solid #eee;
    padding: 15px 20px;
}

.form-select {
    border-radius: 8px;
    padding: 10px;
}

.form-control {
    border-radius: 8px;
}
</style>

<?php include 'footer.php'; ?>