<?php
/*********************************************************
 * SAISIE DES NOTES - ENSEIGNANT (LOGIQUE UNIVERSITAIRE)
 *********************************************************/

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../config/constants.php';
require_once '../auth/check_auth.php';

if ($_SESSION['role'] !== 'enseignant') {
    redirect('/index.php');
}

$pdo = Database::getInstance()->getConnection();
$matriculeEns = $_SESSION['id_reference'];

$message = null;

$periodes = $pdo->query(
    "SELECT codePeriode, libellePeriode
     FROM periode
     ORDER BY dateDebut DESC"
)->fetchAll();

$classes = $pdo->prepare(
    "SELECT DISTINCT c.codecl, c.libellecl
     FROM enseignement e
     JOIN classe c ON c.codecl = e.codecl
     WHERE e.MatriculeEns = ?"
);
$classes->execute([$matriculeEns]);
$classes = $classes->fetchAll();

$codecl = $_GET['codecl'] ?? null;
$matieres = [];
$etudiants = [];

if ($codecl) {
    $stmt = $pdo->prepare(
        "SELECT m.codeMat, m.libelleMat
         FROM enseignement e
         JOIN matiere m ON m.codeMat = e.codeMat
         WHERE e.MatriculeEns = ?
           AND e.codecl = ?"
    );
    $stmt->execute([$matriculeEns, $codecl]);
    $matieres = $stmt->fetchAll();

    // NOUVEAU : Récupérer les étudiants inscrits dans cette classe
    // On prend la période la plus récente par défaut
    $periodeActive = $pdo->query(
        "SELECT codePeriode FROM periode ORDER BY dateDebut DESC LIMIT 1"
    )->fetch();

    if ($periodeActive) {
        $stmt = $pdo->prepare(
            "SELECT DISTINCT e.Matriculation, e.nomEt, e.prenomEt, e.photo
             FROM etudiant e
             JOIN inscription i ON i.Matriculation = e.Matriculation
             WHERE i.codecl = ? AND i.codePeriode = ?
             ORDER BY e.nomEt, e.prenomEt"
        );
        $stmt->execute([$codecl, $periodeActive['codePeriode']]);
        $etudiants = $stmt->fetchAll();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matriculeEtud = trim($_POST['Matriculation']);
    $codeMat = $_POST['codeMat'];
    $codePeriode = $_POST['codePeriode'];
    $note = floatval($_POST['note']);
    $codecl = $_POST['codecl'];

    if ($note < 0 || $note > 20) {
        $message = "❌ Note invalide (0 à 20)";
    } else {
        $check = $pdo->prepare(
            "SELECT COUNT(*) FROM inscription
             WHERE Matriculation = ?
               AND codecl = ?
               AND codePeriode = ?"
        );
        $check->execute([$matriculeEtud, $codecl, $codePeriode]);

        if ($check->fetchColumn() == 0) {
            $message = "❌ Étudiant non inscrit dans cette classe pour cette période.";
        } else {
            $insert = $pdo->prepare(
                "INSERT INTO evaluer
                (MatriculeEns, Matriculation, codeMat, codePeriode, note, date_saisie)
                VALUES (?, ?, ?, ?, ?, NOW())"
            );

            $insert->execute([
                $matriculeEns,
                $matriculeEtud,
                $codeMat,
                $codePeriode,
                $note
            ]);

            redirect('mes_notes.php');
        }
    }
}

$title = 'Saisie des notes - Enseignant';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
require_once '../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">📝 Saisie des notes</h2>
        <p class="text-gray-600">Enregistrez les notes des étudiants par matière et période</p>
    </div>

    <!-- Étape 1 : Sélection de la classe -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6 form-card">
        <h3 class="text-xl font-bold text-gray-800 mb-4">1️⃣ Choisir une classe</h3>
        <form method="get">
            <div class="flex gap-4">
                <select name="codecl" onchange="this.form.submit()" required
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition">
                    <option value="">-- Choisir une classe --</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['codecl'] ?>" <?= $codecl === $c['codecl'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['libellecl']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <?php if ($codecl): ?>
        <!-- NOUVEAU : Étape 2 - Liste des étudiants -->
        <?php if (!empty($etudiants)): ?>
            <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">2️⃣ Sélectionner un étudiant</h3>
                <p class="text-gray-600 mb-4">Cliquez sur un étudiant pour remplir automatiquement ses informations</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="studentList">
                    <?php foreach ($etudiants as $etud): ?>
                        <div class="student-card p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-600 hover:bg-blue-50 transition"
                            data-matricule="<?= htmlspecialchars($etud['Matriculation']) ?>"
                            data-nom="<?= htmlspecialchars($etud['nomEt'] . ' ' . $etud['prenomEt']) ?>">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($etud['photo']) && $etud['photo'] !== 'default.png'): ?>
                                    <img src="<?= BASE_URL ?>/assets/images/etudiants/<?= htmlspecialchars($etud['photo']) ?>"
                                        alt="Photo" class="w-12 h-12 rounded-full object-cover border-2 border-gray-300">
                                <?php else: ?>
                                    <div
                                        class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-xl text-white font-bold">
                                        <?= strtoupper(substr($etud['prenomEt'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-800">
                                        <?= htmlspecialchars($etud['nomEt'] . ' ' . $etud['prenomEt']) ?>
                                    </div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($etud['Matriculation']) ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Étape 3 : Formulaire de saisie -->
        <div class="bg-white rounded-xl shadow-lg p-8 form-card">
            <h3 class="text-xl font-bold text-gray-800 mb-6">3️⃣ Saisir la note</h3>

            <form method="post" class="space-y-6">
                <input type="hidden" name="codecl" value="<?= $codecl ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="codePeriode" class="block text-sm font-semibold text-gray-700 mb-2">
                            Période <span class="text-red-500">*</span>
                        </label>
                        <select id="codePeriode" name="codePeriode" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition">
                            <?php foreach ($periodes as $p): ?>
                                <option value="<?= $p['codePeriode'] ?>">
                                    <?= htmlspecialchars($p['libellePeriode']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="codeMat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Matière <span class="text-red-500">*</span>
                        </label>
                        <select id="codeMat" name="codeMat" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition">
                            <?php foreach ($matieres as $m): ?>
                                <option value="<?= $m['codeMat'] ?>">
                                    <?= htmlspecialchars($m['libelleMat']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- MODIFIÉ : Champ étudiant avec auto-remplissage -->
                <div>
                    <label for="studentDisplay" class="block text-sm font-semibold text-gray-700 mb-2">
                        Étudiant sélectionné <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="studentDisplay" readonly
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed text-gray-700"
                        placeholder="👆 Cliquez sur un étudiant ci-dessus" value="">
                    <input type="hidden" id="Matriculation" name="Matriculation" required>
                </div>

                <div>
                    <label for="note" class="block text-sm font-semibold text-gray-700 mb-2">
                        Note /20 <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="note" name="note" step="0.5" min="0" max="20" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        placeholder="Ex: 15.5">
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-lg transition shadow-lg hover:shadow-xl">
                        💾 Enregistrer
                    </button>
                    <a href="<?= BASE_URL ?>/enseignant/mes_notes.php"
                        class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition text-center">
                        📄 Voir les notes
                    </a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<!-- Styles CSS pour les cartes étudiants -->
<style>
    .student-card {
        transition: all 0.2s ease;
    }

    .student-card.selected {
        border-color: #2563eb !important;
        background-color: #eff6ff !important;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }

    .student-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .student-card:active {
        transform: translateY(0);
    }
</style>

<!-- JavaScript pour la sélection d'étudiant -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const studentCards = document.querySelectorAll('.student-card');
        const matriculeInput = document.getElementById('Matriculation');
        const studentDisplay = document.getElementById('studentDisplay');

        if (studentCards.length > 0 && matriculeInput && studentDisplay) {
            studentCards.forEach(card => {
                card.addEventListener('click', function () {
                    // Retirer la sélection précédente
                    studentCards.forEach(c => {
                        c.classList.remove('border-blue-600', 'bg-blue-50', 'selected');
                    });

                    // Marquer comme sélectionné
                    this.classList.add('border-blue-600', 'bg-blue-50', 'selected');

                    // Remplir les champs
                    const matricule = this.dataset.matricule;
                    const nom = this.dataset.nom;

                    matriculeInput.value = matricule;
                    studentDisplay.value = nom + ' (' + matricule + ')';

                    // Scroll vers le formulaire avec animation douce
                    const formSection = document.querySelector('form[method="post"]').parentElement;
                    if (formSection) {
                        formSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }

                    // Focus sur le champ note après un court délai
                    setTimeout(() => {
                        const noteInput = document.getElementById('note');
                        if (noteInput) {
                            noteInput.focus();
                        }
                    }, 600);
                });
            });
        }
    });
</script>

<?php
require_once '../includes/footer.php';
?>