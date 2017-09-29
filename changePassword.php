
<?php
require_once 'core/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'password' => [
                'required' => true,
                'min' => 6
            ],
            'password-new' => [
                'required' => true,
                'min' => 6
            ],
            'password-conf' => [
                'required' => true,
                'min' => 6,
                'matches' => 'password-new'
            ]
        ]);

        if ($validation->passed()) {
            if (Hash::make(Input::get('password'),
                    $user->data()->salt) !== $user->data()->password) {
                echo "You did mistake";
            } else {
                $salt = Hash::salt(32);
                $user->update([
                    'password' => Hash::make(Input::get('password-new'), $salt),
                    'salt' => $salt
                ]);

                Session::flash('success', 'You password has been changed');
                Redirect::to('index.php');
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo $error . '<br>';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Change password</title>
</head>
<body>
<div class="container">
    <div class="login-form">
        <h1>Change password</h1>
        <form action="changePassword.php" method="POST">
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <label for="password">Old password:</label><br>
            <input type="password" id="password" name="password"><br>

            <label for="password-new">New password:</label><br>
            <input type="password" id="password-new" name="password-new"><br>

            <label for="password-conf">Confirm new password:</label><br>
            <input type="password" id="password-conf" name="password-conf"><br>
            <input type="submit" value="Log in">
        </form>
    </div>
</div>
</body>
</html>
