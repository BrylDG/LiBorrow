<?php
include("connection.php");

$response = []; // Initialize response array

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booktitle = $conn->real_escape_string($_POST['booktitle']);
    $fullname = $conn->real_escape_string($_POST['fullname']);

    if (isset($_POST['approve'])) {
        // Approval logic
        $sql = "SELECT bookid, idno, bookimg, author FROM pendings WHERE TRIM(booktitle) = TRIM('$booktitle') AND TRIM(fullname) = TRIM('$fullname')";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $borrowDate = date('Y-m-d H:i:s');
            $dueDate = date('Y-m-d H:i:s', strtotime('+30 days'));

            $insertSql = "INSERT INTO borrows (bookid, idno, fullname, bookimg, booktitle, author, borrowdate, duedate) 
                          VALUES ('{$row['bookid']}', '{$row['idno']}', '$fullname', '{$row['bookimg']}', '$booktitle', '{$row['author']}', '$borrowDate', '$dueDate')";
            
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

    // Return JSON response for POST requests
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Stop further execution
}

// Handle AJAX requests for filtering, searching, and sorting
if (isset($_GET['action']) && $_GET['action'] == 'filter') {
    $searchTerm = $conn->real_escape_string($_GET['search']);
    $sortBy = $conn->real_escape_string($_GET['sort']);
    
    $sql = "SELECT fullname, requestdate, booktitle, author, bookimg FROM pendings WHERE booktitle LIKE '%$searchTerm%'";

    if ($sortBy == 'booktitle') {
        $sql .= " ORDER BY booktitle";
    } elseif ($sortBy == 'author') {
        $sql .= " ORDER BY author";
    }

    $result = $conn->query($sql);
    $books = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }

    echo json_encode($books);
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
        <div class="input" id="pending-input">
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Search by book title..." onkeyup="loadTransactions()">
                <span class="search-icon">
                    <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                </span>
            </div>
            <select id="sort-dropdown" onchange="loadTransactions()">
                <option value="">Sort By</option>
                <option value="booktitle">Book Title</option>
                <option value="author">Author</option>
            </select>
        </div>

        <!-- PENDING BOX -->
        <div id="td1" class="Trbox">

            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="pendbox-one">
                        <div class="pendbox-top" style="margin-top: 5%;">
                            <p class="name"><?= htmlspecialchars($row["fullname"]) ?></p>
                            <p class="date">Request Date: <?= htmlspecialchars($row["requestdate"]) ?></p>
                        </div>
                        <div class="pendbox global">
                            <div style="width: 50%; margin-right: 10%;">
                                <img src="<?= htmlspecialchars($row["bookimg"]) ?>" alt="Book Image" width="100" height="150">
                            </div>
                            <div style="width: 150%">
                                <p class="book-title"><?= htmlspecialchars($row["booktitle"]) ?></p>
                            </div>
                            <div style="width: 20%">
                                <p class="author"><?= htmlspecialchars($row["author"]) ?></p>
                            </div>
                            <div style="width: 150%">
                                <input type="hidden" class="booktitle" value="<?= htmlspecialchars($row["booktitle"]) ?>">
                            </div>
                            <div style="width: 20%">
                                <input type="hidden" class="fullname" value="<?= htmlspecialchars($row["fullname"]) ?>">
                            </div>
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