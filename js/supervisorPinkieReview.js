// -----------------------------------------------------------------------------
// The functions specifically for a supervisor to use are here.
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
    attachStatus(form, "Supervisor Approved");
    form.submit();
}

function onReject(pinkieID)
{
  var form = document.getElementsByTagName("form")[0];
  attachPinkieID(form, pinkieID);
  attachStatus(form, "Supervisor Deny");
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
