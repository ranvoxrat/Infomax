<?php
$page_title = 'Add User';
require_once('includes/load.php');
page_require_level(1);
$groups = find_all('user_groups');

function validatePassword($password) {
    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{4,}$/";
    return preg_match($pattern, $password);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $req_fields = array('full-name', 'username', 'password', 'conpassword', 'level');
    validate_fields($req_fields);
    
    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['full-name']));
        $username = remove_junk($db->escape($_POST['username']));
        $password = remove_junk($db->escape($_POST['password']));
        $conpassword = remove_junk($db->escape($_POST['conpassword']));

        if (validatePassword($password)) {
            if ($password == $conpassword) {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                $user_level = (int)$db->escape($_POST['level']);

                $query = "INSERT INTO users (name, username, password, user_level, status) VALUES ('$name', '$username', '$hashed_password', '$user_level', '1')";

                if ($db->query($query)) {
                    // Success
                    $session->msg('s', 'User account has been created!');
                    header('Location: add_user.php');
                    exit();
                } else {
                    // Failed
                    $session->msg('d', 'Sorry, failed to create account!');
                    header('Location: add_user.php');
                    exit();
                }
            } else {
                // Passwords do not match
                $session->msg('d', 'Passwords do not match. Please try again!');
                header('Location: add_user.php');
                exit();
            }
        } else {
            // Password does not meet requirements
            $session->msg('d', 'Password is not strong enough. Please follow the requirements.');
            header('Location: add_user.php');
            exit();
        }
    } else {
        $session->msg("d", $errors);
        header('Location: add_user.php');
        exit();
    }
}
?>

<?php include_once('layouts/header.php'); ?>
  <?php echo display_msg($msg); ?>
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New User</span>
       </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-6">
          <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="full-name" placeholder="Full Name">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name ="password"  placeholder="Password">
            </div>
            <div class="form-group">
                <label for="conpassword">Confirm Password</label>
                <input type="password" class="form-control" name ="conpassword"  placeholder="Confirm Password">
            </div>
            <div class="form-group">
              <label for="level">User Role</label>
                <select class="form-control" name="level">
                  <?php foreach ($groups as $group ):?>
                   <option value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option>
                <?php endforeach;?>
                </select>
            </div>
            <div class="form-group clearfix">
              <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
            </div>
        </form>
        </div>

      </div>

    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
