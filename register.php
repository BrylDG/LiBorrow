<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liborrow - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="RegisterStyles.css">
</head>
<body>
    <!-- NAVBAR SECTION -->
    <nav class="navbar navbar-light p-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <!-- LOGO -->
            <a class="navbar-brand" href="index.php">
                <span class="liborrow-logo">Liborrow<span class="dot">.</span></span>
            </a>
        </div>
    </nav>

    <section class="main-section">
        <!-- Left Column: Space -->
        <div class="col-3 left-column">
       </div>

        <!-- Center Column: Sign-up Form -->
        <div class="col-5 center-column">
            <div class="login-form-container">
                <h3 class="text-center">Create Account</h3>
                <h4 class="text-center" >Join our community in just a few steps!</h4>
                <form action="register.php" method="post">
                    <?php
                    include("connection.php");

                    $query = "INSERT INTO users(fullname, email, phoneno, address, username, password, isAdmin) VALUES(?, ?, ?, ?, ?, ?, 0)";
                    
                    if (isset($_POST["submit"])) {
                        $fullname = $_POST["fullname"];
                        $email = $_POST["email"];
                        $phoneno = $_POST["phoneno"];
                        $address = $_POST["address"];
                        $username = $_POST["username"];
                        $password = $_POST["password"];
                        $confirmpass = $_POST["confirmpassword"];
                        
                        if($confirmpass <> $password) {
                            echo "<script>alert('Password not match!')</script>";
                        } elseif($conn->connect_error){
                            die('Connection failed : ' .$conn->connect_error);
                        }
                        else {
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ssisss", $fullname, $email, $phoneno, $address, $username, $password);
                            $stmt->execute();
                            echo "<script>alert('Registered Successfully')</script>";
                            $stmt->close();
                            $conn->close();
                        }
                    }
                    ?>
                    <h5 class="center">Personal Information</h5>
                    <div class="mb-4 text-input input-container">
                        <input type="text" class="form-control" name="fullname" required>
                        <label for="text">Full name</label>
                    </div>

                    <h5 class="center">Contact Information</h5>
                    <div class="mb-4 contact-input input-container">
                        <div class="input-wrapper">
                            <input type="email" class="form-control" id="email" name="email" required>
                            <label class="mt-1" for="email">Email Address</label>
                        </div>
                        <div class="input-wrapper">
                            <input type="tel" class="form-control" id="number" pattern="[0-9]{11}" name="phoneno" required>
                            <label class="mt-1" for="number">Phone Number</label>
                        </div>
                    </div>

                    <h5 class="center">Address</h5>
                    <div class="mb-4 contact-input input-container">
                            <input type="text" class="form-control" id="address" name="address" required>
                            <label for="address">Address</label>
                    </div>

                    <h5 class="center">Account Information</h5>
                    <div class="mb-4 contact-input input-container">
                        <div class="input-wrapper">
                            <input type="text" class="form-control" id="username" name="username" required>
                            <label class="mt-1" for="username">Username</label>
                        </div>
                        <div class="input-wrapper">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <label class="mt-1" for="password">Password</label>
                            <span class="password-toggle" onclick="togglePassword()">
                                <i id="eye-icon1" class="bi bi-eye"></i>
                            </span>
                        </div>
                        <div class="input-wrapper">
                            <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" required>
                            <label class="mt-1"for="confirmpassword">Confirm Password</label>
                            <span class="password-toggle" onclick="toggleConfirmPassword()">
                                <i id="eye-icon2" class="bi bi-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="text-end mb-3">
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>
                    <input type="submit" class="btn btn-primary" name="submit" value="Sign-Up"/>
                </form>
                <p class="text-center mt-3">Already have an Account? <a href="login.php" class="sign-up">Sign-in!</a></p>
            </div>
        </div>

        <!-- Right Column: Space-->
        <div class="col-4 right-column">
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
            const eyeIcon = document.getElementById('eye-icon1');
            
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

        function toggleConfirmPassword() {
            const passwordInput = document.getElementById('confirmpassword');  // Corrected id
            const eyeIcon = document.getElementById('eye-icon2');

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
