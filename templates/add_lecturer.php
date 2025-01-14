<?php
include 'header.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "informatics_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $nama = $_POST['nama'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $perguruan_tinggi = $_POST['perguruan_tinggi'];
        $program_studi = $_POST['program_studi'];
        $jabatan_fungsional = $_POST['jabatan_fungsional'];
        $pendidikan_terakhir = $_POST['pendidikan_terakhir'];
        $status_ikatan_kerja = $_POST['status_ikatan_kerja'];
        $status_aktivitas = $_POST['status_aktivitas'];

        // Handle photo upload
        $foto = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
            $file = $_FILES['foto'];
            $foto = time() . '_' . basename($file['name']);
            $target = "../static/images/lecturers/" . $foto;
            
            if (!move_uploaded_file($file['tmp_name'], $target)) {
                throw new Exception("Failed to upload image");
            }
        }

        // Process JSON data
        $riwayat_pendidikan = isset($_POST['education']) ? json_encode($_POST['education']) : null;
        $riwayat_mengajar = isset($_POST['teaching']) ? json_encode($_POST['teaching']) : null;
        $penelitian = isset($_POST['research']) ? json_encode($_POST['research']) : null;
        $pengabdian_masyarakat = isset($_POST['service']) ? json_encode($_POST['service']) : null;
        $publikasi = isset($_POST['publication']) ? json_encode($_POST['publication']) : null;
        $hki_paten = isset($_POST['patent']) ? json_encode($_POST['patent']) : null;

        // Insert query
        $sql = "INSERT INTO lecturers (
            nama, jenis_kelamin, perguruan_tinggi, program_studi,
            jabatan_fungsional, pendidikan_terakhir, status_ikatan_kerja,
            status_aktivitas, foto, riwayat_pendidikan, riwayat_mengajar,
            penelitian, pengabdian_masyarakat, publikasi, hki_paten
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssssssss",
            $nama, $jenis_kelamin, $perguruan_tinggi, $program_studi,
            $jabatan_fungsional, $pendidikan_terakhir, $status_ikatan_kerja,
            $status_aktivitas, $foto, $riwayat_pendidikan, $riwayat_mengajar,
            $penelitian, $pengabdian_masyarakat, $publikasi, $hki_paten
        );

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Lecturer added successfully!";
            header("Location: manage_lecturers.php");
            exit();
        } else {
            throw new Exception("Error adding lecturer: " . $conn->error);
        }

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Define dropdown options
$jabatan_options = ['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'];
$pendidikan_options = ['S1', 'S2', 'S3'];
$status_kerja_options = ['Dosen Tetap', 'Dosen Tidak Tetap', 'Dosen Tamu'];
?>



<div class="container" style="padding-top: 100px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Add New Lecturer</h2>
        <a href="manage_lecturers.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <form method="POST" enctype="multipart/form-data" id="addLecturerForm">
        <!-- Personal Information Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-user-circle text-primary me-2"></i>
                    <h5 class="card-title mb-0">Personal Information</h5>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="jenis_kelamin" required>
                            <option value="">Select Gender</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">University</label>
                        <input type="text" class="form-control" name="perguruan_tinggi" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Study Program</label>
                        <input type="text" class="form-control" name="program_studi" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Status Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-graduation-cap text-primary me-2"></i>
                    <h5 class="card-title mb-0">Academic Status</h5>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Functional Position</label>
                        <select class="form-select" name="jabatan_fungsional" required>
                            <option value="">Select Position</option>
                            <?php foreach ($jabatan_options as $option): ?>
                                <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Latest Education</label>
                        <select class="form-select" name="pendidikan_terakhir" required>
                            <option value="">Select Education</option>
                            <?php foreach ($pendidikan_options as $option): ?>
                                <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Employment Status</label>
                        <select class="form-select" name="status_ikatan_kerja" required>
                            <option value="">Select Status</option>
                            <?php foreach ($status_kerja_options as $option): ?>
                                <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Activity Status</label>
                        <select class="form-select" name="status_aktivitas" required>
                            <option value="">Select Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Photo Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-camera text-primary me-2"></i>
                    <h5 class="card-title mb-0">Profile Photo</h5>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input type="file" class="form-control" name="foto" accept="image/*">
                        <div class="form-text">Recommended: Square image, max 2MB (JPG, PNG)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- After profile photo card -->

