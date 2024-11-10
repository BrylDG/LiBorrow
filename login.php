<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liborrow - Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="LoginStyles.css">
</head>
<body>
    <!-- NAVBAR SECTION -->
    <nav class="navbar navbar-light p-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="index.php">
                <span class="liborrow-logo">Liborrow<span class="dot">.</span></span>
            </a>
        </div>
    </nav>

    <section class="main-section">
        <div class="col-3 left-column">
            <h1>Your Next <span class="highlight">Chapter</span> Awaits<span class="highlight">.</span></h1>
            <p>Start Borrowing Today: Your next favorite book is waiting.</p>
        </div>

        <div class="col-5 center-column">
            <div class="login-form-container">
                <h3 class="text-center">Welcome Back!</h3>
                <?php
                include('connection.php');
                session_start();

                $loginMessage = '';
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $email = mysqli_real_escape_string($conn, $_POST["email"]);
                    $pass = mysqli_real_escape_string($conn, $_POST["password"]);
                    
                    $query = "SELECT * FROM users WHERE email='$email' AND password='$pass'";
                    $result = mysqli_query($conn, $query);
                    $count = mysqli_num_rows($result);
                    
                    if ($count == 1) {
                        $user = mysqli_fetch_assoc($result);
                        $_SESSION['fullname'] = $user['fullname'];
                        $loginMessage = 'success';
                    } else {
                        $loginMessage = 'failed';
                    }
                }
                ?>
                <form action="login.php" method="post">
                    <div class="mb-5 email-input input-container">
                        <input type="email" class="form-control" id="email" name="email" required>
                        <label for="email">Email Address</label>
                    </div>
                    <div class="mb-3 password-input input-container">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <label for="password">Password</label>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i id="eye-icon" class="bi bi-eye"></i>
                        </span>
                    </div>
                    <div class="text-end mb-3">
                        <a href="#" class="forgot-password"> Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign-In</button>
                </form>
                <p class="text-center mt-3">Don't have an Account? <a href="register.php" class="sign-up">Sign-up!</a></p>
            </div>
        </div>

        <div class="col-4 right-column">
            <div class="image-container">
                <img src="Images/gilBrowsingLaptop.png" alt="Person working on laptop" class="img-fluid character-image" />
            </div>
        </div>
    </section>

    <footer>
        <nav>
            <a href="#">About</a>
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="#">Team</a>
        </nav>
        <p>© 2024 Bravo Two All Rights Reserved.</p>
    </footer>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    if ($loginMessage == 'success' && $user['isAdmin'] == 1) {
                        echo 'Login Success! Redirecting...';
                        echo '<script>setTimeout(function() { window.location.href = "BootDash.php"; }, 2000);</script>';
					} elseif ($loginMessage == 'success' && $user['isAdmin'] == 0) {
						echo 'Login Success! Redirecting...';
                        echo '<script>setTimeout(function() { window.location.href = "UserNavTemplate.html"; }, 2000);</script>';
                    } elseif ($loginMessage == 'failed') {
                        echo 'Login Failed. Please check your credentials.';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
                eyeIcon.style.color = '#FF6600';
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
                eyeIcon.style.color = '#aaa';
            }
        }

        document.querySelectorAll('input').forEach((input) => {
            input.addEventListener('input', function() {
                if (input.value !== '') {
                    input.classList.add('has-content');
                } else {
                    input.classList.remove('has-content');
                }
            });
        });

        <?php if ($loginMessage): ?>
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        <?php endif; ?>
    </script>
</body>
</html>