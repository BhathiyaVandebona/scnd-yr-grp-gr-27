<?php
require_once 'core/init.php';

$user = new User();
if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if(Input::exists()) {
    if(Token::check_token(Input::get(('token')))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'password_current' => array(
                'required' => true,
                'min' => 8,
            ),
            'password_new' => array(
                'required' => true,
                'min' => 8,
            ),
            'password_new_reenter' => array(
                'required' => true,
                'min' => 8,
                'matches' => 'password_new'
            )
        ));

        if($validation->passed()) {
            // check the current password against the password in the database
            // to confirm the user
            if (Hash::make(Input::get('password_current') . $user->data()->salt) !== $user->data()->password) {
                echo 'Please enter your correct current password';
            } else {
                $salt = Hash::salt(32);
                try {
                $user->update(
                    array(
                        'salt' => $salt,
                        'password' => Hash::make(Input::get('password_new'), $salt)
                    )
                );
                Session::flash('home', 'Your password have been updated.');
                Redirect::to('index.php');
            } catch(Exception $exception) {
                die($exception->getMessage());
            }
            }
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
        <div class="field">
            <label for="password_current">Current password</label>
            <input type="password" name="password_current" id="password_current">
        </div>

        <div class="field">
            <label for="password_new">New password</label>
            <input type="password" name="password_new" id="password_new">
        </div>

        <div class="field">  
            <label for="password_new_reenter">Re enter new password</label>
            <input type="password" name="password_new_reenter" id="password_new_reenter">
        </div>

        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <input type="submit" value="Change password">
    </form>
</div>