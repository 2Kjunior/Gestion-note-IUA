<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/functions.php'; // 👈 OBLIGATOIRE

session_destroy();
redirect('/auth/login.php');
