<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../src/Config/db.php';
require_once __DIR__  . '/../../vendor/autoload.php';

use Src\Classes\Database;
use Src\Classes\Security;
use Src\Classes\User;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /wallet/Views/auth/login.php');
    exit();
}

if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
    $_SESSION['login_error'] = 'Token de sécurité invalide';
    header('Location: /wallet/Views/auth/login.php');
    exit();
}

$email = Security::clean($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'Veuillez remplir tous les champs';
    header('Location: /wallet/Views/auth/login.php');
    exit();
}

if (!Security::validateEmail($email)) {
    $_SESSION['login_error'] = 'Email invalide';
    header('Location: /wallet/Views/auth/login.php');
    exit();
}

$user = new User();
$result = $user->login($email, $password);


if ($result) {
    header('Location: /wallet/Views/dashboard.php');
    exit();
} else {
    $_SESSION['login_error'] = 'Email ou mot de passe incorrect';
    header('Location: /wallet/Views/auth/login.php');
    exit();
}
