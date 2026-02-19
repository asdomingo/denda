<?php
require_once '../../klaseak/com/leartik/daw24asdo/eskariak/eskaria.php';
require_once '../../klaseak/com/leartik/daw24asdo/eskariak/eskaria_db.php';
require_once '../../klaseak/com/leartik/daw24asdo/logs/log_db.php';

use com\leartik\daw24asdo\eskariak\EskariaDB;
use com\leartik\daw24asdo\logs\LogDB;

if (isset($_GET['id'])) {
    if (EskariaDB::deleteEskaria($_GET['id'])) {
        LogDB::insertLog("Eskaria ezabatu: " . $_GET['id']);
    } else {
        error_log("Failed to delete eskaria ID: " . $_GET['id']);
    }
}

header("Location: index.php");
exit;
?>
