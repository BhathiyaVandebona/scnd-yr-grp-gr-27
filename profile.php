<?php

require_once 'core/init.php';
// check is there is a username passed in
if(!$username = Input::get('user')) {
    Redirect::to('index.php');
} else {
    $user = new User($username);

    // if there is no such user
    if(!$user->exists()) {
        Redirect::to(404);
    }// if there is such a user
    else {
        $data = $user->data();
    }
    ?>
    <h3><?php echo escape($data->username); ?>></h3>
    <?php

}