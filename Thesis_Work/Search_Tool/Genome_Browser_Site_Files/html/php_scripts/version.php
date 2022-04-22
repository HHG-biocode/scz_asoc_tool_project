<?php
ini_set('display_errors', 1);

$db = new SQLite3('../db/scz_asoc.db');

$ver = $db->querySingle('Select SQLITE_VERSION()');
echo $ver . "\n";