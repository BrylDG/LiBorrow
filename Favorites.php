<?php
session_start();
include('connection.php');

if (!isset($_SESSION['idno'])) {
    header("Location: login.php");
    exit();
}

$UserID = $_SESSION['idno'];

$query = "SELECT f.bookid, 
                 f.booktitle, 
                 f.author, 
                 f.bookimg
           FROM favorites f
           WHERE f.idno = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $UserID);
$stmt->execute();
$result = $stmt->get_result();
$books = $result->fetch_all(MYSQLI_ASSOC);

foreach($books as &$book) {
    $bookid = $book['bookid'];
    $idno = $_SESSION['idno']; // Assume idno is stored in session
    $borrowed = false;

    $borrow_query = "SELECT * FROM borrows WHERE bookid = ? AND idno = ?";
    $stmt = $conn->prepare($borrow_query);
    $stmt->bind_param("ii", $bookid, $idno);
    $stmt->execute();
    $borrow_result = $stmt->get_result();

    if ($borrow_result->num_rows > 0) {
        $borrowed = true;
    }

    // Set button text, class, and icon based on whether the book is borrowed
    if ($borrowed) {
        $book['BorrowButton'] = 'RETURN';
        $book['BorrowStatus'] = 'stats-btn2';
        $book['BorrowIcon'] = './Images/Check.svg';
    } else {
        $book['BorrowButton'] = 'BORROW';
        $book['BorrowStatus'] = 'stats-btn';
        $book['BorrowIcon'] = './Images/Unavalable.svg';
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions Section</title>
    <link rel="stylesheet" href="./TransactionsStyle.css"> <!-- Linking the CSS file here -->
    <link rel="stylesheet" href="./Browse.css">
</head>
<body>

<div class="content-box" id="content2">
    <div class="container">
        <div class="User input" id="search-bok">
            <div class="search-bar">
                <input type="text" placeholder=" Search...">
                <span class="forsearch-icon">
                    <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                </span>
            </div>
            <button class="sort-btn">
                <img src="./Images/Sort.svg" alt="Icon Before" width="20" height="20"> <!-- Icon before text -->
                Sort By
                <img src="./Images/vec.svg" alt="Icon After" width="18" height="18"> <!-- Icon after text -->
            </button>
        </div>
        <div class="pendbox global" id="User -books">
            <?php foreach ($books as $book): ?>
                <div class="book-container">
                    <div class="button-container">
                        <button id="<?php echo $book['BorrowStatus'] ?>">
                            <img src="<?php echo $book['BorrowIcon'] ?>" alt="Book status" class="book-status" id="bookstaticon" width="30" height="30">
                            <?php echo $book['BorrowButton']; ?>
                        </button>
                        <form action="RemoveFromFav.php" method="POST" style="display:inline;">
                            <input type="hidden" name="bookid" value="<?php echo htmlspecialchars($book['bookid']); ?>">
                            <input type="hidden" name="idno" value="<?php echo htmlspecialchars($_SESSION['idno']); ?>">
                            <button type="submit" id="addtofav-btn">
                                <img src="./Images/AddedtoFav.svg" alt="Book fav" class="book-fav">
                            </button>
                        </form>
                    </div>
                    <img src="<?php echo htmlspecialchars($book['bookimg']) ?>" alt="Book Thumbnail" class="book-image" id="book-opacity" width="50" height="50">
                    <img src="./Images/Rating Component.svg" alt="rating one" id="rating-image" width ="150" height="150">
                    <p id="B-title"><?php echo htmlspecialchars($book['booktitle']); ?></p>
                    <p id="Book-Author"><?php echo htmlspecialchars($book['author']); ?></p>
                    <button id="borbtn2"><?php echo $book['BorrowButton']; ?></button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    const titleElement = document.getElementById('Book-tittle2');

    titleElement.addEventListener('click', () => {
        if (titleElement.style.whiteSpace === 'normal') {
            titleElement.style.whiteSpace = 'nowrap';
            titleElement.style.overflow = 'hidden';
            titleElement.style.maxWidth = '25px'; // Collapse to show only the first two letters
        } else {
            titleElement.style.whiteSpace = 'normal';
            titleElement.style.overflow = 'visible';
            titleElement.style.maxWidth = '200px'; // Expand to show the full title
        }
    });
</script>    
</body>
</html>