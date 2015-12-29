<?php
include_once 'functions.php';
secureSessionStart();
  // ---------------------------------------------------------------------------
  // This object is designed to deal with attachements to this pinkie.
  // ---------------------------------------------------------------------------
class Attachement
{
    public $i_PinkieID = -1 ;

    // AKA AttachmentID
    public $i_FileID = -1;

    public $s_FilePath = '';

    // Default constructor.
    public function __construct($i_PID)
    {
        $this->i_PinkieID = $i_PID;
    }


    // Stores this attachement in the database.
    public function toDatabase()
    {
        // Checks for necessary things to add to the database.
        if($this->i_PinkieID < 0)
        {
            onError("Attachement::toDatabase()", "Failed to add attachment to database because PinkieID was not set.");
        }

        if(strlen($this->s_FilePath) == 0)
        {
            onError("Attachement::toDatabase()", "Failed to add attachment to database because there was no FilePath specified.");
        }

        // Everything is okay, do the add.
        if($this->i_FileID > 0)
        {
            $this->update();
        }
        else
        {
            $this->addNew();
        }

    }

    // Gets this attachement from the database.
    public function fromDatabase()
    {
        // Check if a file ID has been set.
        if($this->i_FileID < 0)
        {
            onError("Attachement::fromDatabase()", "Failed to load attachment from database because no AttachmentID was set.");
        }

        // Everything is all good, load it from the database.
        // Connect to the database.
        $_db = getMysqli();
        // SQL query to run.
        $statement = $_db->prepare("SELECT * FROM Attachments WHERE AttachmentID=?");
        $statement->bind_param('i', $this->i_FileID);
        $statement->execute();

        // Error running the statment.
        if($statement->errno != 0)
        {
          $_tmp = $statement->error;
          $statement->close();
          $_db->close();
          onError("Attachement::fromDatabase()",'There was an error running the query [' . $_tmp . '] Could not fetch Attachement.');
        }


        $statement->store_result();
        if($statement->num_rows <= 0)
        {
            $statement->free_result();
            $statement->close();
            $_db->close();
            onError("Attachement::fromDatabase()","Failed to find a Attachement with the given FileID of: ".$this->i_FileID);
        }
        // We have a result, lets bind the result to the variables.
        $statement->bind_result($throwaway, $this->i_PinkieID, $this->s_FilePath);
        $statement->fetch();

        // Cleanup.
        $statement->free_result();
        $statement->close();
        $_db->close();
    }

    function update()
    {
      // Everything all good, lets update the table.
      $_db = getMysqli();
      $_sql = "UPDATE Attachments SET PinkieID=?, FilePath=? WHERE AttachmentID=?";
      $_stmt = $_db->prepare((string)$_sql);

      $_stmt->bind_param('isi', $this->i_PinkieID, $this->s_FilePath, $this->i_FileID);
      $_stmt->execute();

      if ($_stmt->errno)
      {
        onError("Attachment::update()", $_stmt->error);
      }

      $_stmt->close();
      // Close up the database connection.
      $_db->close();
    }

    function addNew()
    {
      // Everything all good, lets insert in to the table.
      $_db = getMysqli();
      $_sql = "INSERT INTO Attachments (PinkieID, FilePath) VALUES (?,?)";
      $_stmt = $_db->prepare((string)$_sql);

      $_stmt->bind_param('is', $this->i_PinkieID, $this->s_FilePath);
      $_stmt->execute();

      if ($_stmt->errno)
      {
        onError("Attachment::addNew()", $_stmt->error);
      }

      $_stmt->close();
      // Close up the database connection.
      $_db->close();
    }
}
?>
