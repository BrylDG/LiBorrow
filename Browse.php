<?php
    session_start(); // Start the session
    include('connection.php'); // Include your connection file
    
    // Check if the user is logged in
    if (!isset($_SESSION['idno'])) { // Replace 'user_id' with your session variable for logged-in users
        header("Location: login.php"); // Redirect to the login page
        exit(); // Make sure to exit after the redirect
    }
    // Retrieve the full name from the session
    $fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User '; // Default to 'User ' if not set
    $role = $_SESSION['isAdmin'];

    $query = "SELECT bookid, booktitle, author FROM books";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();
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
                    <a href="#" id="function">
                        <div class="icon-container">
                            <img src="./images/Genre-Romance.svg" alt="Romance Genre" class="genre-icon">
                            <p class="icon-label">Romance</p>
                        </div>
                    </a>
                    <!-- Icon 2 -->
                    <a href="#" id="function">
                        <div class="icon-container">
                            <img src="./images/Horror.svg" alt="Romance Genre" class="genre-icon">
                            <p class="icon-label">Horror</p>
                        </div>
                    </a>
                    <!-- Icon 3 -->
                    <a href="#" id="function">
                        <div class="icon-container">
                            <img src="./images/Sci-fi.svg" alt="Romance Genre" class="genre-icon">
                            <p class="icon-label">Scifi</p>
                        </div>
                    </a>
                    <!-- Icon 4 -->
                    <a href="#" id="function">
                        <div class="icon-container">
                            <img src="./images/Cooking.svg" alt="Romance Genre" class="genre-icon">
                            <p class="icon-label">Cooking</p>
                        </div>
                    </a>
                    <!-- Icon 5 -->
                    <a href="#" id="function">
                        <div class="icon-container">
                            <img src="./images/Historical.svg" alt="Romance Genre" class="genre-icon">
                            <p class="icon-label">Historical</p>
                        </div>
                    </a>
                    <!-- Icon 6 -->
                    <a href="#" id="function">
                        <div class="icon-container">
                            <img src="./images/Fantasy.svg" alt="Romance Genre" class="genre-icon">
                            <p class="icon-label">Fantasy</p>
                        </div>
                    </a>
                    <!-- Icon 7 -->
                    <a href="#" id="function">
                        <div class="icon-container">
                            <img src="./images/Msytery.svg" alt="Romance Genre" class="genre-icon">
                            <p class="icon-label">Mystery</p>
                        </div>
                    </a>
                    <!-- Icon 8 -->
                    <a href="#" id="function">
                        <div class="icon-container">
                            <img src="./images/Philosophy.svg" alt="Romance Genre" class="genre-icon">
                            <p class="icon-label">Philosophy</p>
                        </div>
                    </a>
                    <!-- Icon 9 -->
                    <a href="#" id="function">
                        <div class="icon-container">
                            <img src="./images/Business.svg" alt="Romance Genre" class="genre-icon">
                            <p class="icon-label">Business</p>
                        </div>
                    </a>
                    <!-- Icon 10 -->
                    <a href="#" id="function">
                        <div class="icon-container">
                            <img src="./images/Comedy.svg" alt="Romance Genre" class="genre-icon">
                            <p class="icon-label">Comedy</p>
                        </div>
                    </a>
                    <img src="./images/Expand_right.svg" alt="Vector " class="expand-icon">
                </div> 
            </div>
            
            <h3 id="All-Books">All Books</h3>
            <div class="pendbox global" id="User-books">
                <div class="userbooks-container">
                    <?php foreach ($books as $book): ?>
                        <div class="book-container">
                            <div class="button-container">
                                <button id="stats-btn">
                                    <img  src="./Images/Unavalable.svg" alt="Book status" class="book-status" id="bookstaticon" width="30" height="30">
                                    Borrowed
                                </button>
                                <button id="addtofav-btn">
                                    <img src="./Images/fav.svg" alt="Book fav" class="book-fav" >
                                </button>
                            </div>
                            <img src="" alt="">
                            <img src="" alt="">
                            <p id="B-title"><?php echo htmlspecialchars($book['booktitle']); ?></p>
                            <p id="Book-Author"><?php echo htmlspecialchars($book['author']); ?></p>
                            <button id="borbtn">Borrow</button>
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
