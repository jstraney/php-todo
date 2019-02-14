// anything with a $ is jQuery related.
// jQuery gets a bad rep, but is still okay 
// to learn more about Javascript and works for small projects.
// In javascript, variables can start with a "$", and so the ominous
// "$" used by jQuery may seem special, but it's just an object with methods.

// Javascript does not have any 'public' or 'private' modifiers.
// for that reason, the prefered method for data-hiding in JS is to wrap
// your main code in a function that gets executed immediately. all the
// variables inside are now inaccessible to outsiders.
(function () {

  function updateToDo (data) {

    // jQuery's ajax method sends an asynchronous request
    // without re-loading the page. The underlying technology is
    // XHR (xml http request). Despite its name, this can be used to
    // request any type of document (JSON, HTML, plain text).
    
    // The function returns an object that "knows" when the request
    // is done, or if there was an error. returning that object
    // here allows us to use it in different contexts.
    
    // An object that behaves this way is known as a "promise"
    // More info https://bit.ly/2ppt4pJ
    return $.ajax('action.php', {
      method: 'POST',
      data  : {
        controller_action: 'update',
        todo_id : data.id,
        done    : data.done ? 1 : 0
      },
    });

  }

  function destroyToDo (data) {

    // same deal as the function above except
    // it sends a request with different values
    return $.ajax('action.php', {
      method: 'POST',
      data  : {
        controller_action: 'destroy',
        todo_id : data.id
      }
    });

  }

  function createToDo (data) {

    // create a TODO, return an object 
    // that keeps track of the state of the request
    return $.ajax('action.php', {
      method: 'POST',
      data: {
        controller_action: 'create',
        description: data.description,
      }
    })

  }

  // takes a Javascript Object and builds the todo
  // document elements
  function createToDoElem (data) {

    // create a div with these HTML attributes
    var $todo = $('<div>').attr({
      class    : 'todo',
      'data-id': data.id
    });

    // make a span with description
    var $label = $('<span>').text(data.description);

    // create a checkbox
    var $checkbox = $('<input>').attr({
      type: 'checkbox',
    });

    // set the "checked" property to the "done" status.
    // Javascript distinguishes between "properties" and "attributes"
    // on a DOM element (even though they appear identical). I, myself
    // do not understand the functional difference...
    $checkbox.prop('checked', data.done);

    // when you see this pattern, we are declaring a block of code
    // (the inner function) which gets called every time a click
    // occurs on the element
    $checkbox.click(function () {

      // this refers to the checkbox (a plain DOM element), $this 
      // refers to that plain DOM element wrapped in jQuery (gives it
      // special methods that can be used)
      var $this = $(this);

      // prop is not a function of this, but works with $(this)
      var done = $this.prop('checked');

      // send this data as a request. an object with a 'then' method
      // is returned. 
      updateToDo({id: data.id, done: done})
      // this block of code is run after the request is successful
      .then(function (result) {

        // you could add a strike through the text
        
        // increment a counter of 'done' activities
       
        // I'm just going to use the native pop-up
        window.alert(
          data.description + ' new status: ' + (done? 'done': 'unfinished')
        );

      })
      // the catch method will call this block of code if there is
      // an error making the request
      .catch(function (err) {

        // You could pop up a modal dialogue. put a message somewhere
        // in the page.
        
        // I'm being lazy, so here's a simple pop-up
        window.alert(
          "We couldn't update the ToDo status right now. sorry!"
        );
        
      });

    });

    var $deleteBtn = $('<button>').text('Remove');

    $deleteBtn.click(function () {

      destroyToDo({id: data.id}) 
      .then(function (result) {

        // ^ once the above request is made, 
        // this block of code runs and removes the ToDo.
        if (result.success)
          $todo.remove();

      });

    });

    // append all the child elements to the ToDo
    $todo.append($label, $checkbox, $deleteBtn);

    // return the ToDo container with the label, checkbox,
    // and button appended
    return $todo;

  }

  function createToDoList (todos) {

    // The map method accepts a function with these args 
    // (oldArrayElement) => newArrayElement
    // and builds a new array. In this case, the old array
    // is just Javascript Objects, the new array is jQuery DOM elements
    return todos.map(createToDoElem);

  }

  function reloadToDos () {

    var $todoContainer = $('.todos');

    var todos = $.ajax('action.php', {
      // technically makes more sense as a GET request, but
      // no harm is done doing it as POST
      method     : 'POST',
      data       : {
        controller_action: 'list'
      }
    })
    .then(function (data) {

      // use the result data to create a todolist
      
      // append to the todo container
      $todoContainer.append(createToDoList(data));
      
    });

  }

  // shorthand for window.onload. This block of code
  // runs when the window is finished loading. A common mistake is to
  // try to run javascript before all the DOM elements are in place.
  // This is handled with an onload event-handler, or by putting your
  // script tags at the bottom of the page.
  $(function () {

    reloadToDos();

    var $createToDoForm = $('form.create-todo');

    // forms have a special event when they are "submitted", the default
    // behavior is to reload the page.
    $createToDoForm.submit(function (e) {

      // here, tell the event object to prevent default 
      // browser behavior of page re-load
      e.preventDefault();

      // this = form element
      var $this = $(this);

      // get an array of field-value objects from 
      var fields = $this.serializeArray();

      // creating my own key-value data object of form data
      var data = {};

      fields.forEach(function (field) {

        // data is keyed by name of input
        // and input value
        data[field.name] = field.value;

      });

      // send data to create the ToDo
      createToDo(data)
      .then(function (data) {

        var $todos = $('.todos');

        // add ToDo
        $todos.append(createToDoElem(data));
        
      });

    })

  });

})();
