<?php
require_once '../../klaseak/com/leartik/daw24asdo/mezuak/mezua.php';
require_once '../../klaseak/com/leartik/daw24asdo/mezuak/mezua_db.php';
require_once '../../klaseak/com/leartik/daw24asdo/logs/log_db.php';

use com\leartik\daw24asdo\mezuak\MezuaDB;
use com\leartik\daw24asdo\logs\LogDB;

if (isset($_GET['id'])) {
    if (MezuaDB::deleteMezua($_GET['id'])) {
        LogDB::insertLog("Mezua ezabatu: " . $_GET['id']);
    }
}

header("Location: ../../index.php");
exit;
?>
