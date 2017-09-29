<?php require_once 'core/init.php';
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'email' => [
                'required' => true,
                'min' => 2,
                'max' => 50,
                'unique' => 'users',
            ],
            'login' => [
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users',
            ],
            'real-name' => [
                'required' => true,
                'min' => 2,
                'max' => 50
            ],
            'password' => [
                'required' => true,
                'min' => 6,
            ],
            'password-confirm' => [
                'required' => true,
                'matches' => 'password',
            ],
            'birth' => [
                 'string' => true
            ],
            'country' => [
                  'number' => true
            ]
        ]);
        if (Input::get('agree') === 'on') {
            if ($validation->passed()) {
                $user = new User();
                $salt = Hash::salt(32);
                try {
                    $user->create([
                        'real_name' => Input::get('real-name'),
                        'email' => Input::get('email'),
                        'login' => Input::get('login'),
                        'password' => Hash::make(Input::get('password'), $salt),
                        'salt' => $salt,
                        'birth_date' => Input::get('birth'),
                        'country' => Input::get('country'),
                        'timestamp' => time()
                    ]);

                    Session::put(Config::get('session/session_name'), $user->getLastId('users'));
                    Session::flash('success', 'You have successfully registered');
                    Redirect::to('index.php');
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                foreach ($validation->errors() as $error) {
                    echo $error . '<br>';
                }
            }
        } else {
            echo "You don't checked agree with conditions on this website";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
</head>
<body>
<div class="container">
    <div class="registr-form">
        <h1>Sign up new account</h1>
        <form action="registr.php" method="post">
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <label for="email">Email:</label><br>
            <input type="text" id="email" name="email"
                   value="<?php echo escape(Input::get('email'))?>"><br>
            <label for="login">Login:</label><br>
            <input type="text" id="login" name="login" value="<?php echo escape(Input::get('login'))?>"><br>
            <label for="real-name">Real name:</label><br>
            <input type="text" id="real-name" name="real-name"><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" value="<?php echo escape(Input::get('password'))?>"><br>
            <label for="password-confirm">Confirmation password:</label><br>
            <input type="password" id="password-confirm" name="password-confirm"><br>
            <label for="birth">Birth date:</label><br>
            <input type="text" id="birth" name="birth" placeholder="YYYY-MM-DD" pattern="[0-9]{4}-[0-1][0-9]-[0-3][0-9]"
                   value="<?php echo escape(Input::get('birth'))?>"><br>
            <label for="country">Country:</label><br>
            <select id="country" name="country">
                <?php
                $user = new User();
                foreach ($user->getCountries() as $key => $value) {
                    echo "<option value=$key>$value</option>";
                }
                ?>
            </select><br>
            <input type="checkbox" id="agree" name="agree"><label for="agree">I Agree with all conditions
                of this website:</label>
            <br>
            <input type="submit" name="register-button">
        </form>
    </div>
</div>
</body>
</html>
