<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Src\Classes\Database;
use Src\Classes\Security;
use Src\Classes\User;

if (isset($_GET['token']) && Security::verifyCSRFToken($_GET['token'])) {
    $user = new User();
    $user->logout();
}

header('Location: login.php');
exit();