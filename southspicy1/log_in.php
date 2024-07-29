<?php

// Initialize the session

session_start();

 

// Check if the user is already logged in, if yes then redirect him to welcome page

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

    header("location: welcome.php");

    exit;

}

 

// Include config file

require_once "config.php";

 

// Define variables and initialize with empty values

$username = $password = "";

$username_err = $password_err = $login_err = "";

 

// Processing form data when form is submitted

if($_SERVER["REQUEST_METHOD"] == "POST"){

 

    // Check if username is empty

    if(empty(trim($_POST["username"]))){

        $username_err = "Please enter username.";

    } else{

        $username = trim($_POST["username"]);

    }

    

    // Check if password is empty

    if(empty(trim($_POST["password"]))){

        $password_err = "Please enter your password.";

    } else{

        $password = trim($_POST["password"]);

    }

    

    // Validate credentials

    if(empty($username_err) && empty($password_err)){

        // Prepare a select statement

        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        

        if($stmt = mysqli_prepare($link, $sql)){

            // Bind variables to the prepared statement as parameters

            mysqli_stmt_bind_param($stmt, "s", $param_username);

            

            // Set parameters

            $param_username = $username;

            

            // Attempt to execute the prepared statement

            if(mysqli_stmt_execute($stmt)){

                // Store result

                mysqli_stmt_store_result($stmt);

                

                // Check if username exists, if yes then verify password

                if(mysqli_stmt_num_rows($stmt) == 1){                    

                    // Bind result variables

                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

                    if(mysqli_stmt_fetch($stmt)){

                        if(password_verify($password, $hashed_password)){

                            // Password is correct, so start a new session

                            session_start();

                            

                            // Store data in session variables

                            $_SESSION["loggedin"] = true;

                            $_SESSION["id"] = $id;

                            $_SESSION["username"] = $username;                            

                            

                            // Redirect user to welcome page

                            header("location: welcome.php");

                        } else{

                            // Password is not valid, display a generic error message

                            $login_err = "Invalid username or password.";

                        }

                    }

                } else{

                    // Username doesn't exist, display a generic error message

                    $login_err = "Invalid username or password.";

                }

            } else{

                echo "Oops! Something went wrong. Please try again later.";

            }



            // Close statement

            mysqli_stmt_close($stmt);

        }

    }

    

    // Close connection

    mysqli_close($link);

}

?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Italian Cuisine</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicons -->
        <link href="img/favicon.ico" rel="icon">
        <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600|Pacifico" rel="stylesheet"> 

        <!-- Bootstrap CSS File -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Libraries CSS Files -->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="vendor/animate/animate.min.css" rel="stylesheet">
        <link href="vendor/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
        <link href="vendor/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

        <!-- Main Stylesheet File -->
        <link href="css/style.css" rel="stylesheet">
    </head>

    <body>

        <!-- Top Header Start -->
        <section id="top-header">
            <div class="logo">
				<img src="img/logo.jpg.jpeg" />
            </div>
        </section>
        <!-- Top Header End -->

        <!-- Header Start -->
        <header id="header">
            <div class="container">
                <nav id="nav-menu-container">
                    <ul class="nav-menu">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="about.html">About</a></li>
                        <li><a href="menu.html">Menu</a></li>
                        <li><a href="reservation.php">Reservation</a></li>
                        <li><a href="order.html">Online Order</a></li>
                        <li class="menu-has-children"><a href="#">pages</a>
                            <ul>
                                <li><a href="login.html">Login</a></li>
                            </ul>
                        </li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <!-- Header End -->

        <!-- Reservations Section Start -->
        <section id="reservations">
            <div class="container">
                <header class="section-header">
                    <h3>Reservations</h3>
                </header>
                <div class="container">
                    <button class="btn btn-block btn-book"><a href="reservation.php">Book Now</a> </button>
                </div>
            </div>
        </section>
        <!-- Reservations Section End -->

        <div class="wrapper">

        <h2>Login</h2>

        <p>Please fill in your credentials to login.</p>



        <?php 

        if(!empty($login_err)){

            echo '<div class="alert alert-danger">' . $login_err . '</div>';

        }        

        ?>



        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group">

                <label>Username</label>

                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">

                <span class="invalid-feedback"><?php echo $username_err; ?></span>

            </div>    

            <div class="form-group">

                <label>Password</label>

                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">

                <span class="invalid-feedback"><?php echo $password_err; ?></span>

            </div>

            <div class="form-group">

                <input type="submit" class="btn btn-primary" value="Login">

            </div>

            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>

        </form>

    

        <!-- Subscriber Section Start -->
        <section id="subscriber">
            <div class="container">
                <div class="section-header">
                    <h3>Get Latest Food Info</h3>
                </div>

                <div class="row">
                    <div class="col-12">
                        <form class="form-inline">
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Enter Your Email">
                            </div>
                            <button type="submit" class="btn">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- Subscriber Section end -->

         <!-- Footer Start -->
         <footer id="footer">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="copyright">
							<p>@southspicy<a href=""></a></p>
							
							<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
							<p>Designed By: VRID group<a href="https://htmlcodex.com"></a></p>
						</div>
                    </div>
                    <div class="col-sm-6">
                        <ul class="icon">
                            <li><a href="#" class="fa fa-twitter"></a></li>
                            <li><a href="#" class="fa fa-facebook"></a></li>
                            <li><a href="#" class="fa fa-pinterest"></a></li>
                            <li><a href="#" class="fa fa-google-plus"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer end -->

        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

        <!-- JavaScript Libraries -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/jquery/jquery-migrate.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="vendor/easing/easing.min.js"></script>
        <script src="vendor/stickyjs/sticky.js"></script>
        <script src="vendor/superfish/hoverIntent.js"></script>
        <script src="vendor/superfish/superfish.min.js"></script>
        <script src="vendor/owlcarousel/owl.carousel.min.js"></script>
        <script src="vendor/tempusdominus/js/moment.min.js"></script>
        <script src="vendor/tempusdominus/js/moment-timezone.min.js"></script>
        <script src="vendor/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

        <!-- Main Javascript File -->
        <script src="js/main.js"></script>

    </body>
</html>
