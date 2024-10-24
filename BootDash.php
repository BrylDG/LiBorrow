<?php
<<<<<<< Updated upstream
session_start(); // Start the session
include('connection.php'); // Include your connection file

// Retrieve the full name from the session
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User '; // Default to 'User ' if not set
=======
    session_start();
    include('connection.php');

    if (!isset($_SESSION['idno'])) {
        // Redirect to login page if not logged in
        header("Location: login.php");
        exit();
    }
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
    
=======
    <link rel="stylesheet" href="./BookInformation.css">
    <link rel="stylesheet" href="./UpdateUser.css">
    <link rel="stylesheet" href="./AddBook.css">
    <link rel="stylesheet" href="./Settings.css">
>>>>>>> Stashed changes
    <title>LiBorrow Dashboard</title>
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
<<<<<<< Updated upstream
                                    <img src="./Images/Vector.svg" alt="Pending Icon" width="18" height="18"> Pending
=======
                                    Pending
>>>>>>> Stashed changes
                                </a>
                            </li>
                            <li>
                                <a href="#" id="BorrowedBtn">
<<<<<<< Updated upstream
                                    <img src="./Images/Vector.svg" alt="Borrowed Icon" width="18" height="18"> Borrowed
=======
                                    Borrowed
>>>>>>> Stashed changes
                                </a>
                            </li>
                            <li>
                                <a href="#" id="ReturnedBtn">
<<<<<<< Updated upstream
                                    <img src="./Images/Vector.svg" alt="Returned Icon" width="18" height="18"> Returned
=======
                                    Returned
>>>>>>> Stashed changes
                                </a>
                            </li>
                            <li>
                                <a href="#" id="OverdueBtn">
<<<<<<< Updated upstream
                                    <img src="./Images/Vector.svg" alt="Overdue Icon" width="18" height="18"> Overdue
=======
                                    Overdue
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
                        <div id="profile" class="col-4">					
                            <a href="#" class="info-column">
                                <img src="./Images/Profile.svg" id="profile-image" alt="Profile" height="60" width="60">
                                <div id="profile-info">
                                    <span><?php echo $fullname; ?></span>
=======
                        <div id="profile" class="col-4">
                            <a href="#" class="info-column">
                                <img src="./Images/Profile.svg" id="profile-image" alt="Profile" height="60" width="60">
                                <div id="profile-info">
                                    <span>Daisy Lyn Laygan</span>
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
                            <a href="logout.php" class="logout">
=======
                            <a href="#" class="logout" id="logout-out">
>>>>>>> Stashed changes
                                <img src="./Images/signin.svg" alt="Logout Icon"> Log Out
                            </a>
                        </div>
                    </div>
                </div>
<<<<<<< Updated upstream
                <div id="body-content" class="col-10">
=======
                    <div id="body-content" class="col-10">
>>>>>>> Stashed changes
                </div>
            </div>
        </div>
    </div>

    <script>
<<<<<<< Updated upstream
=======
        
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
            fetch('./Dashboard.php')
=======
            fetch('./Dashboard.html')
>>>>>>> Stashed changes
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Dashboard"; // Change the page title
                    document.getElementById("page-title").innerText = "Dashboard"; // Change the displayed title
                    renderCharts();
                })
                .catch(error => console.error('Error fetching content:', error));
        }
<<<<<<< Updated upstream

        document.getElementById("button0").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./Dashboard.php')
=======
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

        document.getElementById("button0").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./Dashboard.html')
>>>>>>> Stashed changes
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Dashboard"; // Change the page title
                    document.getElementById("page-title").innerText = "Dashboard"; // Change the displayed title
                    renderCharts();
                })
                .catch(error => console.error('Error fetching content:', error));
        });
<<<<<<< Updated upstream
=======
        
>>>>>>> Stashed changes

        function renderCharts() {
                // Pie Chart Data
const pieCtx = document.getElementById('myPieChart').getContext('2d');
const pieData = {
    labels: ['Borrowed', 'Return', 'Pending', 'Overdue'],
    datasets: [{
        data: [60, 20, 10, 10],
        backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#c9cbcf'],
        hoverBackgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#c9cbcf']
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
        labels: ['Category 1', 'Category 2', 'Category 3', 'Category 4', 'Category 5', 'Category 6', 'Category 7', 'Category 8'],
        datasets: [
            {
                backgroundColor: '#5f76e8',
                hoverBackgroundColor: '#3949ab',
                data: [222, 177, 394, 35, 37, 5, 10, 1],
                borderRadius: 8,  // Rounded corners
                borderSkipped: false,  // Disable sharp corners
                label: '2021',
                maxBarThickness: 10  // Adjust this value to control bar thickness
            },
            {
                backgroundColor: '#c1c9ed',
                hoverBackgroundColor: '#9bacee',
                data: [25, 18, 43, 35, 37, 10, 10, 1],
                borderRadius: 8,  // Rounded corners
                borderSkipped: false,
                label: '2022',
                maxBarThickness: 10  // Adjust this value to control bar thickness
            }
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
<<<<<<< Updated upstream
            fetch('./ReaderDash.html')
=======
            fetch('./ReaderDash.php')
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
                    fetch('./ReadersInformation.html')
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById("body-content").innerHTML = data;
                            document.title = "Reader' Information"; // Change the page title
=======
                    fetch('./ReadersInformation.php')
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById("body-content").innerHTML = data;
                            initializeUpdateButton();
                            document.title = "Reader's Information"; // Change the page title
>>>>>>> Stashed changes
                            document.getElementById("page-title").innerText = "Reader's Information"; // Change the displayed title
                        })
                        .catch(error => handleError('Error fetching ReadersInformation:', error));
                });
            });
<<<<<<< Updated upstream
        }

