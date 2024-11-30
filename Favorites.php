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
                     f.bookimg,
                     CASE
                        WHEN br.status = 1 THEN 'RETURN'
                        ELSE 'BORROW'
                     END AS BorrowButton,
                     CASE
                        WHEN br.status = 1 THEN 'stats-btn2'
                        ELSE 'stats-btn'
                     END AS BorrowStatus,
                     CASE
                        WHEN br.status = 1 THEN './Images/Check.svg'
                        ELSE './Images/Unavalable.svg'
                     END AS BorrowIcon
               FROM favorites f
               LEFT JOIN borrows br ON f.bookid = br.bookid AND br.idno = ?
               WHERE f.idno = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $UserID, $UserID);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = $result->fetch_all(MYSQLI_ASSOC);

    foreach($books as $book) {
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
    <link rel="stylesheet" href="./Browse.ccs">
    <link rel="stylesheet" href="./TransactionsStyle.css">
<body>

<div class="content-box" id="content2">
    <div class="container">
        <div class="Userinput" id="search-bok"  style="position: static;">
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
            <div class="pendbox global" id="User-books">
				<?php foreach ($books as $book): ?>
					<div class="book-container">
						<div class="button-container">
                            <button id="<?php echo $borrowed ? 'stats-btn2' : 'stats-btn' ?>">
                                <img src="<?php echo $borrowed ? './Images/Check.svg' : './Images/Unavalable.svg'; ?>" alt="Book status" class="book-status" id="bookstaticon" width="30" height="30">
                                Borrowed
                            </button>
							<form action="RemoveFromFav.php" method="POST" style="display:inline;">
								<input type="hidden" name="bookid" value="<?php echo htmlspecialchars($book['bookid']); ?>">
								<input type="hidden" name="idno" value="<?php echo htmlspecialchars($_SESSION['idno']); ?>">
								<button type="submit" id="addtofav-btn">
									<img src="./Images/AddedtoFav.svg" alt="Remove from favorites" class="book-fav">
								</button>
							</form>
						</div>
						<a href="javascript:void(0);" onclick="viewDetails(<?php echo $book['bookid']; ?>)">
							<img src="<?php echo htmlspecialchars($book['bookimg']) ?>" alt="Book Thumbnail" class="book-image" id="book-opacity" width="100" height="150">
						</a>
						<img src="./Images/Rating Component.svg" alt="Rating" id="rating-image" width="150" height="150">
						<p id="B-title"><?php echo htmlspecialchars($book['booktitle']); ?></p>
						<p id="Book-Author"><?php echo htmlspecialchars($book['author']); ?></p>
						<form action="borrowbook.php" method="POST" style="display:inline;">
								<input type="hidden" name="bookid" value="<?php echo htmlspecialchars($book['bookid']); ?>">
								<input type="hidden" name="fullname" value="<?php echo htmlspecialchars($_SESSION['fullname']); ?>">
								<input type="hidden" name="booktitle" value="<?php echo htmlspecialchars($book['booktitle']); ?>">
								<input type="hidden" name="author" value="<?php echo htmlspecialchars($book['author']); ?>">
								<input type="hidden" name="bookimg" value="<?php echo htmlspecialchars($book['bookimg']); ?>">
								<input type="hidden" name="action" value="<?php echo $borrowed ? 'return' : 'borrow'; ?>">
								
								<?php if ($book['BorrowButton'] === 'BORROW'): ?>
									<input type="hidden" name="action" value="borrow">
									<button type="submit" id="borrow-btn"><?php echo $book['BorrowButton']; ?></button>
								<?php else: ?>
									<input type="hidden" name="action" value="return">
									<button type="submit" id="return-btn">Return</button>
								<?php endif; ?>
							</form>
					</div>
				<?php endforeach; ?>
			</div>
            
        </div>
    </div>

</div>

</body>
</html>
