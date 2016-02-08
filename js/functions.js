// -----------------------------------------------------------------------------
// Here you can find all the basic helper functions needed for the client.
// -----------------------------------------------------------------------------

// redirects the client to a specific url.
function redirect( url )
{
  window.location = url;
}

function onStartNewPinkie(form)
{
  // if(form["title"].value == "" ||form["title"].value == null )
  // {
  //   alert("You must have a Title for this Pinkie!");
  //   return false;
  // }
  form.submit();
}

//Only Numbers
function OnlyNumbers(e)
{
	var unicode=e.charCode? e.charCode : e.keyCode
	if (unicode!=8)
	{ //if the key isn't the backspace key (which we should allow)
		if (unicode<48||unicode>57) //if not a number
			return false //disable key press
	}
}

//Only Letters
function OnlyLetters(e)
{
	var key = window.event ? e.keyCode : e.which;
	var keychar = String.fromCharCode(key);
	reg = /\d/;
	return !reg.test(keychar);
}
