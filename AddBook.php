<?php
include ('connection.php');

// Initialize the message variable
$message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if required fields are not empty
    if (!empty($_POST['bookid']) && !empty($_POST['author']) && !empty($_POST['booktitle']) && !empty($_POST['pubdate']) && !empty($_POST['genre']) && !empty($_POST['quantity']) && !empty($_POST['descrpt'])) {

        // Get the input values from the form
        $bookid = $_POST['bookid'];
        $author = $_POST['author'];
        $booktitle = $_POST['booktitle'];
        $pubdate = $_POST['pubdate'];
        $genre = $_POST['genre'];
        $quantity = $_POST['quantity'];
        $descrpt = $_POST['descrpt'];
		
		// Handle the image upload
        if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['book_image']['tmp_name'];
            $fileName = $_FILES['book_image']['name'];
            $fileSize = $_FILES['book_image']['size'];
            $fileType = $_FILES['book_image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Define allowed file extensions
            $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Set upload file path
                $uploadFileDir = './images/';
                $dest_path = $uploadFileDir . $fileName;

                // Move the file to the upload directory
                if(move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Prepare the SQL statement
                    $stmt = $conn->prepare("INSERT INTO books (bookid, author, booktitle, pubdate, genre, quantity, descrpt, bookimg) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    // Bind parameters
                    $stmt->bind_param("issssiss", $bookid, $author, $booktitle, $pubdate, $genre, $quantity, $descrpt, $bookimg);

                    // Execute the statement and check for success
                    if ($stmt->execute()) {
                        // Return a success response for AJAX
                        echo json_encode(['success' => true, 'message' => "New record created successfully"]);
                    } else {
                        // Return an error response for AJAX
                        echo json_encode(['success' => false, 'message' => "Error: " . $stmt->error]);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => "Error moving the uploaded file."]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => "Upload failed. Allowed file types: jpg, gif, png, jpeg."]);
            }
        } else {
            echo json_encode(['success' => false, ' message' => "No image uploaded or there was an upload error."]);
        }

        // Close the statement
        $stmt->close();
        exit(); // Stop further execution
    } else {
        // Return an error response for AJAX
        echo json_encode(['success' => false, 'message' => "Please fill in all fields."]);
        exit();
    }
}

// Close the connection
$conn->close();
?>

<!-- HTML form part -->
<div class="content-box" id="content2">
    <div class="container">
        <div id="Readd1" class="Binfobox">
            <div class="Readbox Rglobal" id="BboxView">
                <div class="Bview input-container" id="image-container">
                    <img src="./Images/AddBook.png" alt="Book one" id="book-image">
                    <input type="file" name="book_image" accept="image/*" required> <!-- File input for image -->
                </div>
                <div id="Book-details">
                    <form id="addBookForm" method="POST" enctype="multipart/form-data"> <!-- Add ID to the form -->
                        <div class="input-row">
                            <div class="input-container">
                                <input type="text" name="bookid" id="input-one" placeholder=" " required>
                                <label for="input-one">Book ID</label>
                            </div>
                            <div class="input-container">
                                <input type="text" name="author" id="input-two" placeholder=" " required>
                                <label for="input-two">Author</label>
                            </div>
                        </div>
                        <div class="input-container">
                            <input type="text" name="booktitle" id="input-three" placeholder=" " required>
                            <label for="input-three">Title</label>
                        </div>
                        <div class="input-row">
                            <div class="input-container">
                                <input type="date" name="pubdate" id="input-four" required>
                                <label for="input-four">Publication Date</label>
                            </div>
                            <div class="input-container">
                                <select name="genre" id="input-five" required> <!-- Removed extra space -->
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
                            <input type="number" name="quantity" id="input-six" placeholder=" " required>
                            <label for="input-quantity">Quantity</label>
                        </div>
                        <textarea name="descrpt" id="myTextarea" rows="4" cols="50" placeholder="Description..."></textarea>
                        <div class="updatedelete-btn">
                            <button type="submit" class="update-btn" id="Adbtn">Add</button>
                            <button type="button" class="update-btn" id="banbtn" onclick="window.location.href='#';">Cancel</button>
                        </div>
                    </form> <!-- Form ends here -->
                    <div id="responseMessage"></div> <!-- Placeholder for response message -->
                </div>                   
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById("addBookForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent default form submission

    const formData = new FormData(this); // Collect form data

    fetch('./addbook.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Parse JSON response
    .then(data => {
        const responseMessage = document.getElementById("responseMessage");
        responseMessage.innerHTML = `<p style="color:green;">${data.message}</p>`; // Display success message
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById("responseMessage").innerHTML = `<p style="color:red;">An error occurred. Please try again.</p>`;
    });
});
</script>