<?php
include("connection.php");

$response = []; // Initialize response array

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booktitle = $conn->real_escape_string($_POST['booktitle']);
    $fullname = $conn->real_escape_string($_POST['fullname']);

    if (isset($_POST['approve'])) {
        // Approval logic
        $sql = "SELECT bookid,idno, bookimg, author FROM pendings WHERE TRIM(booktitle) = TRIM('$booktitle') AND TRIM(fullname) = TRIM('$fullname')";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $borrowDate = date('Y-m-d H:i:s');
            $dueDate = date('Y-m-d H:i:s', strtotime('+30 days'));

            $insertSql = "INSERT INTO borrows (bookid,idno, fullname, bookimg, booktitle, author, borrowdate, duedate) 
                          VALUES ('{$row['bookid']}','{$row['idno']}', '$fullname', '{$row['bookimg']}', '$booktitle', '{$row['author']}', '$borrowDate', '$dueDate')";
            
            if ($conn->query($insertSql) === TRUE) {
                $conn->query("DELETE FROM pendings WHERE TRIM(booktitle) = TRIM('$booktitle') AND TRIM(fullname) = TRIM('$fullname')");
                $conn->query("INSERT INTO notification (idno, details, booktitle, time) VALUES ('{$row['idno']}', 'Your book request {$booktitle} approved successfully.', '$booktitle', CURRENT_TIMESTAMP())");
                $response = [
                    'status' => 'success',
                    'message' => 'Request approved successfully.'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error: ' . $conn->error
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No matching record found.'
            ];
        }
    } elseif (isset($_POST['cancel'])) {
        // Cancellation logic
        $deleteSql = "DELETE FROM pendings WHERE TRIM(booktitle) = TRIM('$booktitle') AND TRIM(fullname) = TRIM('$fullname')";
        if ($conn->query($deleteSql) === TRUE) {
            $response = [
                'status' => 'success',
                'message' => 'Request canceled successfully.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error: ' . $conn->error
            ];
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Stop further execution
}

// Query to display pending requests
$sql = "SELECT fullname, requestdate, booktitle, author, bookimg FROM pendings";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions Section</title>
    <link rel="stylesheet" href="./TransactionsStyle.css">
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
                <img src="./Images/Sort.svg" alt="Sort Icon" width="20" height="20"> Sort By
                <img src="./Images/vec.svg" alt="Icon After" width="18" height="18">
            </button>
            <button class="filter-btn">
                <img src="./Images/Filter_alt_fill.svg" alt="Filter Icon" width="20" height="20"> Filter By
                <img src="./Images/Expand_down.svg" alt="Expand Icon" width="18" height="18">
            </button>
        </div>

        <div id="td1" class="Trbox">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="pendbox-one">
                        <p class="name"><?= htmlspecialchars($row["fullname"]) ?></p>
                        <p class="date">Request Date: <?= htmlspecialchars($row["requestdate"]) ?></p>
                        <div class="pendbox global">
                            <img src="<?= htmlspecialchars($row["bookimg"]) ?>" alt="Book Image" width="100" height="150">
                            <p class="book-title"><?= htmlspecialchars($row["booktitle"]) ?></p>
                            <p class="author"><?= htmlspecialchars($row["author"]) ?></p>
                            <input type="hidden" class="booktitle" value="<?= htmlspecialchars($row["booktitle"]) ?>">
                            <input type="hidden" class="fullname" value="<?= htmlspecialchars($row["fullname"]) ?>">
                            <button class="approve-btn">Approve</button>
                            <button class="decline-btn">Cancel</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No pending requests found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>