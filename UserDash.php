<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="UserDash.css">
</head>
<?php
include('connection.php'); // Include your database connection

// Fetch the count of books by main category using the bookgenres table
$query = "
    SELECT 
        CASE 
            WHEN g.name IN ('Romance', 'Mystery', 'Fantasy', 'SciFi') THEN 'Fiction'
            WHEN g.name IN ('Biography', 'History', 'Self-Help') THEN 'Non-Fiction'
            WHEN g.name IN ('Adventure', 'Thriller') THEN 'Action'
            WHEN g.name IN ('Poetry', 'Cooking', 'Graphic Novel') THEN 'Others'
            ELSE 'Unknown'
        END AS main_category,
        COUNT(b.bookid) AS count
    FROM books b
    JOIN bookgenres bg ON b.bookid = bg.bookid
    JOIN genres g ON bg.genreid = g.genreid
    GROUP BY main_category;
";

$result = mysqli_query($conn, $query);

// Initialize an array to hold the counts
$categoryCounts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categoryCounts[$row['main_category']] = $row['count'];
}

$commentedBooksQuery = "
    SELECT b.bookid, b.booktitle, b.author, b.bookimg, COUNT(c.commentid) AS comment_count
    FROM books b
    JOIN comments c ON b.bookid = c.bookid
    GROUP BY b.bookid, b.booktitle, b.author, b.bookimg
    ORDER BY comment_count DESC
    LIMIT 5
";
$commentedBooksResult = mysqli_query($conn, $commentedBooksQuery);

$newReleasesQuery = "
    SELECT bookid, booktitle, author, bookimg, pubdate
    FROM books
    ORDER BY pubdate DESC
    LIMIT 5
";
$newReleasesResult = mysqli_query($conn, $newReleasesQuery);

$borrowedBooksQuery = "
    SELECT b.bookid, b.booktitle, b.author, b.bookimg, bo.borrowdate, bo.duedate
    FROM borrows bo
    JOIN books b ON bo.bookid = b.bookid
    ORDER BY bo.borrowdate DESC
    LIMIT 2
";
$borrowedBooksResult = mysqli_query($conn, $borrowedBooksQuery);

// Fetch the most recently borrowed book
$recentBorrowQuery = "
    SELECT b.bookid, b.booktitle, b.author, b.bookimg, g.name AS genre
    FROM borrows bo
    JOIN books b ON bo.bookid = b.bookid
    JOIN bookgenres bg ON b.bookid = bg.bookid
    JOIN genres g ON bg.genreid = g.genreid
    ORDER BY bo.borrowdate DESC
    LIMIT 1
";
$recentBorrowResult = mysqli_query($conn, $recentBorrowQuery);
$recentBook = mysqli_fetch_assoc($recentBorrowResult);

