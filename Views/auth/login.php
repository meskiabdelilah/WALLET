<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../src/Config/db.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Src\Classes\Security;

$error = $_SESSION['login_error'] ?? '';
$success = $_SESSION['register_success'] ?? '';
unset($_SESSION['login_error'], $_SESSION['register_success']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - MyBudget</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="bg-[#0B0F19] text-slate-300 h-screen flex items-center justify-center overflow-hidden selection:bg-violet-500 selection:text-white relative">

    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-violet-600/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md px-6">

        <div class="flex flex-col items-center mb-8">
            <div class="h-16 w-16 bg-violet-600 rounded-2xl flex items-center justify-center text-white text-2xl shadow-[0_0_15px_rgba(139,92,246,0.5)] mb-4">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Bon retour</h1>
            <p class="text-slate-500 mt-2">Gérez vos finances intelligemment</p>
        </div>

        <div class="bg-[#1F2937] border border-slate-700 rounded-3xl p-8 shadow-2xl relative overflow-hidden">

            <?php if ($error): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif ?>

            <?php if ($success): ?>
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif ?>

            <form action="../../src/service/login_service.php" method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">

                <div>
                    <label class="text-xs text-slate-500 font-bold ml-1 uppercase block mb-1">Email Professionnel</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" name="email" 
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-600 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-none transition"
                            placeholder="exemple@email.com">
                    </div>
                </div>

                <div>
                    <label class="text-xs text-slate-500 font-bold ml-1 uppercase block mb-1">Mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password" 
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-600 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-none transition"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs mt-2">
                    <label class="flex items-center gap-2 text-slate-400 cursor-pointer hover:text-white transition">
                        <input type="checkbox" id="showPassword" class="rounded bg-slate-900 border-slate-700 text-violet-500 focus:ring-0">
                        Se souvenir de moi
                    </label>
                    <a href="#" class="text-violet-400 hover:text-violet-300 font-medium transition">Mot de passe oublié ?</a>
                </div>

                <button type="submit"
                    class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-violet-900/20 transition-all duration-300 transform hover:-translate-y-0.5 mt-2">
                    Se connecter <i class="fa-solid fa-arrow-right ml-2"></i>
                </button>
            </form>
        </div>

        <div class="text-center mt-8">
            <p class="text-slate-500 text-sm">
                Pas encore de compte ?
                <a href="register.php" class="text-violet-400 font-bold hover:text-violet-300 transition">Créer un Wallet</a>
            </p>
        </div>

    </div>

    <script>
        const showPassword = document.getElementById('showPassword');
        const passwordField = document.getElementById('password');

        showPassword.addEventListener('click', function () {
            
            const type = this.checked ? 'text' : 'password';

            passwordField.type = type;
        })
    </script>
</body>

</html>