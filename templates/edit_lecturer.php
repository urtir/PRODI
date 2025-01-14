<?php
include 'header.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    $_SESSION['error_message'] = "Invalid lecturer ID!";
    header("Location: manage_lecturers.php");
    exit();
}

// Fetch lecturer data first
$stmt = $conn->prepare("SELECT * FROM lecturers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$lecturer = $stmt->get_result()->fetch_assoc();

if (!$lecturer) {
    $_SESSION['error_message'] = "Lecturer not found!";
    header("Location: manage_lecturers.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Debug POST data
        error_log("POST Data: " . print_r($_POST, true));
        
        // Basic fields to update
        $fields = [
            'nama', 'jenis_kelamin', 'perguruan_tinggi', 
            'program_studi', 'jabatan_fungsional', 
            'pendidikan_terakhir', 'status_ikatan_kerja', 
            'status_aktivitas'
        ];
        
        $update_data = [];
        $types = "";
        $values = [];

        // Process basic fields
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $update_data[] = "$field = ?";
                $types .= "s";
                $values[] = $_POST[$field];
            }
        }

        // Process JSON fields
        $json_fields = [
            'education' => 'riwayat_pendidikan',
            'teaching' => 'riwayat_mengajar',
            'research' => 'penelitian',
            'service' => 'pengabdian_masyarakat',
            'publication' => 'publikasi',
            'patent' => 'hki_paten'
        ];

        foreach ($json_fields as $post_key => $db_field) {
            if (isset($_POST[$post_key])) {
                error_log("Processing $post_key: " . print_r($_POST[$post_key], true));
                $json_data = array_values($_POST[$post_key]);
                $update_data[] = "$db_field = ?";
                $types .= "s";
                $values[] = json_encode($json_data);
            }
        }

        // Handle photo if uploaded
        if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
            $file = $_FILES['foto'];
            $foto = time() . '_' . basename($file['name']);
            $target = "../static/images/lecturers/" . $foto;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                if ($lecturer['foto'] && file_exists("../static/images/lecturers/" . $lecturer['foto'])) {
                    unlink("../static/images/lecturers/" . $lecturer['foto']);
                }
                $update_data[] = "foto = ?";
                $types .= "s";
                $values[] = $foto;
            }
        }

        // Add ID for WHERE clause
        $types .= "i";
        $values[] = $id;

        // Build and execute update query
        $sql = "UPDATE lecturers SET " . implode(", ", $update_data) . " WHERE id = ?";
        error_log("SQL Query: " . $sql);
        error_log("Values: " . print_r($values, true));
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param($types, ...$values);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Lecturer updated successfully!";
            header("Location: manage_lecturers.php");
            exit();
        } else {
            throw new Exception("No rows were updated");
        }

    } catch (Exception $e) {
        error_log("Error in edit_lecturer.php: " . $e->getMessage());
        $error_message = $e->getMessage();
    }
}


