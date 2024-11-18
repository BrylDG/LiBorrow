<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions Section</title>
    <link rel="stylesheet" href="./TransactionsStyle.css"> <!-- Linking the CSS file here -->
</head>
<body>

<div class="content-box" id="content2">
    <div class="container">
        <div class="input">
            <div class="search-bar">
                <input type="text" placeholder=" Search...">
                <span class="search-icon">
                    <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                </span>
            </div>
            <button class="sort-btn">
                <img src="./Images/Sort.svg" alt="Icon Before" width="20" height="20"> <!-- Icon before text -->
                Sort By
                <img src="./Images/vec.svg" alt="Icon After" width="18" height="18"> <!-- Icon after text -->
            </button>
            <button class="filter-btn">
                <img src="./Images/Filter_alt_fill.svg" alt="Icon Before" width="20" height="20"> <!-- Icon before text -->
                Filter By
                <img src="./Images/Expand_down.svg" alt="Icon After" width="18" height="18"> <!-- Icon after text -->
            </button>
        </div>

        <!-- Dynamic pending request boxes -->
        <div id="td1" class="Trbox">
            <?php
            // Connect to the database
            include("connection.php");

            // Check the connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query the pendings table
            $sql = "SELECT fullname, requestdate, booktitle, author, bookimg FROM pendings";
            $result = $conn->query($sql);

            // Check if there are results
            if ($result->num_rows > 0) {
                // Output data for each row
                while($row = $result->fetch_assoc()) {
                    echo '
                    <div class="pendbox one">
                        <div class="pendbox-globals">
                            <img src="' . htmlspecialchars($row["bookimg"]) . '" alt="Book image" class="book-img">
                                <p class="name">' . htmlspecialchars($row["fullname"]) . '</p>
                                <p class="date">Request Date: ' . htmlspecialchars($row["requestdate"]) . '</p>
                                <p class="btitle">' . htmlspecialchars($row["booktitle"]) . '</p>
                                <p class="author">' . htmlspecialchars($row["author"]) . '</p>
                            <div class="action-buttons">
                                <button class="approve-btn">Approve</button>
                                <button class="decline-btn">Decline</button>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo "<p>No pending requests found.</p>";
            }

            // Close the connection
            $conn->close();
            ?>
        </div>
    </div>

    <script>
        // JavaScript for button functionality can be added here if needed
    </script>    
</div>

</body>
</html>
