<?php
require_once 'core/init.php';

if(Input::exists()) {
    if (Token::check_token(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 4
            ),
            'password' => array(
                'required' => true,
                'min' => 8
            ),
        ));

        if ($validation->passed()) {
            // log the user in
            $user = new User();
            $remember = (Input::get('remember_me') === 'on') ? true : false;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);
            if ($login) {
                Redirect::to('index.php');
            } else {
                echo '<p>Please check the user name and the password</p>';
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}
?>


<div class="container">
    <form action="" method="POST">
        <div class="field">
            <label for="Username">Username</label>
            <input type="text" name="username" id="username" autocomplete="off">
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" autocomplete="off">
        </div>
        <div class="field">
            <input type="checkbox" name="remember_me" id="remember_me">
            <label for="remember_me">Remember me</label>
        </div>
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <input type="submit" value="Log in">
    </form>
</div>