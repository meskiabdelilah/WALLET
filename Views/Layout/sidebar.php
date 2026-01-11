    <?php

use Src\Classes\Security;

    $basePath = '';
    if (strpos($_SERVER['PHP_SELF'], '/student/') !== false) {
        $basePath = '../';
    }
    ?>


    <nav class="hidden md:flex flex-col w-16 lg:w-24 bg-[#111827] border-r border-slate-800 h-screen py-6 lg:py-8 items-center justify-between z-20">

        <div class="h-10 w-10 lg:h-12 lg:w-12 bg-violet-600 rounded-xl flex items-center justify-center text-white text-lg lg:text-xl shadow-[0_0_15px_rgba(139,92,246,0.5)]">
            <i class="fa-solid fa-wallet"></i>
        </div>

        <div class="flex flex-col gap-4 lg:gap-6 w-full px-2 lg:px-4">
            <button onclick="switchTab('dashboard')" id="btn-dashboard" class="nav-btn group flex flex-col items-center justify-center gap-1 p-2 lg:p-3 rounded-xl transition-all duration-300 bg-slate-800 text-white shadow-lg shadow-violet-900/20">
                <i class="fa-solid fa-house text-lg lg:text-xl mb-1"></i>
                <span class="text-[9px] lg:text-[10px] font-bold">Accueil</span>
            </button>

            <button onclick="switchTab('recurring')" id="btn-recurring" class="nav-btn group flex flex-col items-center justify-center gap-1 p-2 lg:p-3 rounded-xl transition-all duration-300 text-slate-500 hover:text-violet-400 hover:bg-slate-800/50">
                <i class="fa-solid fa-rotate text-lg lg:text-xl mb-1 group-hover:rotate-180 transition-transform"></i>
                <span class="text-[9px] lg:text-[10px] font-bold">Récurrent</span>
            </button>

            <button onclick="switchTab('analytics')" id="btn-analytics" class="nav-btn group flex flex-col items-center justify-center gap-1 p-2 lg:p-3 rounded-xl transition-all duration-300 text-slate-500 hover:text-violet-400 hover:bg-slate-800/50">
                <i class="fa-solid fa-chart-simple text-lg lg:text-xl mb-1 group-hover:-translate-y-1 transition-transform"></i>
                <span class="text-[9px] lg:text-[10px] font-bold">Stats</span>
            </button>
        </div>

        <a href="<?= $basePath?>auth/logout.php?token= <?= Security::generateCSRFToken()?>">
            <div class="relative cursor-pointer group" title="Se déconnecter">
                <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-full bg-slate-800 flex items-center justify-center border border-slate-700 hover:border-red-500 hover:text-red-500 transition">
                    <i class="fa-solid fa-power-off text-sm lg:text-base"></i>
                </div>
            </div>
        </a>    
    </nav>