<?php
// Include the database connection file
include('connection.php');

// Initialize variables
$message = '';
$borrowedBook = null;

// Check if the required parameters are present
if (isset($_GET['idno']) && isset($_GET['booktitle'])) {
    $idno = $conn->real_escape_string($_GET['idno']);
    $booktitle = $conn->real_escape_string($_GET['booktitle']);

    // Fetch borrower and book details, including borrowdate and duedate
    $query = "SELECT br.idno, br.fullname, br.booktitle, br.duedate, br.borrowdate, b.bookid, b.author, b.bookimg 
              FROM borrows br
              INNER JOIN books b ON br.booktitle = b.booktitle
              WHERE br.idno = '$idno' AND br.booktitle = '$booktitle'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $borrowedBook = $result->fetch_assoc();
        // Debugging: check the fetched result
        //var_dump($borrowedBook); // This will show the entire result
    } else {
        $message = "No matching record found for this book and borrower.";
    }
}

// Handle the form submission for returning the book
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $conn->real_escape_string($_POST['status']);
    $idno = $conn->real_escape_string($_POST['idno']);
    $booktitle = $conn->real_escape_string($_POST['booktitle']);
    $borrowdate = $conn->real_escape_string($_POST['borrowdate']); // Get borrowdate from POST
    $duedate = $conn->real_escape_string($_POST['duedate']); // Get duedate from POST

    // Check if borrowdate is set and not empty
    if (!empty($borrowdate)) {
        // Insert into the returns table, including borrowdate and duedate
        $insertQuery = "INSERT INTO returns (bookid, booktitle, author, status, fullname, datereturned, borrowdate, duedate, bookimg, idno)
                        VALUES ('{$_POST['bookid']}', '$booktitle', '{$_POST['author']}', '$status', '{$_POST['fullname']}', NOW(), '$borrowdate', '$duedate', '{$_POST['bookimg']}', '$idno')";
        if ($conn->query($insertQuery)) {
            // Remove the book from the borrows table
            $deleteQuery = "DELETE FROM borrows WHERE idno = '$idno' AND booktitle = '$booktitle'";
            $conn->query($deleteQuery);

            // Success message
            $message = "Book returned successfully with status: $status.";
        } else {
            $message = "Error returning book: " . $conn->error;
        }
    } else {
        $message = "Borrow date is missing for this record.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
        }

        select, button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }    
    </style>
</head>
<body>
    <div class="container">
        <h1>Return Book</h1>

        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
            <a href="bootdash.php">Go Back to Dashboard</a> <!-- Redirect to your main dashboard -->
        <?php elseif ($borrowedBook): ?>
            <form action="returnBook.php" method="POST">
                <input type="hidden" name="idno" value="<?php echo htmlspecialchars($borrowedBook['idno']); ?>">
                <input type="hidden" name="booktitle" value="<?php echo htmlspecialchars($borrowedBook['booktitle']); ?>">
                <input type="hidden" name="bookid" value="<?php echo htmlspecialchars($borrowedBook['bookid']); ?>">
                <input type="hidden" name="author" value="<?php echo htmlspecialchars($borrowedBook['author']); ?>">
                <input type="hidden" name="fullname" value="<?php echo htmlspecialchars($borrowedBook['fullname']); ?>">
                <input type="hidden" name="bookimg" value="<?php echo htmlspecialchars($borrowedBook['bookimg']); ?>">
                <input type="hidden" name="borrowdate" value="<?php echo htmlspecialchars($borrowedBook['borrowdate']); ?>">
                <input type="hidden" name="duedate" value="<?php echo htmlspecialchars($borrowedBook['duedate']); ?>">

                <p>Book Title: <strong><?php echo htmlspecialchars($borrowedBook['booktitle']); ?></strong></p>
                <p>Author: <strong><?php echo htmlspecialchars($borrowedBook['author']); ?></strong></p>
                <p>Borrower: <strong><?php echo htmlspecialchars($borrowedBook['fullname']); ?></strong></p>
                <p>Borrow Date: <strong><?php echo htmlspecialchars($borrowedBook['borrowdate']); ?></strong></p>
                <p>Due Date: <strong><?php echo htmlspecialchars($borrowedBook['duedate']); ?></strong></p>

                <label for="status">Select Condition of Returned Book:</label>
                <select name="status" id="status" required>
                    <option value="Good Condition">Good Condition</option>
                    <option value="Minor Issues">Minor Issues</option>
                    <option value="Major Issues">Major Issues</option>
                </select>

                <button type="submit">Confirm Return</button>
            </form>
        <?php else: ?>
            <p>Invalid request or no record found.</p>
            <a href="bootdash.php">Go Back to Dashboard</a>
        <?php endif; ?>
    </div>
</body>
</html>