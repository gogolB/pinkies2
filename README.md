# ePinkies2
By: Souradeep "Gogol" Bhattacharya
This was a rewrite of the purchase order system for the Department of Electrical Engineering at the University of California at Riverside.

It currently is able to support multiple user accounts of various levels, and the login script is fully customizeable, so long as the following are set and correctly:

$_SESSION['Username'] -- This must be the username of the current user.

$_SESSION['Access'] -- This is the current access level of this user. Currently supported are ADMIN, SUPER, TRANS, USER. With ADMIN being the department FAO, SUPER being the Professors or Student Supervisors responsible for approving the pinkie, TRANS is the Transactor, and USER is the studens and graduate students.

$_SESSION['Name'] -- This is the current User's name. It is used for greetings and to humanize the system.

$_SESSION['SubmitTo'] -- This is the array of people that this user can submit to. It must be populated as follows: "[Nice name to display]|[Username]" eg: array("Pinkies Devlopment Transactor|PinkiesTrans", ...);
