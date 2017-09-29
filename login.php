<?php require_once 'core/init.php';

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, [
                'email' => [
                    'required' => true
                    ],
                'password' => [
                    'required' => true
                    ]
        ]);
        if ($validation->passed()) {
            $user = new User();
            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('email'), null, Input::get('password'), $remember);

            if ($login) {
                Redirect::to('index.php');
            } else {
                echo "Sorry, you are not logging";
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
        <title>Login</title>
    </head>
    <body>
        <div class="container">
            <div class="login-form">
                <h1>Login you account</h1>
                <h4><a href="registr.php">Sign up</a> if you haven't account yet</h4>
                <form method="POST">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <label for="email">Email:</label><br>
                    <input type="text" id="email" name="email"  value="<?php echo escape(Input::get('email'))?>"><br>
                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password"  value="<?php echo escape(Input::get('password'))?>"><br>
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label><br>
                    <input type="submit" value="Log in">
                </form>
            </div>
        </div>
</body>
</html>