<?php
    session_start();
    include('connection.php');

    if (!isset($_SESSION['idno'])) {
        header("Location: login.php");
        exit();
    }
    
    $UserID = $_SESSION['idno'];
    
    $query = "
            SELECT 
                b.bookid,
                b.booktitle,
                b.author,
                CASE 
                    WHEN br.status = 1 THEN 'RETURN'
                    ELSE 'BORROW'   
                END AS BorrowButton
            FROM books b
            INNER JOIN favorites f ON b.BookID = f.BookID
            LEFT JOIN borrows br ON b.bookid = br.bookid AND br.idno = ?
            WHERE f.idno = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $UserID, $UserID);
    $stmt->execute();
    $result = $stmt->get_result();
    $favorites = $result->fetch_all(MYSQLI_ASSOC);
    
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
</head>
<body>

<div class="content-box" id="content2">
    <div class="container">
        <div class="Userinput" id="search-bok">
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
                <?php foreach ($favorites as $book): ?>
                    <div class="book-container">
                        <div class="button-container">
                            <button id="stats-btn3">
                                <img  src="./Images/Reading.svg" alt="Book status" class="book-status" id="bookstaticon" width="30" height="30">
                                Currently Reading
                            </button>
                            <button id="addtofav-btn">
                                <img src="./Images/AddedtoFav.svg" alt="Book fav" class="book-fav">
                            </button>
                        </div>
                        <img src="./Images/handmaid.svg" alt="Book one" class="book-image" id="book-opacity">
                        <img src="./Images/Rating Component.svg" alt="rating one" id="rating-image" width="150" height="150">
                        <p id="B-title"><?php echo htmlspecialchars($book['booktitle']); ?></p>
                        <p id="Book-Author"><?php echo htmlspecialchars($book['author']); ?></p>
                        <button id="borbtn2"><?php echo $book['BorrowButton']; ?></button>
                    </div>
                <?php endforeach; ?>
            </div>
            
        </div>
    </div>

    <script>
        //wala ni gamit hehe
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
</div>

</body>
</html>
