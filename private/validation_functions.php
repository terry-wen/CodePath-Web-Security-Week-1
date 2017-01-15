<?php

  // is_blank('abcd')
  function is_blank($value='') {
    return empty($value);
  }

  // has_length('abcd', ['min' => 3, 'max' => 5])
  function has_length($value, $options=array()) {
    return (strlen($value) >= $options["min"] && strlen($value) <= $options["max"]);
  }

  // has_valid_email_format('test@test.com')
  function has_valid_email_format($value) {
    if (strpos($value, '@') !== FALSE)
      return TRUE;

    return FALSE;
  }

  function checkChars($value, $type) {
    if($type == 'name')
      return preg_match('/\A[A-Za-z\s\-,\.\']+\Z/', $value);
    else if($type == 'username')
      return preg_match('/\A[A-Za-z0-9\_]+\Z/', $value);
    else //email
      return preg_match('/\A[A-Za-z0-9\_\@\.]+\Z/', $value);
  }

  function uniqueUsername($value, $db) {
    $sql = "select 1 from globitek.users where username='$value' LIMIT 1";

    $result = db_query($db, $sql);
    if($result->num_rows == 0)
      return TRUE;
    else
      return FALSE;
  }

?>
