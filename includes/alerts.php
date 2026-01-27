<?php
/**
 * Système d'alertes (Toasts) pour afficher les messages de succès/erreur
 * Utilise les variables : $error, $erreur, $message, $succes
 */

$alertType = null;
$alertMessage = null;

// Détection des messages d'erreur
if (isset($error) && !empty($error)) {
    $alertType = 'error';
    $alertMessage = $error;
} elseif (isset($erreur) && !empty($erreur)) {
    $alertType = 'error';
    $alertMessage = $erreur;
}

// Détection des messages de succès
if (isset($message) && !empty($message) && strpos($message, '✅') !== false) {
    $alertType = 'success';
    $alertMessage = $message;
} elseif (isset($succes) && !empty($succes)) {
    $alertType = 'success';
    $alertMessage = $succes;
} elseif (isset($message) && !empty($message) && $alertType === null) {
    // Message neutre si pas d'erreur détectée
    $alertType = 'info';
    $alertMessage = $message;
}

// Affichage du toast si un message existe
if ($alertMessage && $alertType):
?>
<div id="toast-alert" class="fixed top-4 right-4 z-50 animate-slide-in-right">
    <div class="flex items-center p-4 mb-4 rounded-lg shadow-lg max-w-md <?php
        echo $alertType === 'error' ? 'bg-red-50 border border-red-200 text-red-800' : '';
        echo $alertType === 'success' ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : '';
        echo $alertType === 'info' ? 'bg-blue-50 border border-blue-200 text-blue-800' : '';
    ?>">
        <div class="flex-shrink-0 mr-3">
            <?php if ($alertType === 'error'): ?>
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            <?php elseif ($alertType === 'success'): ?>
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            <?php else: ?>
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            <?php endif; ?>
        </div>
        <div class="flex-1 text-sm font-medium">
            <?= htmlspecialchars($alertMessage) ?>
        </div>
        <button onclick="document.getElementById('toast-alert').remove()" class="ml-3 text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</div>

<script>
    // Auto-dismiss après 5 secondes
    setTimeout(function() {
        const toast = document.getElementById('toast-alert');
        if (toast) {
            toast.style.transition = 'opacity 0.3s ease-out';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }
    }, 5000);
</script>
<?php endif; ?>
