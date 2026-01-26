<?php
/**
 * Landing Page - Page d'accueil moderne
 * Présente le système de gestion de notes avec Hero et 3 étapes
 */

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/functions.php';

$title = 'Accueil - Gestion des notes IUA';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Hero Section - Premium avec Gradient Animé -->
<section class="bg-gradient-hero text-white py-24 md:py-32 relative overflow-hidden">
    <!-- Particules décoratives -->
    <div class="absolute inset-0 opacity-10">
        <div
            class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-float">
        </div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-accent rounded-full mix-blend-overlay filter blur-3xl animate-float"
            style="animation-delay: 1s;"></div>
    </div>

    <div class="container mx-auto px-4 text-center relative z-10">
        <div data-animate class="mb-8">
            <h1 class="hero-title mb-6 animate-fade-in-up">
                🎓 Gestion des Notes IUA
            </h1>
            <p class="hero-subtitle text-gray-100 mb-10 max-w-3xl mx-auto animate-fade-in-up stagger-1">
                Système moderne et intuitif pour la gestion académique de l'Institut Universitaire d'Abidjan
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up stagger-2">
            <a href="/gestion-notes-iua - Copy/auth/login.php"
                class="group px-10 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 rounded-xl text-lg font-bold transition-all shadow-2xl hover:shadow-emerald-500/50 transform hover:-translate-y-1 hover:scale-105 btn-premium">
                <span class="flex items-center justify-center space-x-2">
                    <span>Se connecter</span>
                    <span class="transform group-hover:translate-x-1 transition-transform">→</span>
                </span>
            </a>
            <a href="#fonctionnalites"
                class="px-10 py-4 bg-white/10 hover:bg-white/20 rounded-xl text-lg font-bold transition-all backdrop-blur-md border-2 border-white/30 hover:border-white/50 transform hover:-translate-y-1">
                Découvrir
            </a>
        </div>
    </div>
</section>

