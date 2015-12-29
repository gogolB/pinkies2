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
define('PendingSuperApproval', 'Waiting for Super');
define('ApprovedBySuper', 'Super Approved');
define('PendingAdminApproval', 'Waiting for Admin');
define('ApprovedByAdmin', 'Admin Approved');
define('RejectedBySuper', 'Super Deny');
define('RejectedByAdmin', 'Admin Deny');
define('DispatchedToTrans', 'Dispatched');
define('Done', 'Done');

// Pinkie max limits.
define('MAX_OBJECTS', 10);
define('MAX_FUNDS', 5);
define('MAX_ATTACHMENTS', 5);

// Pinkie File Upload location.
define('PATH_PREFIX', '/pinkies/');

?>
