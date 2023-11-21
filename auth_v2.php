<?php include_once('includes/load.php'); ?>
<?php
$req_fields = array('username', 'password');
validate_fields($req_fields);

$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

// Check for validation errors
if (empty($errors)) {
    // Attempt to authenticate the user
    $user = authenticate_v2($username, $password);

    if ($user) {
        // Successfully authenticated
        // Create session with user id
        $session->login($user['id']);
        // Update sign-in time
        updateLastLogIn($user['id']);

        // Redirect user based on user level
        if ($user['user_level'] === '1') {
            $session->msg("s", "Hello " . $user['username'] . ", Welcome to Infomax.");
            redirect('admin.php', false);
        } elseif ($user['user_level'] === '2') {
            $session->msg("s", "Hello " . $user['username'] . ", Welcome to Infomax.");
            redirect('special.php', false);
        } else {
            $session->msg("s", "Hello " . $user['username'] . ", Welcome to Infomax.");
            redirect('home.php', false);
        }
    } else {
        // Authentication failed
        $session->msg("d", "Sorry, Username/Password incorrect.");
        redirect('index.php', false);
    }

} else {
    // Validation errors occurred
    $session->msg("d", $errors);
    redirect('login_v2.php', false);
}
?>
