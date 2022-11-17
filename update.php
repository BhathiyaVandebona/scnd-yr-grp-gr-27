<?php
require_once 'core/init.php';

$user = new User();

// if the user is not logged in
if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if(Input::exists()) {
    if(Token::check_token(Input::get(('token')))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'firstname' => array(
                'required' => true,
                'min' => 4,
                'max' => 255
            ),
            'lastname' => array(
                'required' => true,
                'min' => 4,
                'max' => 255
            ),
            'username' => array(
                'required' => true,
                'min' => 4,
                'max' => 255
            ),
            'password' => array(
                'required' => true,
                'min' => 8,
            )
        ));

        if($validation->passed()) {
            try {
                $user->update(
                    array(
                        'first_name' => Input::get('firstname'),
                        'last_name' => Input::get('lastname'),
                        'username' => Input::get('username'),
                    )
                );
                Session::flash('home', 'Your details have been updated.');
                Redirect::to('index.php');
            } catch(Exception $exception) {
                die($exception->getMessage());
            }
        } else {
            foreach($validation->errors() as $error) {
                echo $error, "<br>";
            }
        }
    }
}
?>

<div class="container">
    <form action="" method="POST">
            <div class="input-field">
                <label for="firstname">First name</label>
                <input type="text" name="firstname" id="firstnam" value="<?php echo escape($user->data()->first_name); ?>">
            </div>

            <div class="input-field">
                <label for="lastname">Last name</label>
                <input type="text" name="lastname" id="lastname" value="<?php echo escape($user->data()->last_name); ?>">
            </div>

            <div class="input-field">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo escape($user->data()->username); ?>">
            </div>
        <br>
        <a href="changepassword.php">Change the password</a>
        <br>
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <input type="submit" value="Submit">
    </form>
</div>