<?php
    session_start();
    
    if(!isset($_SESSION['idno'])) {
        header("Location: login.php");
        exit();
    }

    include('connection.php');

    $idno = $_SESSION['idno'];
    $fullname = '';
    $username = '';
    $email = '';
    $phoneno = '';
    $address = '';

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $phoneno = $_POST['phoneno'];
        $address = $_POST['address'];

        $stmt = $conn->prepare("UPDATE users SET username = ?, fullname = ?, email = ?, phoneno = ?, address = ? WHERE idno = ?");
        $stmt->bind_param("sssssi", $username, $fullname, $email, $phoneno, $address, $idno);

        if ($stmt->execute()) {
            echo "<script>alert('Successfully update information')</script>"; // For approval if update are successful
            // Optional: Redirect to profile or another page after successful update
            // header("Location: 'bisag asa'");
            // exit();
        } else {
            echo "Error updating user information: " . $stmt->error;
        }
    }

    // Fetch user data based on idno from the database
    $sql = "SELECT fullname, username, email, phoneno, address FROM users WHERE idno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $idno = $result->fetch_assoc();
    } else {
        echo "No user data found.";
        exit();
    }

    $stmt->close();
    $conn->close();
?>
<div class="content-box" id="content2">
    <div class="container">
        <div id="Readd1" class="Updatebox">
            <div class="Readbox Rglobal" id="UpdateboxView">
                <div id="profile-view" class="Pview">
                    <h3 id="rp">Reader profile</h3>
                    <img src="./Images/profilepic.png" alt="Book one" id="profilepic">
                    <h2><?php echo htmlspecialchars($idno['username']) ?></h2>
                </div>
                <div id="profile-details">
                    <h3 id="ri">Reader Information</h3>
                    <form action="UpdateUser.php" method="POST">
                        <div class="input-container">
                            <input type="text" id="input-three" name="username" placeholder=" " required>
                            <label for="input-three">User Name</label>
                        </div>
                        <div class="input-container">
                            <input type="text" id="input-three" name="fullname" placeholder=" " required>
                            <label for="input-three">Full Name</label>
                        </div>
                        <div class="input-container">
                            <input type="text" id="input-three" name="email" placeholder=" " required>
                            <label for="input-three">Email</label>
                        </div>
                        <div class="input-container">
                            <input type="text" id="input-three" name="phoneno" placeholder=" " required>
                            <label for="input-three">Phone Number</label>
                        </div>
                        <div class="input-container">
                            <input type="text" id="input-three" name="address" placeholder=" " required>
                            <label for="input-three">Address</label>
                        </div>
                        <div class="UpdateCancel-btn">
                            <input type="submit" class="userupdate-btn" value="Update"> 
                            <input type="submit" class="userupdate-btn" id="cancelbtn" value="Cancel">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <script>
        // Function to handle view more button clicks
        document.querySelectorAll('.view-more-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                // Handle view more action (e.g., load a new page)
            });
        });
    </script>    
</div>
