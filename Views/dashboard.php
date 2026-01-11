<?php
require_once '../vendor/autoload.php';

use Src\Classes\User;
use Src\Classes\Transaction;
use Src\Classes\Database;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_nom'] ?? 'Utilisateur';

$transaction = new Transaction();

$totalExpenses = $transaction->getTotalExpenses($userId) ?? 0;
$totalDeposits = $transaction->getTotalDeposits($userId) ?? 0;
$balance = $transaction->getBalance($userId) ?? 0;
$monthlyCount = $transaction->getMonthlyTransactionCount($userId) ?? 0;
$recentTransactions = $transaction->getRecentTransactions($userId, 4);

$budget = 12450; 

$spentPercentage = 0;
if ($budget > 0) {
    $spentPercentage = ($totalExpenses / $budget) * 100;
}

$error = $_SESSION['login_error'] ?? $_SESSION['error'] ?? '';
$success = $_SESSION['register_success'] ?? $_SESSION['success'] ?? '';
unset($_SESSION['login_error'], $_SESSION['register_success'], $_SESSION['error'], $_SESSION['success']);

$moisFrancais = [
    'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars',
    'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin',
    'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre',
    'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
];
$currentMonthEn = date('F');
$currentMonthFr = $moisFrancais[$currentMonthEn] ?? $currentMonthEn;

    $categoryIcons = [
    'nourriture' => 'fa-utensils',
    'transport' => 'fa-car',
    'loyer'      => 'fa-house',
    'loisirs'    => 'fa-gamepad',
    'salaire'    => 'fa-briefcase',
    'bonus'      => 'fa-gift',
    'freelance'  => 'fa-laptop',
    'autre'      => 'fa-money-bill-wave'
];

require_once 'Layout/Header.php';
?>

