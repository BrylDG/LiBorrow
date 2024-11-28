<?php
session_start(); // Start the session
include('connection.php'); // Include your connection file

// Check if the user is logged in
if (!isset($_SESSION['fullname']) && !isset($_SESSION['isAdmin'])) { // Replace 'user_id' with your session variable for logged-in users
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                                <?php
                                    if(!isset($_SESSION['idno'])) {
                                        echo "<p>No new notifications!<p>";
                                    } else {
                                        $query = "SELECT message, time FROM req_notif ORDER BY time DESC LIMIT 3";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                            while ($notification = $result->fetch_assoc()) {
                                                $formatted_time = date("g:i A", strtotime($notification['time']));

                                                echo "<div class='notification-item'>";
                                                echo "<p class='notification-message'>" . htmlspecialchars($notification['message']) . "</p>";
                                                echo "<p class='notification-time'>" . htmlspecialchars($formatted_time) . "</p>";
                                                echo "</div>";  
                                            }
                                        } else {
                                            echo "<p>No new notifications!</p>";
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="separator"></div>
							<div id="profile" class="col-4">
								<a href="#" class="info-column">
									<?php
									// Fetch user data from the database
									$user_id = $_SESSION['idno']; // Ensure user_id is available from session
									$query = "SELECT fullname, profile_picture FROM users WHERE idno = ?";
									$stmt = $conn->prepare($query);
									$stmt->bind_param("i", $user_id);
									$stmt->execute();
									$result = $stmt->get_result();

									// Check if the user data is found
									$user_data = $result->fetch_assoc();

									$stmt->close();

									// Determine the profile picture to use (from DB or default)
									$profile_picture = isset($user_data) && !empty($user_data['profile_picture']) ? htmlspecialchars($user_data['profile_picture']) : './Images/Profile.svg';
									?>
									<img src="<?php echo $profile_picture; ?>" id="profile-image" alt="Profile" height="60" width="60" style="border-radius:50%;">
									<div id="profile-info">
										<span><?php echo htmlspecialchars($user_data['fullname']); ?></span>
										<h5>Librarian</h5>
									</div>
								</a>
							</div>
                    </div>

                    <div id="profile-dropdown" class="profile-dropdown">
                        <div class="profile-options">
                            <a href="usersettings.php" class="settings" id="SettingsBtn">
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
		
		
		//Dashboard
		<?php
		// Database connection (adjust the connection parameters as needed)
		include("connection.php");

		// Query to get the counts from each table
		$borrowedQuery = "SELECT COUNT(*) AS count FROM borrows";
		$returnedQuery = "SELECT COUNT(*) AS count FROM returns";
		$pendingQuery = "SELECT COUNT(*) AS count FROM pendings";
		$overdueQuery = "SELECT COUNT(*) AS count FROM borrows WHERE duedate < CURDATE()";

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
		// Query to count borrows by genre
		$genreCountQuery = "
			SELECT 
				g.name AS genre, 
				COUNT(bh.bookid) AS borrow_count 
			FROM 
				borrowhistory bh 
			JOIN 
				bookgenres bg ON bh.bookid = bg.bookid 
			JOIN 
				genres g ON bg.genreid = g.genreid 
			GROUP BY 
				g.name
		";

		// Execute the query
		$genreCountResult = $conn->query($genreCountQuery);

		// Prepare arrays for genres and their corresponding borrow counts
		$genreNames = [];
		$borrowCounts = [];

		// Fetch the results
		while ($row = $genreCountResult->fetch_assoc()) {
			$genreNames[] = $row['genre'];
			$borrowCounts[] = (int)$row['borrow_count']; // Ensure it's an integer
		}

		// Close the database connection
		$conn->close();
		?>
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
				labels: <?php echo json_encode($genreNames); ?>, // Genre names
				datasets: [
					{
						backgroundColor: '#5f76e8',
						hoverBackgroundColor: '#3949ab',
						data: <?php echo json_encode($borrowCounts); ?>, // Borrow counts
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
			
       // Event listener for the "Readers List" button
document.getElementById("button1").addEventListener("click", function(event) {
    event.preventDefault();
    fetch('./ReaderDash.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById("body-content").innerHTML = data;
        document.title = "Readers List";
        document.getElementById("page-title").innerText = "Readers List";
        setupViewMoreButton() // Initialize View More buttons
    })
    .catch(error => handleError('Error fetching ReaderDash:', error));
})
// Function to load user details based on idno
function fetchUserDetails(idno) {
	console.log("Fetching details for ID:", idno);
    document.getElementById('loading').style.display = 'block';

    fetch(`ReadersInformation.php?idno=${idno}&ajax=true`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
			console.log("Response Data:", data);
            if (data.success) {
                const profilePic = document.getElementById("profilepic");
                const usernameElement = document.querySelector("#profile-view h2");
                const profileDetailsTable = document.querySelector("#profile-details table");

                if (profilePic) {
                    profilePic.src = "./Images/profilepic.png"; // Update profile picture if needed
                }

                if (usernameElement) {
                    usernameElement.innerText = data.data.username;
                }

                if (profileDetailsTable) {
                    profileDetailsTable.innerHTML = `
                        <tr>
                            <td style="font-weight: bold;">ID Number</td>
                            <td>${data.data.idno}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">User Name:</td>
                            <td>${data.data.username}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Full Name:</td>
                            <td>${data.data.fullname}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Email:</td>
                            <td>${data.data.email}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Phone Number:</td>
                            <td>${data.data.phoneno}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Address:</td>
                            <td>${data.data.address}</td>
                        </tr>
                    `;
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching user details:', error);
            alert('An error occurred while fetching user details.');
        })
        .finally(() => {
            document.getElementById('loading').style.display = 'none';
        });
}

// Function to load users with search, sort, and filter
function loadUsers() {
    const searchTerm = document.getElementById('search-input').value;
    const sortBy = document.getElementById('sort-dropdown').value;
    const filterBy = document.getElementById('filter-dropdown').value;

    document.getElementById('loading').style.display = 'block';

    fetch(`ReaderDash.php?ajax=1&search=${encodeURIComponent(searchTerm)}&sort=${sortBy}&filter=${filterBy}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById("reader-table-body");
            tableBody.innerHTML = "";

            if (data.table_body && data.table_body.length > 0) {
                data.table_body.forEach(user => {
                    const row = `<tr>
                                    <td>${user.idno}</td>
                                    <td>${user.fullname}</td>
                                    <td>${user.email}</td>
                                    <td><a href='#' class='view-more' data-idno="${user.idno}">View more</a></td>
                                 </tr>`;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='4'>No results found</td></tr>";
            }
        })
        .catch(error => console.error('Error fetching users:', error))
        .finally(() => {
            document.getElementById('loading').style.display = 'none';
        });
}

function setupViewMoreButton() {
    document.querySelectorAll('.view-more').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default anchor action
            
            const idno = this.getAttribute('data-idno'); // Get the user ID from the data-idno attribute
            
            if (idno) {
                fetchReaderInformation(idno); // Pass the idno to the fetch function
            } else {
                console.error('ID not found.');
                alert('User ID is missing.');
            }
        });
    });
}

function fetchReaderInformation(idno) {
    fetch(`readersinformation.php?idno=${idno}`) // Fetch readersinformation.php with the idno as a query parameter
        .then(response => response.text()) // Get the content of readersinformation.php
        .then(data => {
            // Insert the content of readersinformation.php into the main content area
            document.getElementById("body-content").innerHTML = data;
            document.title = "Reader Information"; // Update the page title
            document.getElementById("page-title").innerText = "Reader Information"; // Update the page heading
        })
        .catch(error => {
            console.error('Error fetching readersinformation.php:', error);
        });
}

// Call this function when the page loads to set up the event listeners
document.addEventListener('DOMContentLoaded', setupViewMoreButton);

		
		//INVENTORY
		document.addEventListener("DOMContentLoaded", function() {
    const genreSelect = document.getElementById('genre-select');
    if (genreSelect) {
        genreSelect.blur(); // Remove focus from the dropdown
    }

    // Event listener for the Inventory button to load InventoryDash.php
    const button2 = document.getElementById("button2");
    if (button2) {
        button2.addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./InventoryDash.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Inventory"; 
                    document.getElementById("page-title").innerText = "Inventory"; 

                    // Now that the Inventory page is loaded, attach the Add Book button listener
                    setupAddBookButton(); // Set up the Add Book button click event
                    loadBooks(); // Load books after the Inventory page content is loaded
                    setupViewMoreButtons(); // Set up the View More buttons
                })
                .catch(error => console.error('Error fetching InventoryDash.php:', error));
        });
    }
});

function setupAddBookButton() {
    const addBookButton = document.getElementById("addBookButton");
    if (addBookButton) {
        addBookButton.addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./addbook.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Add Book";
                    document.getElementById("page-title").innerText = "Add Book"; 
                    setupAddBookFormSubmission(); // Set up the form submission for adding a book
                    setupGenreSelection(); // Set up the genre selection
                    setupCancelButton();
                })
                .catch(error => {
                    console.error('Error fetching addbook.php:', error);
                });
        });
    }
}

// Function to handle the form submission for adding a new book
function setupAddBookFormSubmission() {
    const form = document.getElementById("addBookForm");
    const checkboxContainer = document.getElementById('checkbox-container'); // Reference to checkbox container

    if (form) {
        form.addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission
            const formData = new FormData(this);

            // Send data to the server (addbook.php)
            fetch('./addbook.php', {
                method: 'POST',
                body: formData
            })
            .then(() => {
                // Show success message using SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Book added successfully.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    form.reset(); // Reset the form after successful submission
                    checkboxContainer.innerHTML = ''; // Clear all checkboxes
                    loadBooks(); // Reload books after adding
                });
            })
            .catch(error => {
                // Show error message using SweetAlert
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred. Please try again.',
                    confirmButtonText: 'OK'
                });
            });
        });
    }
}

// Function to set up genre selection with dynamic checkboxes
function setupGenreSelection() {
    const genreSelect = document.getElementById('genre-select');
    const checkboxContainer = document.getElementById('checkbox-container');

    genreSelect.addEventListener('change', function() {
        // Clear existing checkboxes
        checkboxContainer.innerHTML = '';

        // Get selected options
        const selectedOptions = Array.from(genreSelect.selectedOptions);

        // Create checkboxes for selected options
        selectedOptions.forEach(option => {
            const checkboxId = option.value;

            // Create a new checkbox
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = checkboxId;
            checkbox.value = option.value;
            checkbox.checked = true; // Automatically check the checkbox

            // Create a label for the checkbox
            const label = document.createElement('label');
            label.htmlFor = checkboxId;
            label.textContent = option.text;

            // Append checkbox and label to the container
            checkboxContainer.appendChild(checkbox);
            checkboxContainer.appendChild(label);
        });
    });
}

function setupCancelButton() {
    const cancelButton = document.querySelector('button[type="button"]'); // Assuming "Cancel" button has `type="button"`
    if (cancelButton) {
        cancelButton.addEventListener("click", function () {
            // Fetch and load the Inventory page dynamically
            fetch('./InventoryDash.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Inventory";
                    document.getElementById("page-title").innerText = "Inventory";
                    loadBooks(); // Load books after returning to Inventory
                    setupAddBookButton(); // Reattach listeners for Add Book button
                })
                .catch(error => console.error('Error fetching InventoryDash.php:', error));
        });
    }
}
function loadBooks() {
    const searchTerm = document.getElementById('search-input').value;
    const sortBy = document.getElementById('sort-dropdown').value;
    const genreFilter = document.getElementById('genre-filter').value;

    // Show loading indicator
    document.getElementById('loading').style.display = 'block';

    fetch(`inventorydash.php?ajax=1&search=${encodeURIComponent(searchTerm)}&sort=${sortBy}&genre=${encodeURIComponent(genreFilter)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const tableBody = document.getElementById("inventory-table-body");
            tableBody.innerHTML = ""; // Clear existing rows

            if (data.table_body && data.table_body.length > 0) {
                let rows = "";
                data.table_body.forEach(book => {
                    rows += `<tr>
                                <td>${book.bookid}</td>
                                <td>${book.booktitle}</td>
                                <td>${book.author}</td>
                                <td>${book.genres}</td>
                                <td><a href='#' class='view-more' data-bookid='${book.bookid}'>View more</a></td>
                             </tr>`;
                });
                tableBody.innerHTML = rows; // Update all rows at once
                setupViewMoreButtons(); // Set up the View More buttons after loading books
            } else {
                tableBody.innerHTML = "<tr><td colspan='5'>No results found</td></tr>";
            }
        })
        .catch(error => {
            console.error('Error fetching books:', error);
            document.getElementById("inventory-table-body").innerHTML = 
                "<tr><td colspan='5'>An error occurred while loading data. Please try again later.</td></tr>";
        })
        .finally(() => {
            // Hide loading indicator
            document.getElementById('loading').style .display = 'none';
        });
}

function fetchBookInformation(bookId) {
    fetch(`BooksInformation.php?bookid=${bookId}`) // Fetch BooksInformation.php with the bookid as a query parameter
        .then(response => response.text()) // Get the content of BooksInformation.php
        .then(data => {
            // Insert the content of BooksInformation.php into the main content area
            document.getElementById("body-content").innerHTML = data;
            document.title = "Book Information"; // Update the page title
            document.getElementById("page-title").innerText = "Book Information"; // Update the page heading
        })
        .catch(error => {
            console.error('Error fetching BooksInformation.php:', error);
        });
}
function setupViewMoreButtons() {
    document.querySelectorAll('.view-more').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default anchor action
            
            const bookId = this.getAttribute('data-bookid'); // Get the book ID from the data-bookid attribute
            
            if (bookId) {
                fetchBookInformation(bookId); // Pass the bookId to the fetch function
            } else {
                console.error('Book ID not found.');
                alert('Book ID is missing.');
            }
        });
    });
}

