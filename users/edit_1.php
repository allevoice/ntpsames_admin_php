<?php
// =================================================================
// 1. GÉNÉRATION ET COMPTAGE DES UTILISATEURS
// =================================================================

$users = [];
// Tableau de comptage en deux dimensions : [Rôle] => [Statut] => Nombre
$roleStatusCounts = [
    'Administrateur' => ['En Ligne' => 0, 'Hors Ligne' => 0],
    'Éditeur' => ['En Ligne' => 0, 'Hors Ligne' => 0],
    'Lecteur' => ['En Ligne' => 0, 'Hors Ligne' => 0]
];

$firstNames = ['Alexandre', 'Béatrice', 'Charles', 'Denise', 'Éric', 'Fanny', 'Gabriel', 'Hélène', 'Ivan', 'Julie'];
$lastNames = ['Lefevre', 'Moreau', 'Dubois', 'Thomas', 'Bernard', 'Petit', 'Robert', 'Richard', 'Durand', 'Leroy'];
$roles = ['Administrateur', 'Éditeur', 'Lecteur'];
$numUsers = 40;
$totalUsers = 0;

for ($i = 1; $i <= $numUsers; $i++) {
    $firstName = $firstNames[array_rand($firstNames)];
    $lastName = $lastNames[array_rand($lastNames)];

    $role = $roles[array_rand($roles)];

    $email = strtolower(substr($firstName, 0, 1) . $lastName . $i . '@app.com');
    $email = str_replace(' ', '', $email);

    $isOnline = (rand(1, 10) <= 3);

    if ($isOnline) {
        $statusText = "En Ligne";
        $statusClass = "status-online-fonce";
        $statusIcon = "🟢";
        $lastSeen = "Connecté depuis " . rand(1, 59) . " min";
    } else {
        $statusText = "Hors Ligne";
        $statusClass = "status-offline-grise";
        $statusIcon = "⚪";

        $offlineTimeType = rand(1, 3);
        if ($offlineTimeType === 1) {
            $lastSeen = "Il y a " . rand(1, 23) . " heures";
        } elseif ($offlineTimeType === 2) {
            $lastSeen = "Il y a " . rand(1, 29) . " jours";
        } else {
            $lastSeen = "Il y a " . rand(1, 11) . " mois";
        }
    }

    // NOUVEAU : Incrémentation du compteur croisé
    $roleStatusCounts[$role][$statusText]++;
    $totalUsers++;

    $initial = strtoupper(substr($firstName, 0, 1));

    $users[] = [
        'id' => $i,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'isOnline' => $isOnline,
        'statusText' => $statusText,
        'statusClass' => $statusClass,
        'statusIcon' => $statusIcon,
        'lastSeen' => $lastSeen,
        'initial' => $initial
    ];
}

// =================================================================
// 2. AFFICHAGE HTML/BOOTSTRAP
// =================================================================
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        /* =================================================== */
        /* STYLES SPÉCIFIQUES POUR ONLINE / OFFLINE */
        /* =================================================== */
        /* Vert Foncé pour "En Ligne" */
        .status-online-fonce {
            background-color: #006400 !important; /* Vert Forêt / Dark Green */
            color: white !important;
            font-weight: bold;
        }

        /* Gris Grisé (plus clair) pour "Hors Ligne" */
        .status-offline-grise {
            background-color: #dcdcdc !important; /* Gris Poussière / Gainsboro */
            color: #495057 !important; /* Texte gris foncé pour la lisibilité */
        }

        /* Styles généraux des éléments (Avatar, Cartes, Rôles) */
        .avatar {
            width: 60px; height: 60px; border-radius: 50%;
            background-color: #0d6efd; color: white;
            display: flex; justify-content: center; align-items: center;
            font-size: 24px; font-weight: bold; margin-right: 15px;
            flex-shrink: 0;
        }
        .user-card:hover {
            transform: scale(1.02);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        /* Couleurs des badges de rôle */
        .badge-admin { background-color: #dc3545; }
        .badge-editor { background-color: #ffc107; color: #333; }
        .badge-viewer { background-color: #0d6efd; }
    </style>
</head>
<body>

<div class="container-fluid p-4">

    <header class="mb-4">
        <h1 class="display-5">👤 Panneau d'Administration</h1>
        <p class="lead text-muted">Affiche le décompte détaillé par rôle et statut pour les **<?php echo $totalUsers; ?> utilisateurs**.</p>
    </header>

    <hr>

    <h2 class="h4 mb-3">📊 Décompte Détaillé (Rôle & Statut)</h2>
    <div class="row mb-5 g-3">

        <?php
        // Boucle sur le tableau croisé
        foreach ($roleStatusCounts as $role => $counts) {
            $totalRoleCount = $counts['En Ligne'] + $counts['Hors Ligne'];

            // Déterminer la couleur principale de la carte pour le rôle
            $cardBgClass = 'bg-light';
            $roleBadgeClass = 'badge-viewer';
            if ($role === 'Administrateur') {
                $cardBgClass = 'bg-danger-subtle';
                $roleBadgeClass = 'badge-admin';
            } elseif ($role === 'Éditeur') {
                $cardBgClass = 'bg-warning-subtle';
                $roleBadgeClass = 'badge-editor';
            }
            ?>
            <div class="col-md-4">
                <div class="card shadow-lg border-0 <?php echo $cardBgClass; ?> h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0 fw-bold"><?php echo $role; ?></h4>
                            <span class="badge rounded-pill fs-5 p-2 <?php echo $roleBadgeClass; ?> text-white">
                                    <?php echo $totalRoleCount; ?>
                                </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span class="fw-semibold">🟢 En Ligne :</span>
                            <span class="badge status-online-fonce fs-6 p-2">
                                    <?php echo $counts['En Ligne']; ?>
                                </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span class="fw-semibold">⚪ Hors Ligne :</span>
                            <span class="badge status-offline-grise fs-6 p-2">
                                    <?php echo $counts['Hors Ligne']; ?>
                                </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>

    <hr>

    <h2 class="h4 mb-3">Liste des Utilisateurs</h2>
    <div class="row g-4">
        <?php
        foreach ($users as $user) {
            ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm user-card">

                    <div class="card-body d-flex align-items-center">

                        <div class="avatar">
                            <?php echo $user['initial']; ?>
                        </div>

                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0"><?php echo $user['firstName'] . ' ' . $user['lastName']; ?></h5>
                            <p class="card-text text-muted small mb-1">
                                <?php echo $user['email']; ?>
                            </p>

                            <span class="badge <?php echo $user['statusClass']; ?>">
                                    <?php echo $user['statusIcon']; ?> <?php echo $user['statusText']; ?>
                                </span>
                            <span class="text-muted small ms-2">(<?php echo $user['lastSeen']; ?>)</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>