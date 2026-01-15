<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/functions.php';

if (!isLoggedIn()) {
    redirect('/auth/login.php');
}
