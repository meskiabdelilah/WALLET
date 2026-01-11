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
    header('Location: ../../Views/auth/register.php');
    exit();
}

if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
    $_SESSION['register_error'] = 'Token de sécurité invalide';
    header('Location: ../../Views/auth/register.php');
    exit();
}

$nom = Security::clean($_POST['nom'] ?? '');
$email = Security::clean($_POST['email'] ?? '');
$password = Security::clean($_POST['password'] ?? '');
$confirmPassword = Security::clean($_POST['confirmPassword'] ?? '');

if (empty($nom) || empty($email) || empty($password) || empty($confirmPassword)) {
    $_SESSION['register_error'] =  'Veuillez remplir tous les champs';
    header('Location: ../../Views/auth/register.php');
    exit();
}

if (!Security::validateEmail($email)) {
    $_SESSION['register_error'] = 'Email invalide';
    header('Location: ../../Views/auth/register.php');
    exit();
}

if (!Security::validatePassword($password)) {
    $_SESSION['register_error'] = 'Le mot de passe doit contenir au moins 8 caractères';
    header('Location: ../../Views/auth/register.php');
    exit();
}

if ($password !== $confirmPassword) {
    $_SESSION['register_error'] = 'Les mots de passe ne correspondent pas';
    header('Location: ../../Views/auth/register.php');
    exit();
}

$user = new User();
$result = $user->create($nom, $email, $password);

if ($result) {
    $_SESSION['register_success'] = 'Compte créé avec succès ! Vous pouvez vous connecter.';
    header('Location: ../../Views/auth/login.php');
    exit();
} else {
    $_SESSION['register_error'] = 'Erreur lors de la création du compte. Email déjà utilisé ?';
    header('Location: ../../Views/auth/register.php');
    exit();
}