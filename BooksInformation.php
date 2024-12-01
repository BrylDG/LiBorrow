<?php
include('connection.php');

$book = null;
$bookGenres = [];

// Fetch book details
if (isset($_GET['bookid'])) {
    $bookId = intval($_GET['bookid']);

    // Fetch book details
    $stmt = $conn->prepare("SELECT * FROM books WHERE bookid = ?");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    }
    $stmt->close();

    // Fetch genres linked to this book
    $stmtGenres = $conn->prepare("SELECT genreid FROM bookgenres WHERE bookid = ?");
    $stmtGenres->bind_param("i", $bookId);
    $stmtGenres->execute();
    $resultGenres = $stmtGenres->get_result();

    while ($row = $resultGenres->fetch_assoc()) {
        $bookGenres[] = $row['genreid'];
    }
    $stmtGenres->close();
}

// Fetch all genres for the dropdown
$genreQuery = "SELECT genreid, name FROM genres";
$genreResult = $conn->query($genreQuery);

$genres = [];
while ($row = $genreResult->fetch_assoc()) {
    $genres[] = $row;
}

$conn->close();
?>

<div class="content-box" id="content2">
    <div class="container">
        <div id="Readd1" class="Binfobox">
            <div class="Readbox Rglobal" id="BboxView">
                <div class="Bview">
                    <img src="<?php echo htmlspecialchars($book['bookimg'] ?? './Images/FourthWing_book.png'); ?>" alt="Book Image" id="Bookpic" width="300px" height="400">
                </div>
                <div id="Book-details">
                    <form action="updateBook.php" method="POST" style="width:100%;"> <!-- Form starts here -->
                        <div class="top-row">
                            <div class="input-row">
                                <div class="input-container">
                                    <label for="input-one">Book ID</label>
                                    <input type="text" id="input-one" name="bookid" value="<?php echo htmlspecialchars($book['bookid'] ?? ''); ?>" readonly>
                                </div>
                                <div class="input-container">
                                    <label for="input-two">Author</label>
                                    <input type="text" id="input-two" name="author" value="<?php echo htmlspecialchars($book['author'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="input-container">
                            <label for="input-three">Title</label><br>
                            <input type="text" id="input-three" name="booktitle" value="<?php echo htmlspecialchars($book['booktitle'] ?? ''); ?>" required>
                        </div>
                        <div class="third-row">
                            <div class="input-row">
                                <div class="input-container">
                                    <label for="input-four">Publication Date</label>
                                    <input type="date" id="input-four" name="pubdate" value="<?php echo htmlspecialchars($book['pubdate'] ?? ''); ?>" required>
                                </div>
                                <div class="input-container">
                                    <label>Genre</label>
                                    <select id="input-five" name="genres[]" required multiple>
                                        <?php foreach ($genres as $genre): ?>
                                            <option value="<?php echo htmlspecialchars($genre['genreid']); ?>" 
                                                <?php echo in_array($genre['genreid'], $bookGenres) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($genre['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="input-container">
                            <label for="input-six">Quantity</label>
                            <input type="number" id="input-six" name="quantity" value="<?php echo htmlspecialchars($book['quantity'] ?? ''); ?>" required>
                        </div>
                        <textarea id="myTextarea" name="descrpt" rows="4" cols="50" placeholder="Description..."><?php echo htmlspecialchars($book['descrpt'] ?? ''); ?></textarea>
                        <div class="updatedelete-btn">
                            <button type="submit" class="update-btn" id="banbtn">Update</button>
                            <!-- Delete button -->
                            <form action="deleteBook.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                <input type="hidden" name="bookid" value="<?php echo htmlspecialchars($book['bookid']); ?>">
                                <button type="submit" class="update-btn">Delete</button>
                            </form>
                        </div>
                    </form> <!-- Form ends here -->
                </div>
            </div>
        </div>
    </div>
</div>