// Get lecturer ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// After catching any errors, fetch lecturer data
$stmt = $conn->prepare("SELECT * FROM lecturers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$lecturer = $stmt->get_result()->fetch_assoc();

if (!$lecturer) {
    $_SESSION['error_message'] = "Lecturer not found!";
    header("Location: manage_lecturers.php");
    exit();
}

// Define dropdown options
$jabatan_options = ['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'];
$pendidikan_options = ['S1', 'S2', 'S3'];
$status_kerja_options = ['Dosen Tetap', 'Dosen Tidak Tetap', 'Dosen Tamu'];






?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lecturer - Admin Dashboard</title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
        --success-color: #198754;
        --border-color: #dee2e6;
        --shadow-sm: 0 2px 4px rgba(0,0,0,.05);
        --shadow-md: 0 4px 6px rgba(0,0,0,.1);
    }

    body {
        background-color: #f8f9fa;
    }

    .page-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .section-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
    }

    .section-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-color);
        background: #f8f9fa;
    }

    .section-body {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: var(--secondary-color);
    }

    .btn-action {
        padding: 0.5rem 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .sticky-footer {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 1rem;
        border-top: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        z-index: 1000;
    }

    .guide-box {
        background: #e9ecef;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    </style>
</head>
<body>
    <div class="page-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="manage_lecturers.php">Lecturers</a></li>
                <li class="breadcrumb-item active">Edit Lecturer</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Edit Lecturer Profile</h2>
                <p class="text-muted">Update lecturer information and academic records</p>
            </div>
            <a href="manage_lecturers.php" class="btn btn-secondary btn-action">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Main Form -->
    <form method="POST" enctype="multipart/form-data" id="editLecturerForm">
    <!-- Personal Information Card -->
    <div class="section-card">
        <div class="section-header">
            <h4 class="mb-0">
                <i class="fas fa-user-circle me-2"></i>
                Personal Information
            </h4>
        </div>
        <div class="section-body">
            <div class="row g-4">
                <!-- Name & Gender -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama" class="form-label required">Full Name</label>
                        <input type="text" 
                               class="form-control" 
                               id="nama" 
                               name="nama" 
                               value="<?php echo htmlspecialchars($lecturer['nama']); ?>" 
                               required>
                        <div class="form-text">Enter the lecturer's complete name</div>
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin" class="form-label required">Gender</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            
                            <option value="Laki-laki" <?php echo $lecturer['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="Perempuan" <?php echo $lecturer['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="foto" class="form-label">Profile Photo</label>
                        <div class="d-flex align-items-center gap-3">
                            <?php if ($lecturer['foto']): ?>
                                <img src="../static/images/lecturers/<?php echo htmlspecialchars($lecturer['foto']); ?>" 
                                     alt="Current photo" 
                                     class="rounded-circle"
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <input type="file" 
                                       class="form-control" 
                                       id="foto" 
                                       name="foto" 
                                       accept="image/*">
                                <div class="form-text">
                                    Recommended: Square image, max 2MB (JPG, PNG) <br>
                                    <span style="opacity: 0.3">Made By Rizqullah I The None</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Details Card -->
<div class="section-card">
    <div class="section-header d-flex align-items-center">
        <i class="fas fa-university text-primary me-2"></i>
        <div>
            <h4 class="mb-0">Academic Details</h4>
            <small class="text-muted">University affiliation and academic position</small>
        </div>
    </div>
    
    <div class="section-body">
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label for="perguruan_tinggi" class="form-label">
                        University
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="perguruan_tinggi" 
                           name="perguruan_tinggi" 
                           value="<?php echo htmlspecialchars($lecturer['perguruan_tinggi']); ?>" 
                           required>
                    <div class="form-text">Current university or institution</div>
                </div>

                <div class="form-group">
                    <label for="program_studi" class="form-label">
                        Study Program
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="program_studi" 
                           name="program_studi" 
                           value="<?php echo htmlspecialchars($lecturer['program_studi']); ?>" 
                           required>
                    <div class="form-text">Department or study program</div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label for="pendidikan_terakhir" class="form-label">
                        Latest Education
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="pendidikan_terakhir" name="pendidikan_terakhir" required>
                        
                        <?php foreach ($pendidikan_options as $level): ?>
                            <option value="<?php echo $level; ?>" 
                                    <?php echo $lecturer['pendidikan_terakhir'] == $level ? 'selected' : ''; ?>>
                                <?php echo $level; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Highest education level achieved</div>
                </div>

                <div class="form-group">
                    <label for="jabatan_fungsional" class="form-label">
                        Functional Position
                        
                    </label>
                    <select class="form-select" id="jabatan_fungsional" name="jabatan_fungsional" required>
                        
                        <?php foreach ($jabatan_options as $position): ?>
                            <option value="<?php echo $position; ?>" 
                                    <?php echo $lecturer['jabatan_fungsional'] == $position ? 'selected' : ''; ?>>
                                <?php echo $position; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Current academic position</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Employment Status Card -->
<div class="section-card">
    <div class="section-header d-flex align-items-center">
        <i class="fas fa-briefcase text-primary me-2"></i>
        <div>
            <h4 class="mb-0">Employment Status</h4>
            <small class="text-muted">Current employment and activity status</small>
        </div>
    </div>
    
    <div class="section-body">
        <div class="row g-4">
            <!-- Employment Status -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status_ikatan_kerja" class="form-label">
                        Employment Type
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="status_ikatan_kerja" name="status_ikatan_kerja" required>
                        
                        <?php foreach ($status_kerja_options as $status): ?>
                            <option value="<?php echo $status; ?>" 
                                    <?php echo $lecturer['status_ikatan_kerja'] == $status ? 'selected' : ''; ?>>
                                <?php echo $status; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Current employment relationship with the institution</div>
                </div>
            </div>

            <!-- Activity Status -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status_aktivitas" class="form-label">
                        Activity Status
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="status_aktivitas" name="status_aktivitas" required>
                        
                        <option value="Aktif" <?php echo $lecturer['status_aktivitas'] == 'Aktif' ? 'selected' : ''; ?>>
                            Aktif
                        </option>
                        <option value="Tidak Aktif" <?php echo $lecturer['status_aktivitas'] == 'Tidak Aktif' ? 'selected' : ''; ?>>
                            Tidak Aktif
                        </option>
                    </select>
                    <div class="form-text">Current activity status in the institution</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Academic Records Card -->
<div class="section-card">
    <div class="section-header d-flex align-items-center">
        <i class="fas fa-book text-primary me-2"></i>
        <div>
            <h4 class="mb-0">Academic Records</h4>
            <small class="text-muted">Educational history and academic achievements</small>
        </div>
    </div>

    <!-- Guide Box -->
    <div class="guide-box mx-3 mt-3">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-info-circle text-primary me-2"></i>
            <strong>Quick Guide:</strong>
        </div>
        <ul class="mb-0 small">
            <li>Click "Add Entry" to create new records</li>
            <li>Fill all required fields marked with (*)</li>
            <li>Click trash icon to remove entries</li>
            <li>Changes are saved when you submit the form</li>
        </ul>
    </div>
    
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mx-3 mt-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#education">
                <i class="fas fa-graduation-cap me-1"></i> Education
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#teaching">
                <i class="fas fa-chalkboard-teacher me-1"></i> Teaching
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#research">
                <i class="fas fa-microscope me-1"></i> Research
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#service">
                <i class="fas fa-hands-helping me-1"></i> Service
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#publications">
                <i class="fas fa-file-alt me-1"></i> Publications
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#patents">
                <i class="fas fa-certificate me-1"></i> Patents
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content p-3">
        <!-- Education Tab -->
    <div class="tab-pane fade show active" id="education">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Education History</h5>
            <button type="button" class="btn btn-primary btn-sm" onclick="addRecord('education')">
                <i class="fas fa-plus me-1"></i> Add Education
            </button>
        </div>
        <div id="education-records" class="records-container">
            <!-- Dynamic education records -->
            <?php 
            $educationData = json_decode($lecturer['riwayat_pendidikan'], true) ?: [];
            foreach ($educationData as $index => $edu): 
            ?>
            <div class="record-card mb-3" id="education-<?php echo $index; ?>">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">University</label>
                                <input type="text" class="form-control" name="education[<?php echo $index; ?>][perguruan_tinggi]" 
                                       value="<?php echo htmlspecialchars($edu['perguruan_tinggi']); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Degree</label>
                                <input type="text" class="form-control" name="education[<?php echo $index; ?>][gelar]"
                                       value="<?php echo htmlspecialchars($edu['gelar']); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Level</label>
                                <select class="form-select" name="education[<?php echo $index; ?>][jenjang]">
                                    <option value="S1" <?php echo $edu['jenjang'] == 'S1' ? 'selected' : ''; ?>>S1</option>
                                    <option value="S2" <?php echo $edu['jenjang'] == 'S2' ? 'selected' : ''; ?>>S2</option>
                                    <option value="S3" <?php echo $edu['jenjang'] == 'S3' ? 'selected' : ''; ?>>S3</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Year</label>
                                <input type="number" class="form-control" name="education[<?php echo $index; ?>][tahun]"
                                       value="<?php echo htmlspecialchars($edu['tahun']); ?>">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control" name="education[<?php echo $index; ?>][deskripsi]"
                                       value="<?php echo htmlspecialchars($edu['deskripsi']); ?>">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label d-block">&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord('education-<?php echo $index; ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Teaching Tab -->
    <div class="tab-pane fade" id="teaching">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Teaching History</h5>
            <button type="button" class="btn btn-primary btn-sm" onclick="addRecord('teaching')">
                <i class="fas fa-plus me-1"></i> Add Teaching
            </button>
        </div>
        <div id="teaching-records" class="records-container">
            <?php 
            $teachingData = json_decode($lecturer['riwayat_mengajar'], true) ?: [];
            foreach ($teachingData as $index => $teach): 
            ?>
            <div class="record-card mb-3" id="teaching-<?php echo $index; ?>">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Academic Year</label>
                                <input type="text" class="form-control" name="teaching[<?php echo $index; ?>][tahun]"
                                       value="<?php echo htmlspecialchars($teach['tahun']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Course</label>
                                <input type="text" class="form-control" name="teaching[<?php echo $index; ?>][mata_kuliah]"
                                       value="<?php echo htmlspecialchars($teach['mata_kuliah']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Institution</label>
                                <input type="text" class="form-control" name="teaching[<?php echo $index; ?>][institusi]"
                                       value="<?php echo htmlspecialchars($teach['institusi']); ?>">
                            </div>
                            <div class="col-md-11">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control" name="teaching[<?php echo $index; ?>][deskripsi]"
                                       value="<?php echo htmlspecialchars($teach['deskripsi']); ?>">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label d-block">&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord('teaching-<?php echo $index; ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Research Tab -->
<div class="tab-pane fade" id="research">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Research Projects</h5>
        <button type="button" class="btn btn-primary btn-sm" onclick="addRecord('research')">
            <i class="fas fa-plus me-1"></i> Add Research
        </button>
    </div>
    <div id="research-records" class="records-container">
        <?php 
        $researchData = json_decode($lecturer['penelitian'], true) ?: [];
        foreach ($researchData as $index => $research): 
        ?>
        <div class="record-card mb-3" id="research-<?php echo $index; ?>">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Project Title</label>
                            <input type="text" class="form-control" name="research[<?php echo $index; ?>][judul]"
                                   value="<?php echo htmlspecialchars($research['judul']); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Year</label>
                            <input type="number" class="form-control" name="research[<?php echo $index; ?>][tahun]"
                                   value="<?php echo htmlspecialchars($research['tahun']); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="research[<?php echo $index; ?>][deskripsi]"
                                   value="<?php echo htmlspecialchars($research['deskripsi']); ?>">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord('research-<?php echo $index; ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Service Tab -->
<div class="tab-pane fade" id="service">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Community Service</h5>
        <button type="button" class="btn btn-primary btn-sm" onclick="addRecord('service')">
            <i class="fas fa-plus me-1"></i> Add Service
        </button>
    </div>
    <div id="service-records" class="records-container">
        <?php 
        $serviceData = json_decode($lecturer['pengabdian_masyarakat'], true) ?: [];
        foreach ($serviceData as $index => $service): 
        ?>
        <div class="record-card mb-3" id="service-<?php echo $index; ?>">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Activity Title</label>
                            <input type="text" class="form-control" name="service[<?php echo $index; ?>][judul]"
                                   value="<?php echo htmlspecialchars($service['judul']); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Year</label>
                            <input type="number" class="form-control" name="service[<?php echo $index; ?>][tahun]"
                                   value="<?php echo htmlspecialchars($service['tahun']); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="service[<?php echo $index; ?>][deskripsi]"
                                   value="<?php echo htmlspecialchars($service['deskripsi']); ?>">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord('service-<?php echo $index; ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Publications Tab -->
<div class="tab-pane fade" id="publications">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Publications</h5>
        <button type="button" class="btn btn-primary btn-sm" onclick="addRecord('publication')">
            <i class="fas fa-plus me-1"></i> Add Publication
        </button>
    </div>
    <div id="publication-records" class="records-container">
        <?php 
        $publicationData = json_decode($lecturer['publikasi'], true) ?: [];
        foreach ($publicationData as $index => $pub): 
        ?>
        <div class="record-card mb-3" id="publication-<?php echo $index; ?>">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Publication Title</label>
                            <input type="text" class="form-control" name="publication[<?php echo $index; ?>][judul]"
                                   value="<?php echo htmlspecialchars($pub['judul']); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Journal/Conference</label>
                            <input type="text" class="form-control" name="publication[<?php echo $index; ?>][jurnal]"
                                   value="<?php echo htmlspecialchars($pub['jurnal']); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Year</label>
                            <input type="number" class="form-control" name="publication[<?php echo $index; ?>][tahun]"
                                   value="<?php echo htmlspecialchars($pub['tahun']); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="publication[<?php echo $index; ?>][deskripsi]"
                                   value="<?php echo htmlspecialchars($pub['deskripsi']); ?>">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord('publication-<?php echo $index; ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Patents Tab -->
<div class="tab-pane fade" id="patents">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Patents</h5>
        <button type="button" class="btn btn-primary btn-sm" onclick="addRecord('patent')">
            <i class="fas fa-plus me-1"></i> Add Patent
        </button>
    </div>
    <div id="patent-records" class="records-container">
        <?php 
        $patentData = json_decode($lecturer['hki_paten'], true) ?: [];
        foreach ($patentData as $index => $patent): 
        ?>
        <div class="record-card mb-3" id="patent-<?php echo $index; ?>">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Patent Title</label>
                            <input type="text" class="form-control" name="patent[<?php echo $index; ?>][judul]"
                                   value="<?php echo htmlspecialchars($patent['judul']); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Year</label>
                            <input type="number" class="form-control" name="patent[<?php echo $index; ?>][tahun]"
                                   value="<?php echo htmlspecialchars($patent['tahun']); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="patent[<?php echo $index; ?>][deskripsi]"
                                   value="<?php echo htmlspecialchars($patent['deskripsi']); ?>">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord('patent-<?php echo $index; ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
        <!-- Continue with other tabs similarly... -->
    </div>
</div>

<!-- Add before closing form tag -->
<div class="sticky-footer">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted">Last updated: <?php echo date('d M Y H:i', strtotime($lecturer['updated_at'])); ?></span>
            </div>
            <div class="d-flex gap-2">
                <a href="manage_lecturers.php" class="btn btn-light">
                    <i class="fas fa-times me-1"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" id="saveButton">
                    <i class="fas fa-save me-1"></i>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

        </form>

<script>
// Form submission handler
document.getElementById('editLecturerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = true;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
    
    // Submit form
    this.submit();
});

<?php if (isset($_SESSION['success_message'])): ?>
    // Show success toast and redirect
    setTimeout(function() {
        window.location.href = 'manage_lecturers.php';
    }, 1500);
<?php endif; ?>
</script>

<!-- JavaScript for Record Management -->
<script>
// Record templates and handlers
const templates = {
    education: {
        title: 'Education',
        fields: [
            {name: 'perguruan_tinggi', label: 'University', type: 'text', width: 6},
            {name: 'gelar', label: 'Degree', type: 'text', width: 3},
            {name: 'jenjang', label: 'Level', type: 'select', width: 3, 
             options: ['S1', 'S2', 'S3']},
            {name: 'tahun', label: 'Year', type: 'number', width: 3},
            {name: 'deskripsi', label: 'Description', type: 'text', width: 8}
        ]
    },
    teaching: {
        title: 'Teaching',
        fields: [
            {name: 'tahun', label: 'Academic Year', type: 'text', width: 3},
            {name: 'mata_kuliah', label: 'Course', type: 'text', width: 4},
            {name: 'institusi', label: 'Institution', type: 'text', width: 4},
            {name: 'deskripsi', label: 'Description', type: 'text', width: 11}
        ]
    },
    research: {
        title: 'Research',
        fields: [
            {name: 'judul', label: 'Project Title', type: 'text', width: 6},
            {name: 'tahun', label: 'Year', type: 'number', width: 2},
            {name: 'deskripsi', label: 'Description', type: 'text', width: 3}
        ]
    },
    service: {
        title: 'Service',
        fields: [
            {name: 'judul', label: 'Activity Title', type: 'text', width: 6},
            {name: 'tahun', label: 'Year', type: 'number', width: 2},
            {name: 'deskripsi', label: 'Description', type: 'text', width: 3}
        ]
    },
    publication: {
        title: 'Publication',
        fields: [
            {name: 'judul', label: 'Publication Title', type: 'text', width: 4},
            {name: 'jurnal', label: 'Journal/Conference', type: 'text', width: 3},
            {name: 'tahun', label: 'Year', type: 'number', width: 2},
            {name: 'deskripsi', label: 'Description', type: 'text', width: 2}
        ]
    },
    patent: {
        title: 'Patent',
        fields: [
            {name: 'judul', label: 'Patent Title', type: 'text', width: 6},
            {name: 'tahun', label: 'Year', type: 'number', width: 2},
            {name: 'deskripsi', label: 'Description', type: 'text', width: 3}
        ]
    }
};

// Add new record
function addRecord(type) {
    const container = document.getElementById(`${type}-records`);
    const recordId = Date.now();
    const template = templates[type];
    
    let html = `
        <div class="record-card mb-3" id="${type}-${recordId}">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
    `;

    template.fields.forEach(field => {
        if (field.type === 'select') {
            html += `
                <div class="col-md-${field.width}">
                    <label class="form-label">${field.label}</label>
                    <select class="form-select" name="${type}[${recordId}][${field.name}]">
                        <option value="">Select ${field.label}</option>
                        ${field.options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                    </select>
                </div>
            `;
        } else {
            html += `
                <div class="col-md-${field.width}">
                    <label class="form-label">${field.label}</label>
                    <input type="${field.type}" class="form-control" 
                           name="${type}[${recordId}][${field.name}]" 
                           placeholder="Enter ${field.label.toLowerCase()}">
                </div>
            `;
        }
    });

    html += `
                        <div class="col-md-1">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="deleteRecord('${type}-${recordId}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
}

// Delete record
function deleteRecord(recordId) {
    document.getElementById(recordId).remove();
}
</script>