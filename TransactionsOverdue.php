<?php
// Assume you have a database connection established
// Replace with your actual database credentials
include ('connection.php');

// Fetch overdue books from the database
$sql = "SELECT bookid, booktitle, fullname, duedate, DATEDIFF(duedate, CURDATE()) AS days FROM borrows WHERE duedate < CURDATE()";
$result = $conn->query($sql);
?>
<div class="content-box" id="content2">
    <div class="container">
        <div class="input">
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
        <div id="d1" class="Overdue-box" style="overflow-x:auto; ">
				<table class="Overdue-table">
					<thead>
						<tr>
							<th>Book Id</th>
							<th>Title</th>
							<th>User Name</th>
							<th>Due Date</th>
							<th>Days Overdue</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($result->num_rows > 0) {
							// Output data of each row
							while($row = $result->fetch_assoc()) {
								echo "<tr>
										<td>{$row['bookid']}</td>
										<td>{$row['booktitle']}</td>
										<td>{$row['fullname']}</td>
										<td>{$row['duedate']}</td>
										<td>{$row['days']} Days</td>
										<td class='act'><a href='#'>Send Reminder</a></td>
									  </tr>";
							}
						} else {
							echo "<tr><td colspan='6'>No overdue books found.</td></tr>";
						}
						?>
					</tbody>
				</table>   
        </div>
    </div>

</div>
