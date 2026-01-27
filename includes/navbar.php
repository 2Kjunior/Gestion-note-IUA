<?php
/**
 * Navbar dynamique – Gestion Notes IUA
 * Rôles : admin | enseignant | etudiant
 */
?>

<nav class="navbar-glass fixed top-0 w-full z-50 text-white backdrop-blur-lg bg-blue-900/90 shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">

            <!-- LOGO -->
            <div class="flex items-center space-x-3">
                <span class="text-3xl">🎓</span>
                <a href="/gestion-notes-iua - Copy/index.php"
                   class="text-xl font-bold text-white hover:text-blue-200 transition">
                    <span class="hidden md:inline">JUNIX NOTE</span>
                    <span class="md:hidden">IUA</span>
                </a>
            </div>

            <!-- MENU PRINCIPAL -->
            <div class="hidden md:flex items-center space-x-2">

                <?php if (!isset($_SESSION['role'])): ?>

                    <!-- VISITEUR -->
                    <a href="/gestion-notes-iua - Copy/auth/login.php"
                       class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-medium shadow">
                        🔐 Connexion
                    </a>

                <?php elseif ($_SESSION['role'] === 'admin'): ?>

                    <!-- ADMIN -->
                    <a href="/gestion-notes-iua - Copy/admin/index.php"
                       class="px-4 py-2 rounded-lg hover:bg-white/10 flex items-center gap-2">
                        🏠 Dashboard
                    </a>

                    <!-- GESTION -->
                    <div class="relative group">
                        <button class="px-4 py-2 rounded-lg hover:bg-white/10 flex items-center gap-2">
                            📚 Gestion
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div class="absolute right-0 mt-2 w-56 bg-white text-gray-900 rounded-xl shadow-xl hidden group-hover:block">
                            <a class="menu-item" href="/gestion-notes-iua - Copy/admin/classes/liste.php">📚 Classes</a>
                            <a class="menu-item" href="/gestion-notes-iua - Copy/admin/matieres/liste.php">📘 Matières</a>
                            <a class="menu-item" href="/gestion-notes-iua - Copy/admin/periodes/liste.php">📅 Périodes</a>
                            <a class="menu-item" href="/gestion-notes-iua - Copy/admin/enseignants/liste.php">👨‍🏫 Enseignants</a>
                            <a class="menu-item" href="/gestion-notes-iua - Copy/admin/etudiants/liste.php">🎓 Étudiants</a>
                            <a class="menu-item" href="/gestion-notes-iua - Copy/admin/inscriptions/liste.php">📋 Inscriptions</a>
                        </div>
                    </div>

                    <!-- PARAMÉTRAGE -->
                    <div class="relative group">
                        <button class="px-4 py-2 rounded-lg hover:bg-white/10 flex items-center gap-2">
                            ⚙️ Paramétrage
                        </button>
                        <div class="absolute right-0 mt-2 w-64 bg-white text-gray-900 rounded-xl shadow-xl hidden group-hover:block">
                            <a class="menu-item" href="/gestion-notes-iua - Copy/admin/parametrage/enseignement.php">
                                👨‍🏫 Enseignements
                            </a>
                            <a class="menu-item" href="/gestion-notes-iua - Copy/admin/parametrage/matiere_classe.php">
                                🔗 Matières ↔ Classes
                            </a>
                        </div>
                    </div>

                    <span class="px-3 py-1 rounded-full bg-white/10 text-sm">
                        👤 <?= htmlspecialchars($_SESSION['login']) ?>
                    </span>

                    <a href="/gestion-notes-iua - Copy/auth/logout.php"
                       class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg">
                        🚪 Déconnexion
                    </a>

                <?php elseif ($_SESSION['role'] === 'enseignant'): ?>

                    <!-- ENSEIGNANT -->
                    <a href="/gestion-notes-iua - Copy/enseignant/index.php" class="nav-link">🏠 Dashboard</a>
                    <a href="/gestion-notes-iua - Copy/enseignant/saisir_notes.php" class="nav-link">✍️ Saisir notes</a>
                    <a href="/gestion-notes-iua - Copy/enseignant/mes_notes.php" class="nav-link">📊 Mes notes</a>

                    <span class="px-3 py-1 rounded-full bg-white/10 text-sm">
                        👨‍🏫 <?= htmlspecialchars($_SESSION['login']) ?>
                    </span>

                    <a href="/gestion-notes-iua - Copy/auth/logout.php"
                       class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg">
                        🚪 Déconnexion
                    </a>

                <?php elseif ($_SESSION['role'] === 'etudiant'): ?>

                    <!-- ÉTUDIANT -->
                    <a href="/gestion-notes-iua - Copy/etudiant/index.php" class="nav-link">🏠 Mon espace</a>
                    <a href="/gestion-notes-iua - Copy/etudiant/mes_notes.php" class="nav-link">📘 Mes notes</a>

                    <span class="px-3 py-1 rounded-full bg-white/10 text-sm">
                        🎓 <?= htmlspecialchars($_SESSION['login']) ?>
                    </span>

                    <a href="/gestion-notes-iua - Copy/auth/logout.php"
                       class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg">
                        🚪 Déconnexion
                    </a>

                <?php endif; ?>

            </div>
        </div>
    </div>
</nav>

<!-- ESPACE POUR NAVBAR FIXED -->
<div class="h-16"></div>

<style>
.menu-item {
    display: block;
    padding: 12px 16px;
    font-weight: 500;
}
.menu-item:hover {
    background: #f3f4f6;
}
.nav-link {
    padding: 8px 16px;
    border-radius: 8px;
}
.nav-link:hover {
    background: rgba(255,255,255,0.1);
}
</style>
