<?php
// =================================================================
// 1. DÉMARRAGE DE SESSION ET FONCTIONS PHP
// =================================================================
session_start();

/**
 * Génère une chaîne de caractères aléatoire et sécurisée.
 * @param int $length Longueur souhaitée (par défaut 12).
 * @return string Mot de passe généré.
 */
function generateSecurePassword(int $length = 12): string {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+=-{}[]:;<,>.?/~';
    $password = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, $max)];
    }
    return $password;
}

// =================================================================
// 2. DONNÉES ET INITIALISATION
// =================================================================
$userId = 15;
$roles = ['Public', 'Membre', 'Administrateur', 'Super Admin'];

// Données utilisateur simulées ÉTENDUES
$userData = [
    'id' => $userId,
    'username' => 'heleneL15', // NOUVEAU champ
    'prenom' => 'Hélène',
    'nom' => 'Lefevre',
    'email' => 'helene.l15@app.com',
    'role' => 'Administrateur',
    'telephone' => '06 12 34 56 78',
    'adresse' => '12 Rue des Champs',
    'ville' => 'Paris',
    'code_postal' => '75001',
    'date_naissance' => '1985-10-25',
    'genre' => 'Femme',
    'q_securite_1' => '',
    'langue_preferee' => 'fr',
];
$userData['initial'] = strtoupper(substr($userData['prenom'], 0, 1));

$message = '';
$generatedPassword = '';
$submittedSection = 'Identité & Sécurité'; // Onglet actif par défaut (A)

// =================================================================
// 3. GESTION DES REQUÊTES (GÉNÉRATION / SAUVEGARDE)
// =================================================================

// A. Gérer la requête de GÉNÉRATION (méthode GET via le bouton)
if (isset($_GET['action']) && $_GET['action'] === 'generate_password_request') {
    $newPass = generateSecurePassword(16);
    $_SESSION['generated_password'] = $newPass;
    header("Location: edit_user.php");
    exit;
}

// B. Récupérer le mot de passe généré de la session
if (isset($_SESSION['generated_password'])) {
    $generatedPassword = $_SESSION['generated_password'];
    unset($_SESSION['generated_password']);
    $message = '<div class="alert alert-warning" role="alert">🔑 Un nouveau mot de passe a été généré et inséré ci-dessous. Pensez à **Enregistrer** le compte pour le valider.</div>';
}

// C. Gérer la soumission du formulaire d'ENREGISTREMENT (méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submitted_section'])) {
        $submittedSection = htmlspecialchars($_POST['submitted_section']);
    }

    // --- MISE À JOUR DES DONNÉES (Simulation) ---
    // Les champs Nom et Prénom peuvent être mis à jour depuis n'importe quelle section
    $userData['prenom'] = htmlspecialchars($_POST['prenom'] ?? $userData['prenom']);
    $userData['nom'] = htmlspecialchars($_POST['nom'] ?? $userData['nom']); // Assurez-vous de capturer Nom aussi
    $userData['username'] = htmlspecialchars($_POST['username'] ?? $userData['username']); // Capture du Username
    $userData['role'] = htmlspecialchars($_POST['role'] ?? $userData['role']);
    $userData['initial'] = strtoupper(substr($userData['prenom'], 0, 1));

    $passwordChanged = false;
    if ($submittedSection === 'Identité & Sécurité' && !empty($_POST['password'])) {
        $passwordChanged = true;
    }

    $actionMessage = $passwordChanged ? 'Mot de passe changé !' : 'Informations sauvegardées.';
    $message = '<div class="alert alert-success" role="alert">✅ Section **' . $submittedSection . '** mise à jour. ' . $actionMessage . '</div>';
}

