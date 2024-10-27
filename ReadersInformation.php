<?php
    session_start();
    include('connection.php');

    if (!isset($_SESSION['idno'])) {
        // Redirect to login page if not logged in
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['idno'];

    // Fetch user data based on idno from the database
    $sql = "SELECT idno, fullname, address, username, email, phoneno FROM users WHERE idno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
    } else {
        echo "No user data found.";
        exit();
    }

    $stmt->close();
    $conn->close();
?>
<div class="content-box" id="content2">
    <div class="container">
        <div id="Readd1" class="Readerbox">
            <div class="Readbox Rglobal" id="PboxView">
                <div id="profile-view" class="Pview">
                    <img src="./Images/profilepic.png" alt="Book one" id="profilepic">
                    <h2><?php echo htmlspecialchars($user_data['username']) ?></h2>
                </div>
                <div id="profile-details" class="Pdets">
        
                        <table class="Readersinfo-table">
                            <tr>
                                <td style="font-weight: bold;">ID Number</td>
                                <td><?php echo htmlspecialchars($user_data['idno']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">User Name:</td>
                                <td><?php echo htmlspecialchars($user_data['username']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Full Name:</td>
                                <td><?php echo htmlspecialchars($user_data['fullname']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;" >Email:</td>
                                <td><?php echo htmlspecialchars($user_data['email']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;" >Phone Number:</td>
                                <td><?php echo htmlspecialchars($user_data['phoneno']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Address:</td>
                                <td><?php echo htmlspecialchars($user_data['address']) ?></td>
                            </tr>
                        </table>
                                      
                </div>
                <div class="upbtn">
                    <script>
                        function linktoUpdateUser() {
                            window.location.href = 'UpdateUser.php'; // Link to update information page
                        }
                    </script>
                    <button id="update-button" class="update-btn" onclick="linktoUpdateUser()">
                        Update
                    </button>
                </div>
            </div>
            <div class="ReaderSusBan-btn">
                <button class="update-btn">
                    Suspend
                </button>
                <button class="update-btn" id="banbtn">
                    Ban
                </button>
            </div>
            <div class="Readbox Rglobal" id="ReaderPan">
                <table class="ReadersInfo-details">
                    <thead>
                        <tr>
                            <th>Book Id</th>
                            <th>Book Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>0001</td>
                            <td>Alice in Wonderland</td>
                            <td>Lewis Carroll</td>
                            <td>Fantasy/Adventure</td> 
                        </tr>
                        <tr>
                            <td>0002</td>
                            <td>Treasure Land</td>
                            <td>Lewis Carroll</td>
                            <td>Fantasy/Adventure</td> 
                        </tr>
                        <tr>
                            <td>0003</td>
                            <td>Treasure Land</td>
                            <td>Lewis Carroll</td>
                            <td>Fantasy/Adventure</td> 
                        </tr>
                </table>
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
