<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Poppins:ital,
          wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="readerslistdashStyle.css">
    <title>Reader's List</title>
</head>
<body>
    <div class="container">
            <div class="search-sort-filter-container">
                <div class="email-input search-box">
                    <input type="text" class="search-control" placeholder="Search">
                    <img src="Images/search icon.png" alt="search icon" class="search-icon">
                </div>
                <div class="button-group">
                    <button id="SortBy">Sort by</button>
                    <button id="FilterBy">Filter by</button>
                </div>
            </div>

        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    include("connection.php");
                    
                    $query = "SELECT idno, fullname, email FROM users";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td>" . $row["idno"]. "</td><td>" . $row["fullname"]. "</td><td>" . $row["email"]. "</td><td><a href='#' class='view-more'>View more</a></td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No data found</td></tr>";
                    }
                ?>
                <!--
                <tr>
                    <td>1</td>
                    <td>John Doe</td>
                    <td>JohnDoe@gmail.com</td>
                    <td><a href='#' class='view-more'>View more</a></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jane Smith</td>
                    <td>JaneSmith@gmail.com</td>
                    <td><a href="#" class="view-more">View more</a></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Jonatan</td>
                    <td>Jonatan@gmail.com</td>
                    <td><a href="#" class="view-more">View more</a></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Junjun</td>
                    <td>Junjun@gmail.com</td>
                    <td><a href="#" class="view-more">View more</a></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Juna Mae</td>
                    <td>JunaMae@gmail.com</td>
                    <td><a href="#" class="view-more">View more</a></td>
                </tr>-->
            </tbody>
        </table>

        <div class="pagination">
            <a href="#">Previous</a>
            <a href="#">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
            <a href="#">...</a>
            <a href="#">50</a>
            <a href="#">Next</a>
        </div>
    </div>
</body>
</html>