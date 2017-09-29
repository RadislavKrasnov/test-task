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
            'real-name' => [
                'required' => true,
                'min' => 2,
                'max' => 50
            ],
            'birth' => [
                'string' => true
            ],
            'country' => [
                'number' => true
            ]
        ]);

        if ($validation->passed()) {
            try {
                $user->update([
                    'real_name' => Input::get('real-name'),
                    'birth_date' => Input::get('birth'),
                    'country' => Input::get('country')
                ]);

                Session::flash('success', 'You have successfully updated');
                Redirect::to('index.php');
            } catch (Exception $e) {
                die($e->getMessage);
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo $error . "<br>";
            }
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
        <form action="update.php" method="post">
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <label for="real-name">Real name:</label><br>
            <input type="text" id="real-name" name="real-name"><br>
            <label for="birth">Birth date:</label><br>
            <input type="text" id="birth" name="birth"><br>
            <label for="country">Country:</label><br>
            <select id="country" name="country">
                <?php
                $user = new User();
                foreach ($user->getCountries() as $key => $value) {
                    echo "<option value=$key>$value</option>";
                }
                ?>
            </select><br>
            <input type="submit" name="register-button">
        </form>
    </div>
</div>
</body>
</html>
