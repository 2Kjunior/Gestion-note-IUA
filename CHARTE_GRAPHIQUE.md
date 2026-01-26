# 🎨 Charte Graphique - Système de Gestion des Notes IUA

## 📋 Table des Matières

1. [Introduction](#introduction)
2. [Identité Visuelle](#identité-visuelle)
3. [Palette de Couleurs](#palette-de-couleurs)
4. [Typographie](#typographie)
5. [Composants UI](#composants-ui)
6. [Iconographie](#iconographie)
7. [Animations et Transitions](#animations-et-transitions)
8. [Responsive Design](#responsive-design)
9. [Accessibilité](#accessibilité)

---

## 1. Introduction

### 1.1 Objectif de la Charte

Cette charte graphique définit l'ensemble des règles et standards visuels du **Système de Gestion des Notes IUA**. Elle garantit une cohérence visuelle sur toutes les pages et interfaces de l'application.

### 1.2 Principes de Design

- **Modernité** : Interface contemporaine avec effets glassmorphism
- **Clarté** : Hiérarchie visuelle claire et intuitive
- **Accessibilité** : Contraste suffisant et navigation au clavier
- **Professionnalisme** : Design premium adapté au contexte académique
- **Réactivité** : Adaptation fluide à tous les écrans

---

## 2. Identité Visuelle

### 2.1 Logo et Symbole

**Emoji Principal** : 🎓 (Chapeau de graduation)

**Utilisation** :
- Navbar (coin supérieur gauche)
- Favicon du site
- En-têtes de sections importantes

**Taille** :
- Navbar : `text-3xl` (30px)
- Titres : `text-5xl` (48px)

### 2.2 Nom de l'Application

**Nom Complet** : `GESTION NOTES IUA`

**Typographie** :
- Police : **Outfit** (Google Fonts)
- Poids : Bold (700)
- Taille : 20px (navbar), 48px (hero)
- Couleur : Navy (#1e3a8a)

---

## 3. Palette de Couleurs

### 3.1 Couleurs Principales

#### Navy (Bleu Marine)
```css
--navy: #1e3a8a
--navy-light: #2563eb
--navy-dark: #1e40af
```
**Usage** : Titres, navbar, boutons primaires, texte important

#### Accent (Cyan Électrique)
```css
--accent: #00d9ff
--accent-light: #33e0ff
--accent-dark: #00b8d9
```
**Usage** : Highlights, hover effects, liens actifs

#### Emerald (Vert Émeraude)
```css
--emerald-500: #10b981
--emerald-600: #059669
--emerald-700: #047857
```
**Usage** : Boutons de validation, succès, enseignants

### 3.2 Couleurs Secondaires

#### Purple (Violet)
```css
--purple-500: #a855f7
--purple-600: #9333ea
--purple-700: #7e22ce
```
**Usage** : Étudiants, badges, statistiques

#### Orange
```css
--orange-500: #f97316
--orange-600: #ea580c
```
**Usage** : Matières, alertes, warnings

#### Blue (Bleu Clair)
```css
--blue-500: #3b82f6
--blue-600: #2563eb
--blue-700: #1d4ed8
```
**Usage** : Administrateur, informations

### 3.3 Couleurs Neutres

#### Gris
```css
--gray-50: #f9fafb
--gray-100: #f3f4f6
--gray-200: #e5e7eb
--gray-300: #d1d5db
--gray-600: #4b5563
--gray-700: #374151
--gray-800: #1f2937
--gray-900: #111827
```

#### Blanc et Noir
```css
--white: #ffffff
--black: #000000
--pearl: #f8f9fa (fond de page)
```

### 3.4 Gradients

#### Gradient Principal
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```
**Usage** : Hero section, boutons premium

#### Gradient Animé
```css
background: linear-gradient(
  -45deg, 
  #667eea, 
  #764ba2, 
  #f093fb, 
  #4facfe
);
animation: gradientShift 15s ease infinite;
```
**Usage** : Arrière-plans dynamiques

---

## 4. Typographie

### 4.1 Polices Principales

#### Inter (Corps de texte)
```css
font-family: 'Inter', sans-serif;
font-weights: 300, 400, 500, 600, 700, 800, 900
```
**Usage** : Texte courant, paragraphes, descriptions

#### Outfit (Titres et Display)
```css
font-family: 'Outfit', sans-serif;
font-weights: 400, 500, 600, 700, 800
```
**Usage** : Titres, headers, éléments de navigation

### 4.2 Échelle Typographique

| Élément | Taille | Poids | Classe Tailwind |
|---------|--------|-------|-----------------|
| H1 (Hero) | 56px | 800 | `text-6xl font-display` |
| H1 (Page) | 48px | 700 | `text-5xl font-bold` |
| H2 | 36px | 700 | `text-4xl font-bold` |
| H3 | 30px | 600 | `text-3xl font-semibold` |
| H4 | 24px | 600 | `text-2xl font-semibold` |
| Body Large | 20px | 400 | `text-xl` |
| Body | 16px | 400 | `text-base` |
| Body Small | 14px | 400 | `text-sm` |
| Caption | 12px | 400 | `text-xs` |

### 4.3 Hiérarchie de Texte

**Titres** :
- Couleur : `text-gray-800` ou `text-navy`
- Line-height : `leading-tight` (1.25)

**Paragraphes** :
- Couleur : `text-gray-600` ou `text-gray-700`
- Line-height : `leading-relaxed` (1.625)

**Liens** :
- Couleur : `text-blue-600`
- Hover : `text-blue-800`
- Underline : `hover:underline`

---

## 5. Composants UI

### 5.1 Boutons

#### Bouton Principal (Primary)
```css
.btn-primary {
  background: linear-gradient(to right, #2563eb, #1d4ed8);
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
}
```

#### Bouton Premium
```css
.btn-premium {
  background: linear-gradient(to right, #10b981, #059669);
  box-shadow: 0 10px 40px rgba(16, 185, 129, 0.3);
  animation: pulse 2s infinite;
}
```

#### Bouton Secondaire
```css
.btn-secondary {
  background: white;
  color: #1e3a8a;
  border: 2px solid #e5e7eb;
}
```

### 5.2 Cards (Cartes)

#### Card Standard
```css
.card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: all 0.3s;
}

.card:hover {
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
  transform: translateY(-4px);
}
```

#### Card Premium
```css
.card-premium {
  background: white;
  border-radius: 20px;
  padding: 32px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  border: 2px solid transparent;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-premium:hover {
  border-color: var(--accent);
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 20px 60px rgba(0, 217, 255, 0.2);
}
```

#### Stat Card
```css
.stat-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  border-left: 4px solid;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.stat-number {
  font-size: 36px;
  font-weight: 700;
  background: linear-gradient(135deg, #667eea, #764ba2);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
```

### 5.3 Navigation Bar

#### Navbar Glassmorphism
```css
.navbar-glass {
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
}
```

### 5.4 Formulaires

#### Input Field
```css
.input-field {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 16px;
  transition: all 0.3s;
}

.input-field:focus {
  border-color: #2563eb;
  outline: none;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}
```

#### Select Dropdown
```css
.select-field {
  appearance: none;
  background-image: url("data:image/svg+xml,...");
  background-position: right 12px center;
  background-repeat: no-repeat;
}
```

### 5.5 Tables

```css
.table-modern {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.table-modern thead {
  background: #f9fafb;
}

.table-modern th {
  padding: 16px;
  text-align: left;
  font-weight: 600;
  color: #374151;
  border-bottom: 2px solid #e5e7eb;
}

.table-modern td {
  padding: 16px;
  border-bottom: 1px solid #f3f4f6;
}

.table-modern tr:hover {
  background: #f9fafb;
}
```

---

## 6. Iconographie

### 6.1 Emojis Utilisés

| Contexte | Emoji | Code | Usage |
|----------|-------|------|-------|
| Éducation | 🎓 | U+1F393 | Logo, titres principaux |
| Administrateur | 👨‍💼 | U+1F468 U+200D U+1F4BC | Profil admin |
| Enseignant | 👨‍🏫 | U+1F468 U+200D U+1F3EB | Profil enseignant |
| Étudiant | 🎓 | U+1F393 | Profil étudiant |
| Saisie | 📝 | U+1F4DD | Saisie de notes |
| Calcul | 🧮 | U+1F9EE | Calculs automatiques |
| Consultation | 📊 | U+1F4CA | Statistiques, graphiques |
| Classes | 📚 | U+1F4DA | Gestion des classes |
| Matières | 📘 | U+1F4D8 | Gestion des matières |
| Périodes | 📅 | U+1F4C5 | Gestion des périodes |
| Inscriptions | 📋 | U+1F4CB | Gestion des inscriptions |
| Connexion | 🔐 | U+1F510 | Authentification |
| Dashboard | 🏠 | U+1F3E0 | Page d'accueil |
| Paramètres | ⚙️ | U+2699 | Configuration |

### 6.2 Taille des Icônes

- **Petite** : `text-2xl` (24px) - Inline avec texte
- **Moyenne** : `text-4xl` (36px) - Cards, boutons
- **Grande** : `text-6xl` (60px) - Hero sections
- **Extra Large** : `text-7xl` (72px) - Sections principales

---

## 7. Animations et Transitions

### 7.1 Animations CSS

#### Fade In Up
```css
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}
```

#### Pulse
```css
@keyframes pulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}
```

#### Float
```css
@keyframes float {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-20px);
  }
}
```

#### Gradient Shift
```css
@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
```

### 7.2 Transitions

**Standard** :
```css
transition: all 0.3s ease;
```

**Smooth** :
```css
transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
```

**Rapide** :
```css
transition: all 0.2s ease-in-out;
```

### 7.3 Hover Effects

**Élévation** :
```css
transform: translateY(-4px);
box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
```

**Scale** :
```css
transform: scale(1.05);
```

**Rotation** :
```css
transform: rotate(6deg);
```

---

## 8. Responsive Design

### 8.1 Breakpoints

```css
/* Mobile First */
@media (min-width: 640px) { /* sm */ }
@media (min-width: 768px) { /* md */ }
@media (min-width: 1024px) { /* lg */ }
@media (min-width: 1280px) { /* xl */ }
@media (min-width: 1536px) { /* 2xl */ }
```

### 8.2 Grilles Responsives

**Mobile** : 1 colonne
```html
<div class="grid grid-cols-1 gap-4">
```

**Tablet** : 2 colonnes
```html
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
```

**Desktop** : 3 colonnes
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
```

### 8.3 Typographie Responsive

```css
/* Titres adaptatifs */
.hero-title {
  font-size: clamp(2rem, 5vw, 4rem);
}

.section-title {
  font-size: clamp(1.5rem, 3vw, 2.5rem);
}
```

---

## 9. Accessibilité

### 9.1 Contraste

**Minimum WCAG AA** :
- Texte normal : 4.5:1
- Texte large : 3:1

**Combinaisons Validées** :
- ✅ Navy (#1e3a8a) sur Blanc (#ffffff) - 12.6:1
- ✅ Gray-800 (#1f2937) sur Blanc - 14.7:1
- ✅ Blanc sur Navy - 12.6:1
- ✅ Blanc sur Emerald-600 (#059669) - 4.8:1

### 9.2 Focus Visible

```css
*:focus {
  outline: 2px solid #2563eb;
  outline-offset: 2px;
}

button:focus-visible {
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
}
```

### 9.3 Reduced Motion

```css
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

---

## 10. Exemples d'Application

### 10.1 Page d'Accueil

**Hero Section** :
- Background : Gradient animé
- Titre : Outfit 56px Bold Navy
- Bouton CTA : Emerald gradient avec pulse
- Espacement : py-20 (80px vertical)

**Section 3 Étapes** :
- Cards : Premium avec hover scale
- Badges : Colorés par étape (Bleu, Vert, Violet)
- Icônes : 72px avec rotation au hover

### 10.2 Dashboard

**Stat Cards** :
- Border-left : 4px coloré par catégorie
- Nombre : 36px gradient text
- Hover : translateY(-5px) + shadow-xl

**Navigation** :
- Glassmorphism navbar fixée en haut
- Logo animé au hover (scale + rotate)
- Active state : Souligné avec accent color

### 10.3 Formulaires

**Login Page** :
- Card centrée avec shadow-2xl
- Inputs : Focus avec border-blue + shadow
- Bouton : Gradient avec hover lift
- Messages d'erreur : Rouge avec icône ⚠️

---

## 11. Règles d'Usage

### 11.1 À Faire ✅

- Utiliser les classes Tailwind prédéfinies
- Respecter la palette de couleurs
- Appliquer les animations avec parcimonie
- Tester sur mobile, tablette et desktop
- Vérifier le contraste des couleurs
- Utiliser les emojis de manière cohérente

### 11.2 À Éviter ❌

- Mélanger trop de couleurs sur une même page
- Animations trop rapides ou agressives
- Texte trop petit (< 14px)
- Contraste insuffisant
- Boutons sans état hover
- Ignorer le responsive design

---

## 12. Ressources

### 12.1 Fichiers de Référence

- **CSS Principal** : `assets/css/style.css`
- **JavaScript** : `assets/js/animations.js`
- **Tailwind Config** : Intégré dans `includes/header.php`

### 12.2 Outils Recommandés

- **Palette** : [Coolors.co](https://coolors.co)
- **Contraste** : [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)
- **Fonts** : [Google Fonts](https://fonts.google.com)
- **Icons** : [Emojipedia](https://emojipedia.org)

---

**Version** : 1.0  
**Date** : Janvier 2026  
**Projet** : Système de Gestion des Notes IUA
