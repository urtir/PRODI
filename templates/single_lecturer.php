<?php
include 'header.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get lecturer ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch lecturer data
$stmt = $conn->prepare("SELECT * FROM lecturers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$lecturer = $result->fetch_assoc();

// Redirect if lecturer not found
if (!$lecturer) {
    header("Location: lecturers.php");
    exit();
}

// Decode JSON data
$education = json_decode($lecturer['riwayat_pendidikan'], true);
$teaching = json_decode($lecturer['riwayat_mengajar'], true);
$research = json_decode($lecturer['penelitian'], true);
$community_service = json_decode($lecturer['pengabdian_masyarakat'], true);
$publications = json_decode($lecturer['publikasi'], true);
$patents = json_decode($lecturer['hki_paten'], true);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($lecturer['nama']); ?> - Profile</title>
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #152340;
            --secondary-color: #f8f9fa;
            --accent-color: #3366cc;
            --text-color: #495057;
        }

        .profile-header {
            background: var(--primary-color);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }

        .profile-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .nav-tabs {
            border: none;
            margin-bottom: 2rem;
        }

        .nav-tabs .nav-link {
            color: var(--text-color);
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
            background: none;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
            display: inline-block;
            margin-top: 1rem;
        }

        .status-active {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }
    </style>
</head>

