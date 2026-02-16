    <nav class="navbar fixed-top navbar-expand-lg" id="nav-home">
        <div class="container-fluid px-4">

            <!-- Logo -->
            <a class="navbar-brand" href="./index.php">
                <img src="./img/mediq.png" alt="MediQ Logo" style="height:60px;">
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- Center Menu -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="./index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="./doctor/doctor.php">Doctor</a></li>
                    <li class="nav-item"><a class="nav-link" href="./doctor/doctor.php">Hospital</a></li>
                    <li class="nav-item"><a class="nav-link" href="./contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="./about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="./faq.php">FAQs</a></li>
                </ul>

                <!-- Right Buttons -->
                <div class="d-flex">
                    <button class="btn btn-outline-danger me-2"
                        onclick="location.href='./login.php'"
                        style="border-radius:38px; border:2px solid #FF5A5F;">
                        Login
                    </button>

                    <button class="btn btn-outline-secondary"
                        onclick="window.location.href='./login.php#register'"
                        style="border-radius:38px; border-width:2px;">
                        Register
                    </button>
                </div>

            </div>
        </div>
    </nav>