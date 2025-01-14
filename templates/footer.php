<!DOCTYPE html>
<html lang="en">
<body>

<footer class="footer bg-dark text-white py-4">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4">
                <h5 class="mb-3">Program Studi Informatika</h5>
                <p class="mb-1">Email: info@informatika.edu</p>
                <p class="mb-3">Phone: +62 123 4567 890</p>
            <div class="social-links">
                <a href="https://web.facebook.com/unhanhumas.idu.ac.id" target="_blank" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="https://x.com/Unhan_RI" target="_blank" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/unhan_informatics?igsh=MXAzcjFzeTlqdTcyZA==" target="_blank" class="text-white me-2"><i class="fab fa-instagram"></i></a>
            </div>
            </div>
            
            <div class="col-md-4">
                <h5 class="mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="courses.php" class="text-white text-decoration-none">Mata Kuliah</a></li>
                    <li class="mb-2"><a href="events.php" class="text-white text-decoration-none">Acara</a></li>
                    <li class="mb-2"><a href="buletin.php" class="text-white text-decoration-none">FAQ</a></li>
                    <li class="mb-2"><a href="contact.php" class="text-white text-decoration-none">Hubungi</a></li>
                 </ul>
            </div>

            
        </div>
    </div>
</footer>

<style>
html, body {
    height: 100%;
    margin: 0;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.content-wrapper {
    flex: 1 0 auto;
}

.footer {
    flex-shrink: 0;
    margin-top: auto;
}

.social-links a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    transition: all 0.3s ease;
}

.social-links a:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-3px);
}

.list-unstyled a {
    transition: all 0.3s ease;
}

.list-unstyled a:hover {
    opacity: 0.8;
    padding-left: 5px;
}

.subscribe-form .form-control {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.subscribe-form .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
</style>

</body>
</html>