<body>
    <!-- Profile Header -->
    <section class="profile-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="../static/images/lecturers/<?php echo htmlspecialchars($lecturer['foto']); ?>" 
                         class="profile-image" 
                         alt="<?php echo htmlspecialchars($lecturer['nama']); ?>"
                         onerror="this.src='../static/images/lecturers/default.jpg'">
                </div>
                <div class="col-md-9">
                    <h1><?php echo htmlspecialchars($lecturer['nama']); ?></h1>
                    <div class="mb-2">
                        <span class="me-3">
                            <i class="fas fa-user-tie me-2"></i>
                            <?php echo htmlspecialchars($lecturer['jabatan_fungsional']); ?>
                        </span>
                        <span class="me-3">
                            <i class="fas fa-graduation-cap me-2"></i>
                            <?php echo htmlspecialchars($lecturer['pendidikan_terakhir']); ?>
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="me-3">
                            <i class="fas fa-university me-2"></i>
                            <?php echo htmlspecialchars($lecturer['perguruan_tinggi']); ?>
                        </span>
                        <span class="me-3">
                            <i class="fas fa-book me-2"></i>
                            <?php echo htmlspecialchars($lecturer['program_studi']); ?>
                        </span>
                    </div>
                    <div class="mt-3">
                        <span class="status-badge <?php echo $lecturer['status_aktivitas'] == 'Aktif' ? 'status-active' : 'status-inactive'; ?>">
                            <i class="fas fa-circle me-2"></i>
                            <?php echo htmlspecialchars($lecturer['status_aktivitas']); ?>
                        </span>
                        <span class="status-badge ms-2">
                            <i class="fas fa-briefcase me-2"></i>
                            <?php echo htmlspecialchars($lecturer['status_ikatan_kerja']); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Navigation Tabs -->
    <div class="container">
        <ul class="nav nav-tabs" id="lecturerTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="biodata-tab" data-bs-toggle="tab" href="#biodata">
                    <i class="fas fa-user me-2"></i>Biodata
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="education-tab" data-bs-toggle="tab" href="#education">
                    <i class="fas fa-graduation-cap me-2"></i>Pendidikan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="teaching-tab" data-bs-toggle="tab" href="#teaching">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Pengajaran
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="research-tab" data-bs-toggle="tab" href="#research">
                    <i class="fas fa-microscope me-2"></i>Penelitian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="publication-tab" data-bs-toggle="tab" href="#publications">
                    <i class="fas fa-book me-2"></i>Publikasi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#services" role="tab">
                    <i class="fas fa-hands-helping me-2"></i>Pengabdian
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="patents-tab" data-bs-toggle="tab" 
                        data-bs-target="#patents" type="button" role="tab">
                    <i class="fas fa-certificate me-2"></i>Patents
                </button>
            </li>
        </ul>
    </div>

    <div class="container mb-5">
        <div class="tab-content" id="lecturerTabContent">
        <!-- Biodata Tab -->
        <div class="tab-pane fade show active" id="biodata">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Informasi Pribadi</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-venus-mars me-2"></i>Jenis Kelamin:</strong> 
                                        <?php echo htmlspecialchars($lecturer['jenis_kelamin']); ?></p>
                                    <p><strong><i class="fas fa-university me-2"></i>Perguruan Tinggi:</strong> 
                                        <?php echo htmlspecialchars($lecturer['perguruan_tinggi']); ?></p>
                                    <p><strong><i class="fas fa-graduation-cap me-2"></i>Program Studi:</strong> 
                                        <?php echo htmlspecialchars($lecturer['program_studi']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-user-tie me-2"></i>Jabatan:</strong> 
                                        <?php echo htmlspecialchars($lecturer['jabatan_fungsional']); ?></p>
                                    <p><strong><i class="fas fa-certificate me-2"></i>Pendidikan Terakhir:</strong> 
                                        <?php echo htmlspecialchars($lecturer['pendidikan_terakhir']); ?></p>
                                    <p><strong><i class="fas fa-briefcase me-2"></i>Status:</strong> 
                                        <?php echo htmlspecialchars($lecturer['status_ikatan_kerja']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Education Tab -->
        <div class="tab-pane fade" id="education">
            <?php 
            if ($education):
                // Sort education by year descending
                usort($education, function($a, $b) {
                    return strcmp($b['tahun'], $a['tahun']);
                });
                
                $current_year = null;
                foreach ($education as $edu): 
                    // Group by year
                    if ($current_year !== $edu['tahun']):
                        if ($current_year !== null) echo '</div>';
                        $current_year = $edu['tahun'];
            ?>
                <div class="d-flex align-items-center mb-3">
                    <h4 class="fw-bold mb-0">
                        <span class="badge bg-primary rounded-pill">
                            <?php echo htmlspecialchars($edu['tahun']); ?>
                        </span>
                    </h4>
                    <hr class="flex-grow-1 mx-3">
                </div>
                <div class="year-group mb-4">
            <?php endif; ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="card-title mb-1">
                                        <?php echo htmlspecialchars($edu['perguruan_tinggi']); ?>
                                    </h5>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-graduation-cap me-2"></i>
                                        <?php echo htmlspecialchars($edu['gelar']); ?>
                                        <span class="badge bg-secondary ms-2">
                                            <?php echo htmlspecialchars($edu['jenjang']); ?>
                                        </span>
                                    </p>
                                    <?php if (!empty($edu['deskripsi'])): ?>
                                        <p class="card-text small">
                                            <?php echo htmlspecialchars($edu['deskripsi']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php 
                endforeach;
                echo '</div>';
            else:
            ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No education history available.
                </div>
            <?php endif; ?>
        </div>


         <!-- Teaching Tab -->
        <div class="tab-pane fade" id="teaching">
            <?php 
            if ($teaching):
                // Sort teaching by year descending
                usort($teaching, function($a, $b) {
                    return strcmp($b['tahun'], $a['tahun']);
                });
                
                $current_year = null;
                foreach ($teaching as $teach): 
                    if ($current_year !== $teach['tahun']):
                        if ($current_year !== null) echo '</div>';
                        $current_year = $teach['tahun'];
            ?>
                <div class="d-flex align-items-center mb-3">
                    <h4 class="fw-bold mb-0">
                        <span class="badge bg-success rounded-pill">
                            <?php echo htmlspecialchars($teach['tahun']); ?>
                        </span>
                    </h4>
                    <hr class="flex-grow-1 mx-3">
                </div>
                <div class="year-group mb-4">
            <?php endif; ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="card-title mb-1">
                                        <i class="fas fa-book-open text-success me-2"></i>
                                        <?php echo htmlspecialchars($teach['mata_kuliah']); ?>
                                    </h5>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-university me-2"></i>
                                        <?php echo htmlspecialchars($teach['institusi']); ?>
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <?php if (!empty($teach['deskripsi'])): ?>
                                        <p class="card-text small mb-0">
                                            <?php echo htmlspecialchars($teach['deskripsi']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php 
                endforeach;
                echo '</div>';
            else:
            ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No teaching history available.
                </div>
            <?php endif; ?>
        </div>

        <!-- Research Tab -->
        <div class="tab-pane fade" id="research">
            <?php 
            if ($research):
                // Sort research by year descending
                usort($research, function($a, $b) {
                    return strcmp($b['tahun'], $a['tahun']);
                });
                
                $current_year = null;
                foreach ($research as $res): 
                    if ($current_year !== $res['tahun']):
                        if ($current_year !== null) echo '</div>';
                        $current_year = $res['tahun'];
            ?>
                <div class="d-flex align-items-center mb-3">
                    <h4 class="fw-bold mb-0">
                        <span class="badge bg-info rounded-pill">
                            <?php echo htmlspecialchars($res['tahun']); ?>
                        </span>
                    </h4>
                    <hr class="flex-grow-1 mx-3">
                </div>
                <div class="year-group mb-4">
            <?php endif; ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-9">
                                    <h5 class="card-title mb-2">
                                        <i class="fas fa-microscope text-info me-2"></i>
                                        <?php echo htmlspecialchars($res['judul']); ?>
                                    </h5>
                                    <?php if (!empty($res['deskripsi'])): ?>
                                        <p class="card-text text-muted mb-0 small">
                                            <?php echo htmlspecialchars($res['deskripsi']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php 
                endforeach;
                echo '</div>';
            else:
            ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No research projects available.
                </div>
            <?php endif; ?>
        </div>
    

        <!-- Publications Tab -->
        <div class="tab-pane fade" id="publications">
            <?php 
            if ($publications):
                // Sort publications by year descending
                usort($publications, function($a, $b) {
                    return strcmp($b['tahun'], $a['tahun']);
                });
                
                $current_year = null;
                foreach ($publications as $pub): 
                    if ($current_year !== $pub['tahun']):
                        if ($current_year !== null) echo '</div>';
                        $current_year = $pub['tahun'];
            ?>
                <div class="d-flex align-items-center mb-3">
                    <h4 class="fw-bold mb-0">
                        <span class="badge bg-warning rounded-pill">
                            <?php echo htmlspecialchars($pub['tahun']); ?>
                        </span>
                    </h4>
                    <hr class="flex-grow-1 mx-3">
                </div>
                <div class="year-group mb-4">
            <?php endif; ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 class="card-title mb-2">
                                        <i class="fas fa-file-alt text-warning me-2"></i>
                                        <?php echo htmlspecialchars($pub['judul']); ?>
                                    </h5>
                                    <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                                        <?php if (!empty($pub['jurnal'])): ?>
                                            <span class="text-muted">
                                                <i class="fas fa-book me-1"></i>
                                                <?php echo htmlspecialchars($pub['jurnal']); ?>
                                            </span>
                                        <?php endif; ?>
                            
                                    </div>
                                    <?php if (!empty($pub['deskripsi'])): ?>
                                        <p class="card-text text-muted small mb-0">
                                            <?php echo htmlspecialchars($pub['deskripsi']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php 
                endforeach;
                echo '</div>';
            else:
            ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No publications available.
                </div>
            <?php endif; ?>
        </div>


<!-- Services Tab -->
<div class="tab-pane fade" id="services" role="tabpanel">
        <?php 
        $services = [];
        if (!empty($lecturer['pengabdian_masyarakat'])) {
            $services = json_decode($lecturer['pengabdian_masyarakat'], true) ?? [];
        }
        
        if (!empty($services)):
            echo '<div class="accordion-body">';
            foreach ($services as $service): 
        ?>
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-9">
                                <h5 class="card-title mb-2">
                                    <i class="fas fa-hands-helping text-success me-2"></i>
                                    <?php echo htmlspecialchars($service['judul']); ?>
                                </h5>
                                <?php if (!empty($service['deskripsi'])): ?>
                                    <p class="card-text text-muted mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <?php echo htmlspecialchars($service['deskripsi']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-3 text-lg-end mt-2 mt-lg-0">
                                <span class="badge bg-light text-primary">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    <?php echo htmlspecialchars($service['tahun']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
        <?php 
            endforeach;
            echo '</div>';
        else:
        ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                No community service records available.
            </div>
        <?php endif; ?>
    </div>

<!-- Patents Tab (Separate Structure) -->
<div class="tab-pane fade" id="patents">
    <?php 
    $patents = [];
    if (!empty($lecturer['hki_paten'])) {
        $patents = json_decode($lecturer['hki_paten'], true) ?? [];
    }
    
    if (!empty($patents)):
        echo '<div class="accordion-body">';
        foreach ($patents as $patent): 
    ?>
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-9">
                            <h5 class="card-title mb-2">
                                <i class="fas fa-certificate text-success me-2"></i>
                                <?php echo htmlspecialchars($patent['judul']); ?>
                            </h5>
                            <?php if (!empty($patent['deskripsi'])): ?>
                                <p class="card-text text-muted mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <?php echo htmlspecialchars($patent['deskripsi']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-3 text-lg-end mt-2 mt-lg-0">
                            <span class="badge bg-light text-primary">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <?php echo htmlspecialchars($patent['tahun']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
    <?php 
        endforeach;
        echo '</div>';
    else:
    ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            No patent records available.
        </div>
    <?php endif; ?>
</div>
