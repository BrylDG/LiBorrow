<?php
session_start(); // Start the session
include('connection.php'); // Include your connection file

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) { // Replace 'user_id' with your session variable for logged-in users
    header("Location: login.php"); // Redirect to the login page
    exit(); // Make sure to exit after the redirect
}
// Retrieve the full name from the session
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User '; // Default to 'User ' if not set
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LiBorrow Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./dashStyle.css">
    <link rel="stylesheet" href="./ReaderStyle.css">
    <link rel="stylesheet" href="./InventoryStyle.css">
    <link rel="stylesheet" href="./HistoryStyle.css">
    <link rel="stylesheet" href="./dashboardStyle.css">
    <link rel="stylesheet" href="./TransactionBorrowed.css">
    <link rel="stylesheet" href="./TransactionsReturned.css">
    <link rel="stylesheet" href="./TransactionsOverdue.css">
    <link rel="stylesheet" href="./ReadersInformation.css">
    
    <title >LiBorrow Dashboard</title>
</head>
<body>
    <div class="container-fluid" style="padding: 0;">
        <div class="row">
            <div id="sidebar" class="col-2">
                <h3 id="brand-name">LiBorrow.</h3>
                <ul id="nav-list">
                    <li>
                        <a href="#" id="button0">
                            <img src="./Images/DashIcon.svg" alt="Dashboard Icon" width="24" height="24"> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#" id="button1">
                            <img src="./Images/ReaderIcon.svg" alt="Readers Icon" width="24" height="24"> Readers List
                        </a>
                    </li>
                    <li>
                        <a href="#" id="button2">
                            <img src="./Images/InventoryIcon.svg" alt="Inventory Icon" width="24" height="24"> Inventory
                        </a>
                    </li>
                    <li id="submenu-list">
                        <a id="button3" href="#" id="submenu-title">
                            <img src="./Images/TransacIcon.svg" alt="Transactions Icon" width="24" height="24"> Transactions
                            <img id="submenu-toggle-icon" src="./Images/Vec.svg" alt="Toggle">
                        </a>
                        <ul id="submenu">
                            <li>
                                <a href="#" id="pendBtn">
                                   Pending
                                </a>
                            </li>
                            <li>
                                <a href="#" id="BorrowedBtn">
                                   Borrowed
                                </a>
                            </li>
                            <li>
                                <a href="#" id="ReturnedBtn">
                                   Returned
                                </a>
                            </li>
                            <li>
                                <a href="#" id="OverdueBtn">
                                   Overdue
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" id="button4">
                            <img src="./Images/HistoryIcon.svg" alt="History Icon" width="25" height="25"> History
                        </a>
                    </li>
                </ul>
            </div>

            <div id="main-content" class="col-10">
                <div id="topbar">
                    <h3 id="page-title">Readers List</h3>
                    <div id="profile-section" class="col-3">
                        <div id="notification">
                            <a href="#">
                                <img src="./Images/Bell_pin.svg" alt="Notifications" height="30" width="30">
                            </a>
                        </div>
                        <div id="notification-dropdown" class="notification-dropdown">
                            <div class="notification-options">
                                <a href="#" class="notification-item">New Message</a>
                                <a href="#" class="notification-item">New Comment</a>
                                <a href="#" class="notification-item">System Alert</a>
                            </div>
                        </div>
                        <div class="separator"></div>
                        <div id="profile" class="col-4">					
                            <a href="#" class="info-column">
                                <img src="./Images/Profile.svg" id="profile-image" alt="Profile" height="60" width="60">
                                <div id="profile-info">
                                    <span><?php echo $fullname; ?></span>
                                    <h5>Librarian</h5>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div id="profile-dropdown" class="profile-dropdown">
                        <div class="profile-options">
                            <a href="#" class="settings">
                                <img src="./Images/settings.svg" alt="Settings Icon"> Settings
                            </a>
                            <a href="logout.php" class="logout">
                                <img src="./Images/signin.svg" alt="Logout Icon"> Log Out
                            </a>
                        </div>
                    </div>
                </div>
                <div id="body-content" class="col-10">
                </div>
            </div>
        </div>
    </div>
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
		$percentages[$status] = ($totalBooks > 0) ? ($count / $totalBooks) * 100 : 0;
	}
	
	
	// BARCHART
	// Fetch genre names from the genres table
	$genreQuery = "SELECT name FROM genres";
	$genreResult = $conn->query($genreQuery);

	// Store genre names in an array
	$genreNames = [];
	while ($row = $genreResult->fetch_assoc()) {
		$genreNames[] = $row['name'];
	}

	// Query to count borrows by genre
	$genreCountQuery = "SELECT genre, COUNT(*) AS borrow_count FROM borrowhistory GROUP BY genre";

	// Execute the query
	$genreCountResult = $conn->query($genreCountQuery);

	// Prepare arrays for genres and their corresponding borrow counts
	$genreNames = [];
	$borrowCounts = [];

	// Fetch the results
	while ($row = $genreCountResult->fetch_assoc()) {
		$genreNames[] = $row['genre'];
		$borrowCounts[] = $row['borrow_count'];
	}

	// Close the database connection
	$conn->close();
	?>
    <script>
       
        function toggleSubmenu() {
            const submenu = document.getElementById('submenu');
            const icon = document.getElementById('submenu-toggle-icon');
    
            if (submenu.style.maxHeight === "0px" || submenu.style.maxHeight === "") {
                submenu.style.maxHeight = submenu.scrollHeight + "px";
                icon.classList.add('rotate');
            } else {
                submenu.style.maxHeight = "0px";
                icon.classList.remove('rotate');
            }
        }
        
        function loadDashboard() {
            fetch('./Dashboard.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Dashboard"; // Change the page title
                    document.getElementById("page-title").innerText = "Dashboard"; // Change the displayed title
                    renderCharts();
                })
                .catch(error => console.error('Error fetching content:', error));
        }
		
        document.getElementById("button0").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./Dashboard.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Dashboard"; // Change the page title
                    document.getElementById("page-title").innerText = "Dashboard"; // Change the displayed title
                    renderCharts();
                })
                .catch(error => console.error('Error fetching content:', error));
        });

        function renderCharts() {
                // Pie Chart Data
		const pieCtx = document.getElementById('myPieChart').getContext('2d');
		const pieData = {
			labels: ['Borrowed', 'Returned', 'Pending', 'Overdue'],
			datasets: [{
				 data: [<?php echo implode(',', array_values($percentages)); ?>],
				backgroundColor: ['orange', '#5f76e8', '#ff8c61', 'red'],
				hoverBackgroundColor: ['orange', '#5f76e8', '#ff8c61', 'red']
			}]
		};

// Create Pie Chart
new Chart(pieCtx, {
    type: 'doughnut',
    data: pieData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false,
                position: 'bottom'
            }
        }
    }
});

