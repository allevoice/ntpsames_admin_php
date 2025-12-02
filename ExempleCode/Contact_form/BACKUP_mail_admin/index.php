<?php
$url = "../";
include $url.'include/header.php';
?>


<?php
// Ajoutez plus d'éléments (au moins 15-20) pour tester correctement la pagination
$mails = [
    [
        'sender' => 'Support Technique',
        'subject' => 'Problème de connexion après la mise à jour.',
        'preview' => 'Nous avons identifié un bug critique sur le module d\'authentification...',
        'time' => '10:30 AM',
        'is_read' => false,
        'id' => 1
    ],
    [
        'sender' => 'Marketing & Co',
        'subject' => 'Nouvelle offre spéciale pour le Black Friday.',
        'preview' => 'Salut ! Nous préparons notre plus grande vente de l\'année et voulions...',
        'time' => 'Hier',
        'is_read' => true,
        'id' => 2
    ],
    [
        'sender' => 'Comptabilité',
        'subject' => 'Facture en retard [URGENT].',
        'preview' => 'Veuillez procéder au règlement de la facture A409 avant la fin du mois.',
        'time' => '11:00 AM',
        'is_read' => false,
        'id' => 3
    ],
    [
        'sender' => 'Support Technique',
        'subject' => 'Demande d\'assistance #1234.',
        'preview' => 'J\'ai un souci avec l\'export des données en format CSV.',
        'time' => '14:45 PM',
        'is_read' => true,
        'id' => 4
    ],
    [
        'sender' => 'Projets',
        'subject' => 'Mise à jour du calendrier de déploiement.',
        'preview' => 'Le jalon 3 est reporté à la semaine prochaine en raison d\'un blocage sur le serveur.',
        'time' => '15:15 PM',
        'is_read' => false,
        'id' => 5
    ],
    [
        'sender' => 'Marketing & Co',
        'subject' => 'Statistiques de la campagne d\'emails.',
        'preview' => 'Le taux d\'ouverture a atteint 45%, ce qui est un excellent résultat !',
        'time' => '16:00 PM',
        'is_read' => false,
        'id' => 6
    ],
    [
        'sender' => 'Ressources Humaines',
        'subject' => 'Rappel : Entretien annuel d\'évaluation.',
        'preview' => 'N\'oubliez pas de remplir votre grille d\'auto-évaluation avant mercredi.',
        'time' => 'Mardi',
        'is_read' => false,
        'id' => 7
    ],
    [
        'sender' => 'Client XYZ',
        'subject' => 'Demande de fonctionnalité',
        'preview' => 'Serait-il possible d\'ajouter un filtre par date de création dans l\'interface ?',
        'time' => 'Lundi',
        'is_read' => false,
        'id' => 8
    ],
    // Ajoutez plus de mails ici pour que la pagination fonctionne (simulez des IDs 9 à 20)
    // ...
];

// Répétition des données pour atteindre plus de 10 éléments
for ($i = 9; $i <= 20; $i++) {
    $mails[] = [
        'sender' => 'Test ' . ($i % 3 == 0 ? 'Bug' : 'Info'),
        'subject' => 'Mail de test N°' . $i,
        'preview' => 'Contenu générique pour la pagination.',
        'time' => 'Hier ' . $i . ':00',
        'is_read' => ($i % 4 == 0),
        'id' => $i
    ];
}

// Assumons que $url est la base de votre application
//$url = "/";

// -----------------------------------------------------------
// 2. LOGIQUE DE FILTRAGE
// -----------------------------------------------------------

$search_term = $_GET['search_term'] ?? '';
$search_term = trim($search_term);

$filtered_mails = $mails;

if (!empty($search_term)) {
    $term_lower = strtolower($search_term);

    $filtered_mails = array_filter($mails, function($mail) use ($term_lower) {
        $content = strtolower($mail['sender'] . ' ' . $mail['subject'] . ' ' . $mail['preview']);
        // Vérifie si le terme est trouvé
        return str_contains($content, $term_lower);
    });
}
// IMPORTANT : Réinitialiser les clés numériques après le filtre
$filtered_mails = array_values($filtered_mails);


// -----------------------------------------------------------
// 3. LOGIQUE DE PAGINATION
// -----------------------------------------------------------

$elements_per_page = 10;
$total_mails = count($filtered_mails);
$total_pages = ceil($total_mails / $elements_per_page);
$total_pages = max(1, $total_pages); // Assure qu'il y a au moins 1 page si la liste est vide

$current_page = intval($_GET['page'] ?? 1);
$current_page = max(1, min($current_page, $total_pages)); // Assure que la page est dans les limites [1, total_pages]

// Calculer l'index de début
$start_index = ($current_page - 1) * $elements_per_page;

// Appliquer la pagination (récupérer seulement les éléments de la page actuelle)
$mails_to_display = array_slice($filtered_mails, $start_index, $elements_per_page);

// Préparer les paramètres GET pour les liens de pagination
// On conserve le terme de recherche dans tous les liens
$query_params = http_build_query(['search_term' => $search_term]);
$base_pagination_url = 'index.php?' . $query_params . '&page=';

?>

    <div class="pagetitle">
        <h1>Dashboard</h1>  <?php echo '20';?>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">

                            <div class="card-body">

                                <?php include $url."mail_admin/content.php"?>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->

                </div>
            </div><!-- End Left side columns -->

            <?php include $url."include/sidebare_right.php"?>

        </div>
    </section>

<?php include $url.'include/footer.php';?>