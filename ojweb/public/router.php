<?php
// Author: CSGrandeur
// $Id$

if (is_file($_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    require __DIR__ . "/index.php";
}
