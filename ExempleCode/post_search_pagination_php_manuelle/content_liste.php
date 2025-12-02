<?php
// Récupérer le terme de recherche depuis la requête GET
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$data->setSearchTerm($search_term);
$jobs = $data->readAll();
?>

<div class="col-lg-8">
    <div class="row">

        <style>
            /*            .bi_menu_perso {
                            !* Propriétés de Taille *!
                            width: 90%;
                            max-width: 1200px; !* Ne dépasse jamais 1200px sur les grands écrans *!
                            min-height: 500px;

                            !* Propriétés de Fond *!
                            background-color: #333;
                            padding: 5px;
                            border-radius: 5px;
                        }
                        .bi_menu_perso :hover{
                            !* Propriétés de Taille *!
                            width: 90%;
                            max-width: 1200px; !* Ne dépasse jamais 1200px sur les grands écrans *!
                            min-height: 500px;

                            !* Propriétés de Fond *!
                            background-color: #2472ca;
                            padding: 5px;
                            border-radius: 5px;
                        }*/

            .bi_menu_perso {
                background-color: #073896; /* Bleu Bootstrap Primary */
                color: white;
                padding: 10px 15px;
                border: none;
                cursor: pointer; /* Indique que l'élément est cliquable */
                transition: background-color 0.3s ease; /* Ajoute une transition pour un effet fluide */
                border-radius: 10px;
            }

            /* Style au survol (couleur au passage de la souris) */
            .bi_menu_perso:hover {
                background-color: #4f7fc1; /* Un bleu légèrement plus foncé pour le survol */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Ajoute une ombre légère */
            }

        </style>


        <style>
            .table-responsive {
                /* Si la table est enveloppée dans un div responsive */
                margin-left: auto;
                margin-right: auto;
            }
        </style>
        <!-- Recent Sales -->
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-plus bi_menu_perso"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>

                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <h5 class="card-title">
                        <div class="row">
                            <div class="col-3">Liste</div>
                            <div class="col-6">
                                <form action="index.php" method="GET" id="searchForm">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" placeholder="Rechercher un poste..." value="<?= @$search_term==true ? htmlspecialchars($search_term) : '' ?>" id="searchInput">
                                        <button class="btn btn-info text-white" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                        <button class="btn btn-secondary text-white" type="button" id="resetBtn">
                                            <i class="bi bi-x-circle"></i> clearn
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>




                    </h5>


                    <?php if (empty($jobs)): ?>

                        <div class="col-12 text-center">
                            <p>Aucune offre d'emploi ne correspond à votre recherche.</p>
                        </div>

                    <?php else: ?>

                        <div class="table-responsive">
                            <table class="table table-borderless datatable border-1 w-100">
                                <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col">Titre</th>
                                    <th scope="col" class="text-center">Statut</th>
                                    <th scope="col" class="d-none d-sm-table-cell">Créé le</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                                </thead>

                                <tbody id="jobTableBody">
                                <?php
                                $index = 1;
                                foreach ($jobs as $job): ?>
                                    <tr class="job-data-row">

                                        <td class="text-center align-middle"><?= $index++ ?></td>

                                        <td class="align-middle">
                                            <strong><?= htmlspecialchars($job['title'] ?? '') ?></strong>
                                        </td>

                                        <td class="text-center align-middle">
                                            <?= htmlspecialchars($job['statuts'] ?? '') ?>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>
            </div>
        </div><!-- End Recent Sales -->

    </div>
</div>