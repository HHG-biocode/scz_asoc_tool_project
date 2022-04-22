<?php
// Searching rsid value in a batch search

if ($_SERVER['REQUEST_METHOD']=="GET") {
    $rsid = ($_GET['rsid']);
    $cell_type = ($_GET['cell_type']);
}