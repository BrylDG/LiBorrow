<?php
    session_start();
    include('connection.php');

    if (!isset($_SESSION['idno'])) {
        header("Location: login.php");
        exit();
    }
    $idno = $_SESSION['idno'];

    $query = "SELECT * FROM pendings WHERE idno = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idno);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions Section</title>
</head>
<body>

<div class="content-box" id="content2">
    <div class="container">
        <div class="Userinput" id="search-boks">
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

        <!-- Stacking 5 divs inside the Trbox class -->
        <div id="td1" class="Trbox Browbox">
            <div class="pendbox global" id="User-books">
                <?php foreach ($books as $book): ?>
                <div class="book-container" id="Request-Books">
					<a href="javascript:void(0);" onclick="viewDetails(<?php echo $book['bookid']; ?>)">
                    <img src="<?php echo htmlspecialchars($book['bookimg']); ?>" alt="Book one" class="book-image" id="book-loc" width="100" height="150">
					</a>
                    <img src="./Images/Rating Component.svg" alt="rating one" id="rating-image" width="150" height="150">
                    <p id="B-title"><?php echo htmlspecialchars($book['booktitle']); ?></p>
                    <p id="Book-Author"><?php echo htmlspecialchars($book['author']); ?></p>
                    <p id="Req-Date">Request Date: <?php echo htmlspecialchars($book['requestdate']); ?></p>
                    <form action="RemoveFromRequest.php" method="POST">
                        <input type="hidden" name="fullname" value="<?php echo htmlspecialchars($_SESSION['fullname']); ?>">
                        <input type="hidden" name="idno" value="<?php echo htmlspecialchars($_SESSION['idno']); ?>">
                        <input type="hidden" name="bookid" value="<?php echo htmlspecialchars($book['bookid']); ?>">
                        <button id="return-btn">Cancel</button>
                    </form>
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
