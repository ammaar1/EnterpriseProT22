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
                            <div class="row">
                                <div class="col-sm text-center">
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
                                
                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    
                                    // check if all fields have been provided
                                    if (!ifNull($_POST['address1']) && !ifNull($_POST['address2']) && !ifNull($_POST['postcode']) && !ifNull($_POST['phone'])) {
                                        // create variables for all the fields inputted
                                        $address1 = $_POST['address1'];
                                        $address2 = $_POST['address2'];
                                        $postcode = $_POST['postcode'];
                                        $phone = $_POST['phone'];
                                        
                                        // if not, then ge the reference to the users
                                        $reference = $database->getReference('/Users/');
                                        $snapshot = $reference->getSnapshot();
                                        
                                        // data to push to the tree holding all the users
                                        $postData = [
                                                'address1' => $address1,
                                                'address2' => $address2,
                                                'postcode' => $postcode,
                                                'phone' => $phone,
                                                'user' =>$_COOKIE['logged_in_username'],
                                                'items' =>$_COOKIE['basket']
                                        ];
                                        
                                        //push to the tree holding all the users, this will also create a unique key
                                        $database->getReference('/Orders')->push($postData);
                                        
                                       // clear basket
                                        setcookie("basket", "", time() - 3600);
                                        
                                        echo '<h2>Congratulations, your order was successful.</h2>';
                                        echo '<h3>Order Details</h3>';
                                        echo '<p>Address 1: ' . $address1 . '</p>';
                                        echo '<p>Address 2: ' . $address2 . '</p>';
                                        echo '<p>Postcode: ' . $postcode . '</p>';
                                        echo '<p>Phone: ' . $phone . '</p>';
                                        
                                    } else { // if data sent is corrupted
                                        echo '<h2>Something went wrong, please try again.</h2>';
                                    }
                                    
                                }
                                
                                ?>
                                <a class="btn btn-primary" href="/mysupermarketshopt22/index.php">New Order</a>'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
