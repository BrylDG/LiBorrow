<?php
session_start(); // Start the session
include('connection.php'); // Include your connection file

// Check if the user is logged in
if (!isset($_SESSION['idno'])) { // Replace 'user_id' with your session variable for logged-in users
    header("Location: login.php"); // Redirect to the login page
    exit(); // Make sure to exit after the redirect
}
// Retrieve the full name from the session
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User '; // Default to 'User ' if not set
$role = $_SESSION['isAdmin'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./UserNavTemplate.css">
    <link rel="stylesheet" href="./UserHistory.css">
    <link rel="stylesheet" href="./Browse.css">
    <link rel="stylesheet" href="./Favorites.css">
    <link rel="stylesheet" href="./BooksRequest.css">
    <link rel="stylesheet" href="./ViewDetails.css">
    <link rel="stylesheet" href="./ViewDetailsPending.css">
    <link rel="stylesheet" href="./ViewDetailsAvailable.css">
    <title>LiBorrow User's Dashboard</title>
</head>
<body>
    <script>
        <?php if (isset($_GET['message'])): ?>
            <?php if ($_GET['message'] === 'added_successfully'): ?>
                alert("Book added to favorites successfully!");
            <?php elseif ($_GET['message'] === 'already_in_favorites'): ?>
                alert("This book is already in your favorites!");
            <?php elseif ($_GET['message'] === 'error'): ?>
                alert("Error: <?php echo isset($_GET['error']) ? htmlspecialchars($_GET['error']) : 'Unknown error.'; ?>");
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($_GET['message'])): ?>
        <?php if ($_GET['message'] === 'added_to_pendings'): ?>
            alert("Book request successfully submitted!");
        <?php elseif ($_GET['message'] === 'already_in_pendings'): ?>
            alert("This book is already in pending request.");
        <?php elseif ($_GET['message'] === 'error'): ?>
            alert("Error: <?php echo isset($_GET['error']) ? htmlspecialchars($_GET['error']) : 'Unknown error.'; ?>");
        <?php endif; ?>
    <?php endif; ?>
    </script>
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
						<a href="#" id="buttonbrowse">
							<img src="./Images/browse icon.svg" alt="Browse Icon" width="24" height="24"> Browse
						</a>
					</li>
					<li>
						<a href="#" id="button1">
							<img src="./Images/heart.svg" alt="Heart Icon" width="24" height="24"> Favorites
						</a>
					</li>
					<li>
						<a href="#" id="button2">
							<img src="./Images/borrowed books.svg" alt="Borrowed Books Icon" width="24" height="24"> My Books
						</a>
					</li>
					<li>
						<a href="#" id="button3">
							<img src="./Images/book request.svg" alt="Book Request Icon" width="24" height="24"> Book Request
						</a>
					</li>
					<li>
						<a href="#" id="button4">
							<img src="./Images/HistoryIcon.svg" alt="Browse Icon" width="25" height="25"> History
						</a>
					</li>
				</ul>
			</div>

