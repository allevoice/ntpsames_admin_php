<?php
// =================================================================
// 1. GÉNÉRATION ET COMPTAGE DES UTILISATEURS
// =================================================================

$users = [];
// Tableau de comptage en deux dimensions : [Rôle] => [Statut] => Nombre
$roleStatusCounts = [
    'Administrateur' => ['En Ligne' => 0, 'Hors Ligne' => 0],
    'Utilisateur' => ['En Ligne' => 0, 'Hors Ligne' => 0],
    'public' => ['En Ligne' => 0, 'Hors Ligne' => 0]
];

$firstNames = ['Alexandre', 'Béatrice', 'Charles', 'Denise', 'Éric', 'Fanny', 'Gabriel', 'Hélène', 'Ivan', 'Julie'];
$lastNames = ['Lefevre', 'Moreau', 'Dubois', 'Thomas', 'Bernard', 'Petit', 'Robert', 'Richard', 'Durand', 'Leroy'];
$roles = ['Administrateur', 'Utilisateur', 'public'];
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


        <?php
        // Boucle sur le tableau croisé
        foreach ($roleStatusCounts as $role => $counts) {
            $totalRoleCount = $counts['En Ligne'] + $counts['Hors Ligne'];
            ?>


            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card" >



                    <div class="card-body">
                        <h5 class="card-title"><?php echo $role; ?> <span>| <?php echo $totalRoleCount; ?></span></h5>

                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <?php if($role=='Administrateur') : ?>
                                <i class="bi bi-person-circle" title="<?php echo $role; ?>"></i>
                                <?php elseif($role=='Utilisateur') : ?>
                                <i class="bi bi-person-gear" title="<?php echo $role; ?>"></i>
                                    <?php else : ?>
                                <i class="bi bi-person-dash" title="<?php echo $role; ?>"></i>
                                <?php endif; ?>
                            </div>
                            <div class="ps-3">
                                <h6><span class="badge bg-success text-white me-1"><?php echo $counts['En Ligne']; ?></span></h6>

                                <span class="text-muted small pt-2 ps-1">
                                    <?php echo  $counts['Hors Ligne']; ?>
                                </span>

                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Sales Card -->

        <?php } ?>

