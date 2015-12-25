<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
secureSessionStart();
//------------------------------------------------------------------------------
// The user can start a new pinkie here.
//------------------------------------------------------------------------------
$s_PinkieTitle = 'Untitled Pinkie';
if(isset($_POST['title']))
{
  $s_PinkieTitle  = $_POST['title'];
}
?>
<!DOCTYPE html>
<HTML>
  <HEAD>
    <?php printHeaderInfo(); ?>
    <title>New Pinkie</title>
    <script type="text/javascript" src="js/pinkie.js"></script>
  </HEAD>
  <body>
    <div class="jumbotron text-center">
      <H1>ePinkies 2</H1>
      <h2>University of California at Riverside</h2>
      <h3>Department of Electrical and Computer Engineering</h3>
    </div>

    <!-- Header Container -->
    <div class="container">
      <div class="well">
        <H2>
          Welcome <?php echo(getName());?> to ePinkies2.
        </H2>
        <H4>Here you will be able to to start a new pinkie object.</H4>
      </div>
    </div>

    <form class="form-horizontal" role="form" action="#" method="POST" name="newPinkieForm">
      <!-- Title, who you are submitting to, who is submitting it. -->
      <div class="container">
        <div class="well">

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="title">Title:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="title" name="title" placeholder="Title of the pinkie" value="<?php echo $_POST['title']; ?>">
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="submittedBy">Submitted By:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="submittedBy" name="submittedBy" placeholder="Submitter" value="<?php echo getName(); ?>" readonly>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="submitTo">Submitted To:</label>
            <div class="col-sm-10">
              <select class="form-control" id="submitTo" name="submitTo">
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
              </select>
            </div>
          </div>

        </div>
      </div>
    </form>

  </body>
</HTML>
