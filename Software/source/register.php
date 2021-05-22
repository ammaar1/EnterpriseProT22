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
                        <li class="nav-item">
                            <a class="nav-link active" href="/mysupermarketshopt22/register.php">Register</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="/mysupermarketshopt22/login.php">Log In</a>
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
                            <h5 class="card-title text-center">Register</h5>
                            <form action="register.php" method="post">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Username</label>
                                    <input name ="username" "type="text" class="form-control" id="usernameInput">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                                    <input name = "email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Password</label>
                                    <input name ="password" type="password" class="form-control" id="exampleInputPassword1">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Date of Birth</label>
                                    <input name = "dateofbirth" class="form-control" type="date" value="2021-01-01" id="example-date-input">
                                </div>
                                <button type="submit" class="btn btn-primary">Register</button>
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

        // check if the user came from a post action, meaning a register procces should start
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // create a firebase factory that will connect to the uri of Google Firebase Realtime Database
            $factory = (new Factory)->withDatabaseUri('https://mysupermarketshopt22-default-rtdb.firebaseio.com/');
            // create database instance
            $database = $factory->createDatabase();

            // check if all fields have been provided
            if (!ifNull($_POST['username']) && !ifNull($_POST['email']) && !ifNull($_POST['password']) && !ifNull($_POST['dateofbirth'])) {

                // create variables for all the fields inputted
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $dateofbirth = $_POST['dateofbirth'];

                // get all user keys which correspond to users to check if username exists
                $user_keys = $database->getReference('/Users/')->getChildKeys();
                foreach ($user_keys as $user_key) {
                    // check if the key has a username inside as the one provided 
                    $usernameFromDatabase = $database->getReference('/Users/'.strval($user_key).'/'.$username.'/')->getSnapshot();
                    // if yes, it means a user exists!
                    if($usernameFromDatabase->exists()){
                        echo '<div class="container">
                                 <div class="row">
                                    <div class="col-sm">
                                        <br>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-sm">
                                        <div class="alert alert-danger" role="alert">Username already exists!</div>
                                    </div>
                                </div>
                            </div>';
                        exit;
                    }
                }
                
                // if not, then ge the reference to the users
                $reference = $database->getReference('/Users/');
                $snapshot = $reference->getSnapshot();
                
                // data to push to the tree holding all the users
                $postData = [
                    strval($username) => [
                        'password' => $password,
                        'email' => $email,
                        'dateofbirth' => date($dateofbirth),
                    ],
                ];
                
                //push to the tree holding all the users, this will also create a unique key
                $database->getReference('/Users')->push($postData);

                // set cookie to show logged in user
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
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Register Successful.. Please wait!</div>
                  </div>
                  </div>
                  </div>';
                echo '<script type="text/javascript">location.href = "/mysupermarketshopt22/profile.php";</script>';
            }else{
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
