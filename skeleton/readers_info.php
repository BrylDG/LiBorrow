<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reader's Information</title>
    <link rel="stylesheet" href="readers_infostyles.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h1 class="logo">LiBorrow.</h1>
            <nav>
                <ul>
                    <li class="active"><a href="l_dashboard.php">Dashboard</a></li>
                    <li><a href="readers_list.php">Readers List</a></li>
                    <li><a href="inventory.php">Inventory</a></li>
                    <li><a href="transactions.php">Transactions</a>
                        <ul class="sub-menu">
                            <li><a href="pending.php">Pending</a></li>
                            <li><a href="borrowed.php">Borrowed</a></li>
                            <li><a href="returned.php">Returned</a></li>
                            <li><a href="overdue.php">Overdue</a></li>
                        </ul>
                    </li>
                    <li><a href="history.php">History</a></li>
                </ul>
            </nav>
        </aside>

        <main class="reader-info">
            <header class="dashboard-header">
                <div class="user-info">
                    <p>Welcome Back, Daisy Laygan!</p>
                    <p>August 12, 2024 | 11:20:30 A.M.</p>
                </div>
                <div class="user-settings">
                    <div class="user-dropdown">
                        <img src="avatar.png" alt="User Avatar">
                        <span>Daisy Lyn Laygan</span>
                        <div class="dropdown-content">
                            <a href="#">Settings</a>
                            <a href="#">Log Out</a>
                        </div>
                    </div>
                </div>
            </header>

            <section class="reader-details">
                <h2>Reader's Information</h2>
                <p><a href="#">Readers List</a> > Reader's Information</p>

                <div class="profile">
                    <img src="user_image.png" alt="Profile Image" class="profile-img">
                    <h3>Jonatan</h3>
                </div>

                <div class="info">
                    <p><strong>ID Number:</strong> 3</p>
                    <p><strong>Username:</strong> Jonatan</p>
                    <p><strong>Fullname:</strong> Jonatan</p>
                    <p><strong>Email:</strong> Jonatan@gmail.com</p>
                    <p><strong>Phone Number:</strong> +7 900 451-65-88</p>
                    <p><strong>Address:</strong> Volgograd, Russia</p>
                    <button class="update-btn">Update</button>
                </div>

                <div class="actions">
                    <button class="ban-btn">Ban</button>
                    <button class="suspend-btn">Suspend</button>
                </div>
            </section>

            <section class="transaction-history">
                <h2>Transaction History</h2>
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Book Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>The Great Gatsby</td>
                            <td>01/01/2022</td>
                            <td>01/15/2022</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>1984</td>
                            <td>02/10/2022</td>
                            <td>02/24/2022</td>
                        </tr>
                        <!-- Add more transactions as needed -->
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
