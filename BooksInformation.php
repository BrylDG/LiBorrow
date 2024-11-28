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
                    <form action="updateBook.php" method="POST"> <!-- Form starts here -->
                        <div class="input-row">
                            <div class="input-container">
                                <input type="text" id="input-one" name="bookid" value="<?php echo htmlspecialchars($book['bookid'] ?? ''); ?>" readonly>
                                <label for="input-one">Book ID</label>
                            </div>
                            <div class="input-container">
                                <input type="text" id="input-two" name="author" value="<?php echo htmlspecialchars($book['author'] ?? ''); ?>" required>
                                <label for="input-two">Author</label>
                            </div>
                        </div>
                        <div class="input-container">
                            <input type="text" id="input-three" name="booktitle" value="<?php echo htmlspecialchars($book['booktitle'] ?? ''); ?>" required>
                            <label for="input-three">Title</label>
                        </div>
                        <div class="input-row">
                            <div class="input-container">
                                <input type="date" id="input-four" name="pubdate" value="<?php echo htmlspecialchars($book['pubdate'] ?? ''); ?>" required>
                                <label for="input-four">Publication Date</label>
                            </div>
                            <div class="input-container">
                                <select id="input-five" name="genres[]" required multiple>
                                    <?php foreach ($genres as $genre): ?>
                                        <option value="<?php echo htmlspecialchars($genre['genreid']); ?>" 
                                            <?php echo in_array($genre['genreid'], $bookGenres) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($genre['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label>Genre</label>
                            </div>
                        </div>
                        <div class="input-container">
                            <input type="number" id="input-six" name="quantity" value="<?php echo htmlspecialchars($book['quantity'] ?? ''); ?>" required>
                            <label for="input-six">Quantity</label>
                        </div>
                        <textarea id="myTextarea" name="descrpt" rows="4" cols="50" placeholder="Description..."><?php echo htmlspecialchars($book['descrpt'] ?? ''); ?></textarea>
                        <div class="updatedelete-btn">
                            <button type="submit" class="update-btn">Update</button>
                        </div>
                    </form> <!-- Form ends here -->
                </div>
            </div>
            <div class="updatedelete-btn">
                <!-- Delete button -->
                <form action="deleteBook.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this book?');">
                    <input type="hidden" name="bookid" value="<?php echo htmlspecialchars($book['bookid']); ?>">
                    <button type="submit" class="update-btn" id="banbtn">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>