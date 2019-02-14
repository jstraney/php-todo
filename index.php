<?
// This is an easy way to greatly simplify including php
// and .inc files. An more professional way is to use 'autoloading'
// but I'm doing it this way for simplicity
DEFINE('APPLICATION_ROOT', getcwd());
// ^ note that anything defined in this file will be available
// to an included files file-level scope;
?>
<!DOCTYPE html/>
<html>
  <head>
    <!-- 
     example include. includes are used to not only include PHP code,
     but templated HTML. The benefit is re-using parts of your HTML page
     for various templates and layouts
     -->
    <?php include_once 'includes/head.inc'?>
  </head>
  <body>

    <!--
    class and ID attributes are just a way to specially mark HTML elements
    for styling with CSS and manipulation with JS
    -->
    <div class="container">

      <h1>TODOS</h1>

      <div class="todos">
        <!-- Populated with AJAX. see app.js -->
      </div>

      <!-- 
      A forms action is just the URL it sends the field info to.
      The method just tells the browser and the server what special actions 
      to take when making the request (for example, a browser will not store
      a POST request in browsing history, but will store GET. Servers may
      cache a GET request and not a POST request, but that depends on the server)
      -->
      <form class="create-todo" action="controllers/todos.php" method="POST">

        <!--
        each 'name' and 'value' attribute creates a
        key => value pair in the body of the request.
        -->
        <input type="text" name="description" placeholder="Add description"/>
        <input type="hidden" name="controller_action" value="create"/> 

        <!--
        That's true even for the submit button.
        Try adding 'name="submit-action". Now a key-value pair exists in the
        body request of submit-action => "Add ToDo". inputs with type=submit
        are weird because the displayed text uses the value attribute and not
        inner HTML
        -->
        <input  type="submit" value="Add ToDo"/>

      </form>

    </div>

  </body>
</html>
