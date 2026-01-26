<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Système moderne de gestion des notes - Institut Universitaire d'Abidjan (IUA). Plateforme intuitive pour étudiants, enseignants et administrateurs.">
    <meta name="keywords" content="IUA, gestion notes, université, Abidjan, étudiants, enseignants">
    <meta name="author" content="IUA - Institut Universitaire d'Abidjan">
    <title><?= $title ?? 'Gestion des notes IUA' ?></title>
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    
    <!-- Google Fonts - Premium Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS - Premium Design System -->
    <link rel="stylesheet" href="/gestion-notes-iua - Copy/assets/css/style.css">
    
    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'navy': '#1e3a8a',
                        'pearl': '#f1f5f9',
                        'emerald': '#10b981',
                        'primary': '#e50914',
                        'accent': '#00d9ff',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                        'display': ['Outfit', 'Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'fade-in-up': 'fadeInUp 0.8s ease-out',
                        'slide-in-right': 'slideInRight 0.4s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'glow': 'glow 2s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    
    <!-- Favicon (Optional) -->
    <link rel="icon" type="image/png" href="/gestion-notes-iua - Copy/assets/images/favicon.png">
</head>
<body class="bg-pearl font-sans min-h-screen flex flex-col antialiased">