=======
            
        }

        
        // Function to initialize the Update button
        function initializeUpdateButton() {
            const updateButton = document.querySelector(".update-btn"); // Use querySelector for a single element
            updateButton.addEventListener("click", function(event) {
                event.preventDefault();
                fetch('./UpdateUser.php')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("body-content").innerHTML = data;
                        document.title = "Update User"; // Change the page title
                        document.getElementById("page-title").innerText = "Update User"; // Change the displayed title
                    })
                    .catch(error => handleError('Error fetching ReadersInformation:', error));
            });
        }


        
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
=======
                    initializeBookViewMoreButtons();
                    initializeAddBookButton()
>>>>>>> Stashed changes
                    document.title = "Inventory"; // Change the page title
                    document.getElementById("page-title").innerText = "Inventory"; // Change the displayed title
                })
                .catch(error => console.error('Error fetching content:', error));
        });
<<<<<<< Updated upstream
            
=======
        function initializeBookViewMoreButtons() {
            document.querySelectorAll(".view-book a").forEach(button => {
                button.addEventListener("click", function(event) {
                    event.preventDefault();
                    fetch('./BooksInformation.html')
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById("body-content").innerHTML = data;
                            document.title = "Book's Information"; // Change the page title
                            document.getElementById("page-title").innerText = "Book's Information"; // Change the displayed title
                        })
                        .catch(error => handleError('Error fetching ReadersInformation:', error));
                });
            });
            
        }
        function initializeAddBookButton() {
            const updateButton = document.querySelector(".addbtn"); // Use querySelector for a single element
            updateButton.addEventListener("click", function(event) {
                event.preventDefault();
                fetch('./AddBook.html')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("body-content").innerHTML = data;
                        document.title = "Add Book"; // Change the page title
                        document.getElementById("page-title").innerText = "Add Book"; // Change the displayed title
                    })
                    .catch(error => handleError('Error fetching ReadersInformation:', error));
            });
        }
        function initializeSettingsButton() {
            const updateButton = document.querySelector(".settings"); // Use querySelector for a single element
            updateButton.addEventListener("click", function(event) {
                event.preventDefault();
                fetch('./Settings.html')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("body-content").innerHTML = data;
                        document.title = "Settings"; // Change the page title
                        document.getElementById("page-title").innerText = "Settings "; // Change the displayed title
                    })
                    .catch(error => handleError('Error fetching ReadersInformation:', error));
            });
        }


        
>>>>>>> Stashed changes
        document.getElementById("BorrowedBtn").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./TransactionsBorrowed.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("body-content").innerHTML = data;
                    document.title = "Borrowed Books"; // Change the page title
                    document.getElementById("page-title").innerText = "Borrowed Books"; // Change the displayed title
<<<<<<< Updated upstream
=======
                    initializeBorrowedListeners();
                    initializeViewMoreListeners();
>>>>>>> Stashed changes
                })
                .catch(error => console.error('Error fetching content:', error));
        });
        document.getElementById("ReturnedBtn").addEventListener("click", function(event) {
            event.preventDefault();
            fetch('./TransactionsReturned.html')
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
<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
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
        
<<<<<<< Updated upstream
=======
        
>>>>>>> Stashed changes

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
<<<<<<< Updated upstream
=======
            initializeSettingsButton()
>>>>>>> Stashed changes
        });

        document.addEventListener("click", function(event) {
            const dropdown = document.getElementById("profile-dropdown");
            const profileSection = document.querySelector(".info-column");

            if (!profileSection.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = "none";
            }
<<<<<<< Updated upstream
=======
            initializeSettingsButton()
>>>>>>> Stashed changes
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

        window.onload = function() {
            loadDashboard();  // Automatically load the Dashboard content
        };

<<<<<<< Updated upstream
=======
        function initializeBorrowedListeners() {
    // Function to toggle the visibility of the dropdown
    function toggleDropdown(event) {
        event.stopPropagation();
        const borrbox = event.target.closest('.Borrbox'); // Get the parent .Borrbox
        const dropdown = borrbox.nextElementSibling; // Get the .dropdown-content below the .Borrbox
        
        // Toggle by adding/removing the display property
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'contents'; // Show dropdown
        } else {
            dropdown.style.display = 'none'; // Hide dropdown
        }
    }

    // Add the click event listener to each "View Borrowers" link
    document.querySelectorAll('.view-borrowers').forEach(link => {
        link.addEventListener('click', toggleDropdown);
    });

    // Close the dropdown if the user clicks outside of it
    document.addEventListener('click', function(event) {
        // Loop through all dropdowns
        document.querySelectorAll('.dropdown-content').forEach(dropdown => {
            const borrbox = dropdown.previousElementSibling; // The corresponding .Borrbox

            // Close dropdown if the click was outside of both the dropdown and its corresponding .Borrbox
            if (!borrbox.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none'; // Hide the dropdown
            }
        });
    });
}
function initializeViewMoreListeners() {
    function fetchAndDisplayContent(event) {
        event.preventDefault();

        fetch('./ReadersInformation.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text(); 
            })
            .then(data => {
                document.getElementById("body-content").innerHTML = data;
                initializeUpdateButton();
                document.title = "Reader's Information";

                document.querySelectorAll('.view-more-btn').forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                    });
                });
            })
            .catch(error => console.error('Error fetching content:', error));
    }

    document.querySelectorAll(".ViewMoreInfo a").forEach(button => {
        button.addEventListener("click", fetchAndDisplayContent);
    });
}

    document.querySelector('.pencil-icon').addEventListener('click', function() {
            var inputField = document.getElementById('full-name');
            inputField.disabled = false;  // Enable the input field
            inputField.focus();  // Optionally focus the input field
    });
>>>>>>> Stashed changes
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
