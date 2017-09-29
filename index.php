<?php
require_once 'core/init.php';

if (Session::exists('success')) {
    echo Session::flash('success');
}

$user = new User();

if ($user->isLoggedIn()) {
?>
    <p>Hello <?php echo escape($user->data()->real_name); ?></p>
    <p>Email: <?php echo escape($user->data()->email); ?></p>
    <p><a href="logout.php">Logout</a></p>
    <p><a href="update.php">Update account</a></p>
    <p><a href="changePassword.php">Change password</a></p>
<?php
} else {
    Redirect::to('login.php');
}


