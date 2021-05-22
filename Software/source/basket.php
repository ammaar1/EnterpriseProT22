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
        <script src="res/js/emptyBasket.js"></script>
        <script src="res/js/order.js"></script>

    </head>

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
                        <li class="nav-item active">
                            <a class="nav-link active" aria-current="page" href="/mysupermarketshopt22/basket.php">Basket</a>
                        </li>
                        <?php
                        // check from the cookies if there is a user logged in
                        // if yes then show profile option (with their name) and logout option
                        if (isset($_COOKIE["logged_in"])) {
                            if ($_COOKIE["logged_in"] == true) {
                                echo '<li class="nav-item">
                                <a class="nav-link" href="profile.php">Orders (' . $_COOKIE['logged_in_username'] . ') </a>
                              </li>
                              <li class="nav-item">
                                <button type="button" class="btn btn-link" style="color: red;text-decoration: none !important;" onclick="logout()">Log out</button>
                              </li>
                              ';
                            }
                        }
                        // if user not logged in, then show the register and login options
                        else {
                            echo '<li class="nav-item">
                            <a class="nav-link" href="/mysupermarketshopt22/register.php">Register</a>
                         </li>
                         <li class="nav-item dropdown">
                            <a class="nav-link" href="/mysupermarketshopt22/login.php">Log In</a>
                          </li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-2">
            <div class="row mt-2 mb-2">
                <div class="col-sm text-center">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="row" id ="mybasket">
                                <div class="col-sm text-center">
                                    <h2>My Basket</h2>
                                </div>
                            </div>
                            <div class="row" style="display: none;" id ="loginalert">
                                <div class="alert alert-danger" role="alert">
                                    Please log in to proceed with order!
                                </div>
                            </div>
                            <?php
                            // method to determine if a string is set/empty/null
                            function ifNull($post) {
                                if (!isset($post) || empty($post)) {
                                    return true;
                                }
                            }
                             // get from the cookies the basket variable holding all the orders
                            if(isset($_COOKIE['basket'])){
                                $basket = $_COOKIE['basket']; 
                            }else{
                                echo '<div class="row" id ="mybasket">
                                <div class="col-sm text-center">
                                    <h2>Your basket is currently empty.</h2>
                                </div>
                            </div>';
                                return;
                            }
                            // since all orders are concatinated and seperated by a comma (,)
                            // create an array to hold all the orders 
                            $orders = explode("!", strval($basket)); 
                            echo '<table class="table" id ="tableOrders">
                                      <thead>
                                        <tr>
                                           <th scope="col">#</th>
                                           <th scope="col">Product</th>
                                           <th scope="col">Supermarket</th>
                                           <th scope="col">Quantity</th>
                                           <th scope="col">Price</th>
                                           <th scope="col">Total</th>
                                        </tr>
                                      </thead>
                                      <tbody>';
                            $i = 0;
                            // for each order in the orders array
                            foreach ($orders as $order) {
                                // order format = product-supermarket-quantity-price!
                                // spplit the string to get each value
                                $words = explode("-", strval($order));
                                // if the word exists
                                if(isset($words)&&!empty($words)&&isset($words[1])){
                                    echo '<tr>
                                              <th scope="row">' . $i . '</th>
                                              <td>'.$words[0].'</td>
                                              <td>'.$words[1].'</td>
                                              <td>'.$words[3].'</td>
                                              <td>'.(float)$words[2].'</td>
                                              <td>'.$words[2]*(float)$words[3][0].'</td>
                                            </tr>';
                                    $i = $i + 1;
                                }
                            }
                            echo '</tbody></table>';
                            ?>
                            <!-- Button to erase all from the basket -->
                            <button type="button" id="emptyBasket" onclick="emptyBasket()" class="btn btn-danger">Empty Basket</button>
                            <button type="button" id="orderBtn" onclick="order()" class="btn btn-success">Order</button>
                            
                            <div class="row" style="display: none;" id = "order">
                                <div class="col-sm">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title text-center">Please provide your details to confirm your order.</h5>
                                            <form action="order.php" method="post">
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Address 1</label>
                                                    <input name ="address1" "type="text" class="form-control" id="address1Input" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Address 2</label>
                                                    <input name ="address2" "type="text" class="form-control" id="address2Input" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Postcode</label>
                                                    <input name ="postcode" "type="text" class="form-control" id="postcodeInput" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleInputPassword1" class="form-label">Phone Number</label>
                                                    <input name ="phone" type="text" class="form-control" id="phoneInput" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Order</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
