<?php
$url = "../";
include $url.'class/Mainclass.php';
include $url.'class/Contact.php';

$datas = new Contact();

include $url.'include/header.php';
?>


<?php
$elements_per_page = 10;
$search_term = $_GET['search_term'] ?? '';

$current_page = intval($_GET['page'] ?? 1);

// -----------------------------------------------------------
// 2. APPEL DE LA MÉTHODE ET RÉCUPÉRATION DES DONNÉES
// -----------------------------------------------------------

$data = $datas->findPaginatedAndFilteredmail(
    $search_term,
    $current_page,
    $elements_per_page
);

$mails_to_display = $data['mails'];
$total_mails      = $data['total'];
$total_pages      = $data['totalPages'];
$current_page     = $data['currentPage']; // Assuré d'être valide par la classe

// Préparation de l'URL pour la pagination
$query_params = http_build_query(['search_term' => $search_term]);
$base_pagination_url = 'index.php?' . $query_params . '&page=';

?>

    <div class="pagetitle">
        <h1>Dashboard</h1>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAllCheckbox');
            const checkboxes = document.querySelectorAll('.mail-checkbox');
            const applyButton = document.getElementById('applyActionButton');

            function updateApplyButton() {
                const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                applyButton.disabled = !isAnyChecked;
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = selectAll.checked;
                });
                    updateApplyButton();
                });
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateApplyButton);
        });

            updateApplyButton();
        });
    </script>

