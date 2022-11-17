<?php
require_once 'core/init.php';

if (Session::exists('home')) {
    echo '<p>' . Session::flash('home') . '</p>';
}


$user = new User();

if($user->isLoggedIn()) {
?>
    <p> Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>!</p>
    <ul>
        <li><a href="update.php">Update details</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
<?php


    if ($user->hasPermission('admin')) {
        // depending on the user permissions you can change what the user will see
        // or you can check this at the top of every page and then get the expected result
    }


} else {
    echo '<p> You need to <a href="login.php">log in</a> or <a href="register.php">register</a></p>';
}