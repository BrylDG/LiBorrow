<?php
include('connection.php'); // Include your database connection

if (isset($_GET['bookid'])) {
    $bookid = $_GET['bookid'];

    // Query to fetch the genre of the current book
    $genre_query = "SELECT g.genreid FROM books b
                    JOIN bookgenres bg ON b.bookid = bg.bookid
                    JOIN genres g ON bg.genreid = g.genreid
                    WHERE b.bookid = ?";
    
    $stmt = $conn->prepare($genre_query);
    $stmt->bind_param("i", $bookid);
    $stmt->execute();
    $genre_result = $stmt->get_result();
    $genres = $genre_result->fetch_all(MYSQLI_ASSOC);

    if ($genres) {
        $genre_ids = array_column($genres, 'genreid');
        $genre_ids_placeholder = implode(',', array_fill(0, count($genre_ids), '?'));

        // Query to fetch similar books based on genres
        $similar_books_query = "SELECT b.bookid, b.booktitle, b.author, b.bookimg 
                                FROM books b
                                JOIN bookgenres bg ON b.bookid = bg.bookid
                                WHERE bg.genreid IN ($genre_ids_placeholder) AND b.bookid != ?
                                GROUP BY b.bookid";

        $stmt = $conn->prepare($similar_books_query);
        $types = str_repeat('i', count($genre_ids)) . 'i'; // Create types string for bind_param
        $params = array_merge($genre_ids, [$bookid]); // Combine genre IDs and current book ID
        $stmt->bind_param($types, ...$params); // Bind parameters
        $stmt->execute();
        $similar_books_result = $stmt->get_result();
        $similar_books = $similar_books_result->fetch_all(MYSQLI_ASSOC);

        echo json_encode($similar_books); // Return the similar books as JSON
    } else {
        echo json_encode([]); // No genres found
    }
} else {
    echo json_encode([]); // No book ID provided
}
?>