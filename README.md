Secret Santa - Airangel technical challenge - Alberto Carrillo

PROBLEM

Secret Santa is a random way for people to exchange gifts. The problem is you can draw yourself and in this case we also don't want to draw family.

SOLUTION

Create an app to help participants draw randomly meeting exclusion criteria. It can be accessed from a webpage or API.
The solution is full stack.

RATIONALE

I decided to use MySQL as the database engine to allocate the list. For the backend I decided to use PHP with CodeIgniter as a framework because in my opinion it is faster to configure the development and production environment.
For the front end I chose jQuery to make it dynamic, Bootstrap and W3 CSS library to create the design, and Fontawesome for the icons in the buttons. This allows a simple and easy navigation for the end user and its easy implementation.

TRADE-OFFS

If I had more time, there are three elements I would have added:

* Validation when registering a participant, no optional fields and email format verification.
* Confirmation alert when choosing to "Clear List" from the front end.
* Adding an edit and delete option from the front end

URLs

Github: https://github.com/AlbertoCarrillo79/secretsanta

Webpage: secretsanta.servehttp.com

API INSTRUCTIONS

Structure:

{
  "id": "1",
  "firstName": "Luke",
  "lastName": "Skywalker",
  "eMail": "luke@theforce.net"
}


Get all Secret Santa participants

http://secretsanta.servehttp.com/api/secretsanta/

Find participant by id

http://secretsanta.servehttp.com/api/secretsanta/find/1

Update participant data

http://secretsanta.servehttp.com/api/secretsanta/update/1

{
  "firstName": "Luke",
  "lastName": "Skywalker",
  "eMail": "luke@theforce.net"
}

Add Participant

http://secretsanta.servehttp.com/api/secretsanta/add

{
  "firstName": "Luke",
  "lastName": "Skywalker",
  "eMail": "luke@theforce.net"
}

Delete participant

http://secretsanta.servehttp.com/api/secretsanta/delete/1

I've added a csv file to load the list as well
