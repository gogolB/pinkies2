// -----------------------------------------------------------------------------
// The functions specifically for an admin to use are here.
// -----------------------------------------------------------------------------

function attachPinkieID(form, pinkieID)
{
  var input = document.createElement("input");
  input.setAttribute("type", "hidden");
  input.setAttribute("name", "pinkieID");
  input.setAttribute("value", pinkieID);
  form.appendChild(input);
}

function onApprove(pinkieID)
{
    var form = document.getElementsByTagName("form")[0];
    attachPinkieID(form, pinkieID);
    attachStatus("Admin Approved");
    form.submit();
}

function onReject(pinkieID)
{
  var form = document.getElementsByTagName("form")[0];
  attachPinkieID(form, pinkieID);
  attachStatus("Admin Deny");
  form.submit();
}

function attachStatus(form, status)
{
  var input = document.createElement("input");
  input.setAttribute("type", "hidden");
  input.setAttribute("name", "status");
  input.setAttribute("value", status);
  form.appendChild(input);
}