// Bar Chart Data
const ctx = document.getElementById('myBarChart').getContext('2d');
const myBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($genreNames); ?>,
        datasets: [
            {
                backgroundColor: '#5f76e8',
                hoverBackgroundColor: '#3949ab',
                data: <?php echo json_encode($borrowCounts); ?>,
                borderRadius: 8,  // Rounded corners
                borderSkipped: false,  // Disable sharp corners
                label: '2024',
                maxBarThickness: 10  // Adjust this value to control bar thickness
            },
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                labels: {
                    color: '#333',
                    font: {
                        size: 14
                    }
                },
            },
            tooltip: {
                backgroundColor: '#5f76e8',
                titleFont: { size: 16 },
                bodyFont: { size: 14 },
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: ${context.raw}`;
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false,
                },
                ticks: {
                    font: {
                        size: 14,
                    }
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: '#ddd',
                    borderDash: [5, 5],
                },
                ticks: {
                    font: {
                        size: 14,
                    }
                }
            }
        }
    }
});
        }

        document.getElementById("button1").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./ReaderDash.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    initializeViewMoreButtons();
                    document.title = "Readers List"; // Change the page title
                    document.getElementById("page-title").innerText = "Readers List"; // Change the displayed title
                })
                .catch(error => handleError('Error fetching ReaderDash:', error));
        });

        // Function to attach listeners to View More buttons
        function initializeViewMoreButtons() {
            document.querySelectorAll(".view-more a").forEach(button => {
                button.addEventListener("click", function(event) {
                    event.preventDefault();
                    fetch('./ReadersInformation.php')
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById("body-content").innerHTML = data;
                            document.title = "Reader' Information"; // Change the page title
                            document.getElementById("page-title").innerText = "Reader's Information"; // Change the displayed title
                        })
                        .catch(error => handleError('Error fetching ReadersInformation:', error));
                });
            });
        }
		
        // Function to handle errors and display a user-friendly message
        function handleError(message, error) {
            console.error(message, error);
            // You can add code here to display an error message to the user, e.g., using a modal or alert.
        }
        document.getElementById("button2").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./InventoryDash.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Inventory"; // Change the page title
                    document.getElementById("page-title").innerText = "Inventory"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });
            
        document.getElementById("BorrowedBtn").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./TransactionsBorrowed.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Borrowed Books"; // Change the page title
                    document.getElementById("page-title").innerText = "Borrowed Books"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });
        document.getElementById("ReturnedBtn").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./TransactionsReturned.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Returned Books"; // Change the page title
                    document.getElementById("page-title").innerText = "Returned Books"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });
        document.getElementById("pendBtn").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./TransactionsDash.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Pending"; // Change the page title
                    document.getElementById("page-title").innerText = "Pending"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });
        document.getElementById("OverdueBtn").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./TransactionsOverdue.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Overdue Books"; // Change the page title
                    document.getElementById("page-title").innerText = "Overdue Books"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });
        
		
        document.querySelector("#button3").addEventListener("click", function(event) {
            event.preventDefault();

            // Toggle the submenu
            const submenu = document.getElementById('submenu');
            const icon = document.getElementById('submenu-toggle-icon');

            if (submenu.style.maxHeight === "0px" || submenu.style.maxHeight === "") {
                submenu.style.maxHeight = submenu.scrollHeight + "px";
                icon.classList.add('rotate');
            } else {
                submenu.style.maxHeight = "0px";
                icon.classList.remove('rotate');
            }

            // Fetch and load Transactions content
            fetch('./TransactionsDash.html') 
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data; // Update the content div
                    document.title = "Transactions"; // Change the page title
                    document.getElementById("page-title").innerText = "Pending"; // Change the displayed title
                })
                .catch(error => {
                    console.error('Error fetching content:', error);
                });
        });
        
			
        document.getElementById("button4").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./HistoryDash.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "History"; // Change the page title
                    document.getElementById("page-title").innerText = "History"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });

        document.querySelectorAll('#submenu li a').forEach(function(submenuItem) {
            submenuItem.addEventListener('click', function() {
                document.querySelectorAll('#submenu li a').forEach(function(item) {
                    item.classList.remove('active');
                });
                submenuItem.classList.add('active');
            });
        });

        document.querySelector(".info-column").addEventListener("click", function(event) {
            event.preventDefault();
            const dropdown = document.getElementById("profile-dropdown");

            if (dropdown.style.display === "none" || dropdown.style.display === "") {
                dropdown.style.display = "block";
            } else {
                dropdown.style.display = "none";
            }
        });

        document.addEventListener("click", function(event) {
            const dropdown = document.getElementById("profile-dropdown");
            const profileSection = document.querySelector(".info-column");

            if (!profileSection.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = "none";
            }
        });
        
        document.getElementById("notification").addEventListener("click", function(event) {
            event.preventDefault();
            const dropdown = document.getElementById("notification-dropdown");

            dropdown.style.display = (dropdown.style.display === "none" || dropdown.style.display === "") ? "block" : "none";
        });
		
        document.addEventListener("click", function(event) {
            const dropdown = document.getElementById("notification-dropdown");
            const notification = document.getElementById("notification");

            if (!notification.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = "none";
            }
        });
		function updateTime() {
		const now = new Date(); // Get the current date and time

		// Extract hours, minutes, and seconds
		const hours = String(now.getHours()).padStart(2, '0');
		const minutes = String(now.getMinutes()).padStart(2, '0');
		const seconds = String(now.getSeconds()).padStart(2, '0');

		// Format the time
		const formattedTime = `${hours}:${minutes}:${seconds}`;

		// Update the HTML element with the current time
		document.getElementById("current-time").innerHTML = `${formattedTime}`;
	}
        window.onload = function() {
            loadDashboard();
			setInterval(updateTime, 1);
			// Initial call to display the time immediately on page load
			updateTime();
				};
		
		
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