function loadBooksHistory() {
    const searchTerm = document.getElementById('search-input').value;
    const sortBy = document.getElementById('sort-dropdown').value;
    const statusFilter = document.getElementById('status-filter').value;

    // Show loading indicator
    document.getElementById('loading').style.display = 'block';

    // Use historydash.php for the fetch request
    fetch(`historydash.php?ajax=1&search=${encodeURIComponent(searchTerm)}&sort=${sortBy}&status=${encodeURIComponent(statusFilter)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const tableBody = document.querySelector(".History-table tbody");
            tableBody.innerHTML = ""; // Clear existing rows

            if (data.table_body && data.table_body.length > 0) {
                let rows = "";
                data.table_body.forEach(book => {
                    rows += `<tr>
                                <td>${book.bookid}</td>
                                <td>${book.booktitle}</td>
                                <td>${book.idno}</td>
                                <td>${book.fullname}</td>
                                <td>${book.borrowdate}</td>
                                <td>${book.duedate}</td>
                                <td class='act'>${book.status}</td>
                             </tr>`;
                });
                tableBody.innerHTML = rows; // Update all rows at once
            } else {
                tableBody.innerHTML = "<tr><td colspan='7'>No results found</td></tr>";
            }
        })
        .catch(error => {
            console.error('Error fetching books:', error);
            document.querySelector(".History-table tbody").innerHTML = 
                "<tr><td colspan='7'>An error occurred while loading data. Please try again later.</td></tr>";
        })
        .finally(() => {
            // Hide loading indicator
            document.getElementById('loading').style.display = 'none';
        });
}

function loadTransactions() {
        const searchTerm = document.getElementById('search-input').value;
        const sortBy = document.getElementById('sort-dropdown').value;

        const xhr = new XMLHttpRequest();
        xhr.open('GET', `transactionsdash.php?action=filter&search=${encodeURIComponent(searchTerm)}&sort=${encodeURIComponent(sortBy)}`, true);
        xhr.onload = function() {
            if (this.status === 200) {
                const books = JSON.parse(this.responseText);
                const resultsContainer = document.getElementById('td1');
                resultsContainer.innerHTML = '';

                if (books.length > 0) {
                    books.forEach(book => {
                        resultsContainer.innerHTML += `
                            <div class="pendbox-one">
                                <p class="name">${book.fullname}</p>
                                <p class="date">Request Date: ${book.requestdate}</p>
                                <div class="pendbox global">
                                    <img src="${book.bookimg}" alt="Book Image" width="100" height="150">
                                    <p class="book-title">${book.booktitle}</p>
                                    <p class="author">${book.author}</p>
                                    <input type="hidden" class="booktitle" value="${book.booktitle}">
                                    <input type="hidden" class="fullname" value="${book.fullname}">
                                    <button class="approve-btn">Approve</button>
                                    <button class="decline-btn">Cancel</button>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    resultsContainer.innerHTML = '<p>No pending requests found.</p>';
                }
            }
        };
        xhr.send();
    }

