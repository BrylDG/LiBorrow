<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Readers List</title>
    <link rel="stylesheet" href="readers_liststyles.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h1 class="logo">LiBorrow.</h1>
            <nav>
                <ul>
                    <li><a href="#">Dashboard</a></li>
                    <li class="active"><a href="#">Readers List</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li><a href="#">Transactions</a>
                        <ul class="sub-menu">
                            <li><a href="#">Pending</a></li>
                            <li><a href="#">Borrowed</a></li>
                            <li><a href="#">Returned</a></li>
                            <li><a href="#">Overdue</a></li>
                        </ul>
                    </li>
                    <li><a href="#">History</a></li>
                </ul>
            </nav>
        </aside>

        <main class="readers-list">
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

            <section class="table-section">
                <h2>Readers List</h2>
                <div class="table-controls">
                    <input type="text" placeholder="Search" class="search-bar">
                    <button class="sort-button">Sort by</button>
                    <button class="filter-button">Filter by</button>
                </div>

                <table class="readers-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>JohnDoe@gmail.com</td>
                            <td><a href="#">View more</a></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane Smith</td>
                            <td>JaneSmith@gmail.com</td>
                            <td><a href="#">View more</a></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Jonatan</td>
                            <td>Jonatan@gmail.com</td>
                            <td><a href="#">View more</a></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Junjun</td>
                            <td>Junjun@gmail.com</td>
                            <td><a href="#">View more</a></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Juna Mae</td>
                            <td>JunaMae@gmail.com</td>
                            <td><a href="#">View more</a></td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination">
                    <a href="#" class="previous">Previous</a>
                    <a href="#" class="page">1</a>
                    <a href="#" class="page">2</a>
                    <a href="#" class="page">3</a>
                    <a href="#" class="page">4</a>
                    <span>...</span>
                    <a href="#" class="page">50</a>
                    <a href="#" class="next">Next</a>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