<div class="flex h-screen bg-[#0B0F19]">
    <?php require_once 'Layout/sidebar.php'; ?>

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-20 px-8 flex items-center justify-between flex-shrink-0 border-b border-slate-800/50">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Mon Wallet</h1>
                <p class="text-sm text-slate-500 font-medium"><?php echo $currentMonthFr . ' ' . date('Y'); ?></p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-right hidden sm:block">
                        <p class="text-sm text-white font-bold"><?= htmlspecialchars($userName) ?></p>
                        <p class="text-xs text-slate-500">Membre Premium</p>
                    </span>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($userName) ?>&background=2e1065&color=a78bfa" class="w-10 h-10 rounded-full border-2 border-[#1F2937]" alt="Profile">
                </div>
            </div>
        </header>

        <div class="flex-1 flex flex-col lg:flex-row overflow-hidden">

            <div class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 space-y-6 fade-enter">
                <?php if (!empty($error) || !empty($success)) {
                    require_once 'Layout/alerts.php';
                } ?>

                <section class="max-w-6xl mx-auto space-y-6">
                    <div class="bg-[#1F2937] border border-slate-700 rounded-2xl p-4 md:p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-6 opacity-10">
                            <i class="fa-solid fa-wallet text-9xl text-white"></i>
                        </div>
                        <div class="relative z-10">
                            <p class="text-slate-400 text-sm uppercase tracking-wider mb-1">Solde Restant (<?php echo ucfirst($currentMonthFr); ?>)</p>
                            <h2 class="text-2xl md:text-4xl font-bold text-white mb-4 md:mb-6">
                                <?php echo number_format($balance, 2); ?> <span class="text-lg md:text-xl text-slate-500">MAD</span>
                            </h2>
                            <div class="w-full bg-slate-800 rounded-full h-2 mb-2">
                                <div class="<?php echo $spentPercentage > 100 ? 'bg-red-500' : 'bg-violet-500'; ?> h-2 rounded-full" style="width: <?php echo min($spentPercentage, 100); ?>%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-slate-400">
                                <span>Dépensé : <?php echo number_format($totalExpenses, 0); ?> MAD</span>
                                <span>Budget : <?php echo number_format($budget, 0); ?> MAD</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                        <div onclick="openBudgetPopup()" class="bg-[#111827] p-4 md:p-5 rounded-2xl border border-slate-800 flex items-center gap-3 md:gap-4 cursor-pointer hover:bg-slate-800 transition-colors group">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-slate-800 text-blue-400 flex items-center justify-center text-lg md:text-xl group-hover:bg-blue-500/10 transition">
                                <i class="fa-solid fa-sack-dollar"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-500 uppercase font-bold truncate">Budget Défini</p>
                                <p class="text-base md:text-lg font-bold text-white truncate"><?php echo number_format($budget, 0); ?> MAD</p>
                            </div>
                            <i class="fa-solid fa-pen text-slate-600 text-xs group-hover:text-blue-400"></i>
                        </div>

                        <div class="bg-[#111827] p-4 md:p-5 rounded-2xl border border-slate-800 flex items-center gap-3 md:gap-4">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-slate-800 text-rose-400 flex items-center justify-center text-lg md:text-xl">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-500 uppercase font-bold truncate">Total Dépenses</p>
                                <p class="text-base md:text-lg font-bold text-white truncate"><?php echo number_format($totalExpenses, 0); ?> MAD</p>
                            </div>
                        </div>

                        <div class="bg-[#111827] p-4 md:p-5 rounded-2xl border border-slate-800 flex items-center gap-3 md:gap-4">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-slate-800 text-green-400 flex items-center justify-center text-lg md:text-xl">
                                <i class="fa-solid fa-piggy-bank"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-500 uppercase font-bold truncate">Économies</p>
                                <p class="text-base md:text-lg font-bold text-white truncate"><?php echo number_format($balance, 0); ?> MAD</p>
                            </div>
                        </div>

                        <div class="bg-[#111827] p-4 md:p-5 rounded-2xl border border-slate-800 flex items-center gap-3 md:gap-4">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-slate-800 text-yellow-400 flex items-center justify-center text-lg md:text-xl">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-500 uppercase font-bold truncate">Ce Mois</p>
                                <p class="text-base md:text-lg font-bold text-white truncate"><?php echo $monthlyCount; ?> Ops</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-base md:text-lg font-bold text-white mb-4">Dernières Opérations</h3>
                        <div class="space-y-2 md:space-y-3">
                            <?php if (!empty($recentTransactions)): ?>
                                <?php foreach ($recentTransactions as $trans): 
                                    // Déterminer l'icone et la couleur selon le type et la catégorie
                                    $isExpense = ($trans['type'] === 'expense');
                                    $categoryKey = strtolower($trans['category'] ?? 'autre');
                                    $iconClass = $categoryIcons[$categoryKey] ?? $categoryIcons['autre'];
                                    
                                    // Couleur de l'icone
                                    $iconColorClass = $isExpense ? 'bg-orange-500/10 text-orange-400' : 'bg-emerald-500/10 text-emerald-400';
                                    $amountColorClass = $isExpense ? 'text-white' : 'text-emerald-400';
                                    $sign = $isExpense ? '-' : '+';
                                ?>
                                    <div class="bg-[#111827] p-3 md:p-4 rounded-xl border border-slate-800 flex justify-between items-center hover:bg-slate-800 transition-colors duration-200">
                                        <div class="flex items-center gap-3 md:gap-4 min-w-0 flex-1">
                                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg <?php echo $iconColorClass; ?> flex items-center justify-center flex-shrink-0">
                                                <i class="fa-solid <?php echo $iconClass; ?> text-sm md:text-base"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="font-bold text-sm text-slate-200 truncate"><?php echo htmlspecialchars($trans['title']); ?></p>
                                                <p class="text-xs text-slate-500 truncate">
                                                    <?php echo ucfirst($trans['category'] ?? 'Autre'); ?> • <?php echo date('d M', strtotime($trans['created_at'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <span class="font-bold text-sm md:text-base flex-shrink-0 ml-2 <?php echo $amountColorClass; ?>">
                                            <?php echo $sign . number_format($trans['amount'], 2); ?> MAD
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="bg-[#111827] border border-slate-800 rounded-xl p-8 text-center">
                                    <i class="fa-solid fa-ghost text-slate-600 text-4xl mb-3"></i>
                                    <p class="text-slate-400">Aucune transaction trouvée</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>

            <div class="w-full lg:w-80 xl:w-96 p-4 md:p-6 lg:p-8 space-y-6 lg:border-l lg:border-slate-800/50 overflow-y-auto">
                <div class="bg-[#111827] p-6 rounded-3xl border border-slate-800 shadow-xl">

                    <div class="flex bg-slate-900 rounded-xl p-1 mb-6">
                        <button onclick="showExpenseForm()" id="expenseBtn" class="flex-1 py-2 px-4 rounded-lg text-sm font-bold transition bg-violet-600 text-white shadow-lg">
                            <i class="fa-solid fa-minus mr-2"></i>Dépense
                        </button>
                        <button onclick="showDepositForm()" id="depositBtn" class="flex-1 py-2 px-4 rounded-lg text-sm font-bold transition text-slate-400 hover:text-white">
                            <i class="fa-solid fa-plus mr-2"></i>Dépôt
                        </button>
                    </div>

                    <div id="expenseForm">
                        <h3 class="text-base font-bold text-white mb-6 flex items-center gap-2">
                            <div class="w-2 h-6 bg-red-500 rounded-full"></div> Nouvelle Dépense
                        </h3>

                        <form method="POST" action="../src/service/add_expense.php" class="space-y-4">
                            <div>
                                <label class="text-xs text-slate-500 font-bold ml-1 uppercase">Titre</label>
                                <input type="text" name="title" placeholder="Ex: Café, Loyer..." required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:border-violet-500 focus:outline-none transition mt-1 placeholder-slate-600">
                            </div>

                            <div>
                                <label class="text-xs text-slate-500 font-bold ml-1 uppercase">Montant (MAD)</label>
                                <input type="number" name="amount" step="0.01" min="0.01" placeholder="0.00" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:border-violet-500 focus:outline-none transition mt-1 placeholder-slate-600">
                            </div>

                            <div>
                                <label class="text-xs text-slate-500 font-bold ml-1 uppercase">Catégorie</label>
                                <div class="grid grid-cols-3 gap-2 mt-1">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="category" value="nourriture" class="peer sr-only" checked>
                                        <div class="rounded-lg border border-slate-700 bg-slate-900 p-2 text-center text-slate-400 peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 transition hover:bg-slate-800">
                                            <i class="fa-solid fa-utensils block mb-1 text-sm"></i>
                                            <span class="text-[9px] font-bold block">Nourriture</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="category" value="transport" class="peer sr-only">
                                        <div class="rounded-lg border border-slate-700 bg-slate-900 p-2 text-center text-slate-400 peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 transition hover:bg-slate-800">
                                            <i class="fa-solid fa-car block mb-1 text-sm"></i>
                                            <span class="text-[9px] font-bold block">Transport</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="category" value="loyer" class="peer sr-only">
                                        <div class="rounded-lg border border-slate-700 bg-slate-900 p-2 text-center text-slate-400 peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 transition hover:bg-slate-800">
                                            <i class="fa-solid fa-house block mb-1 text-sm"></i>
                                            <span class="text-[9px] font-bold block">Loyer</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="category" value="loisirs" class="peer sr-only">
                                        <div class="rounded-lg border border-slate-700 bg-slate-900 p-2 text-center text-slate-400 peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 transition hover:bg-slate-800">
                                            <i class="fa-solid fa-gamepad block mb-1 text-sm"></i>
                                            <span class="text-[9px] font-bold block">Loisirs</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer col-span-2">
                                        <input type="radio" name="category" value="autre" class="peer sr-only">
                                        <div class="rounded-lg border border-slate-700 bg-slate-900 p-2 text-center text-slate-400 peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 transition hover:bg-slate-800 h-full flex flex-col justify-center">
                                            <span class="text-[10px] font-bold">Autre</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition shadow-lg mt-2 flex items-center justify-center gap-2">
                                Ajouter Dépense <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>

                    <div id="depositForm" style="display: none;">
                        <h3 class="text-base font-bold text-white mb-6 flex items-center gap-2">
                            <div class="w-2 h-6 bg-green-500 rounded-full"></div> Nouveau Dépôt
                        </h3>

                        <form method="POST" action="../src/service/add_deposit.php" class="space-y-4">
                            <div>
                                <label class="text-xs text-slate-500 font-bold ml-1 uppercase">Titre</label>
                                <input type="text" name="title" placeholder="Ex: Salaire, Bonus..." required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:border-green-500 focus:outline-none transition mt-1 placeholder-slate-600">
                            </div>

                            <div>
                                <label class="text-xs text-slate-500 font-bold ml-1 uppercase">Montant (MAD)</label>
                                <input type="number" name="amount" step="0.01" min="0.01" placeholder="0.00" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:border-green-500 focus:outline-none transition mt-1 placeholder-slate-600">
                            </div>

                            <div>
                                <label class="text-xs text-slate-500 font-bold ml-1 uppercase">Source</label>
                                <div class="grid grid-cols-2 gap-2 mt-1">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="category" value="salaire" class="peer sr-only" checked>
                                        <div class="rounded-lg border border-slate-700 bg-slate-900 p-2 text-center text-slate-400 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 transition hover:bg-slate-800">
                                            <i class="fa-solid fa-briefcase block mb-1 text-sm"></i>
                                            <span class="text-[9px] font-bold block">Salaire</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="category" value="bonus" class="peer sr-only">
                                        <div class="rounded-lg border border-slate-700 bg-slate-900 p-2 text-center text-slate-400 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 transition hover:bg-slate-800">
                                            <i class="fa-solid fa-gift block mb-1 text-sm"></i>
                                            <span class="text-[9px] font-bold block">Bonus</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="category" value="freelance" class="peer sr-only">
                                        <div class="rounded-lg border border-slate-700 bg-slate-900 p-2 text-center text-slate-400 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 transition hover:bg-slate-800">
                                            <i class="fa-solid fa-laptop block mb-1 text-sm"></i>
                                            <span class="text-[9px] font-bold block">Freelance</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="category" value="autre" class="peer sr-only">
                                        <div class="rounded-lg border border-slate-700 bg-slate-900 p-2 text-center text-slate-400 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 transition hover:bg-slate-800">
                                            <span class="text-[10px] font-bold">Autre</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition shadow-lg mt-2 flex items-center justify-center gap-2">
                                Ajouter Dépôt <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-gradient-to-b from-[#1e1b4b] to-[#111827] text-white p-6 rounded-3xl border border-indigo-900/50 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-indigo-500 blur-3xl opacity-20 rounded-full"></div>
                    <h3 class="text-sm font-bold mb-2 text-indigo-200 flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-yellow-400"></i> Conseil du mois
                    </h3>
                    <p class="text-xs text-indigo-300/60 leading-relaxed">
                        Vous avez dépensé <strong class="text-indigo-100"><?php echo round($spentPercentage); ?>%</strong> de votre budget.
                        <?php if ($spentPercentage > 85): ?>
                            <span class="text-red-300 block mt-1">⚠️ Ralentissez les dépenses pour finir le mois !</span>
                        <?php elseif ($spentPercentage > 50): ?>
                            <span class="text-yellow-200 block mt-1">Vous êtes à mi-chemin, gardez le cap.</span>
                        <?php else: ?>
                            <span class="text-green-300 block mt-1">Excellent ! Vous gérez parfaitement.</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    function showExpenseForm() {
        document.getElementById('expenseForm').style.display = 'block';
        document.getElementById('depositForm').style.display = 'none';

        const expBtn = document.getElementById('expenseBtn');
        const depBtn = document.getElementById('depositBtn');

        expBtn.className = 'flex-1 py-2 px-4 rounded-lg text-sm font-bold transition bg-violet-600 text-white shadow-lg';
        depBtn.className = 'flex-1 py-2 px-4 rounded-lg text-sm font-bold transition text-slate-400 hover:text-white';
    }

    function showDepositForm() {
        document.getElementById('expenseForm').style.display = 'none';
        document.getElementById('depositForm').style.display = 'block';

        const expBtn = document.getElementById('expenseBtn');
        const depBtn = document.getElementById('depositBtn');

        expBtn.className = 'flex-1 py-2 px-4 rounded-lg text-sm font-bold transition text-slate-400 hover:text-white';
        depBtn.className = 'flex-1 py-2 px-4 rounded-lg text-sm font-bold transition bg-green-600 text-white shadow-lg';
    }

    // Fonction qui manquait pour le budget
    function openBudgetPopup() {
        let currentBudget = <?php echo $budget; ?>;
        let newBudget = prompt("Entrez votre nouveau budget mensuel :", currentBudget);
        
        if (newBudget !== null && newBudget !== "") {
            // Exemple : window.location.href = 'service/update_budget.php?amount=' + newBudget;
            alert("Fonctionnalité à implémenter : Nouveau budget de " + newBudget + " MAD enregistré (simulation).");
        }
    }
</script>

<?php require_once 'Layout/footer.php'; ?>