function loadBooksR() {
    const searchTerm = document.getElementById('search-input').value;
    const sortBy = document.getElementById('sort-dropdown').value;

    // Show loading indicator
    document.getElementById('loading').style.display = 'block';

    fetch(`transactionsreturned.php?ajax=1&search=${encodeURIComponent(searchTerm)}&sort=${encodeURIComponent(sortBy)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const tableBody = document.getElementById("returned-table-body");
            tableBody.innerHTML = ""; // Clear existing rows

            if (data.table_body && data.table_body.length > 0) {
                let rows = "";
                data.table_body.forEach(book => {
                    rows += `<tr>
                                <td>${book.bookimg ? `<img src='${book.bookimg}' alt='Book Image' width='50' height='70' style='margin-right:10px;'>` : `<img src='./Images/default-book.png' alt='Default Book Image' width='50' height='70' style='margin-right:10px;'>`}${book.booktitle}</td>
                                <td>${book.author}</td>
                                <td>${book.status}</td>
                                <td>${book.fullname}</td>
                                <td>${book.datereturned}</td>
                             </tr>`;
                });
                tableBody.innerHTML = rows; // Update all rows at once
            } else {
                tableBody.innerHTML = "<tr><td colspan='5'>No results found</td></tr>";
            }
        })
        .catch(error => {
            console.error('Error fetching books:', error);
            document.getElementById("returned-table-body").innerHTML = 
                "<tr><td colspan='5'>An error occurred while loading data. Please try again later.</td></tr>";
        })
        .finally(() => {
            // Hide loading indicator
            document.getElementById('loading').style.display = 'none';
        });
}

function loadBooksO() {
    const searchTerm = document.getElementById('search-input').value;
    const sortBy = document.getElementById('sort-dropdown').value;

    // Show loading indicator
    document.getElementById('loading').style.display = 'block';

    fetch(`transactionsoverdue.php?ajax=1&search=${encodeURIComponent(searchTerm)}&sort=${encodeURIComponent(sortBy)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const tableBody = document.getElementById("overdue-table-body");
            tableBody.innerHTML = ""; // Clear existing rows

            if (data.table_body && data.table_body.length > 0) {
                let rows = "";
                data.table_body.forEach(book => {
                    rows += `<tr>
                                <td>${book.bookid}</td>
                                <td>${book.booktitle}</td>
                                <td>${book.fullname}</td>
                                <td>${book.duedate}</td>
                                <td>${book.days} Days</td>
                                <td class="act">
                                    <form method="POST" action="SendReminder.php">
                                        <input type="hidden" name="send" value="1">
                                        <input type="hidden" name="booktitle" value="${book.booktitle}">
                                        <input type="hidden" name="fullname" value="${book.fullname}">
                                        <button type="submit">Send Reminder</button>
                                    </form>
                                </td>
                            </tr>`;
                });
                tableBody.innerHTML = rows;
            } else {
                tableBody.innerHTML = "<tr><td colspan='6'>No overdue books found.</td></tr>";
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById("overdue-table-body").innerHTML = "<tr><td colspan='6'>Error loading data.</td></tr>";
        })
        .finally(() => {
            // Hide loading indicator
            document.getElementById('loading').style.display = 'none';
        });
}

		//BORROWED			
document.addEventListener("DOMContentLoaded", function () {
    const borrowedBtn = document.getElementById("BorrowedBtn");
    const bodyContent = document.getElementById("body-content");
    const pageTitle = document.getElementById("page-title");

    if (borrowedBtn) {
        borrowedBtn.addEventListener("click", function (event) {
            event.preventDefault();
            fetch('./TransactionsBorrowed.php')
                .then(response => response.text())
                .then(data => {
                    bodyContent.innerHTML = data;
                    document.title = "Borrowed Books";
                    pageTitle.innerText = "Borrowed Books";

                    // Attach listeners after loading the content
                    setupViewBorrowerButtons();
                    setupSearchAndSort(); // Set up search and sort listeners
                })
                .catch(error => console.error('Error fetching TransactionsBorrowed.php:', error));
        });
    }
});

function setupViewBorrowerButtons() {
    const viewBorrowerButtons = document.querySelectorAll('.view-borrowers');

    viewBorrowerButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            const dropdown = event.target.closest('.Borrbox').querySelector('.borrowers-dropdown');

            // Toggle the visibility of the dropdown
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";

            // Fetch and populate borrower data if displaying the dropdown
            if (dropdown.style.display === "block") {
                const booktitle = event.target.getAttribute('data-booktitle');
                fetchBorrowers(booktitle, dropdown);
            }
        });
    });
}

