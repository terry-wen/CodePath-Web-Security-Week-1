<?php
  require_once('../private/initialize.php');

  // Set default values for all variables the page needs.
  $db = db_connect();
  $first_name = $last_name = $email = $username = '';
  $errors = array();
  $sql = '';

  // if this is a POST request, process the form
  // Hint: private/functions.php can help
  if(is_post_request()) {
    // Confirm that POST values are present before accessing them.
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Perform Validations
    // Hint: Write these in private/validation_functions.php
    if(is_blank($first_name))
      array_push($errors, 'First name cannot be blank.');
    else if(!has_length($first_name, ['min' => 2, 'max' => 255]))
      array_push($errors, 'First name must be between 2 and 255 characters.');
    else if(!checkChars($first_name, 'name'))
      array_push($errors, 'Invalid character in first name.');

    if(is_blank($last_name))
      array_push($errors, 'Last name cannot be blank.');
    else if(!has_length($last_name, ['min' => 2, 'max' => 255]))
      array_push($errors, 'Last name must be between 2 and 255 characters.');
    else if(!checkChars($last_name, 'name'))
      array_push($errors, 'Invalid character in last name.');

    if(is_blank($email))
      array_push($errors, 'Email cannot be blank.');
    else if(!has_valid_email_format($email))
      array_push($errors, 'Email must be a valid format.');
    else if(strlen($email) > 255)
      array_push($errors, 'Email cannot exceed 255 characters.');
    else if(!checkChars($email, 'email'))
      array_push($errors, 'Invalid character in email.');

    if(is_blank($username))
      array_push($errors, 'Username cannot be blank.');
    else if(strlen($username) < 8)
      array_push($errors, 'Username must be at least 8 characters.');
    else if(strlen($username) > 255)
      array_push($errors, 'Username cannot exceed 255 characters.');
    else if(!checkChars($username, 'username'))
      array_push($errors, 'Invalid character in username.');
    else if(!uniqueUsername($username, $db))
      array_push($errors, 'Username is taken.');

    // if there were no errors, submit data to database
    if(empty($errors)) {
      // Write SQL INSERT statement
      $sql = 'insert into globitek.users (first_name, last_name, email, username) values (\'' . $first_name . '\', \'' . $last_name . '\', \'' . $email . '\', \'' . $username . '\');';

      // For INSERT statments, $result is just true/false
      $result = db_query($db, $sql);
      if($result) {
        db_close($db);

        header("Location: registration_success.php");
      } else {
        // The SQL INSERT statement failed.
        // Just show the error, not the form
        //echo db_error($db);
        db_close($db);
        exit;
      }
    }
  }
?>

<?php $page_title = 'Register'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <h1>Register</h1>
  <p>Register to become a Globitek Partner.</p>

  <?php
    echo display_errors($errors);
  ?>

  <form method="post" action="">
    <p>First name:</p>
    <input type="input" name="first_name" value="<?php echo h($first_name); ?>">
    <p>Last name:</p>
    <input type="input" name="last_name" value="<?php echo h($last_name); ?>">
    <p>Email:</p>
    <input type="input" name="email" value="<?php echo h($email); ?>">
    <p>Username:</p>
    <input type="input" name="username" value="<?php echo h($username); ?>">
    <input name="submit" type="submit" value="Submit">
  </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
