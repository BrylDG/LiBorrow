<?php
include('connection.php');
session_start();

$loginMessage = '';
$user = null; // Initialize the user variable
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $pass = mysqli_real_escape_string($conn, $_POST["password"]);

    // Adjust the query to select only by email
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $user = mysqli_fetch_assoc($result);
        // Check if the user exists and if the password matches
        if ($user && $pass === $user['password']) { // Compare plain text passwords
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['idno'] = $user['idno'];
            $_SESSION['isAdmin'] = $user['isAdmin'];
            $loginMessage = 'success';
        } else {
            $loginMessage = 'failed'; // Invalid credentials
        }
    } else {
        // Handle SQL error
        $loginMessage = 'failed';
    }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap @5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            if ('<?php echo $loginMessage; ?>' === 'success') {
                Swal.fire({
                    title: 'Login Success!',
                    text: 'Redirecting...',
                    icon: 'success',
                    timer: 1000,
                    timerProgressBar: true,
                    willClose: () => {
                        window.location.href = '<?php echo isset($user) && $user['isAdmin'] == 1 ? "BootDash.php" : "User NavTemplate.php"; ?>';
                    }
                });
            } else if ('<?php echo $loginMessage; ?>' === 'failed') {
                Swal.fire({
                    title: 'Login Failed',
                    text: 'Please check your credentials.',
                    icon: 'error',
                    confirmButtonText: 'Try Again'
                });
            }
        <?php endif; ?>
    </script>
</body>
</html>