function fetchBorrowers(booktitle, dropdown) {
    fetch(`TransactionsBorrowed.php?booktitle=${encodeURIComponent(booktitle)}`)
        .then(response => response.text())
        .then(data => {
            dropdown.querySelector('tbody').innerHTML = data; // Populate the table body with fetched data

            // Attach listeners for the Return buttons
            setupReturnButtons();
        })
        .catch(error => {
            dropdown.querySelector('tbody').innerHTML = "<tr><td colspan='4'>Error fetching borrowers.</td></tr>";
            console.error('Error fetching borrowers:', error);
        });
}

function setupReturnButtons() {
    const returnButtons = document.querySelectorAll('.return-button');

    returnButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            const idno = event.target.getAttribute('data-idno');
            const booktitle = event.target.getAttribute('data-booktitle');

            // Redirect to the return page with query parameters
            const returnUrl = `returnBook.php?idno=${encodeURIComponent(idno)}&booktitle=${encodeURIComponent(booktitle)}`;
            window.location.href = returnUrl;
        });
    });
}

function setupSearchAndSort() {
    const searchInput = document.getElementById('search-input');
    const sortDropdown = document.getElementById('sort-dropdown');

    // Ensure event listeners are only added once
    if (searchInput && !searchInput.dataset.listenerAdded) {
        searchInput.addEventListener('input', function () {
            loadBooksB();
        });
        searchInput.dataset.listenerAdded = true; // Mark that the listener has been added
    }

    if (sortDropdown && !sortDropdown.dataset.listenerAdded) {
        sortDropdown.addEventListener('change', function () {
            loadBooksB();
        });
        sortDropdown.dataset.listenerAdded = true; // Mark that the listener has been added
    }
}

