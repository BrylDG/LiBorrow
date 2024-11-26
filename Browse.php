<?php
	session_start(); // Start the session
	include('connection.php'); // Include your connection file

	// Check if the user is logged in
	if (!isset($_SESSION['idno'])) { // Replace 'user_id' with your session variable for logged-in users
		header("Location: login.php"); // Redirect to the login page
		exit(); // Make sure to exit after the redirect
	}

	// Retrieve the full name from the session
	$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User  '; // Default to 'User  ' if not set
	$role = $_SESSION['isAdmin'];

	// Get the selected genre from the GET parameters
	$selected_genre = isset($_GET['genre']) ? $_GET['genre'] : null;

	// Prepare the base query
	$query = "SELECT b.bookid, b.booktitle, b.author, b.bookimg, 
					 GROUP_CONCAT(g.name SEPARATOR ', ') AS genres 
			  FROM books b 
			  JOIN bookgenres bg ON b.bookid = bg.bookid 
			  JOIN genres g ON bg.genreid = g.genreid";

	if ($selected_genre) {
		// Use a prepared statement to prevent SQL injection
		$query .= " WHERE g.name = ?";
	}

	// Group by bookid to avoid duplicates
	$query .= " GROUP BY b.bookid";

	$stmt = $conn->prepare($query);
	if ($selected_genre) {
		$stmt->bind_param("s", $selected_genre);
	}
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
    <title>Browse Section</title>
    <link rel="stylesheet" href="./Browse.css">
</head>
<body>

