<?php
    session_start();

    include('connection.php');

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Query to check the user's credentials
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $db_user = $result->fetch_assoc();

            // Verify password
            if ($password === $db_user['password']) {
                // Store idno in session
                $_SESSION['idno'] = $db_user['idno'];

                // Redirect to BootDash page
                header("Location: BootDash.php");
                exit();
            } else {
                echo "<script>alert('Invalid Password!')</script>";
            }
        } else {
            echo "<script>alert('Email does not exist!')</script>";
        }

        $stmt->close();
        $conn->close();
    }
?>
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
            <!-- LOGO -->
            <a class="navbar-brand" href="#">
                <span class="liborrow-logo">Liborrow<span class="dot">.</span></span>
            </a>
        </div>
    </nav>

    <section class="main-section">
        <!-- Left Column: Slogan -->
        <div class="col-3 left-column">
            <h1>Your Next <span class="highlight">Chapter</span> Awaits<span class="highlight">.</span></h1>
            <p>Start Borrowing Today: Your next favorite book is waiting.</p>
        </div>

        <!-- Center Column: Sign-in Form and Image -->
        <div class="col-5 center-column">
            <div class="login-form-container"   >
                <h3 class="text-center">Welcome Back!</h3>
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
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign-In</button>
                </form>
                <p class="text-center mt-3">Don't have an Account? <a href="./register.php" class="sign-up">Sign-up!</a></p>
            </div>
        </div>

        <!-- Right Column: Image-->
        <div class="col-4 right-column">
            <div class="image-container">
                <img src="Images/gilBrowsingLaptop.png" alt="Person working on laptop" class="img-fluid character-image" />
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <nav>
            <a href="#">About</a>
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="#">Team</a>
        </nav>
        <p>© 2024 Bravo Two All Rights Reserved.</p>
    </footer>

    <!-- BOOTSTRAP SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';  // Show password
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');  // Change icon to "eye-slash"
                eyeIcon.style.color = '#FF6600';  // Change color to orange
            } else {
                passwordInput.type = 'password';  // Hide password
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');  // Change icon back to "eye"
                eyeIcon.style.color = '#aaa';  // Change color to gray
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
    </script>

    
</body>
</html>
