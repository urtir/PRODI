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
                    <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <div class="col-md-4">
                <h5 class="mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/courses" class="text-white text-decoration-none">Mata Kuliah</a></li>
                    <li class="mb-2"><a href="/events" class="text-white text-decoration-none">Acara</a></li>
                    <li class="mb-2"><a href="/admission" class="text-white text-decoration-none">Buletin</a></li>
                    <li class="mb-2"><a href="/contact" class="text-white text-decoration-none">Kontak</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5 class="mb-3">Newsletter</h5>
                <p class="mb-3">Subscribe untuk mendapatkan informasi terbaru</p>
                <form class="subscribe-form">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Enter your email">
                        <button class="btn btn-warning" type="submit">Subscribe</button>
                    </div>
                </form>
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