<!-- Academic Records Tabs -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" role="tablist">
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
                <div id="education-records" class="records-container"></div>
            </div>

            <!-- Teaching Tab -->
            <div class="tab-pane fade" id="teaching">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Teaching History</h5>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addRecord('teaching')">
                        <i class="fas fa-plus me-1"></i> Add Teaching
                    </button>
                </div>
                <div id="teaching-records" class="records-container"></div>
            </div>

            <!-- Research Tab -->
            <div class="tab-pane fade" id="research">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Research Projects</h5>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addRecord('research')">
                        <i class="fas fa-plus me-1"></i> Add Research
                    </button>
                </div>
                <div id="research-records" class="records-container"></div>
            </div>

            <!-- Service Tab -->
            <div class="tab-pane fade" id="service">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Community Service</h5>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addRecord('service')">
                        <i class="fas fa-plus me-1"></i> Add Service
                    </button>
                </div>
                <div id="service-records" class="records-container"></div>
            </div>

            <!-- Publications Tab -->
            <div class="tab-pane fade" id="publications">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Publications</h5>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addRecord('publication')">
                        <i class="fas fa-plus me-1"></i> Add Publication
                    </button>
                </div>
                <div id="publication-records" class="records-container"></div>
            </div>

            <!-- Patents Tab -->
            <div class="tab-pane fade" id="patents">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Patents</h5>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addRecord('patent')">
                        <i class="fas fa-plus me-1"></i> Add Patent
                    </button>
                </div>
                <div id="patent-records" class="records-container"></div>
            </div>
        </div>
    </div>
</div>

<!-- Save Button -->
<div class="d-grid gap-2 mb-5">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-2"></i>Save Lecturer
    </button>
</div>

</form>

<script>
// Record templates with field definitions
const templates = {
    education: {
        fields: [
            { name: 'perguruan_tinggi', label: 'University', type: 'text', width: 6 },
            { name: 'gelar', label: 'Degree', type: 'text', width: 3 },
            { name: 'jenjang', label: 'Level', type: 'select', width: 3, options: ['S1', 'S2', 'S3'] },
            { name: 'tahun', label: 'Year', type: 'number', width: 2 },
            { name: 'deskripsi', label: 'Description', type: 'text', width: 9 }
        ]
    },
    teaching: {
        fields: [
            { name: 'mata_kuliah', label: 'Course', type: 'text', width: 6 },
            { name: 'institusi', label: 'Institution', type: 'text', width: 4 },
            { name: 'tahun', label: 'Year', type: 'number', width: 2 },
            { name: 'deskripsi', label: 'Description', type: 'text', width: 11 }
        ]
    },
    research: {
        fields: [
            { name: 'judul', label: 'Title', type: 'text', width: 6 },
            { name: 'tahun', label: 'Year', type: 'number', width: 2 },
            { name: 'deskripsi', label: 'Description', type: 'text', width: 4 }
        ]
    },
    service: {
        fields: [
            { name: 'judul', label: 'Activity', type: 'text', width: 6 },
            { name: 'tahun', label: 'Year', type: 'number', width: 2 },
            { name: 'deskripsi', label: 'Description', type: 'text', width: 4 }
        ]
    },
    publication: {
        fields: [
            { name: 'judul', label: 'Title', type: 'text', width: 5 },
            { name: 'jurnal', label: 'Journal', type: 'text', width: 3 },
            { name: 'tahun', label: 'Year', type: 'number', width: 2 },
            { name: 'deskripsi', label: 'Description', type: 'text', width: 2 }
        ]
    },
    patent: {
        fields: [
            { name: 'judul', label: 'Title', type: 'text', width: 6 },
            { name: 'tahun', label: 'Year', type: 'number', width: 2 },
            { name: 'deskripsi', label: 'Description', type: 'text', width: 4 }
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