function loadBooksB() {
    const searchInput = document.getElementById('search-input').value;
    const sortDropdown = document.getElementById('sort-dropdown').value;

    fetch(`TransactionsBorrowed.php?search=${encodeURIComponent(searchInput)}&sort=${encodeURIComponent(sortDropdown)}`)
        .then(response => response.text())
        .then(data => {
            // Instead of replacing the entire body-content, replace just the books-list
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');

            // Replace the existing books-list with the new one
            const newBooksList = doc.getElementById('books-list');
            const existingBooksList = document.getElementById('books-list');

            if (existingBooksList) {
                existingBooksList.innerHTML = newBooksList.innerHTML; // Update the inner HTML
            }

            setupViewBorrowerButtons(); // Re-setup the view borrower buttons after loading new content
        })
        .catch(error => console.error('Error fetching data:', error));
}
		
		//RETURNED
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
		
		//PENDINGS
		document.addEventListener("DOMContentLoaded", function() {
    const pendBtn = document.getElementById("pendBtn");
    if (pendBtn) {
        pendBtn.addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./TransactionsDash.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Pending"; 
                    document.getElementById("page-title").innerText = "Pending"; 
                    setupButtons();
                })
                .catch(error => console.error('Error fetching TransactionsDash.php:', error));
        });
    }

    setupButtons();
});

