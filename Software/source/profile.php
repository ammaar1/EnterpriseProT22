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
                                <a class="nav-link active" href="/mysupermarketshopt22/profile.php">Orders ('.$_COOKIE['logged_in_username'].') </a>
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
                            <?php
                            
                            // method to determine if a string is set/empty/null
                            function ifNull($post) {
                                if (!isset($post) || empty($post)) {
                                    return true;
                                }
                            }
                            // libraries used for PHP to communicate to firebase realtime database
                            use Kreait\Firebase\Factory;
                            require __DIR__ . '/includes/vendor/autoload.php';

                            // create a firebase factory that will connect to the uri of Google Firebase Realtime Database
                            $factory = (new Factory)->withDatabaseUri('https://mysupermarketshopt22-default-rtdb.firebaseio.com/');
                            // create database instance
                            $database = $factory->createDatabase();
                            
                            // get all the orders
                            $orders = $database->getReference('/Orders/')->getChildKeys();
                           
                            $found_an_order = false;
                            
                            foreach ($orders as $order) {
                                $order_reference = $database->getReference('/Orders/'.$order);
                                
                                $user_from_order = $order_reference->getChild('/user/')->getSnapshot()->getValue();
                                
                                if($user_from_order==$_COOKIE['logged_in_username']){
                                    $found_an_order = true;
                                    
                                    $items_from_order = $order_reference->getChild('/items/')->getSnapshot()->getValue();
                                    
                                    $items = explode("!", strval($items_from_order)); 
                                    echo '<div class="row mt-2 mb-2">
                                            <div class="col-sm text-center">
                                                <div class="card">
                                                    <div class="card-body text-center">';
                                    echo '<h4>Order ID: '.$order.'</h4>';
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
                                    foreach ($items as $item) {
                                        // order format = product-supermarket-quantity-price!
                                        // spplit the string to get each value
                                        $words = explode("-", strval($item));
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
                                    echo '</tbody></table>                        </div>
                    </div>
                </div>
            </div>';
                                }
                            }
                            if($found_an_order==false){
                                echo '            <div class="row mt-2 mb-2">
                                            <div class="col-sm text-center">
                                                <div class="card">
                                                    <div class="card-body text-center"><h2>You currently have no pending orders</h2>                        </div>
                                                </div>
                                            </div>
                                        </div>';
                                exit;
                            }
                            ?>
        </div>

    </body>
</html>
