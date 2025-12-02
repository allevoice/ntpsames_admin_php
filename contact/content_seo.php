<?php

include $url.'classes/Mainclass.php';
include $url.'classes/Metainfo.php';


$metaview = new Metainfo();

$allview = $metaview->readAll();


// ====================================================================
// 2. LOGIQUE DE TRAITEMENT DU FORMULAIRE (SIMULATION)
// ====================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'update_meta') {

    $meta_name = isset($_POST['meta_name']) ? trim($_POST['meta_name']) : '';
    $meta_content = isset($_POST['meta_content']) ? $_POST['meta_content'] : '';

    // ⚠️ Placez votre VRAIE logique de mise à jour (DB, fichier) ici ⚠️
    $success = true; // Simulation de succès

    if (empty($meta_name) || empty($meta_content)) {
        $success = false;
    }



    // Redirection (Post/Redirect/Get pattern)
    header("Location: index.php");
    exit;
}
?>

<style>
    .meta-list {
        /* S'assurer que la liste a une hauteur fixe et est défilable */
        max-height: 400px;
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>






        <div class="col-lg-6">
            <div class="info-box card p-2">
                <h3 class="card-header">SEO | Meta Tags</h3>

                <ul class="list-group list-group-flush meta-list">

                    <?php foreach ($allview as $item):
                        // Préparation des données pour l'affichage et la modale
                        $content_display = strlen($item['content']) > 120 ? substr($item['content'], 0, 117) . '...' : $item['content'];
                        $data_type = htmlspecialchars($item['typedr']);
                        $data_name = htmlspecialchars($item['title']);
                        $data_content = htmlspecialchars($item['content']); // Contenu complet pour la modale
                        $data_id = htmlspecialchars($item['id']); // L'identifiant unique
                        ?>

                        <li class="list-group-item d-flex align-items-center py-2">

                            <span class="text-secondary flex-shrink-0 me-3" style="width: 15%;">
                                <small><?= htmlspecialchars($item['typedr']) ?></small>
                            </span>

                            <span class="fw-bold text-dark flex-shrink-0" style="width: 25%;">
                                <?= htmlspecialchars($item['title']) ?>
                            </span>

                            <span class="text-muted text-truncate flex-grow-1 mx-2" title="<?= htmlspecialchars($item['content']) ?>">
                                <?= htmlspecialchars($content_display) ?>
                            </span>

                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary flex-shrink-0 edit-meta-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editMetaModal<?= $data_id ?>">
                                Edit
                            </button>

                        </li>

                        <div class="modal fade" id="editMetaModal<?= $data_id ?>" tabindex="-1" aria-labelledby="editMetaModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editMetaModalLabel">Modifier le Meta Tag</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form id="editMetaForm" method="POST" action="index.php">
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="update_meta">

                                            <div class="mb-3">
                                                <label for="modal-meta-type" class="form-label">Type</label>
                                                <input type="text" class="form-control" value="<?= $data_type ?>" id="modal-meta-type" disabled>

                                            </div>

                                            <div class="mb-3">
                                                <label for="modal-meta-name-display" class="form-label">Nom du Tag (Meta Name)</label>
                                                <input type="text" class="form-control" id="modal-meta-name-display" value="<?= $data_name ?>"  >
                                            </div>

                                            <div class="mb-3">
                                                <label for="modal-meta-content" class="form-label">Contenu du Tag (Content)</label>
                                                <textarea class="form-control" id="modal-meta-content" name="meta_content" rows="4" required><?=$data_content?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    <?php endforeach; ?>

                </ul>
            </div>
        </div>






    <meta name="robots" content="index">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary">

    <meta property="og:url" content="https://www.brightsparkcol.com">
    <meta property="og:site_name" content="brightsparkcol">
    <meta property="og:image" itemprop="image primaryImageOfPage" content="./images/logo/logo.jpg">
    <meta name="twitter:domain" content="brightsparkcol.com">
    <meta name="twitter:title" property="og:title" itemprop="name" content="Bright Spark Center of Learning">
    <meta name="twitter:description" property="og:description" itemprop="description" content="Welcome to Bright Spark Center of Learning, In this engaging 5-minute animated film, discover how we empower learners of all ages to achieve their dreams through innovative teaching and compassionate guidance.

Our mission is to boost confidence and improve skills by creating personalized learning experiences that lead to success. Join us as we explore the culture of excellence at Bright Spark, where every step taken is a step toward a brighter future. Watch as our animated characters embody the spirit of learning and growth, demonstrating the transformative power of education.

Bright Spark Center Of Learning has provided and will continue to provide proven tutoring and test prep programs. We have empowered countless students to build confidence, achieve their goals, and excel academically. Our certified tutors, experienced in all academic subjects, are dedicated to helping your student reach their fullest potential.

Don&amp;#039;t forget to like, share, and comment below! #BrightSpark #Learning #Education #Empowerment #Animation #AchieveYourDreams

 ">
    <meta name="description" content="Welcome to Bright Spark Center of Learning, In this engaging 5-minute animated film, discover how we empower learners of all ages to achieve their dreams through innovative teaching and compassionate guidance.

Our mission is to boost confidence and improve skills by creating personalized learning experiences that lead to success. Join us as we explore the culture of excellence at Bright Spark, where every step taken is a step toward a brighter future. Watch as our animated characters embody the spirit of learning and growth, demonstrating the transformative power of education.

Bright Spark Center Of Learning has provided and will continue to provide proven tutoring and test prep programs. We have empowered countless students to build confidence, achieve their goals, and excel academically. Our certified tutors, experienced in all academic subjects, are dedicated to helping your student reach their fullest potential.

Don&amp;#039;t forget to like, share, and comment below! #BrightSpark #Learning #Education #Empowerment #Animation #AchieveYourDreams

 ">




</head>