<!-- Section 3 Étapes - Premium Cards -->
<section id="fonctionnalites" class="py-20 bg-gradient-to-b from-white to-pearl">
    <div class="container mx-auto px-4">
        <div data-animate class="text-center mb-16">
            <h2 class="text-5xl font-display font-bold text-gray-800 mb-4">
                Comment ça fonctionne ?
            </h2>
            <div class="w-24 h-1 bg-gradient-primary mx-auto rounded-full"></div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Étape 1 : Saisie -->
            <div data-animate
                class="card-premium text-center p-10 bg-gradient-to-br from-blue-50 to-white border-2 border-blue-100 hover:border-blue-300 group">
                <div
                    class="text-7xl mb-6 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                    📝</div>
                <div class="inline-block px-4 py-1 bg-blue-500 text-white text-sm font-bold rounded-full mb-4">Étape 1
                </div>
                <h3 class="text-3xl font-display font-bold mb-4 text-navy">Saisie</h3>
                <p class="text-gray-700 leading-relaxed">
                    Les enseignants saisissent facilement les notes des étudiants par matière, classe et période
                    académique.
                </p>
                <div class="mt-6 pt-6 border-t border-blue-200">
                    <span class="text-blue-600 font-semibold">Rapide & Intuitif</span>
                </div>
            </div>

            <!-- Étape 2 : Calcul -->
            <div data-animate
                class="card-premium text-center p-10 bg-gradient-to-br from-emerald-50 to-white border-2 border-emerald-100 hover:border-emerald-300 group stagger-1">
                <div
                    class="text-7xl mb-6 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                    🧮</div>
                <div class="inline-block px-4 py-1 bg-emerald-500 text-white text-sm font-bold rounded-full mb-4">Étape
                    2</div>
                <h3 class="text-3xl font-display font-bold mb-4 text-navy">Calcul</h3>
                <p class="text-gray-700 leading-relaxed">
                    Le système calcule automatiquement les moyennes et génère les bulletins de notes pour chaque
                    étudiant.
                </p>
                <div class="mt-6 pt-6 border-t border-emerald-200">
                    <span class="text-emerald-600 font-semibold">100% Automatique</span>
                </div>
            </div>

            <!-- Étape 3 : Consultation -->
            <div data-animate
                class="card-premium text-center p-10 bg-gradient-to-br from-purple-50 to-white border-2 border-purple-100 hover:border-purple-300 group stagger-2">
                <div
                    class="text-7xl mb-6 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                    📊</div>
                <div class="inline-block px-4 py-1 bg-purple-500 text-white text-sm font-bold rounded-full mb-4">Étape 3
                </div>
                <h3 class="text-3xl font-display font-bold mb-4 text-navy">Consultation</h3>
                <p class="text-gray-700 leading-relaxed">
                    Les étudiants consultent leurs notes en temps réel, et les administrateurs gèrent l'ensemble des
                    données.
                </p>
                <div class="mt-6 pt-6 border-t border-purple-200">
                    <span class="text-purple-600 font-semibold">Accès Instantané</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Accès par rôle - Premium Cards -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div data-animate class="text-center mb-16">
            <h2 class="text-5xl font-display font-bold text-gray-800 mb-4">
                Accès selon votre profil
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Choisissez votre espace personnalisé selon votre rôle
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Admin -->
            <div data-animate
                class="card-premium group bg-gradient-to-br from-white to-blue-50 p-8 text-center border-2 border-blue-100 hover:border-blue-400 relative overflow-hidden">
                <!-- Decorative background -->
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-blue-200 rounded-full -mr-16 -mt-16 opacity-20 group-hover:scale-150 transition-transform duration-500">
                </div>

                <div class="relative z-10">
                    <div class="text-6xl mb-6 transform group-hover:scale-110 group-hover:rotate-12 transition-all">
                        👨‍💼</div>
                    <h3 class="text-2xl font-display font-bold mb-3 text-navy">Administrateur</h3>
                    <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                        Gestion complète : classes, matières, périodes, enseignants, étudiants etc.
                    </p>
                    <div class="space-y-2 mb-6 text-sm text-left">
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span class="text-blue-500">✓</span>
                            <span>Gestion des utilisateurs</span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span class="text-blue-500">✓</span>
                            <span>Configuration système</span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span class="text-blue-500">✓</span>
                            <span>Rapports complets</span>
                        </div>
                    </div>
                    <a href="/gestion-notes-iua - Copy/auth/login.php"
                        class="inline-block w-full px-6 py-3 bg-gradient-to-r from-navy to-blue-700 text-white rounded-lg hover:from-blue-800 hover:to-blue-900 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-semibold btn-premium">
                        Accéder →
                    </a>
                </div>
            </div>

            <!-- Enseignant -->
            <div data-animate
                class="card-premium group bg-gradient-to-br from-white to-emerald-50 p-8 text-center border-2 border-emerald-100 hover:border-emerald-400 relative overflow-hidden stagger-1">
                <!-- Decorative background -->
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-emerald-200 rounded-full -mr-16 -mt-16 opacity-20 group-hover:scale-150 transition-transform duration-500">
                </div>

                <div class="relative z-10">
                    <div class="text-6xl mb-6 transform group-hover:scale-110 group-hover:rotate-12 transition-all">
                        👨‍🏫</div>
                    <h3 class="text-2xl font-display font-bold mb-3 text-navy">Enseignant</h3>
                    <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                        Saisie des notes, consultation des notes saisies et calcul des moyennes.
                    </p>
                    <div class="space-y-2 mb-6 text-sm text-left">
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span class="text-emerald-500">✓</span>
                            <span>Saisie rapide des notes</span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span class="text-emerald-500">✓</span>
                            <span>Suivi des classes</span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span class="text-emerald-500">✓</span>
                            <span>Statistiques détaillées</span>
                        </div>
                    </div>
                    <a href="/gestion-notes-iua - Copy/auth/login.php"
                        class="inline-block w-full px-6 py-3 bg-gradient-to-r from-navy to-blue-700 text-white rounded-lg hover:from-blue-800 hover:to-blue-900 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-semibold btn-premium">
                        Accéder →
                    </a>
                </div>
            </div>

            <!-- Étudiant -->
            <div data-animate
                class="card-premium group bg-gradient-to-br from-white to-purple-50 p-8 text-center border-2 border-purple-100 hover:border-purple-400 relative overflow-hidden stagger-2">
                <!-- Decorative background -->
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-purple-200 rounded-full -mr-16 -mt-16 opacity-20 group-hover:scale-150 transition-transform duration-500">
                </div>

                <div class="relative z-10">
                    <div class="text-6xl mb-6 transform group-hover:scale-110 group-hover:rotate-12 transition-all">🎓
                    </div>
                    <h3 class="text-2xl font-display font-bold mb-3 text-navy">Étudiant</h3>
                    <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                        Consultation de vos notes, bulletins et informations personnelles.
                    </p>
                    <div class="space-y-2 mb-6 text-sm text-left">
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span class="text-purple-500">✓</span>
                            <span>Notes en temps réel</span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span class="text-purple-500">✓</span>
                            <span>Bulletins téléchargeables</span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span class="text-purple-500">✓</span>
                            <span>Profil personnalisé</span>
                        </div>
                    </div>
                    <a href="/gestion-notes-iua - Copy/auth/login.php"
                        class="inline-block w-full px-6 py-3 bg-gradient-to-r from-navy to-blue-700 text-white rounded-lg hover:from-blue-800 hover:to-blue-900 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-semibold btn-premium">
                        Accéder →
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section CTA Final - Premium avec Gradient -->
<section class="py-20 bg-gradient-to-br from-navy via-blue-800 to-navy text-white relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute inset-0 opacity-10">
        <div
            class="absolute top-10 left-20 w-64 h-64 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-float">
        </div>
        <div class="absolute bottom-10 right-20 w-80 h-80 bg-accent rounded-full mix-blend-overlay filter blur-3xl animate-float"
            style="animation-delay: 1.5s;"></div>
    </div>

    <div class="container mx-auto px-4 text-center relative z-10">
        <div data-animate>
            <h2 class="text-4xl md:text-5xl font-display font-bold mb-6">Prêt à commencer ?</h2>
            <p class="text-xl md:text-2xl text-gray-200 mb-10 max-w-2xl mx-auto">
                Connectez-vous pour accéder à votre espace personnel et profiter de toutes les fonctionnalités
            </p>
            <a href="/gestion-notes-iua - Copy/auth/login.php"
                class="group inline-block px-12 py-5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 rounded-xl text-xl font-bold transition-all shadow-2xl hover:shadow-emerald-500/50 transform hover:-translate-y-1 hover:scale-105 btn-premium">
                <span class="flex items-center space-x-3">
                    <span>🚀</span>
                    <span>Se connecter maintenant</span>
                    <span class="transform group-hover:translate-x-2 transition-transform">→</span>
                </span>
            </a>
        </div>

        <!-- Stats rapides -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-16 max-w-4xl mx-auto">
            <div data-animate class="text-center">
                <div class="text-4xl font-bold text-emerald-400 mb-2" data-count="1000">0</div>
                <div class="text-sm text-gray-300">Étudiants</div>
            </div>
            <div data-animate class="text-center stagger-1">
                <div class="text-4xl font-bold text-emerald-400 mb-2" data-count="50">0</div>
                <div class="text-sm text-gray-300">Enseignants</div>
            </div>
            <div data-animate class="text-center stagger-2">
                <div class="text-4xl font-bold text-emerald-400 mb-2" data-count="30">0</div>
                <div class="text-sm text-gray-300">Classes</div>
            </div>
            <div data-animate class="text-center stagger-3">
                <div class="text-4xl font-bold text-emerald-400 mb-2" data-count="100">0</div>
                <div class="text-sm text-gray-300">Matières</div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/alerts.php';
require_once __DIR__ . '/includes/footer.php';
?>