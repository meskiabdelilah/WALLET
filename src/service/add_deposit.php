<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Config/db.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Src\Classes\Depose;

if (!isset($_SESSION['user_id'])) {
    header('Location: /wallet/Views/auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /wallet/Views/dashboard.php');
    exit();
}

$userId = $_SESSION['user_id'];
$title = trim($_POST['title'] ?? '');
$amount = floatval($_POST['amount'] ?? 0);
$source = $_POST['source'] ?? 'autre';

if (empty($title) || $amount <= 0) {
    $_SESSION['error'] = 'Veuillez remplir tous les champs correctement';
    header('Location: /wallet/Views/dashboard.php');
    exit();
}

$depose = new Depose();
$result = $depose->addDeposit($userId, $title, $amount, $source);

if ($result) {
    $_SESSION['success'] = 'Dépôt ajouté avec succès!';
} else {
    $_SESSION['error'] = "Erreur lors de l'ajout du dépôt";
}

header('Location: /wallet/Views/dashboard.php');
exit();