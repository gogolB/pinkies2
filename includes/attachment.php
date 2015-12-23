<?php
include_once 'functions.php';
secureSessionStart();
  // ---------------------------------------------------------------------------
  // This object is designed to deal with attachements to this pinkie.
  // ---------------------------------------------------------------------------
class Attachement
{
    public $i_PinkieID;

    public $i_FileID;
    public $s_FilePath;

    // Default constructor.
    public function __construct($i_PID)
    {
        $this->i_PinkieID = $i_PID;
    }
}
?>
