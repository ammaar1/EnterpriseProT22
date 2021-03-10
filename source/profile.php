<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
            />

        <title>My Supermarket Shop T22</title>

        <!-- Links to all the CSS and JS libraries that will used as well as the custom css and js modules -->
        <link rel="stylesheet" href="res/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="res/css/main.css" />
        <script src="res/bootstrap/js/bootstrap.min.js"></script>
        <script src="res/js/logout.js"></script>
        <script src="res/js/addToBasket.js"></script>

    </head>
    
    <!--
    ################### UNDER CONSTRUCTION #########
   -->

    <body style="background-color: #00A6FB;">

        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #F0CF65;">
          <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="./res/icons/supermarket.svg" alt="" width="30" height="30">
                My Supermarket Shop
                <img src="./res/icons/supermarket.svg" alt="" width="30" height="30"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link" aria-current="page" href="/mysupermarketshopt22/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/mysupermarketshopt22/basket.php">Basket</a>
                </li>
                <?php
                if (isset($_COOKIE["logged_in"])) {
                    if ($_COOKIE["logged_in"] == true) {
                        echo '<li class="nav-item">
                                <a class="nav-link active" href="/mysupermarketshopt22/profile.php">Profile ('.$_COOKIE['logged_in_username'].') </a>
                              </li>
                              <li class="nav-item">
                                <button type="button" class="btn btn-link" style="color: red;text-decoration: none !important;" onclick="logout()">Log out</button>
                              </li>
                              ';
                    }
                } else {
                    echo '<li class="nav-item">
                            <a class="nav-link" href="/mysupermarketshopt22/register.php">Register</a>
                         </li>
                         <li class="nav-item dropdown">
                            <a class="nav-link" href="/mysupermarketshopt22/login.php">Log In</a>
                          </li>';
                    echo '<script type="text/javascript">location.href = "/mysupermarketshopt22/login.php";</script>';
}
                ?>
              </ul>
            </div>
          </div>
        </nav>
        
        <div class="container">
            <div class="row">
                <div class="col-sm text-center">
                    <img src="/mysupermarketshopt22/res/img/tobedone.jpg" alt="tobedone" width="512" height="384">
                </div>
            </div>
        </div>

    </body>
</html>
