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

    </head>

    <body style="background-color: #00A6FB;"></body>
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
                        <li class="nav-item">
                            <a class="nav-link" href="/mysupermarketshopt22/register.php">Register</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link active" href="/mysupermarketshopt22/login.php">Log In</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Login</h5>
                            <form action="login.php" method="post">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Username</label>
                                    <input name ="username" "type="text" class="form-control" id="usernameInput">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Password</label>
                                    <input name ="password" type="password" class="form-control" id="exampleInputPassword1">
                                </div>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        // if page came from a post method, meaning loggin in has taken place, then start the login procces
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // if username and password fields were both set
            if (!ifNull($_POST['username']) && !ifNull($_POST['password'])) {
                
                // create a firebase factory that will connect to the uri of Google Firebase Realtime Database
                $factory = (new Factory)->withDatabaseUri('https://mysupermarketshopt22-default-rtdb.firebaseio.com/');
                // create database instance
                $database = $factory->createDatabase();

                // crate the variables to hold the post values
                $username = $_POST['username'];
                $password = $_POST['password'];

                // check if it is admin account
                $adminUsername = $database->getReference('/Admin/username')->getSnapshot()->getValue();
                $adminPassword = $database->getReference('/Admin/password')->getSnapshot()->getValue();
                
                if($username === $adminUsername && $password === $adminPassword){
                    setcookie("logged_in", true, time() + (86400 * 30), "/");
                    setcookie("logged_in_username", $username, time() + (86400 * 30), "/");
                    echo '<div class="container">
                         <div class="row">
                            <div class="col-sm">
                                <br>
                            </div>
                         </div>
                         <div class="row">
                            <div class="col-sm">
                                <div class="alert alert-success" role="alert">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Welcome '. strval($username).'! Please wait...</div>
                            </div>
                        </div>
                    </div>';
                    echo '<script type="text/javascript">location.href = "/mysupermarketshopt22/admin.php";</script>';
                }
                
                
                // get all the user keys, each corresponding to one user
                $user_keys = $database->getReference('/Users/')->getChildKeys();
                $found = false;
                // for each user key
                foreach ($user_keys as $user_key) {
                    // find the reference and if the username exists under that key
                    $usernameFromDatabase = $database->getReference('/Users/' . strval($user_key) . '/' . strval($username) . '/')->getSnapshot();
                    if ($usernameFromDatabase->exists()) {
                        // if the password from that username also matches
                        if (strval($usernameFromDatabase->getChild('/password/')->getValue()) == strval($password)) {
                            $found = true;
                            // create cookies to hold the logged in user
                            setcookie("logged_in", true, time() + (86400 * 30), "/");
                            setcookie("logged_in_username", $username, time() + (86400 * 30), "/");
                            echo '<div class="container">
                                 <div class="row">
                                    <div class="col-sm">
                                        <br>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-sm">
                                        <div class="alert alert-success" role="alert">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Welcome back '. strval($username).'! Please wait...</div>
                                    </div>
                                </div>
                            </div>';
                            echo '<script type="text/javascript">location.href = "/mysupermarketshopt22/profile.php";</script>';
                        } else { // wrong password = wrong credentials, do not show if the username exists, just show that credentials are wrong
                            echo '<div class="container">
                                 <div class="row">
                                    <div class="col-sm">
                                        <br>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-sm">
                                        <div class="alert alert-danger" role="alert">Incorrect Credentials!</div>
                                    </div>
                                </div>
                            </div>';
                            exit;
                        }
                    }
                }
                if ($found == false) { // wrong username = wrong credentials
                    echo '<div class="container">
                                 <div class="row">
                                    <div class="col-sm">
                                        <br>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-sm">
                                        <div class="alert alert-danger" role="alert">Incorrect Credentials!</div>
                                    </div>
                                </div>
                            </div>';
                    exit;
                }
            }else{ // some field was left unfilled
                echo '<div class="container">
                         <div class="row">
                            <div class="col-sm">
                                <br>
                            </div>
                         </div>
                         <div class="row">
                            <div class="col-sm">
                                <div class="alert alert-danger" role="alert">Please provide all details!</div>
                            </div>
                        </div>
                    </div>'; 
                exit;
            }
        } else {
            exit;
        }
        ?>

    </body>
</html>