if ($recentBook) {
    // Get the genre of the recently borrowed book
    $genre = $recentBook['genre'];

    // Fetch books that have the same genre as the recently borrowed book
    $recommendedBooksQuery = "
        SELECT b.bookid, b.booktitle, b.author, b.bookimg
        FROM books b
        JOIN bookgenres bg ON b.bookid = bg.bookid
        JOIN genres g ON bg.genreid = g.genreid
        WHERE g.name = '$genre' AND b.bookid != {$recentBook['bookid']}
        LIMIT 1
    ";
    $recommendedBooksResult = mysqli_query($conn, $recommendedBooksQuery);
}
?>
<div class="Reader-Home container-fluid d-flex p-0">

    <!-- SEARCHBAR AND SORT BUTTON -->
    <div class="inputSection">
         <div class="search-bar">
            <input type="text" placeholder=" Search...">
            <span class="search-icon">
                <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
            </span>
        </div>
        <button class="sort-btn">
            <img src="./Images/Sort.svg" alt="Icon Before" width="20" height="20"> <!-- Icon before text -->
                Sort By
            <img src="./Images/vec.svg" alt="Icon After" width="18" height="18"> <!-- Icon after text -->
        </button>
    </div>  

    <div class="bottom-section col-12">
        <!-- LEFT SIDE -->
        <div class="col-8 Left-Side">
            
            <!-- CONTENT HEADER -->
            <div class="ContentHeader" style="background-image: url(./Images/stackedBooks.jpg);">
                <h1>LiBorrow</h1>
                <div class="Clickable">
                    <p>Check out the latest and greatest stories hand-picked by our team.</p>
                </div>
            </div>

            <!-- GENRE SECTION -->
            <div class="GenreSectionContainer">
                <h1>Genre Sections</h1>
                <div class="GenreSection">
                    <div class="Section" id="Fiction">
                        <div class="left">
                            <h4>Fiction</h4>
                            <p>Romance</p>
                            <p>Mystery</ <p>Fantasy</p>
                            <p>SciFi</p>
                        </div>
                        <div class="right">
                            <h3><?php echo isset($categoryCounts['Fiction']) ? number_format($categoryCounts['Fiction']) : '0'; ?></h3>
                            <p>Books Available</p>
                        </div>
                    </div>
                    <div class="Section" id="Non-Fiction">
                        <div class="left">
                            <h4>Non-Fiction</h4>
                            <p>Biography</p>
                            <p>History</p>
                            <p>Self-Help</p>
                        </div>
                        <div class="right">
                            <h3><?php echo isset($categoryCounts['Non-Fiction']) ? number_format($categoryCounts['Non-Fiction']) : '0'; ?></h3>
                            <p>Books Available</p>
                        </div>
                    </div>
                    <div class="Section" id="Action">
                        <div class="left">
                            <h4>Action</h4>
                            <p>Adventure</p>
                            <p>Thriller</p>
                        </div>
                        <div class="right">
                            <h3><?php echo isset($categoryCounts['Action']) ? number_format($categoryCounts['Action']) : '0'; ?></h3>
                            <p>Books Available</p>
                        </div>
                    </div>
                    <div class="Section" id="Others">
                        <div class="left">
                            <h4>Others</h4>
                            <p>Poetry</p>
                            <p>Cooking</p>
                            <p>Graphic Novel</p>
                            <p>and etc.</p>
                        </div>
                        <div class="right">
                            <h3><?php echo isset($categoryCounts['Others']) ? number_format($categoryCounts['Others']) : '0'; ?></h3>
                            <p>Books Available</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FREATURED STORIES -->
            <div class="FeaturedStoriesSectionContainer">
				<h1>Featured Stories</h1>
				<div class="FeaturedBooksContainer">
					<?php
					if (mysqli_num_rows($commentedBooksResult) > 0) {
						while ($book = mysqli_fetch_assoc($commentedBooksResult)) {
							echo '<div class="Books">';
							echo '<img src="' . htmlspecialchars($book['bookimg']) . '" height="185px" width="125px">';
							echo '<p class="Title">' . htmlspecialchars($book['booktitle']) . '</p>';
							echo '<p class="Author">' . htmlspecialchars($book['author']) . '</p>';
							echo '<img src="./Images/RatingComponent.svg" class="rating">';
							echo '</div>';
						}
					} else {
						echo '<p>No featured stories available.</p>';
					}
					?>
				</div>
			</div>


            <!-- NEW RELEASE -->
            <div class="NewReleaseSectionContainer">
				<h1>New Release</h1>
				<div class="NewReleaseContainer">
					<?php
					if (mysqli_num_rows($newReleasesResult) > 0) {
						while ($book = mysqli_fetch_assoc($newReleasesResult)) {
							echo '<div class="Books">';
							echo '<img src="' . htmlspecialchars($book['bookimg']) . '" height="185px" width="125px">';
							echo '<p class="Title">' . htmlspecialchars($book['booktitle']) . '</p>';
							echo '<p class="Author">' . htmlspecialchars($book['author']) . '</p>';
							echo '<img src="./Images/RatingComponent.svg" class="rating">';
							echo '</div>';
						}
					} else {
						echo '<p>No new releases available.</p>';
					}
					?>
				</div>
			</div>
        </div>

        <!-- RIGH SIDE -->
        <div class="col-3 Right-Side">

            <!-- MY BOOKS -->
			<div class="MyBooksContainer">
				<h1>My Books</h1>

				<?php
				if (mysqli_num_rows($borrowedBooksResult) > 0) {
					while ($book = mysqli_fetch_assoc($borrowedBooksResult)) {
						// Calculate the total borrow duration and remaining time
						$borrowDate = new DateTime($book['borrowdate']);
						$dueDate = new DateTime($book['duedate']);
						$now = new DateTime();

						// Calculate the difference between now and the due date
						$interval = $now->diff($dueDate);

						// If the due date has passed, set remaining time to "Overdue"
						if ($interval->invert == 1) {
							$remainingTime = 'Overdue';
							$progress = 0;  // No progress for overdue books
						} else {
							// Format remaining time as days, hours, minutes
							$remainingTime = $interval->format('%a days %h hrs. %i mins left');

							// Calculate progress percentage
							$totalInterval = $borrowDate->diff($dueDate);
							$totalMinutes = ($totalInterval->days * 24 * 60) + ($totalInterval->h * 60) + $totalInterval->i;
							$elapsedMinutes = ($borrowDate->diff($now))->days * 24 * 60 + ($borrowDate->diff($now))->h * 60 + ($borrowDate->diff($now))->i;

							// Avoid division by zero if there's no borrow period (e.g., if due date is the same as borrow date)
							if ($totalMinutes > 0) {
								$progress = ($elapsedMinutes / $totalMinutes) * 100;
							} else {
								$progress = 0;
							}
						}

						// Display the book and progress
						echo '<div class="MyBook" id="Book' . $book['bookid'] . '">';
						echo '<div class="BookImage"><img src="' . htmlspecialchars($book['bookimg']) . '" height="150px" width="100px"></div>';
						echo '<div class="BookDetails">';
						echo '<p class="BookTitle">' . htmlspecialchars($book['booktitle']) . '</p>';
						echo '<p class="BookAuthor">' . htmlspecialchars($book['author']) . '</p>';
						echo '<img src="./Images/RatingComponent.svg" class="rating">';
						echo '<br>';
						echo '<a href="#" class="ViewDetailsButton">View Details</a>';
						echo '<div class="ProgBarContainer">';
						echo '<div class="progress">';
						echo '<div class="progress-bar bg-warning" role="progressbar" style="width:' . $progress . '%" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100"></div>';
						echo '</div>';
						echo '</div>';
						echo '<p class="ProgressStatus">' . $remainingTime . '</p>';
						echo '</div>';
						echo '</div>';
					}
				} else {
					echo '<p>No borrowed books available.</p>';
				}
				?>
			</div>

            <!-- RECOMMENDATIONS -->
            <div class="RecommendationsContainer">
				<h1>Recommendations</h1>
				<p id="BookSimilar">Books Similar to <a href="#"><?php echo htmlspecialchars($recentBook['booktitle']); ?></a></p>

				<?php
				if (mysqli_num_rows($recommendedBooksResult) > 0) {
					while ($recommendedBook = mysqli_fetch_assoc($recommendedBooksResult)) {
						echo '<div class="RecommendedBook">';
						echo '<img src="' . htmlspecialchars($recommendedBook['bookimg']) . '" id="RecommendationImage" height="150px" width="100px">';
						echo '<p id="RecoBook">' . htmlspecialchars($recommendedBook['booktitle']) . '</p>';
						echo '<p id="RecoAuthor">' . htmlspecialchars($recommendedBook['author']) . '</p>';
						echo '<img src="./Images/RatingComponent.svg" id="BookRating">';
						echo '</div>';
					}
				} else {
					echo '<p>No similar books available.</p>';
				}
				?>

				<button class="MoreButton">More Like This</button>
			</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>