<div class="browse-box" >
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
        
            <div class="UserBrowsebox">
                <div class="browse-genre " id="browse-books">
                    <h3 id="genre-heading">Genre</h3>
                    
                    <img src="./images/Expand_left.svg" alt="Vector " class="expand-icon">
                    <!-- Icon 1 -->
                    <a href="Browse.php?genre=Romance Fiction" id="function">
                        <div class="icon-container">
                            <img src="./images/Genre-Romance.svg" alt="Romance Genre" class="genre-icon">
                            <p class="icon-label">Romance</p>
                        </div>
                    </a>
                    <!-- Icon 2 -->
                    <a href="Browse.php?genre=Horror Fiction" id="function">
                        <div class="icon-container">
                            <img src="./images/Horror.svg" alt="Horror Genre" class="genre-icon">
                            <p class="icon-label">Horror</p>
                        </div>
                    </a>
                    <!-- Icon 3 -->
                    <a href="Browse.php?genre=Science Fiction" id="function">
                        <div class="icon-container">
                            <img src="./images/Sci-fi.svg" alt="Scifi Genre" class="genre-icon">
                            <p class="icon-label">Scifi</p>
                        </div>
                    </a>
                    <!-- Icon 4 -->
                    <a href="Browse.php?genre=Cooking Fiction" id="function">
                        <div class="icon-container">
                            <img src="./images/Cooking.svg" alt="Cooking Genre" class="genre-icon">
                            <p class="icon-label">Cooking</p>
                        </div>
                    </a>
                    <!-- Icon 5 -->
                    <a href="Browse.php?genre=Historical Fiction" id="function">
                        <div class="icon-container">
                            <img src="./images/Historical.svg" alt="Historical Genre" class="genre-icon">
                            <p class="icon-label">Historical</p>
                        </div>
                    </a>
                    <!-- Icon 6 -->
                    <a href="Browse.php?genre=Fantasy Fiction" id="function">
                        <div class="icon-container">
                            <img src="./images/Fantasy.svg" alt="Fantasy Genre" class="genre-icon">
                            <p class="icon-label">Fantasy</p>
                        </div>
                    </a>
                    <!-- Icon 7 -->
                    <a href="Browse.php?genre=Mystery Fiction" id="function">
                        <div class="icon-container">
                            <img src="./images/Msytery.svg" alt="Mystery Genre" class="genre-icon">
                            <p class="icon-label">Mystery</p>
                        </div>
                    </a>
                    <!-- Icon 8 -->
                    <a href="Browse.php?genre=Philosophical Fiction" id="function">
                        <div class="icon-container">
                            <img src="./images/Philosophy.svg" alt="Philosophy Genre" class="genre-icon">
                            <p class="icon-label">Philosophy</p>
                        </div>
                    </a>
                    <!-- Icon 9 -->
                    <a href="Browse.php?genre=Business Fiction" id="function">
                        <div class="icon-container">
                            <img src="./images/Business.svg" alt="Business Genre" class="genre-icon">
                            <p class="icon-label">Business</p>
                        </div>
                    </a>
                    <!-- Icon 10 -->
                    <a href="Browse.php?genre=Comedy Fiction" id="function">
                        <div class="icon-container">
                            <img src="./images/Comedy.svg" alt="Comedy Genre" class="genre-icon">
                            <p class="icon-label">Comedy</p>
                        </div>
                    </a>
                    <img src="./images/Expand_right.svg" alt="Vector " class="expand-icon">
                </div> 
            </div>
            
            <h3 id="All-Books">All Books</h3>
            <div class="pendbox global" id="User-books" style="height: 500px;">
                <div class="userbooks-container" style="display: flex; flex-wrap: wrap; gap: 20px;">
                    <?php foreach ($books as $book): ?>
                        <?php
                            $bookid = $book['bookid'];
                            $idno = $_SESSION['idno']; // Assume idno is stored in session
                            $borrowed = false;
                    
                            $borrow_query = "SELECT * FROM borrows WHERE bookid = ? AND idno = ?";
                            $stmt = $conn->prepare($borrow_query);
                            $stmt->bind_param("ii", $bookid, $idno);
                            $stmt->execute();
                            $borrow_result = $stmt->get_result();
                    
                            if ($borrow_result->num_rows > 0) {
                                $borrowed = true; // The book has been borrowed by the user
                            }
                        ?>
                        <div class="book-container">
                            <div class="button-container">
                            <button id="<?php echo $borrowed ? 'stats-btn2' : 'stats-btn' ?>">
                                <img src="<?php echo $borrowed ? './Images/Check.svg' : './Images/Unavalable.svg'; ?>" alt="Book status" class="book-status" id="bookstaticon" width="30" height="30">
                                Borrowed
                            </button>
                            <?php
                                $bookid = $book['bookid'];
                                $idno = $_SESSION['idno']; // Assume idno is stored in session
                                $favorite = false;
                        
                                $fav = "SELECT * FROM favorites WHERE bookid = ? AND idno = ?";
                                $stmt = $conn->prepare($fav);
                                $stmt->bind_param("ii", $bookid, $idno);
                                $stmt->execute();
                                $fav_res = $stmt->get_result();
                        
                                if ($fav_res->num_rows > 0) {
                                    $favorite = true;
                                }
                                $stmt->close();
                            ?>    
                            <form action="AddToFav.php" method="POST">
                                <input type="hidden" name="bookid" value="<?php echo htmlspecialchars($book['bookid']); ?>">
                                <input type="hidden" name="idno" value="<?php echo htmlspecialchars($_SESSION['idno']); ?>">
                                <input type="hidden" name="booktitle" value="<?php echo htmlspecialchars($book['booktitle']); ?>">
                                <input type="hidden" name="author" value="<?php echo htmlspecialchars($book['author']); ?>">
                                <input type="hidden" name="bookimg" value="<?php echo htmlspecialchars($book['bookimg']); ?>">
                                <button type="submit" id="addtofav-btn">
                                    <img src="<?php echo $favorite ? './Images/AddedtoFav.svg' : './Images/fav.svg' ?>" alt="Book fav" class="book-fav">
                                </button>
                            </form>
                            </div>
                            <img src="<?php echo htmlspecialchars($book['bookimg']); ?>" alt="Book Thumbnail" width="100" height="150">
                            <img src="./Images/Rating Component.svg" alt="rating one" id="rating-image" width="150" height="150">
                            <p id="B-title"><?php echo htmlspecialchars($book['booktitle']); ?></p>
                            <p id="Book-Author"><?php echo htmlspecialchars($book['author']); ?></p>
                            <form action="BorrowBook.php" method="POST">
                                <input type="hidden" name="bookid" value="<?php echo htmlspecialchars($book['bookid']); ?>">
                                <input type="hidden" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>">
                                <input type="hidden" name="booktitle" value="<?php echo htmlspecialchars($book['booktitle']); ?>">
                                <input type="hidden" name="author" value="<?php echo htmlspecialchars($book['author']); ?>">
                                <input type="hidden" name="bookimg" value="<?php echo htmlspecialchars($book['bookimg']); ?>">
                                <button id="<?php echo $borrowed ? 'borbtn2' : 'borbtn' ?>">Borrow</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
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
