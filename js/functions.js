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
  if(form["title"].value == "" ||form["title"].value == null )
  {
    alert("You must have a Title for this Pinkie!");
    return false;
  }
  form.submit();
}