function setupButtons() {
    document.querySelectorAll('.approve-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const booktitle = this.parentElement.querySelector('.booktitle').value;
            const fullname = this.parentElement.querySelector('.fullname').value;

            fetch('TransactionsDash.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    booktitle: booktitle,
                    fullname: fullname,
                    approve: true
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire ({
                    title: data.status === 'success' ? 'Success' : 'Error',
                    text: data.message,
                    icon: data.status === 'success' ? 'success' : 'error'
                });
                document.getElementById("pendBtn").click();
            })
            .catch(error => console.error('Error:', error));
        });
    });

    document.querySelectorAll('.decline-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const booktitle = this.parentElement.querySelector('.booktitle').value;
            const fullname = this.parentElement.querySelector('.fullname').value;

            fetch('TransactionsDash.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    booktitle: booktitle,
                    fullname: fullname,
                    cancel: true
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: data.status === 'success' ? 'Success' : 'Error',
                    text: data.message,
                    icon: data.status === 'success' ? 'success' : 'error'
                });
                document.getElementById("pendBtn").click();
            })
            .catch(error => console.error('Error:', error));
        });
    });
}

		//OVERDUE
        document.getElementById("OverdueBtn").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./TransactionsOverdue.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Overdue Books"; // Change the page title
                    document.getElementById("page-title").innerText = "Overdue Books"; // Change the displayed title
					loadBooksO();
                })
                .catch(error => console.error('Error fetching content:', error));
        });

        document.getElementById("SettingsBtn").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./Settings.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "User Profile"; // Change the page title
                    document.getElementById("page-title").innerText = "User Profile"; // Change the displayed title
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
		});
        
			
        document.getElementById("button4").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./HistoryDash.php')
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
		
        function enableEditing(fieldId) {
            const inputField = document.getElementById(fieldId);

            if (inputField.hasAttribute('readonly')) {
                inputField.removeAttribute('readonly');
            }

            if (inputField.hasAttribute('disabled')) {
                inputField.removeAttribute('disabled');
            }

            inputField.focus();
        }
		//dISABLE input fields when cancel button is napislit
        function disableEdit() {
            const cancelBtn = document.getElementById('cancelsetbtn');
            const inputs = document.querySelectorAll('#info-box .inputs');

            cancelBtn.addEventListener('click', function () {
                inputs.forEach(input => {
                    input.readOnly = true;
                    input.disabled = true;
                });
            });
        };

		function updateTime() {
		const now = new Date(); // Get the current date and time

		// Extract hours, minutes, and seconds
		const hours = String(now.getHours()).padStart(2, '0');
		const minutes = String(now.getMinutes()).padStart(2, '0');
		const seconds = String(now.getSeconds()).padStart(2, '0');

		// Format the time
		const formattedTime = `${hours}:${minutes}:${seconds}`;

		// Update the HTML element with the current time
		 const timeElement = document.getElementById("current-time");
    if (timeElement) {
        timeElement.innerHTML = formattedTime;
    } 
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
