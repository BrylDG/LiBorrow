<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LiBorrow Dashboard</title>
    <link rel="stylesheet" href="readers_listStyles.css">
</head>
<body>

<div class="container">
    <div class="sidebar">
        <h2>LiBorrow.</h2>
        <ul>
            <li class="active">Dashboard</li>
            <li>Readers List</li>
            <li>Inventory</li>
            <li>Transactions</li>
            <li>Pending</li>
            <li>Borrowed</li>
            <li>Returned</li>
            <li>Overdue</li>
            <li>History</li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="header">
            <div class="greeting">Welcome Back, Daisy Laygan!</div>
            <div class="user-menu">
                <img src="user-icon.png" alt="User" class="user-icon">
                <div class="user-menu-content">
                    <p>Daisy Lyn Laygan</p>
                    <p>Librarian</p>
                    <a href="#">Settings</a>
                    <a href="#">Log Out</a>
                </div>
            </div>
        </div>
        <div class="date-time">August 12, 2024 11:20:30 A.M.</div>
        
        <div class="stats">
            <div class="stat-box">
                <h3>100</h3>
                <p>Total Readers</p>
            </div>
            <div class="stat-box">
                <h3>200</h3>
                <p>Total Books</p>
            </div>
        </div>
        
        <div class="transaction-overview">
            <h3>Transaction Overview</h3>
            <div class="pie-chart">
                <div class="segment borrowed" style="--percentage: 60;">Borrowed</div>
                <div class="segment pending" style="--percentage: 10;">Pending</div>
                <div class="segment returned" style="--percentage: 20;">Returned</div>
                <div class="segment overdue" style="--percentage: 10;">Overdue</div>
            </div>
        </div>

        <div class="book-genre-chart">
            <h3>Books by Genre</h3>
            <div class="bar-chart">
                <div class="bar" style="height: 394px;">394</div>
                <div class="bar" style="height: 256px;">256</div>
                <div class="bar" style="height: 189px;">189</div>
                <div class="bar" style="height: 176px;">176</div>
                <div class="bar" style="height: 43px;">43</div>
            </div>
        </div>
    </div>
</div>

<!-- Readers List Section -->
<div class="readers-list">
    <h2>Readers List</h2>
    <input type="text" placeholder="Search" class="search-bar">
    <div class="table-container">
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
    </div>
    <div class="pagination">
        <button>Previous</button>
        <span>1</span>
        <button>Next</button>
    </div>
</div>

</body>
</html>