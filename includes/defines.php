<?php
// -----------------------------------------------------------------------------
// Here are all the defines for the Pinkies 2 project.
// -----------------------------------------------------------------------------

// Database Defines.
define('HOST', 'localhost');
define('DB', 'epinkie2');
define('DB_USER', 'epinkie2');
define('DB_PASS', 'epinkie2');

// Defines for Pinkie Status.
define('InProgress', 'In Progress');
define('PendingSuperApproval', 'Waiting for Supervisor Approval');
define('ApprovedBySuper', 'Supervisor Approved');
define('PendingAdminApproval', 'Waiting for Admin Approval');
define('ApprovedByAdmin', 'Admin Approved');
define('RejectedBySuper', 'Supervisor Deny');
define('RejectedByAdmin', 'Admin Deny');
define('DispatchedToTrans', 'Dispatched');
define('Done', 'Done');
define('Archived', 'Archived'); // This means it will not show up in regular searches.
define('Cancelled','Cancelled');

// Pinkie max limits.
define('MAX_OBJECTS', 10);
define('MAX_FUNDS', 5);
define('MAX_ATTACHMENTS', 5);

// Pinkie File Upload location.
define('PATH_PREFIX', '/pinkies/');


// Pinkies Debug stuff
define('DEBUG', FALSE);
define('SQL_NO_RESULTS_BREAK',FALSE);

?>
