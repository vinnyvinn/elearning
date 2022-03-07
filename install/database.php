<?php
if(file_exists('../env.php')){
    header("Location: ..");
}
error_reporting(0);
session_start();
if($_POST){
    //check connection
    $conn = mysqli_connect($_POST['db_server'], $_POST['db_username'], $_POST['db_password'], $_POST['db_name']);
    if(mysqli_connect_error()) {
        $message = "Cannot connect to the database with the provided information";
    } else {
        $database = [
                'server'    => $_POST['db_server'],
                'username'  => $_POST['db_username'],
                'password'  => $_POST['db_password'],
                'database'  => $_POST['db_name'],
                'prefix'    => $_POST['db_prefix'],
        ];
        $_SESSION['database'] = $database;
        header("Location: user.php");
        exit;
    }
}
include 'header.php';
?>
<div class="login">
    <div class="auth-heading mt-15">
        <h2 class="text-center">Database Setup</h2>
    </div>
    <div class="auth-form">
        <p>
            Please fill in the information below
        </p>
        <?php
        if(isset($message)) {
            echo '<div class="alert alert-danger">'.$message.'</div>';
        }
        ?>
        <form action="" data-parsley-validate="" method="POST">
            <div class="form-group">
                <label for="server">Database Server</label>
                <input type="text" id="server" class="form-control" name="db_server" value="localhost" required />
            </div>
            <div class="form-group">
                <label for="name">Database Name</label>
                <input type="text" id="name" class="form-control" name="db_name" required />
            </div>
            <div class="form-group">
                <label for="username">Database UserName</label>
                <input type="text" id="username" class="form-control" name="db_username" required />
            </div>
            <div class="form-group">
                <label for="password">Database Password</label>
                <input type="text" id="password" class="form-control" name="db_password" />
            </div>
            <div class="form-group">
                <label for="prefix">Database Prefix</label>
                <input type="text" id="prefix" class="form-control" name="db_prefix"/>
            </div>
            <button type="submit" class="btn btn-primary">Submit and Continue</button>
        </form>
    </div>
</div>
<?php
include 'footer.php';
?>
