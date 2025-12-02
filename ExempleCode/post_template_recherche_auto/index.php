<?php
// ====================================================================
// PARTIE PHP : Génération des données simulées
// ====================================================================

function getPostsRecords(int $count = 1000): array
{
    $records = [];
    // Ajout de caractères accentués pour tester la normalisation
    $statuses = ['Publié', 'Brouillon', 'Archivé', 'En attente'];
    $authors = ['Admin', 'Support', 'Client', 'Développeur'];

    for ($i = 1; $i <= $count; $i++) {
        $records[] = [
            'id' => $i,
            'title' => ($i % 50 == 0) ? "Étude spéciale du Référencement N°{$i}" : "Record de Post N°{$i} : Taux de Lecture",
            'views' => rand(100, 15000),
            'likes' => rand(5, 1500),
            'status' => $statuses[array_rand($statuses)],
            'author' => $authors[array_rand($authors)],
            'date' => date('Y-m-d', strtotime("-{$i} days")),
        ];
    }
    return $records;
}

// Génère 1000 records
$all_posts_records = getPostsRecords(1000);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau des Records (Recherche Dynamique)</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <div class="content-wrapper" style="min-height: 80vh;">
        <section class="content-header">
            <div class="container-fluid">
                <h1>📊 Tableau des Records de Posts (<?php echo count($all_posts_records); ?> Éléments)</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <div class="row mb-3">
                    <div class="col-md-5">
                        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par titre, auteur, statut, etc. (Ex: Développeur ou Étude)">
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th style="width: 10px">ID</th>
                                <th>Titre du Record</th>
                                <th>Auteur</th>
                                <th>Vues</th>
                                <th>Likes</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody id="recordsBody">
                            <?php foreach ($all_posts_records as $record):
                                $status_class = match ($record['status']) {
                                'Publié' => 'badge bg-success',
                                        'Brouillon' => 'badge bg-warning',
                                        'En attente' => 'badge bg-info',
                                        default => 'badge bg-secondary',
                                    };
                                ?>
                                <tr
                                        class="record-row"
                                        data-id="<?php echo $record['id']; ?>"
                                        data-title="<?php echo htmlspecialchars($record['title']); ?>"
                                        data-author="<?php echo htmlspecialchars($record['author']); ?>"
                                        data-status="<?php echo htmlspecialchars($record['status']); ?>"
                                        data-date="<?php echo htmlspecialchars($record['date']); ?>"
                                        data-views="<?php echo $record['views']; ?>"
                                        data-likes="<?php echo $record['likes']; ?>"
                                >
                                    <td><?php echo $record['id']; ?></td>
                                    <td><?php echo htmlspecialchars($record['title']); ?></td>
                                    <td><?php echo htmlspecialchars($record['author']); ?></td>
                                    <td><?php echo number_format($record['views'], 0, ',', ' '); ?></td>
                                    <td><?php echo number_format($record['likes'], 0, ',', ' '); ?></td>
                                    <td><span class="<?php echo $status_class; ?>"><?php echo htmlspecialchars($record['status']); ?></span></td>
                                    <td><?php echo $record['date']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <nav aria-label="Pagination des Records">
                            <ul class="pagination justify-content-center" id="paginationControls">
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </section>
    </div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', () => {

        // --- 1. VARIABLES GLOBALES ET CONFIGURATION ---
        const allRows = Array.from(document.querySelectorAll('#recordsBody .record-row'));
    const paginationControls = document.getElementById('paginationControls');
    const searchInput = document.getElementById('searchInput');

    let currentPage = 1;
    const recordsPerPage = 10;
    const maxPagesToShow = 7;

    // --- 2. FONCTION DE NORMALISATION (Insensibilité accent/casse) ---

    /**
     * Normalise le texte: Supprime les accents et met tout en minuscule.
     */
    const normalizeText = (text) => {
        if (!text) return '';
        return text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "");
    };


    // --- 3. FONCTION UTILITAIRE ET PAGINATION ---

    const createButton = (text, pageNum, isDisabled = false, isActive = false) => {
        const li = document.createElement('li');
        li.className = `page-item ${isDisabled ? 'disabled' : ''} ${isActive ? 'active' : ''}`;

        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.innerHTML = text;

        if (!isDisabled && !isActive) {
            a.addEventListener('click', (e) => {
                e.preventDefault();
            currentPage = pageNum;
            applySearchAndPagination();
        });
        }
        li.appendChild(a);
        return li;
    };


    function setupPagination(totalItems) {
        paginationControls.innerHTML = '';
        const pageCount = Math.ceil(totalItems / recordsPerPage);

        if (pageCount <= 1) return;

        let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(pageCount, startPage + maxPagesToShow - 1);

        if (endPage - startPage + 1 < maxPagesToShow) {
            startPage = Math.max(1, endPage - maxPagesToShow + 1);
        }

        // Précédent
        paginationControls.appendChild(createButton('<i class="fas fa-chevron-left"></i> Précédent', currentPage - 1, currentPage === 1));

        // Numéros de page
        if (startPage > 1) {
            paginationControls.appendChild(createButton('1', 1));
            if (startPage > 2) paginationControls.appendChild(createButton('...', startPage, true));
        }

        for (let i = startPage; i <= endPage; i++) {
            paginationControls.appendChild(createButton(i.toString(), i, false, i === currentPage));
        }

        // Suivant
        if (endPage < pageCount) {
            if (endPage < pageCount - 1) paginationControls.appendChild(createButton('...', pageCount, true));
            paginationControls.appendChild(createButton(pageCount.toString(), pageCount));
        }

        paginationControls.appendChild(createButton('Suivant <i class="fas fa-chevron-right"></i>', currentPage + 1, currentPage === pageCount));
    }


    // --- 4. FONCTION D'AFFICHAGE ET DE PAGINATION (RECHERCHE DYNAMIQUE) ---

    function applySearchAndPagination() {
        // Terme de recherche normalisé
        const searchTerm = normalizeText(searchInput.value.trim());
        let currentFilteredRows = [];

        // 1. Filtrer les lignes (Recherche Dynamique)
        allRows.forEach(row => {
            let match = false;

        // Si le terme de recherche est vide, on considère qu'il y a correspondance
        if (searchTerm === '') {
            match = true;
        } else {
            // Parcourir TOUS les attributs data-* de la ligne
            const data = row.dataset;

            for (const key in data) {
                // Normaliser la valeur de la donnée (ex: 'Développeur', 'Publié')
                const normalizedValue = normalizeText(data[key]);

                // Vérifier si la valeur normalisée inclut le terme de recherche normalisé
                if (normalizedValue.includes(searchTerm)) {
                    match = true;
                    break; // Arrêter la boucle dès qu'une correspondance est trouvée
                }
            }
        }

        if (match) {
            currentFilteredRows.push(row);
        } else {
            row.style.display = 'none'; // Masquer la ligne
        }
    });

        const totalVisibleItems = currentFilteredRows.length;

        // 2. Appliquer la pagination sur les lignes filtrées
        const startIndex = (currentPage - 1) * recordsPerPage;
        const endIndex = startIndex + recordsPerPage;

        currentFilteredRows.forEach((row, index) => {
            // Afficher uniquement les lignes dans la plage de pagination
            if (index >= startIndex && index < endIndex) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });

        // 3. Gestion du message "Aucun résultat"
        const noResultsRow = document.getElementById('noResultsRow');
        if (totalVisibleItems === 0) {
            if (!noResultsRow) {
                const newRow = document.createElement('tr');
                newRow.id = 'noResultsRow';
                newRow.innerHTML = '<td colspan="7" class="text-center text-danger">Aucun record trouvé correspondant à la recherche.</td>';
                document.getElementById('recordsBody').appendChild(newRow);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }

        // 4. Mettre à jour les contrôles de pagination
        setupPagination(totalVisibleItems);
    }


    // --- 5. ÉVÉNEMENTS & INITIALISATION (Mode automatique) ---

    // GESTION DE LA RECHERCHE (automatique à chaque frappe)
    searchInput.addEventListener('input', () => {
        currentPage = 1; // Toujours revenir à la première page
    applySearchAndPagination();
    });

    // INITIALISATION (Affiche le premier jeu de données au chargement)
    applySearchAndPagination();
    });
</script>
</body>
</html>