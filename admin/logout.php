<?php
require_once __DIR__.'/../includes/config.php';
$_SESSION = [];
session_destroy();
redirect(APP_URL.'/admin/login.php');
