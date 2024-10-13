<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LiBorrow Dashboard</title>
    <link rel="stylesheet" href="l_dashboardstyles.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h1 class="logo">LiBorrow.</h1>
            <nav>
                <ul>
                    <li class="active"><a href="#">Dashboard</a></li>
                    <li><a href="#">Readers List</a></li>
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

        <main class="dashboard">
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

            <section class="dashboard-stats">
                <div class="stat-box">
                    <p>100</p>
                    <p>Total Readers</p>
                </div>
                <div class="stat-box">
                    <p>200</p>
                    <p>Total Books</p>
                </div>
            </section>

            <section class="transaction-overview">
                <h2>Transaction Overview</h2>
                <div class="overview-charts">
                    <div class="pie-chart">
                        <canvas id="pieChart"></canvas>
                        <ul>
                            <li><span class="dot borrowed"></span> Borrowed: 60%</li>
                            <li><span class="dot pending"></span> Pending: 10%</li>
                            <li><span class="dot returned"></span> Returned: 20%</li>
                            <li><span class="dot overdue"></span> Overdue: 10%</li>
                        </ul>
                    </div>
                    <div class="bar-chart">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="script.js"></script>
</body>
</html>
