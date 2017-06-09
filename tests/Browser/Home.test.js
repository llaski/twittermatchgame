var test = require('tape'); // assign the tape library to the variable "test"

test('should return -1 when the value is not present in Array', function (t) {
  t.equal(-1, [1,2,3].indexOf(4)); // 4 is not present in this array so passes
  t.end();
});

/*
- es6
- run on command line
- testing functionality that a browser would require
- ajax requests

Home page

- theres a get started button
- on click
    - the url becomes /game

Game Page
- there is a loader displayed
- on load, an ajax request is made to retrieve the data
- when the data is finished loading
    - the loader is hidden
    - the data is displayed on the page
    - the data is mixed up
 */