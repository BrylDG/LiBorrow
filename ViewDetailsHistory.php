<?php
// Include database connection
include('connection.php');

// Start session if needed
session_start();

// Initialize variables
$profilePicture = 'default_profile_picture.jpg'; // Fallback profile picture

// Check if the user ID is set in the session
if (isset($_SESSION['idno'])) {
    $userid = $_SESSION['idno'];

    // Query to fetch user profile picture
    $user_query = "SELECT profile_picture FROM users WHERE idno = ?";
    $user_stmt = $conn->prepare($user_query);
    $user_stmt->bind_param("i", $userid);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user = $user_result->fetch_assoc();

    // Check if the user exists and set profile picture
    if ($user) {
        $profilePicture = htmlspecialchars($user['profile_picture'] ?? 'default_profile_picture.jpg');
    }
}

// Check if the bookid is passed
if (isset($_GET['bookid'])) {
    $bookid = $_GET['bookid'];

    // Query to fetch book details based on the bookid
    $query = "SELECT b.*, GROUP_CONCAT(g.name SEPARATOR ', ') AS genres 
              FROM books b
              JOIN bookgenres bg ON b.bookid = bg.bookid
              JOIN genres g ON bg.genreid = g.genreid
              WHERE b.bookid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bookid);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    // Check if the book exists
    if ($book) {
        // Fetch comments for the book
        $comment_query = "SELECT c.commentid, c.comment, c.comment_date, u.username 
                          FROM comments c 
                          JOIN users u ON c.idno = u.idno 
                          WHERE c.bookid = ? 
                          ORDER BY c.comment_date DESC"; 
        $comment_stmt = $conn->prepare($comment_query);
        $comment_stmt->bind_param("i", $bookid);
        $comment_stmt->execute();
        $comment_result = $comment_stmt->get_result();
        $comments = $comment_result->fetch_all(MYSQLI_ASSOC);
    } else {
        $book = null; // Book not found
    }
} else {
    $book = null; // No book ID provided
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="stylesheet" href="./Browse.css">
    <link rel="stylesheet" href="./ViewDetails.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
	<style>
        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .book-container {
            display: inline-block;
            margin: 10px;
            text-align: center;
        }

        .book-container img {
            border-radius: 5px;
        }

        #similar-books-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        #similar-books-btn {
            margin-top: 2%;
            font-size: 12px;
            padding: 8px 20px;
            border-radius: 30px;
            cursor: pointer;
            border: 1px solid #ff6600;
            background-color: white;
            color: #ff6600;
        }
    </style>
</head>
<body>

<div class="content-box">
    <div class="Details-container">
        <div class="actions">
            <p>Browse</p>
            <img src="./Images/Expand_right.svg" alt="expand-icon" width="15" height="15" id="expan">
            <p id="book-title"><?php echo htmlspecialchars($book['booktitle'] ?? 'Book Title Not Found'); ?></p>
        </div>
        <div class="Viewdiv1">
            <div class="Book-Cover">
                <img id="book-cover" src="<?php echo htmlspecialchars($book['bookimg'] ?? ''); ?>" alt="Book Cover" height="400">
            </div>
            <div class="BookDetails-View">
                <div class="title-book">
                    <h2 id="main-title"><?php echo htmlspecialchars($book['booktitle'] ?? ''); ?></h2>
                    <div class="status">
                        <button class="av-btn" id="stats-btn">
                            <img src="./Images/Check.svg" alt="Book status" class="book-status" id="staticon" width="19" height="19">
                            Available
                        </button>
                    </div>
                </div>
                <div class="auth">
                    <p id="author">
                        <?php 
                        if ($book) {
                            echo "Author: " . htmlspecialchars($book['author'] ?? '') . " | Publication Date: " . htmlspecialchars($book['pubdate'] ?? '');
                        } else {
                            echo "Author information not available.";
                        }
                        ?>
                    </p>
                </div>
                <div class="Ave-Rating" id="book-rating">
                    <p>Average Rating: 
                        <span id="Rate-Numbers"><?php echo htmlspecialchars($book['average_rating'] ?? 'N/A'); ?></span>
                    </p>
                </div>
                <div id="Genre">
                    <p id="genres"><?php echo "Genre: " . htmlspecialchars($book['genres'] ?? ''); ?></p>
                </div>
                <div class="desc">
                    <p><span id="desnuts">Description:</span><br id="book-description"><?php echo htmlspecialchars($book['descrpt'] ?? 'No description available.'); ?></p>
                    <a id="Read-More" href="#">Read More</a>
                </div>
				<!-- Similar Books Button -->
				<div class="similar-books">
					<button id="similar-books-btn" onclick="fetchSimilarBooks(<?php echo $book['bookid']; ?>)">Similar Books</button>
				</div>

				<!-- Modal Structure -->
				<div id="similarBooksModal" class="modal">
					<div class="modal-content">
						<span class="close" onclick="closeModal()">&times;</span>
						<h2>Similar Books</h2>
						<div id="similar-books-container"></div>
					</div>
				</div>
            </div>
        </div>

        <div class="Viewdiv2">
            <div class="Revs">
                <h4>Reviews:</h4>
                <div id="reviews-container">
                    <?php if ($comments): ?>
                        <?php
                        foreach ($comments as $comment) {
                            echo '<div class="review">';
                            echo '<strong>' . htmlspecialchars($comment['username']) . '</strong>: ' . htmlspecialchars($comment['comment']) . ' <em>' . htmlspecialchars($comment['comment_date']) . '</em>';
                            echo '</div>';
                        }
                        ?>
                    <?php else: ?>
                        <p>No reviews available for this book.</p>
                    <?php endif; ?>
                </div>
                <hr>
            </div>
            <div class="Viewdiv3">
                <div class="pof">
                    <img src="<?php echo $profilePicture; ?>" alt="User  Profile Picture" height="50" width="50" style="border-radius:50%">
                </div>
                <div id="input-comment">
                    <input type="text" id="comment-input" placeholder="Write a Review...">    
                    <button id="submit-comment-btn" data-bookid="<?php echo $book['bookid']; ?>" onclick="submitComment(this)">Post Review</button>
                </div>
            </div>
        </div> <!-- End of container -->
    </div> <!-- End of content-box -->
</div>

</body>
</html>