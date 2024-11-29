<?php
    session_start();
    include('connection.php');
	header('Content-Type: application/json');
	
	if (!isset($_SESSION['fullname'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
    // Get the search and sort parameters from the GET request
    $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : '%';
    $sort = isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'DESC' : 'ASC';

    // Prepare the query
    $query = "SELECT b.bookid, b.booktitle, b.author, b.bookimg, GROUP_CONCAT(g.name SEPARATOR ', ') AS genres
              FROM books b
              JOIN bookgenres bg ON b.bookid = bg.bookid
              JOIN genres g ON bg.genreid = g.genreid
              WHERE b.booktitle LIKE ?
              GROUP BY b.bookid
              ORDER BY b.booktitle $sort";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = $result->fetch_all(MYSQLI_ASSOC);

    // Return data as JSON
    if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
        echo json_encode(['books' => $books]);
        exit();
    }
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
       <div class="Userinput" id="search-books">
            <div class="search-bar">
                <!-- Search input with ID 'search-input' -->
                <input type="text" id="search-input" placeholder="Search..." oninput="loadBooks()">
                <span class="forsearch-icon">
                    <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                </span>
            </div>
            <!-- Sort dropdown with ID 'sort-dropdown' -->
            <select id="sort-dropdown" onchange="loadBooks()">
                <option value="asc">Sort by Title (Ascending)</option>
                <option value="desc">Sort by Title (Descending)</option>
            </select>
        </div>

        <!-- Stacking 5 divs inside the Trbox class -->
        <div id="td1" class="Trbox Browbox">
        
			<div class="User Browsebox">
				<div class="browse-genre" id="browse-books">
					<h3 id="genre-heading">Genre</h3>
					<div class="carousel-container">
						<div class="carousel">
							<div class="carousel-item">
								<a href="Browse.php?genre=Romance Fiction" id="function">
									<div class="icon-container">
										<img src="./images/Genre-Romance.svg" alt="Romance Genre" class="genre-icon">
										<p class="icon-label">Romance</p>
									</div>
								</a>
								<a href="Browse.php?genre=Horror Fiction" id="function">
									<div class="icon-container">
										<img src="./images/Horror.svg" alt="Horror Genre" class="genre-icon">
										<p class="icon-label">Horror</p>
									</div>
								</a>
								<a href="Browse.php?genre=History Fiction" id="function">
									<div class="icon-container">
										<img src="./images/Genre-Romance.svg" alt="Romance Genre" class="genre-icon">
										<p class="icon-label">Romance</p>
									</div>
								</a>
								<a href="Browse.php?genre=Cat Fiction" id="function">
									<div class="icon-container">
										<img src="./images/Horror.svg" alt="Horror Genre" class="genre-icon">
										<p class="icon-label">Horror</p>
									</div>
								</a>
							</div>
							<div class="carousel-item">
								<a href="Browse.php?genre=Romance Fiction" id="function">
									<div class="icon-container">
										<img src="./images/Genre-Romance.svg" alt="Romance Genre" class="genre-icon">
										<p class="icon-label">Romance</p>
									</div>
								</a>
								<a href="Browse.php?genre=Horror Fiction" id="function">
									<div class="icon-container">
										<img src="./images/Horror.svg" alt="Horror Genre" class="genre-icon">
										<p class="icon-label">Horror</p>
									</div>
								</a>
							</div>						
						</div>
						<button class="carousel-button prev" onclick="moveCarousel(-1)">&#10094;</button>
						<button class="carousel-button next" onclick="moveCarousel(1)">&#10095;</button>
					</div>
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
							<a href="javascript:void(0);" onclick="viewDetails(<?php echo $book['bookid']; ?>)">
								<img src="<?php echo htmlspecialchars($book['bookimg']); ?>" alt="Book Thumbnail" width="100" height="150">
							</a>
                            <img src="./Images/Rating Component.svg" alt="rating one" id="rating-image" width="150" height="150">
                            <p id="B-title"><?php echo htmlspecialchars($book['booktitle']); ?></p>
                            <p id="Book-Author"><?php echo htmlspecialchars($book['author']); ?></p>
                            <form action="BorrowBook.php" method="POST">
								<input type="hidden" name="bookid" value="<?php echo htmlspecialchars($book['bookid']); ?>">
								<input type="hidden" name="fullname" value="<?php echo htmlspecialchars($_SESSION['fullname']); ?>">
								<input type="hidden" name="booktitle" value="<?php echo htmlspecialchars($book['booktitle']); ?>">
								<input type="hidden" name="author" value="<?php echo htmlspecialchars($book['author']); ?>">
								<input type="hidden" name="bookimg" value="<?php echo htmlspecialchars($book['bookimg']); ?>">
								<input type="hidden" name="action" value="<?php echo $borrowed ? 'return' : 'borrow'; ?>">
								<button id="<?php echo $borrowed ? 'return-btn' : 'borrow-btn'; ?>">
									<?php echo $borrowed ? 'Return' : 'Borrow'; ?>
								</button>
							</form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
