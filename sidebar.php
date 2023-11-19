<link rel="stylesheet" href="css/sidebar.css">
<div class="sidebar">
    <div class="logo-container">
        <div class="logo-wrapper">
            <div class="logo">
                <img src="images/logo.svg" alt="Logo">
            </div>
            <div class="name">
                <h2>MONEY Protocol</h2>
            </div>
        </div>
    </div>
    <div class="links">
        <div>
            <img src="images/market.svg" alt="">
            <a href="market">Market</a>
        </div>

        <div>
            <img src="images/trade.svg" alt="">
            <a href="dashboard">Dashboard</a>
        </div>

        <div>
            <img src="images/news.svg" alt="">
            <a href="news">News</a>
        </div>

        <div>
            <img src="images/ipo.svg" alt="">
            <a href="ipo">IPO</a>
        </div>

        <?php
        if (isset($_SESSION['user_id'])) {
            // Check if the user is the admin (with user ID 6)
            if ($_SESSION['user_id'] == 6) {
                ?>
                <div>
                    <img src="images/addnews.svg" alt="">
                    <a href="addnews">Add News</a>
                </div>

                <div>
                    <img src="images/addipo.svg" alt="">
                    <a href="addipo">Add IPO</a>
                </div>
                <?php
            }
            ?>
            <div>
                <img src="images/logout.svg" alt="">
                <a href="logout">Logout</a>
            </div>
            <?php
        } else {
            ?>
            <div>
                <img src="images/login.svg" alt="">
                <a href="login">Login</a>
            </div>
            <?php
        }
        ?>
    </div>
</div>
