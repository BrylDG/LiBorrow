<?php
include('connection.php');

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
						<?php if ($result->num_rows > 0): ?>
							<?php while ($row = $result->fetch_assoc()): ?>
								<tr>
									<td><?= htmlspecialchars($row['bookid']) ?></td>
									<td><?= htmlspecialchars($row['booktitle']) ?></td>
									<td><?= htmlspecialchars($row['fullname']) ?></td>
									<td><?= htmlspecialchars($row['duedate']) ?></td>
									<td><?= htmlspecialchars($row['days']) ?> Days</td>
									<td class="act">
										<!-- Form for sending reminder -->
										<form method="POST" action="SendReminder.php">
											<input type="hidden" name="send" value="1">
											<input type="hidden" name="booktitle" value="<?= htmlspecialchars($row['booktitle']) ?>">
											<input type="hidden" name="fullname" value="<?= htmlspecialchars($row['fullname']) ?>">
											<button type="submit">Send Reminder</button>
										</form>
									</td>
								</tr>
							<?php endwhile; ?>
						<?php else: ?>
							<tr><td colspan="6">No overdue books found.</td></tr>";
						<?php endif; ?>
					</tbody>
				</table>   
        </div>
    </div>
</div>
