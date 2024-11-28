<?php
include('connection.php');
// Clear any previous output
ob_clean();

// Set the Content-Type header to JSON
header('Content-Type: application/json');

// Your existing logic to prepare the JSON response
if (isset($_GET['bookid'])) {
    $bookId = intval($_GET['bookid']);
    if ($bookId > 0) {
        $stmt = $conn->prepare("SELECT * FROM books WHERE bookid = ?");
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $book = $result->fetch_assoc();
            echo json_encode(['success' => true, 'data' => $book]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No book found.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid book ID.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Book ID missing.']);
}

// Close the connection
$conn->close();
?>


<!--HTML FORM-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <script>
        // JavaScript to fetch and populate book details dynamically
        function fetchBookDetails(bookId) {
            fetch(`BooksInformation.php?bookid=${bookId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const book = data.data;
                        document.getElementById('input-one').value = book.bookid;
                        document.getElementById('input-two').value = book.author;
                        document.getElementById('input-three').value = book.booktitle;
                        document.getElementById('input-four').value = book.pubdate;
                        document.getElementById('input-five').value = book.genres;
                        document.getElementById('input-six').value = book.quantity;
                        document.getElementById('myTextarea').value = book.descrpt;
                        document.getElementById('Bookpic').src = book.bookimg || "./Images/FourthWing_book.png";
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error fetching book details:', error));
        }

        // Example of calling the function with a book ID (use actual book ID dynamically)
        window.onload = function() {
            fetchBookDetails(1); // Replace 1 with the actual book ID you want to fetch
        };
    </script>
</head>
<body>
<div class="content-box" id="content2">
    <div class="container">
        <div id="Readd1" class="Binfobox">
            <div class="Readbox Rglobal" id="BboxView">
                <div class="Bview">
                    <img src="./Images/FourthWing_book.png" alt="Book Image" id="Bookpic">
                </div>
                <div id="Book-details">
                    <div class="input-row">
                        <div class="input-container">
                            <input type="text" id="input-one" placeholder=" " readonly>
                            <label for="input-one">Book ID</label>
                        </div>
                        <div class="input-container">
                            <input type="text" id="input-two" placeholder=" " required>
                            <label for="input-two">Author</label>
                        </div>
                    </div>
                    <div class="input-container">
                        <input type="text" id="input-three" placeholder=" " required>
                        <label for="input-three">Title</label>
                    </div>
                    <div class="input-row">
                        <div class="input-container">
                            <input type="text" id="input-four" placeholder=" " required>
                            <label for="input-four">Publication Date</label>
                        </div>
                        <div class="input-container">
                            <select id="input-five" required>
                                <option value="">Select a genre</option>
                                <option value="fiction">Fiction</option>
                                <option value="non-fiction">Non-Fiction</option>
                                <option value="mystery">Mystery</option>
                                <option value="sci-fi">Sci-Fi</option>
                                <option value="fantasy">Fantasy</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-container">
                        <input type="number" id="input-six" placeholder=" " required>
                        <label for="input-six">Quantity</label>
                    </div>
                    <textarea id="myTextarea" rows="4" cols="50" placeholder="Description..."></textarea>
                </div>                   
            </div>
            <div class="updatedelete-btn">
                <button class="update-btn" onclick="updateBook()">Update</button>
                <button class="update-btn" id="banbtn" onclick="deleteBook()">Delete</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>