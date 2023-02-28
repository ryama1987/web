<?php
require_once 'config.php';
require_once 'class/pages/admin/pickup/LC_Pages_Admin_pickup.php';

$objPage = new LC_Pages_Admin_Pickup();
$objPage->init();
$objPage->process();
?>
