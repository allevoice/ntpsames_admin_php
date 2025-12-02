<?php
// index.php

require_once 'config.php'; // Inclut la fonction connectDB()

$pdo = connectDB();
$url = "/"; // Base de l'URL pour la redirection

// -----------------------------------------------------------
// 1. GESTION DU FILTRAGE ET DE LA PAGINATION
// -----------------------------------------------------------

$elements_per_page = 10;
$search_term = $_GET['search_term'] ?? '';
$search_term = trim($search_term);

// Construction de la clause WHERE et des paramètres (requêtes préparées)
$where_clauses = ["deleted_at IS NULL"]; // Ignorer les soft deletes
$params = [];

if (!empty($search_term)) {
    // On recherche dans le fullname, email, subject, et content
    $where_clauses[] = "(fullname LIKE :search OR email LIKE :search OR subject LIKE :search OR content LIKE :search)";
    $params['search'] = '%' . $search_term . '%';
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(' AND ', $where_clauses) : "";


// -----------------------------------------------------------
// 2. COMPTER LE TOTAL (pour la pagination)
// -----------------------------------------------------------

$count_sql = "SELECT COUNT(id) AS total FROM contacts " . $where_sql; // Assurez-vous que la table s'appelle 'contacts'
$stmt_count = $pdo->prepare($count_sql);
$stmt_count->execute($params);

$total_mails = $stmt_count->fetch()['total'];
$total_pages = ceil($total_mails / $elements_per_page);
$total_pages = max(1, $total_pages);

$current_page = intval($_GET['page'] ?? 1);
$current_page = max(1, min($current_page, $total_pages));

$start_index = ($current_page - 1) * $elements_per_page;


// -----------------------------------------------------------
// 3. RÉCUPÉRER LES DONNÉES DE LA PAGE ACTUELLE
// -----------------------------------------------------------

$limit_sql = " LIMIT :start_index, :elements_per_page";
$order_sql = " ORDER BY created_at DESC"; // Tri par date de création (le plus récent en premier)

$sql = "SELECT id, fullname, email, subject, content, is_read, created_at FROM contacts "
    . $where_sql
    . $order_sql
    . $limit_sql;

$stmt = $pdo->prepare($sql);

// Bind des paramètres pour la recherche (si elle existe)
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

// Bind des paramètres de pagination (doit être un entier)
$stmt->bindValue('start_index', $start_index, PDO::PARAM_INT);
$stmt->bindValue('elements_per_page', $elements_per_page, PDO::PARAM_INT);

$stmt->execute();
$mails_to_display = $stmt->fetchAll();


// Préparer les paramètres GET pour les liens de pagination
$query_params = http_build_query(['search_term' => $search_term]);
$base_pagination_url = 'index.php?' . $query_params . '&page=';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boîte de Réception Filtrée & Paginée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
        }
        .list-group-item-action:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="mb-4">📧 Boîte de Réception (<?= $total_mails ?> Mails)</h2>

    <form method="GET" action="index.php" class="d-flex mb-4">
        <input type="hidden" name="page" value="<?= $current_page ?>">
        <input type="text"
               name="search_term"
               class="form-control me-2"
               placeholder="Rechercher par Sujet, Expéditeur ou Contenu..."
               value="<?= htmlspecialchars($search_term); ?>">
        <button type="submit" class="btn btn-primary">Rechercher</button>
        <?php if (!empty($search_term)): ?>
            <a href="index.php" class="btn btn-secondary ms-2">Réinitialiser</a>
        <?php endif; ?>
    </form>

    <div class="list-group list-group-flush border rounded shadow-sm">

        <?php
        if (empty($mails_to_display)): ?>
            <div class="alert alert-info text-center m-0 py-4">
                <?php if (!empty($search_term)): ?>
                    Aucun résultat trouvé pour la recherche "<?= htmlspecialchars($search_term); ?>".
                <?php else: ?>
                    Votre boîte de réception est vide.
                <?php endif; ?>
            </div>
            <?php
        endif;

        // Boucle sur les MAILS DE LA PAGE ACTUELLE
        foreach ($mails_to_display as $mail):

            // Adaptation des noms de colonnes : 'fullname' est l'expéditeur, 'content' est l'aperçu, 'created_at' est l'heure.

            $is_read = $mail['is_read'] > 0; // is_read est un int(100)
            $text_class = $is_read ? 'text-secondary' : 'text-dark';
            $is_strong = $is_read ? '' : '<strong>';
            $end_strong = $is_read ? '' : '</strong>';

            // Formatage de la date (simple)
            $time_display = date('H:i A', strtotime($mail['created_at']));

            $read_link = $url . 'mail_admin/index_read.php?id=' . $mail['id'];
            ?>

            <a href="<?= htmlspecialchars($read_link) ?>" class="list-group-item list-group-item-action py-3">
                <div class="d-flex w-100 justify-content-between">
                    <div class="form-check me-3 d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" value="<?= $mail['id'] ?>" id="mailCheck_<?= $mail['id'] ?>">
                        <label class="form-check-label ms-3" for="mailCheck_<?= $mail['id'] ?>">
                            <span class="<?= $text_class ?>"><?= $is_strong . htmlspecialchars($mail['fullname'] ?? $mail['email']) . $end_strong ?></span>
                        </label>
                    </div>
                    <small class="text-muted text-end"><?= $time_display ?></small>
                </div>

                <p class="mb-1 ps-4 <?= $text_class ?>">
                    <?= $is_strong ?>
                    Sujet : <?= htmlspecialchars($mail['subject']) ?>
                    <?= $end_strong ?>
                </p>

                <small class="text-muted d-block ps-4 text-truncate">
                    <?= htmlspecialchars(substr($mail['content'], 0, 100)) ?>...
                </small>
            </a>

        <?php endforeach; ?>

    </div>

    <?php if ($total_pages > 1): ?>

        <nav class="mt-4">
            <ul class="pagination justify-content-center">

                <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= htmlspecialchars($base_pagination_url . ($current_page - 1)) ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php
                    // Afficher seulement les pages proches
                    if ($i >= $current_page - 2 && $i <= $current_page + 2):
                        ?>
                        <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= htmlspecialchars($base_pagination_url . $i) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= htmlspecialchars($base_pagination_url . ($current_page + 1)) ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>

            </ul>
        </nav>

    <?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>