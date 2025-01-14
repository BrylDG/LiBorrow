    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Liborrow</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="LandingStyles.css">
    </head>
    <body>
        <!-- NAVBAR SECTION -->
        <nav class="navbar navbar-light p-4">

            <div class="container-fluid d-flex justify-content-between align-items-center">

                <!-- LOGO -->
                <a class="col-2 navbar-brand p-0" href="#">
                    <span class="liborrow-logo">Liborrow<span class="dot">.</span></span>
                </a>

                <!-- SEARCH BAR -->
                <form class="col-3 search-bar position-relative justify-content-end">
                    <input class="form-control me-1" type="search" placeholder="Search" aria-label="Search">
                    <i class="fas fa-search search-icon"></i>
                </form>

                <!-- BUTTONS -->
                <div class="d-none d-lg-block">
                    <a href="login.php" class="btn sign-in">Sign-In</a>
                    <a href="register.php" class="btn btn-get-started">Get Started</a>
                </div>

                <!-- BURGER MENU -->
                <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- BURGER CONTENT -->
                <div class="collapse navbar-collapse" id="navbarMenu">
                    <ul class="col-sm-2 offset-sm-10 navbar-nav">
                        <li class="nav-item">
                            <a href="login.php" class="nav-link">Sign-In</a>
                        </li>
                        <li class="nav-item">
                            <a href="register.php" class="nav-link">Get Started</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- MAIN SECTION -->
        <section class="main-section">
            <div class="container">
                <div class="row align-items-center">

                    <!-- SLOGAN -->
                    <div class="col-xxl-12 slogan-text">
                        <h1>Your Next <span class="highlight">Chapter</span> Awaits<span class="highlight">.</span></h1>
                        <p>Start Borrowing Today: Your next favorite book is waiting.</p>
                        <a href="register.php" class="btn btn-warning btn-lg">Start Reading</a>
                    </div>

                    <!-- TRENDING BOOKS -->
                    <div class="col-xxl-12 trending-books-section">
                        <h2 class="text-center">Trending Books Today</h2>

                        <!-- BOOK GRIDS-->
                        <div class="book-grid row text-center mt-4 justify-content-center">
                            <div class="col-lg-3 col-md-4 col-sm-6 book-item">
                                <img src="Images\FateBlood_book.svg" class="img-fluid" alt="A Fate Inked in Blood">
                                <p>A Fate Inked in Blood</p>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6 book-item">
                                <img src="Images\FourthWing_book.svg" class="img-fluid" alt="Fourth Wing">
                                <p>Fourth Wing</p>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6 book-item">
                                <img src="Images\KillMock_book.svg" class="img-fluid" alt="To Kill a Mockingbird">
                                <p>To Kill a Mockingbird</p>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6 book-item">
                                <img src="Images\HungerGames_book.svg" class="img-fluid" alt="The Hunger Games">
                                <p>The Hunger Games</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SUB SECTION -->
        <section class="sub-section">
            <div class="container">
                <div class="row convenience-section">
                    <!-- LEFT GRID -->
                    <div class="col-md-12 convenience-text">
                        <h1>
                            Borrow with <span class="highlight">Ease</span>, <span class="highlight">Anytime</span> You Need.
                        </h1>
                        <p>Browse your library's collection online, borrow with a click.</p>
                    </div>

                    <!-- RIGHT GRID -->
                    <div class="col-md-12 convenience-image">
                        <img src="Images\gilBrowsingLaptop.png" alt="Person working on laptop" class="img-fluid" />
                    </div>
                </div>
            </div>
        </section>

        <!-- END SECTION -->
        <section class="end-section">
            <div class="container">
                <div class="row embark-section">

                    <!-- LEFT GRID (Image) -->
                    <div class="col-md-12 image-content order-md-2">
                        <img src="Images\girl holding book with backpack.png" alt="An image depicting [accurate description here]" class="img-fluid">
                    </div>

                    <!-- RIGHT GRID (Text) -->
                    <div class="col-md-12 text-content order-md-1">
                        <h1>Embark on a new <span class="highlight">reading</span> adventure<span class="highlight">.</span></h1>
                        <p>Start your journey today. Borrow your next book now!</p>
                        <a href="register.php" class="btn btn-warning btn-lg cta-button">Start Reading</a>
                    </div>

                </div>
            </div>
        </section>

        <!-- FOOTER SECTION -->
        <footer>
            <nav>
                <a href="#">About</a>
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Team</a>
            </nav>
            <p>© 2024 Bravo Two All Rights Reserved.</p>

        <!-- BOOTSTRAP SCRIPT -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
