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
                            <a class="nav-link active" aria-current="page" href="/mysupermarketshopt22/index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="/mysupermarketshopt22/basket.php">Basket</a>
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
                        // if suer not logged in, then show the register and login options
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
            <?php
            // show the welcome message only when coming in the page first time, not when searching a product
            if (!($_SERVER['REQUEST_METHOD'] === 'POST')) {
                echo '<div class="row">
                    <div class="col-sm text-center">
                        <div class="card" style="width: 50%;margin: auto;">
                            <div class="card-body">
                                <h3>Welcome to My Supermarket Shop!</h3>
                                Your every day portal to access the best prices for your favourite products!<br>
                                Find the best products and prices among 100+ supermarkets!
                            </div>
                        </div>
                    </div>
                </div>';
            }
            ?>
            <div class="row mt-2 mb-2" style="width: 75%;margin: auto;">
                <div class="col-sm text-center">
                    <div class="card">
                        <div class="card-body text-center">
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
                            
                            // get all the supermarket keys
                            $supermarkets = $database->getReference('/Products/')->getChildKeys();
                            
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                
                                // get the product selected to search
                                $product = $_POST['products'];
                                
                                echo '<h3 class="card-title">'.$product.'</h3>';
                                
                                // from the products available, find the product and get the supermarkets that have it
                                $supermarkets = $database->getReference('/Products/'.$product.'/')->getChildKeys();
                                
                                // show how many supermarkets have the product
                                echo '<h6 class="card-subtitle mb-2 text-muted">Available in: '. sizeof($supermarkets).' supermarkets</h6>';
                                echo '<h6>Set the amount you wish to buy and click "Add To Basket"!</h6>';
                                echo '<hr>';
                                echo '<div class="container text-center">';
                                
                                $i = 0;
                                // for every supermarket that sells the product, create a card showing supermarket name, price and quantity
                                foreach ($supermarkets as $supermarket) {
                                    // the reference to the supermarket information
                                    $supermarket_reference = $database->getReference('/Products/'.$product.'/'.$supermarket.'/');
                                    // the name of the supermarket
                                    $supermarket_name = $supermarket_reference->getChild('/supermarketName/')->getSnapshot()->getValue();
                                    // the price of the product int hat supermarket
                                    $supermarket_price = $supermarket_reference->getChild('/priceOfUnit/')->getSnapshot()->getValue();
                                    // for every third item, make a new row
                                    if($i==0 || $i==3 || $i==6 ){
                                        echo '<div class="row justify-content-center">';
                                    }
                                    echo //display a card, with supermarket name, price and a functionality to set quantity and add to basket
                                    '<div class="col text-center">
                                        '.'
                                    <div class="card" style="width: 18em;margin: auto; margin-bottom: 0.5em;">
                                      <div class="card-header">
                                        '.$supermarket_name.'
                                      </div>
                                      <ul class="list-group list-group-flush text-center">
                                        <li class="list-group-item">Price : £<p style="display: inline;" id = "'.$supermarket.'-price">'.$supermarket_price.'</p></li>
                                        <li class="list-group-item">Quantity: <input class="w-25" type="number" value="0" id="'.$supermarket.'-quantity" name="quantity" min="1" max="5"></li>
                                        <li class="list-group-item"><button id="'.$supermarket.'-button" onclick="addToBasket('."'".$product."' , '".$supermarket."'".')" type="button" class="btn btn-success">Add To Basket</button></li>
                                      </ul>
                                    </div>'.
                                            '
                                    </div>';
                                    //for every third supermarker close the row div
                                    if($i==2 || $i==5 || $i==8 ){
                                        echo '</div>';
                                    }
                                    $i = $i + 1;
                                }
                                echo '</div>';
                                echo '<hr>';
                                echo '<a class="btn btn-primary" href="/mysupermarketshopt22/index.php">New Search</a>';
                            }else{
                                // show all the available products that can be searched for
                                echo '<h5 class="card-title">Search Available Products</h5>';
                                echo '<h6>Search through our products, pick the one you wish and click "Search"!</h6>';
                                // get all products
                                $products = $database->getReference('/Products/')->getChildKeys();
                                
                                echo '<form action="index.php" method="post" class = "w-50 text-center" style="margin:auto;margin-top:1.5em;">
                                      <div class="mb-3">';
                                echo '<select name="products" class="form-select">';
                                // show all products in the select functionality
                                foreach ($products as $product) {
                                    echo $product;
                                    echo '<option value="'.$product.'" >'.$product.'</option>';
                                    $i = $i + 1;
                                }
                                echo '      </select>
                                         </div>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>';
                            }
                            echo '</div>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
