<?php
// Created by Bennito254 (https://www.bennito254.com)
if(file_exists('../env.php')){
    header("Location: ..");
}
error_reporting(0);
session_start();
if (!isset($_SESSION['database'])) {
    header("Location: database.php");
}

if ($_POST) {
    $email = $_POST['user_email'];
    $pass1 = $_POST['user_password'];
    $pass2 = $_POST['user_password_confirm'];
    $url = $_POST['user_url'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email';
    } elseif (strlen($pass1) < 6) {
        $message = 'Password must be at least 6 characters long';
    } elseif ($pass1 != $pass2) {
        $message = 'Passwords do not match';
    } elseif (!$url || $url == '') {
        $message = "Invalid application URL";
    } else {
        $_SESSION['user'] = [
            'email' => $email,
            'password' => $pass1,
            'url'   => $url,
            'fname' => $_POST['user_fname'],
            'lname' => $_POST['user_lname']
        ];
        header("Location: complete.php");
    }
}
include 'header.php';
?>
<div class="login">
    <div class="auth-heading mt-15">
        <h2 class="text-center">Administrator Setup</h2>
    </div>
    <div class="auth-form">
        <p>
            Please fill in the information below
        </p>
        <?php
        if (isset($message)) {
            echo '<div class="alert alert-danger">' . $message . '</div>';
        }
        $url = "https";
        $url .= "://" . $_SERVER['HTTP_HOST'];
        $url .= str_replace([basename($_SERVER['SCRIPT_NAME']), 'install/'], "", $_SERVER['SCRIPT_NAME']);
        ?>
        <form action="" data-parsley-validate="" method="POST">
            <div class="form-group">
                <label for="url">Application URL</label>
                <input type="text" id="url" class="form-control" value="<?php echo $url; ?>" name="user_url" required/>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" class="form-control" name="user_fname" required/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fname">Last Name</label>
                        <input type="text" id="lname" class="form-control" name="user_lname" required/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="server">E-Mail</label>
                <input type="email" id="server" class="form-control" name="user_email" required/>
            </div>
            <div class="form-group">
                <label for="name">Password</label>
                <input type="password" id="name" class="form-control" name="user_password" required/>
            </div>
            <div class="form-group">
                <label for="username">Confirm Password</label>
                <input type="password" id="username" class="form-control" name="user_password_confirm" required/>
            </div>
            <button type="submit" class="btn btn-primary">Complete Installation</button>
        </form>
    </div>
</div>
<?php
include 'footer.php';
?>
