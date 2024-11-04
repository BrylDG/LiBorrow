<div class="content-box" id="dashboard">
    <div class="row">
        <!-- Header Section -->
        <div class="col-6" id="top">
			<?php
			session_start(); // Start the session
			include('connection.php'); // Include your connection file

			// Retrieve the full name from the session
			$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User '; // Default to 'User ' if not set
			?>
            <h1>Welcome Back, <?php echo $fullname; ?>!</h1>
            <div class="DT">
                <div class="datediv">
                <img src="./Images/date.svg" alt="date Icon" width="24" height="24">
                <h5><?php echo date('F d, Y'); ?></h5>
                </div>
                <div class="timediv">
                <img src="./Images/time.svg" alt="time Icon" width="24" height="24">
                <h5 id="current-time"></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Left Side Section -->
        <div class="col-md-4" style="padding-right: 1%;">
            <!-- Stat Cards -->
            <div class="row">
				<?php
				include("connection.php");
				
				$sql = "SELECT COUNT(*) as userCount FROM users WHERE isAdmin = 0";
				$result = $conn->query($sql);

				// Initialize user count
				$userCount = 0;
				if ($result->num_rows > 0) 
				{
					// Fetch the result
					$row = $result->fetch_assoc();
					$userCount = $row['userCount'];
				}
				
				$sql = "SELECT SUM(quantity) as bookCount FROM books";
				$result = $conn->query($sql);
				
				$bookCount = 0;
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$bookCount = $row['bookCount'];
				}
				?>
				<?php
	// Database connection (adjust the connection parameters as needed)
	include ("connection.php");
	
	// Query to get the counts from each table
	$borrowedQuery = "SELECT COUNT(*) AS count FROM borrows";
	$returnedQuery = "SELECT COUNT(*) AS count FROM returns";
	$pendingQuery = "SELECT COUNT(*) AS count FROM pendings";
	$overdueQuery = "SELECT COUNT(*) AS count FROM overdues";

	// Execute queries
	$borrowedResult = $conn->query($borrowedQuery);
	$returnedResult = $conn->query($returnedQuery);
	$pendingResult = $conn->query($pendingQuery);
	$overdueResult = $conn->query($overdueQuery);

	// Fetch counts
	$counts = [
		'Borrowed' => $borrowedResult->fetch_assoc()['count'],
		'Returned' => $returnedResult->fetch_assoc()['count'],
		'Pending' => $pendingResult->fetch_assoc()['count'],
		'Overdue' => $overdueResult->fetch_assoc()['count']
	];

	// Calculate total books
	$totalBooks = array_sum($counts);

	// Calculate percentages
	$percentages = [];
    foreach ($counts as $status => $count) {
    $percentages[$status] = ($totalBooks > 0) ? round(($count / $totalBooks) * 100) : 0;
    }

	// Close the database connection
	$conn->close();
	?>
                <div class="col-6" style="padding-left: 0;">
                    <div class="stat-card">
                        <div class="icon">
                            <img src="./Images/totalReaders.svg" alt="Total Readers Icon" width="50" height="50">
                        </div>
                        <div class="text">
                            <h2><?php echo $userCount; ?></h2>
                            <p>Total Readers</p>
                        </div>
                    </div>
                </div>
                <div class="col-6" style="padding-right: 0;">
                    <div class="stat-card">
                        <div class="icon">
                                <img src="./Images/totalBooks.svg" alt="Total Books Icon" width="50" height="50">
                        </div>
                        <div class="text">
                            <h2><?php echo $bookCount; ?></h2>
                            <p>Total Books</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="chart-container pie-container" style="margin-top: 0;">
                <h3>Transaction Overview</h3> <!-- Title for the chart -->
                <canvas id="myPieChart"></canvas>
                <!-- Labels similar to the image -->
                <ul style="list-style: none; padding-left: 20%; text-align: start;">
                    <li><span style="color: orange; font-size: 30px; padding-right: 2%;">●</span> Borrowed <span style="font-weight: bold; font-size: 17px; padding-left: 32%;"><?php echo $percentages['Borrowed']; ?>%</span></li>
                    <li><span style="color: #5f76e8; font-size: 30px; padding-right: 2%;">●</span> Pending <span style="font-weight: bold; font-size: 17px; padding-left: 35%;"><?php echo $percentages['Pending']; ?>%</span></li>
                    <li><span style="color: #ff8c61; font-size: 30px; padding-right: 2%;">●</span> Returned <span style="font-weight: bold; font-size: 17px; padding-left: 33%;"><?php echo $percentages['Returned']; ?>%</span></li>
                    <li><span style="color: red; font-size: 30px; padding-right: 2%;">●</span> Overdue <span style="font-weight: bold; font-size: 17px; padding-left: 34%;"><?php echo $percentages['Overdue']; ?>%</span></li>
                </ul>
            </div>
        </div>

        <!-- Right Side Section -->
        <div class="col-md-8" id="body-side">
            <div class="chart-container" style="margin-top: 0; height: 642px">
                <canvas id="myBarChart" style=" height:100%;"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
function updateTime() {
    const now = new Date(); // Get the current date and time

    // Extract hours, minutes, and seconds
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    // Format the time
    const formattedTime = `${hours}:${minutes}:${seconds}`;

    // Update the HTML element with the current time
    document.getElementById("current-time").innerHTML = `Current Time: ${formattedTime}`;
}
</script>
