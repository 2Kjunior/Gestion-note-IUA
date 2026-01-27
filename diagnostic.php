<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>🔍 Diagnostic Navigation - IUA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 2.5em;
        }

        h2 {
            color: #333;
            margin: 20px 0 15px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }

        h3 {
            color: #555;
            margin: 15px 0 10px;
        }

        .success {
            color: #10b981;
            font-weight: bold;
        }

        .error {
            color: #ef4444;
            font-weight: bold;
        }

        .warning {
            color: #f59e0b;
            font-weight: bold;
        }

        .info {
            color: #3b82f6;
            font-weight: bold;
        }

        .test-link {
            display: inline-block;
            margin: 10px 10px 10px 0;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .test-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f3f4f6;
            font-weight: 600;
        }

        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }

        .alert-success {
            background: #d1fae5;
            border-left: 4px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
        }

        .alert-warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
        }

        .alert-info {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
        }

        .btn-clear-cache {
            background: #ef4444;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-clear-cache:hover {
            background: #dc2626;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>🔍 Diagnostic Complet - Navigation IUA</h1>
            <p style="color: #666; font-size: 1.1em;">Analyse de tous les problèmes de navigation</p>
        </div>

        <?php
        // Désactiver le cache
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        require_once __DIR__ . '/config/constants.php';
        require_once __DIR__ . '/config/functions.php';

        $baseDir = __DIR__;
        $webRoot = '/gestion-notes-iua - Copy';
        ?>

        <!-- Statut PHP -->
        <div class="card">
            <h2>✅ Statut PHP</h2>
            <div class="alert alert-success">
                <strong>✓ PHP fonctionne correctement</strong><br>
                Version :
                <?= phpversion() ?><br>
                Serveur :
                <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Inconnu' ?>
            </div>
        </div>

        <!-- Configuration -->
        <div class="card">
            <h2>⚙️ Configuration</h2>
            <table>
                <tr>
                    <th>Variable</th>
                    <th>Valeur</th>
                    <th>Statut</th>
                </tr>
                <tr>
                    <td><code>BASE_URL</code></td>
                    <td><code><?= BASE_URL ?></code></td>
                    <td><span class="success">✓ Défini</span></td>
                </tr>
                <tr>
                    <td><code>__DIR__</code></td>
                    <td><code><?= __DIR__ ?></code></td>
                    <td><span class="success">✓ OK</span></td>
                </tr>
                <tr>
                    <td><code>$_SERVER['REQUEST_URI']</code></td>
                    <td><code><?= $_SERVER['REQUEST_URI'] ?? 'N/A' ?></code></td>
                    <td><span class="info">ℹ Info</span></td>
                </tr>
                <tr>
                    <td><code>$_SERVER['SCRIPT_NAME']</code></td>
                    <td><code><?= $_SERVER['SCRIPT_NAME'] ?? 'N/A' ?></code></td>
                    <td><span class="info">ℹ Info</span></td>
                </tr>
            </table>
        </div>

        <!-- Test des Fichiers -->
        <div class="card">
            <h2>📁 Vérification des Fichiers</h2>
            <table>
                <tr>
                    <th>Fichier</th>
                    <th>Existe</th>
                    <th>Taille</th>
                </tr>
                <?php
                $files = [
                    'index.php',
                    'auth/login.php',
                    'admin/index.php',
                    'enseignant/index.php',
                    'etudiant/index.php',
                    'config/constants.php',
                    'config/functions.php',
                    'includes/navbar.php',
                    'includes/footer.php',
                    'includes/header.php',
                ];

                foreach ($files as $file) {
                    $path = $baseDir . '/' . $file;
                    $exists = file_exists($path);
                    $size = $exists ? filesize($path) : 0;
                    echo "<tr>";
                    echo "<td><code>$file</code></td>";
                    echo "<td>" . ($exists ? '<span class="success">✓ Oui</span>' : '<span class="error">✗ Non</span>') . "</td>";
                    echo "<td>" . ($exists ? number_format($size) . ' octets' : '-') . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <!-- Test des Liens -->
        <div class="card">
            <h2>🔗 Test des Liens de Navigation</h2>
            <div class="alert alert-warning">
                <strong>⚠️ IMPORTANT :</strong> Cliquez sur chaque lien ci-dessous pour vérifier qu'il fonctionne.
                Si vous restez sur cette page, il y a un problème de cache navigateur.
            </div>

            <h3>Pages Principales</h3>
            <a href="<?= $webRoot ?>/index.php" class="test-link">🏠 Page d'Accueil</a>
            <a href="<?= $webRoot ?>/auth/login.php" class="test-link">🔐 Page de Connexion</a>
            <a href="<?= $webRoot ?>/diagnostic.php" class="test-link">🔄 Recharger ce Diagnostic</a>

            <h3>Dashboards</h3>
            <a href="<?= $webRoot ?>/admin/index.php" class="test-link">👨‍💼 Admin Dashboard</a>
            <a href="<?= $webRoot ?>/enseignant/index.php" class="test-link">👨‍🏫 Enseignant Dashboard</a>
            <a href="<?= $webRoot ?>/etudiant/index.php" class="test-link">🎓 Étudiant Dashboard</a>
        </div>

        <!-- Problème de Cache -->
        <div class="card">
            <h2>🗑️ Problème de Cache Navigateur</h2>
            <div class="alert alert-error">
                <strong>❌ PROBLÈME DÉTECTÉ :</strong> Si vous cliquez sur les liens ci-dessus et que vous restez sur
                cette page,
                c'est que votre navigateur a mis en cache la page d'accueil.
            </div>

            <h3>Solutions :</h3>
            <ol style="line-height: 2; margin: 15px 0 15px 30px;">
                <li><strong>Vider le cache du navigateur :</strong>
                    <ul style="margin-left: 20px; margin-top: 5px;">
                        <li>Chrome/Edge : <code>Ctrl + Shift + Delete</code> → Cocher "Images et fichiers en cache" →
                            Effacer</li>
                        <li>Firefox : <code>Ctrl + Shift + Delete</code> → Cocher "Cache" → Effacer maintenant</li>
                    </ul>
                </li>
                <li><strong>Forcer le rechargement :</strong> <code>Ctrl + F5</code> ou <code>Ctrl + Shift + R</code>
                </li>
                <li><strong>Mode navigation privée :</strong> <code>Ctrl + Shift + N</code> (Chrome) ou
                    <code>Ctrl + Shift + P</code> (Firefox)</li>
                <li><strong>Fermer et rouvrir le navigateur</strong></li>
            </ol>

            <button class="btn-clear-cache" onclick="clearCacheAndReload()">
                🗑️ Vider le Cache et Recharger
            </button>
        </div>

        <!-- URLs Générées -->
        <div class="card">
            <h2>🌐 URLs Générées par le Système</h2>
            <table>
                <tr>
                    <th>Page</th>
                    <th>URL Générée</th>
                    <th>Test</th>
                </tr>
                <tr>
                    <td>Accueil</td>
                    <td><code><?= $webRoot ?>/index.php</code></td>
                    <td><a href="<?= $webRoot ?>/index.php" target="_blank">Tester →</a></td>
                </tr>
                <tr>
                    <td>Connexion</td>
                    <td><code><?= $webRoot ?>/auth/login.php</code></td>
                    <td><a href="<?= $webRoot ?>/auth/login.php" target="_blank">Tester →</a></td>
                </tr>
                <tr>
                    <td>Admin</td>
                    <td><code><?= $webRoot ?>/admin/index.php</code></td>
                    <td><a href="<?= $webRoot ?>/admin/index.php" target="_blank">Tester →</a></td>
                </tr>
            </table>
        </div>

        <!-- Instructions Finales -->
        <div class="card">
            <h2>📋 Instructions de Test</h2>
            <div class="alert alert-info">
                <strong>1. Videz le cache de votre navigateur</strong> (voir solutions ci-dessus)<br>
                <strong>2. Fermez TOUS les onglets du site</strong><br>
                <strong>3. Rouvrez un nouvel onglet</strong><br>
                <strong>4. Allez sur :</strong> <code>http://localhost/gestion-notes-iua%20-%20Copy/index.php</code><br>
                <strong>5. Cliquez sur "Se connecter"</strong><br>
                <strong>6. Vous devez arriver sur la page de connexion !</strong>
            </div>
        </div>
    </div>

    <script>
        function clearCacheAndReload() {
            // Tenter de vider le cache via JavaScript
            if ('caches' in window) {
                caches.keys().then(function (names) {
                    for (let name of names) caches.delete(name);
                });
            }

            // Recharger en forçant le bypass du cache
            window.location.reload(true);
        }

        // Désactiver le cache pour cette page
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>

</html>