<?php

include_once("db.php");

function create_todo () {

  // creates a new DB object
  $db = connect_db();

  // get description of new ToDo from $_POST super global
  $description = $_POST['description'];

  // perform query using placeholders
  query(
    $db,
    'INSERT INTO todos (description, done) VALUES (:description, false);',
    array(
      "description" => $description
    )
  );

  // now query the created ToDo in the database
  $result = query(
    $db,
    'SELECT id, description, done FROM todos WHERE id = LAST_INSERT_ID();'
  );

  // return first result
  return $result->fetch();

}

function list_todo () {

  $db = connect_db();

  $results = query (
    $db,
    'SELECT id, description, done FROM todos'
  );

  // returns array of all results
  return $results->fetchAll();

}

function destroy_todo () {

  $db = connect_db();

  $todo_id = $_POST['todo_id'];

  $result = query(
    $db,
    'DELETE FROM todos WHERE id = :id;',
    array(
      "id" => $todo_id
    )
  );

  // if there is a count of rows effected,
  // return true as status
  if ($result->rowCount())
    return array('success' => TRUE);

  // no rows effected, delete failed
  return array('success' => FALSE);

}

function update_todo () {

  $db = connect_db();

  $id   = $_POST['todo_id'];
  $done = $_POST['done'];

  query(
    $db,
    'UPDATE todos SET done = :done WHERE id = :id;',
    array(
      "done" => $done,
      "id" => $id
    )
  );

  $todo = query(
    $db,
    'SELECT id, description, done FROM todos WHERE id = :id',
    array(
      "id" => $id
    )
  );

  return array(
    'success' => TRUE,
    'todo' => $todo->fetch()
  );

}

function handle_request () {

  // here is a list of functions we will explicitly allow
  // to be used.
  $controller_functions = array(
    'create_todo'  => TRUE,
    'list_todo'    => TRUE,
    'update_todo'  => TRUE,
    'destroy_todo' => TRUE,
  );

  // get the action we want to perform
  $controller_action = isset($_POST['controller_action']) ? $_POST['controller_action'] : NULL;

  // die if no action
  if (!$controller_action)
    die();

  // see if you can understand what the following lines do.
  // PHP and JS can do crazy stuff like this.
  $function_name = $controller_action . '_todo';

  if ($controller_functions[$function_name]) {

    if (function_exists($function_name))
      $result = call_user_func($function_name);

    // set the content type header so the browser
    // knows what to do with our response
    header('Content-Type: application/json'); 

    // echo out the php array after converting it to
    // a JSON string.
    echo json_encode($result);

  }

}

?>