// =================================================================
// 4. LOGIQUE D'ACTIVATION DES ONGLET APRÈS POST
// =================================================================
$activeTab = 'one'; // Par défaut
if ($submittedSection === 'Identité & Sécurité') {
    $activeTab = 'one';
} elseif ($submittedSection === 'Informations Personnelles & Contact') {
    $activeTab = 'two';
} elseif ($submittedSection === 'Questions de Sécurité') {
    $activeTab = 'three';
} elseif ($submittedSection === 'Paramètres') {
    $activeTab = 'four';
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer Compte Utilisateur (4 Onglets)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .avatar {
            width: 100px; height: 100px; border-radius: 50%;
            background-color: #0d6efd; color: white;
            display: flex; justify-content: center; align-items: center;
            font-size: 40px; font-weight: bold; margin-bottom: 20px;
        }
        .card-apercu {
            background-color: #f8f9fa;
        }
        .nav-link.active {
            font-weight: bold;
            color: #0d6efd !important;
            border-bottom: 3px solid #0d6efd !important;
        }
        .tab-content {
            border: 1px solid #dee2e6;
            border-top: none;
            padding: 20px;
            border-radius: 0 0 .25rem .25rem;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <header class="mb-4">
        <h1 class="display-5">✏️ Éditer le Compte Utilisateur</h1>
    </header>

    <?php echo $message; ?>

    <div class="row g-4">

        <div class="col-lg-4">
            <div class="card shadow-lg card-apercu p-4 h-100 d-flex flex-column justify-content-start align-items-center">

                <h2 class="h5 mb-4 text-center">1. Aperçu de l'Utilisateur</h2>
                <div class="avatar"><?php echo $userData['initial']; ?></div>

                <div class="text-center w-100">
                    <h4 class="mb-1"><?php echo $userData['prenom'] . ' ' . $userData['nom']; ?></h4>
                    <p class="text-muted small mb-3">ID: <?php echo $userData['id']; ?></p>

                    <ul class="list-group list-group-flush text-start mb-4">
                        <li class="list-group-item bg-transparent">📧 **Email:** <?php echo $userData['email']; ?></li>
                        <li class="list-group-item bg-transparent">👤 **Rôle Actuel:** <span class="badge bg-primary"><?php echo $userData['role']; ?></span></li>
                    </ul>

                    <button class="btn btn-outline-secondary btn-sm">
                        Mettre à jour l'image de profil
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-lg p-4 h-100">
                <div class="card-body">
                    <h2 class="h5 mb-4">2. Informations du Compte à Éditer</h2>

                    <form action="edit_user.php" method="POST">

                        <input type="hidden" name="user_id" value="<?php echo $userData['id']; ?>">

                        <ul class="nav nav-tabs" id="userInfoTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo ($activeTab === 'one' ? 'active' : ''); ?>" id="tab-one" data-bs-toggle="tab" data-bs-target="#content-one" type="button" role="tab" aria-controls="content-one" aria-selected="true">
                                    A. 🔑 Sécurité & Identifiant
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo ($activeTab === 'two' ? 'active' : ''); ?>" id="tab-two" data-bs-toggle="tab" data-bs-target="#content-two" type="button" role="tab" aria-controls="content-two" aria-selected="false">
                                    B. 🧑 Infos Civiles & Contact
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo ($activeTab === 'three' ? 'active' : ''); ?>" id="tab-three" data-bs-toggle="tab" data-bs-target="#content-three" type="button" role="tab" aria-controls="content-three" aria-selected="false">
                                    C. 🔒 Questions de Sécurité
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo ($activeTab === 'four' ? 'active' : ''); ?>" id="tab-four" data-bs-toggle="tab" data-bs-target="#content-four" type="button" role="tab" aria-controls="content-four" aria-selected="false">
                                    D. ⚙️ Paramètres
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="userInfoTabContent">

                            <div class="tab-pane fade <?php echo ($activeTab === 'one' ? 'show active' : ''); ?>" id="content-one" role="tabpanel" aria-labelledby="tab-one">
                                <div class="mt-3">
                                    <input type="hidden" name="submitted_section" value="Identité & Sécurité">

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="username" class="form-label">Nom d'utilisateur (Username)</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $userData['username']; ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $userData['email']; ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="role" class="form-label">Rôle / Niveau d'Accès</label>
                                            <select class="form-select" id="role" name="role" required>
                                                <?php
                                                foreach ($roles as $role) {
                                                    $selected = ($role === $userData['role']) ? 'selected' : '';
                                                    echo '<option value="' . $role . '" ' . $selected . '>' . $role . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password" class="form-label">Nouveau Mot de Passe (Laisser vide si inchangé)</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($generatedPassword); ?>">
                                                <a href="?action=generate_password_request" class="btn btn-primary" title="Générer un mot de passe aléatoire et sécurisé">
                                                    🔑 Générer
                                                </a>
                                            </div>
                                        </div>
                                        <input type="hidden" name="prenom" value="<?php echo $userData['prenom']; ?>">
                                        <input type="hidden" name="nom" value="<?php echo $userData['nom']; ?>">
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-sm btn-success">💾 Enregistrer Section A</button>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade <?php echo ($activeTab === 'two' ? 'show active' : ''); ?>" id="content-two" role="tabpanel" aria-labelledby="tab-two">
                                <div class="mt-3">
                                    <input type="hidden" name="submitted_section" value="Informations Personnelles & Contact">

                                    <h4 class="h6 text-primary mb-3">Identité Civile</h4>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label for="prenom" class="form-label">Prénom</label>
                                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $userData['prenom']; ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nom" class="form-label">Nom</label>
                                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $userData['nom']; ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="date_naissance" class="form-label">Date de Naissance</label>
                                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?php echo $userData['date_naissance']; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="genre" class="form-label">Genre</label>
                                            <select class="form-select" id="genre" name="genre">
                                                <option value="">Sélectionner...</option>
                                                <option value="Homme" <?php echo ($userData['genre'] === 'Homme') ? 'selected' : ''; ?>>Homme</option>
                                                <option value="Femme" <?php echo ($userData['genre'] === 'Femme') ? 'selected' : ''; ?>>Femme</option>
                                                <option value="Autre" <?php echo ($userData['genre'] === 'Autre') ? 'selected' : ''; ?>>Autre</option>
                                            </select>
                                        </div>
                                        <input type="hidden" name="username" value="<?php echo $userData['username']; ?>">
                                        <input type="hidden" name="email" value="<?php echo $userData['email']; ?>">
                                        <input type="hidden" name="role" value="<?php echo $userData['role']; ?>">
                                    </div>

                                    <h4 class="h6 text-primary mb-3 mt-4 pt-3 border-top">Coordonnées</h4>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="telephone" class="form-label">Numéro de Téléphone</label>
                                            <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo $userData['telephone']; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="adresse" class="form-label">Adresse</label>
                                            <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo $userData['adresse']; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="ville" class="form-label">Ville</label>
                                            <input type="text" class="form-control" id="ville" name="ville" value="<?php echo $userData['ville']; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="code_postal" class="form-label">Code Postal</label>
                                            <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?php echo $userData['code_postal']; ?>">
                                        </div>
                                        <div class="col-12 mt-4">
                                            <label for="notes_admin" class="form-label">Notes Administrateur (Questions liées au compte)</label>
                                            <textarea class="form-control" id="notes_admin" name="notes_admin" rows="3">Informations spécifiques...</textarea>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-sm btn-success">💾 Enregistrer Section B</button>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade <?php echo ($activeTab === 'three' ? 'show active' : ''); ?>" id="content-three" role="tabpanel" aria-labelledby="tab-three">
                                <div class="mt-3">
                                    <input type="hidden" name="submitted_section" value="Questions de Sécurité">

                                    <input type="hidden" name="username" value="<?php echo $userData['username']; ?>">
                                    <input type="hidden" name="prenom" value="<?php echo $userData['prenom']; ?>">
                                    <input type="hidden" name="nom" value="<?php echo $userData['nom']; ?>">
                                    <input type="hidden" name="email" value="<?php echo $userData['email']; ?>">
                                    <input type="hidden" name="role" value="<?php echo $userData['role']; ?>">

                                    <p class="text-muted small">Ces questions sont utilisées pour la récupération du mot de passe en cas d'oubli.</p>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="q_securite_1" class="form-label">Question de sécurité 1 : Nom de votre premier animal ?</label>
                                            <input type="text" class="form-control" id="q_securite_1" name="q_securite_1" placeholder="Entrez la réponse secrète (sensible à la casse)" value="<?php echo $userData['q_securite_1']; ?>">
                                        </div>
                                        <div class="col-12">
                                            <label for="q_securite_2" class="form-label">Question de sécurité 2 : Ville de naissance de votre mère ?</label>
                                            <input type="text" class="form-control" id="q_securite_2" name="q_securite_2" placeholder="Entrez la réponse secrète (sensible à la casse)">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-sm btn-success">💾 Enregistrer Section C</button>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade <?php echo ($activeTab === 'four' ? 'show active' : ''); ?>" id="content-four" role="tabpanel" aria-labelledby="tab-four">
                                <div class="mt-3">
                                    <input type="hidden" name="submitted_section" value="Paramètres">

                                    <input type="hidden" name="username" value="<?php echo $userData['username']; ?>">
                                    <input type="hidden" name="prenom" value="<?php echo $userData['prenom']; ?>">
                                    <input type="hidden" name="nom" value="<?php echo $userData['nom']; ?>">
                                    <input type="hidden" name="email" value="<?php echo $userData['email']; ?>">
                                    <input type="hidden" name="role" value="<?php echo $userData['role']; ?>">

                                    <p class="text-muted small">Définissez les préférences d'interface et de communication de l'utilisateur.</p>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="langue_preferee" class="form-label">Langue de l'interface</label>
                                            <select class="form-select" id="langue_preferee" name="langue_preferee">
                                                <option value="fr" <?php echo ($userData['langue_preferee'] === 'fr' ? 'selected' : ''); ?>>Français</option>
                                                <option value="en" <?php echo ($userData['langue_preferee'] === 'en' ? 'selected' : ''); ?>>Anglais</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="theme" class="form-label">Thème d'affichage</label>
                                            <select class="form-select" id="theme" name="theme">
                                                <option value="light">Clair (Light)</option>
                                                <option value="dark">Foncé (Dark)</option>
                                            </select>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" checked>
                                                <label class="form-check-label" for="email_notifications">Recevoir les notifications par email</label>
                                            </div>
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                                <label class="form-check-label" for="newsletter">Abonnement à la newsletter</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-sm btn-success">💾 Enregistrer Section D</button>
                                    </div>
                                </div>
                            </div>

                        </div> </form>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="users_cards.php" class="btn btn-secondary">Annuler / Retour</a>
                    </div>
                </div>
            </div>
        </div>

    </div> </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>