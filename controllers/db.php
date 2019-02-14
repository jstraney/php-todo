<?php

//  returns a PDO object for connecting to database
function connect_db () {

  // use these settings for PDO
  $options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // fetches rows as associated arrays (key, value pairs)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // actually perform prepared statements
    PDO::ATTR_EMULATE_PREPARES => FALSE 
  );

  // note, your MySQL would have to be running on localhost
  // with root user and blank password for this to work.
  // don't run MySQL with these settings in a production setting.
  $db = new PDO('mysql:host=localhost;dbname=php_todo;charset=utf8mb4', 'root', '', $options);

  return $db;

}

// uses a PDO (PHP Data Object), a query string, and array of values
function query ($db, $querystr, $values=array()) {

  // if values are supplied, try to make a prepared statement.
  // Note: this is an important step to prevent SQL injection.
  // never concatenate values directly into your SQL query
  if ($values) {

    // prepare the query
    $stmt = $db->prepare($querystr);

    // execute using values
    $stmt->execute($values);

    // return the executed prepared statement
    // so fetchAll, or fetch can be called
    return $stmt;

  }

  // no values passed, just perform a plain query
  // without placeholders
  return $db->query($querystr);

}

?>