<div id="main-content" class="col-10">
    <div id="topbar">
        <h3 id="page-title">Dashboard</h3>
        <div id="profile-section" class="col-3">
            <!-- Notification Icon -->
            <div id="notification">
                <a href="#">
                    <img src="./Images/Bell_pin.svg" alt="Notifications" height="30" width="30">
                </a>
            </div>

            <!-- Notification Dropdown -->
            <div id="notification-dropdown" class="notification-dropdown">
                <div class="notification-options">
                    <?php
                    if (!isset($_SESSION['idno'])) {
                        echo "<p class='no-notification'>No new notifications!</p>";
                    } else {
                        $idno = $_SESSION['idno'];
                        $query = "SELECT details, time FROM notification WHERE idno = ? ORDER BY time DESC LIMIT 3";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $idno);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            while ($notification = $result->fetch_assoc()) {
                                $formatted_time = date("g:i A", strtotime($notification['time']));
                                echo "<div class='notification-item'>";
                                echo "<p class='notification-message'>" . htmlspecialchars($notification['details']) . "</p>";
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

            <!-- Profile Section -->
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
                        <h5>Reader</h5>
                    </div>
                </a>
            </div>
        </div>

        <!-- Profile Dropdown Options -->
        <div id="profile-dropdown" class="profile-dropdown">
            <div class="profile-options">
                <a href="#" class="Usersettings" id="SettingsBtn">
                    <img src="./Images/settings.svg" alt="Settings Icon"> Settings
                </a>
                <a href="logout.php" class="logout">
                    <img src="./Images/signin.svg" alt="Logout Icon"> Log Out
                </a>
            </div>
        </div>
    </div>

    <!-- Body Content (can be populated later) -->
    <div id="body-content" class="col-10">
    </div>
</div>
		</div>
	</div>

    <script>
        //FUNCTIONS
        function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
    }
        //DASHBOARD CONTENT
        document.getElementById("button0").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./UserDash.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    initializeViewDetailsButtons();
                    initializeSeeAllButtons()
                    document.title = "Dashboard"; // Change the page title
                    document.getElementById("page-title").innerText = "Dashboard"; // Change the displayed title
                    renderCharts();
                })
                .catch(error => console.error('Error fetching content:', error));
        });

        //BROWSE CONTENT
		document.getElementById("buttonbrowse").addEventListener("click", function(event) {
			event.preventDefault();
			fetch('./Browse.php')
				.then(response => response.text())
				.then(data => {
					document.getElementById("body-content").innerHTML = data;
					document.title = "Browse"; // Change the page title
					document.getElementById("page-title").innerText = "Browse"; // Change the displayed title
					
					// Initialize the carousel after content is loaded
					initializeCarousel();
				})
				.catch(error => console.error('Error fetching content:', error));
		});

		function moveCarousel(direction) {
		const carousel = document.querySelector('.carousel');
		const items = document.querySelectorAll('.carousel-item');
		const totalItems = items.length;

		let currentIndex = 0; // Define currentIndex here

		currentIndex += direction;

		// Loop back if we go out of bounds
		if (currentIndex < 0) {
			currentIndex = totalItems - 1;
		} else if (currentIndex >= totalItems) {
			currentIndex = 0;
		}

		// Move the carousel
		const offset = -currentIndex * 100; // Adjust this value based on your item width
		carousel.style.transform = `translateX(${offset}%)`;
	}

		function initializeCarousel() {
			// Add event listeners to carousel buttons
			document.querySelector('.prev').addEventListener('click', function() {
				moveCarousel(-1);
			});

			document.querySelector('.next').addEventListener('click', function() {
				moveCarousel(1);
			});
		}
		
		function loadBooks() {
		const searchInput = document.getElementById('search-input').value;
		const sortOption = document.getElementById('sort-dropdown').value;

		const xhr = new XMLHttpRequest();
		xhr.open('GET', `Browse.php?search=${encodeURIComponent(searchInput)}&sort=${sortOption}&ajax=1`, true);
		xhr.onload = function() {
			if (xhr.status === 200) {
				const response = JSON.parse(xhr.responseText);
				const booksContainer = document.querySelector('.userbooks-container');
				booksContainer.innerHTML = ''; // Clear existing books

				response.books.forEach(book => {
					const bookElement = document.createElement('div');
					bookElement.classList.add('book-container');
					bookElement.innerHTML = `
						<div class="button-container">
							<button id="${book.borrowed ? 'stats-btn2' : 'stats-btn'}">
								<img src="${book.borrowed ? './Images/Check.svg' : './Images/Unavalable.svg'}" alt="Book status" class="book-status" width="30" height="30">
								Borrowed
							</button>
							<form action="AddToFav.php" method="POST">
								<input type="hidden" name="bookid" value="${book.bookid}">
								<input type="hidden" name="idno" value="${book.idno}">
								<input type="hidden" name="booktitle" value="${book.booktitle}">
								<input type="hidden" name="author" value="${book.author}">
								<input type="hidden" name="bookimg" value="${book.bookimg}">
								<button type="submit" id="addtofav-btn">
									<img src="${book.favorite ? './Images/AddedtoFav.svg' : './Images/fav.svg'}" alt="Book fav" class="book-fav">
								</button>
							</form>
						</div>
						<img src="${book.bookimg}" alt="Book Thumbnail" width="100" height="150">
						<p id="B-title">${book.booktitle}</p>
						<p id="Book-Author">${book.author}</p>
						<form action="BorrowBook.php" method="POST">
							<input type="hidden" name="bookid" value="${book.bookid}">
							<button id="${book.borrowed ? 'borbtn2' : 'borbtn'}">Borrow</button>
						</form>
					`;
					booksContainer.appendChild(bookElement);
				});
			}
		};
		xhr.send();
	}
	
	

        //FAVORITES CONTENT
        document.getElementById("button1").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./Favorites.php')    
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Favorites"; // Change the page title
                    document.getElementById("page-title").innerText = "Favorites"; // Change the displayed title
                    renderCharts();
                })
                .catch(error => console.error('Error fetching content:', error));
        });


        //BORROWED BOOKS CONTENT
        document.getElementById("button2").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./UserMyBooks.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "My Books"; // Change the page title
                    document.getElementById("page-title").innerText = "My Books"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });

        //BOOK REQUEST CONENT
        document.getElementById("button3").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./BookRequest.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Borrowed Request"; // Change the page title
                    document.getElementById("page-title").innerText = "Borrowed Request"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });

        
        //HISTORY CONENT
        document.getElementById("button4").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./UserHistory.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "History"; // Change the page title
                    document.getElementById("page-title").innerText = "History"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });

        document.getElementById("SettingsBtn").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./UserSettings.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "User Profile"; // Change the page title
                    document.getElementById("page-title").innerText = "User Profile"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });


    //PROFILE DROP DOWN
            document.addEventListener("click", function(event) {
            const dropdown = document.getElementById("profile-dropdown");
            const profileSection = document.querySelector(".info-column");

            // Toggle dropdown when profile is clicked
            if (profileSection.contains(event.target)) {
                event.preventDefault();
                dropdown.style.display = (dropdown.style.display === "none" || dropdown.style.display === "") ? "block" : "none";
                
                // Initialize the settings button only when the dropdown is shown
                if (dropdown.style.display === "block") {
                    initializeUserSettingsButton();// Initialize only once when dropdown is visible
                }
            } else if (!dropdown.contains(event.target)) {
                // Close dropdown if clicked outside
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
        
    //NOTIFICATION
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

        function loadUserDashboard() {
            fetch('./UserDash.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    initializeViewDetailsButtons();
                    initializeSeeAllButtons()
                    document.title = "Dashboard"; // Change the page title
                    document.getElementById("page-title").innerText = "Dashboard"; // Change the displayed title
                    renderCharts();
                })
                .catch(error => console.error('Error fetching content:', error));
        };

        window.onload = function() {
            loadUserDashboard();  // Automatically load the Dashboard content
        };

        function initializeViewDetailsButtons() {
        document.querySelectorAll(".ViewDetailsButton").forEach(button => {
            button.addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./ViewDetailsAvailable.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "View Details"; // Change the page title
                    document.getElementById("page-title").innerText = "View Details"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });
    });
}

function initializeSeeAllButtons() {
    document.querySelectorAll(".SeeAllButton").forEach(button => {
        button.addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./UserMyBooks.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "My Books"; // Change the page title
                    document.getElementById("page-title").innerText = "My Books"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });
    });
}

        document.getElementById("ViewDetailsButton").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./ViewDetails.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "View Details"; // Change the page title
                    document.getElementById("page-title").innerText = "View Details"; // Change the displayed title
                    renderCharts();
                })
                .catch(error => console.error('Error fetching content:', error));
        });
        //SETTINGS BUTTON
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
        function initializeUserSettingsButton() {
            const updateButton = document.querySelector(".Usersettings"); // Use querySelector for a single element
            updateButton.addEventListener("click", function(event) {
                event.preventDefault();
                fetch('./UserSettings.php')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("body-content").innerHTML = data;
                        document.title = "Settings"; // Change the page title
                        document.getElementById("page-title").innerText = "Settings "; // Change the displayed title
                    })
                    .catch(error => handleError('Error fetching ReadersInformation:', error));
            });
        }
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
