<?php
    session_start();
    include('connection.php');

    if (!isset($_SESSION['idno'])) { // Replace 'user_id' with your session variable for logged-in users
        header("Location: login.php"); // Redirect to the login page
        exit(); // Make sure to exit after the redirect
    }

    $query = "SELECT * FROM borrows";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
?>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="UserMyBooks.css">
</head>

<div class="Reader-MyBooks container-fluid d-flex p-0">
    <div class="MBcontainer" style="padding-left: 15%;">
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
            <button class="filter-btn">
                <img src="./Images/Filter_alt_fill.svg" alt="Icon Before" width="20" height="20"> <!-- Icon before text -->
                Filter By
                <img src="./Images/Expand_down.svg" alt="Icon After" width="18" height="18"> <!-- Icon after text -->
            </button>
        </div>  

        <div class="bottom-section col-12" style="padding-left: 2%;">
            <h1>Currently Borrowed</h1>
            <!-- TOP ROW -->
            <div class="top-row">
                <div class="MyBooksContainer">
                    <div class="BookContainer">
                        <?php foreach ($books as $book): ?>
                            <?php
                                $dueDate = new DateTime($book['duedate']);
                                $currentDate = new DateTime();
                                $interval = $currentDate->diff($dueDate);

                                if ($currentDate > $dueDate) {
                                    $timeLeft = "Overdue!";
                                } else {
                                    $timeLeft = $interval->days . " days " . $interval->h . " hrs " . $interval->i . " mins left";
                                }
                            ?>
							
                            <div class="Books">
								<a href="javascript:void(0);" onclick="viewDetails(<?php echo $book['bookid']; ?>)">
                                <img src="<?php echo htmlspecialchars($book['bookimg']); ?>" height="185px" width="140px">
								</a>
                                <div class="ProgBarContainer">
                                    <div class="progress" style="height: 10px;">
                                    <?php
                                        $borrowDate = new DateTime($book['borrowdate']);
                                        $totalDuration = $borrowDate->diff($dueDate)->days * 24 * 60 + $borrowDate->diff($dueDate)->h * 60 + $borrowDate->diff($dueDate)->i;
                                        $timeElapsed = $borrowDate->diff($currentDate)->days * 24 * 60 + $borrowDate->diff($currentDate)->h * 60 + $borrowDate->diff($currentDate)->i;

                                        $progressPercentage = min(100, max(0, ($timeElapsed / $totalDuration) * 100));
                                    ?>
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $progressPercentage; ?>%;" aria-valuenow="<?php echo $progressPercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <p class="ReadStatus"><?php echo $timeLeft; ?></p>   
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- BOTTOM ROM -->
            <div class="bottom-row">
                <div class="MyBooksContainer">
                    <div class="BookContainer">
                        <div class="Books">

                        </div>
                        <div class="Books">
                            
                        </div>
                        <div class="Books">
                            
                        </div>
                        <div class="Books">
                            
                        </div>
                        <div class="Books">

                        </div>
                        <div class="Shelf"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>