<?php
// ====================================================================
// SECTION 1 : LOGIQUE PHP DE TRAITEMENT ET DE VALIDATION (PDO)
// ====================================================================
require '../vendor/autoload.php';
include '../class/Mainclass.php';
include '../class/Contact.php';


$data = new Contact();




// Définir la limite de caractères pour le message
$max_length = 200;

// --- 2. Initialisation des variables pour le premier chargement ---
$name_value = '';
$email_value = '';
$subject_value = '';
$message_value = '';

$name_class = $email_class = $subject_class = $message_class = '';
$name_error = $email_error = $subject_error = $message_error = '';
$name_success = 'Nom vérifié.';
$email_success = 'E-mail vérifié.';
$subject_success = 'Sujet OK.';
$message_success = 'Message OK.';

$global_success = '';
$global_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors_found = false;

    // --- 3. Récupération et nettoyage des données ---
    $name_value = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email_value = trim(htmlspecialchars($_POST['email'] ?? ''));
    $subject_value = trim(htmlspecialchars($_POST['subject'] ?? ''));
    $message_value = trim(htmlspecialchars($_POST['message'] ?? ''));

    // --- 4. Validation et attribution des classes ---

    // Validation du Nom
    if (empty($name_value)) {
        $name_class = 'is-invalid';
        $name_error = 'Veuillez entrer votre nom.';
        $errors_found = true;
    } else {
        $name_class = 'is-valid';
    }

    // Validation de l'Email
    if (empty($email_value) || !filter_var($email_value, FILTER_VALIDATE_EMAIL)) {
        $email_class = 'is-invalid';
        $email_error = 'Veuillez entrer une adresse e-mail valide.';
        $errors_found = true;
    } else {
        $email_class = 'is-valid';
    }

    // Validation du Sujet
    if (empty($subject_value)) {
        $subject_class = 'is-invalid';
        $subject_error = 'Le sujet est obligatoire.';
        $errors_found = true;
    } else {
        $subject_class = 'is-valid';
    }

    // Validation du Message (Champ vide OU longueur excessive)
    if (empty($message_value)) {
        $message_class = 'is-invalid';
        $message_error = 'Veuillez écrire un message.';
        $errors_found = true;
    } elseif (strlen($message_value) > $max_length) {
        $message_class = 'is-invalid';
        $message_error = "Le message est trop long. Il doit contenir au maximum $max_length caractères.";
        $errors_found = true;
    } else {
        $message_class = 'is-valid';
    }

    // --- 5. Insertion dans la table via PDO si aucune erreur ---
    if (!$errors_found) {
        try {


            $data->setFullname($name_value);
            $data->setEmail($email_value);
            $data->setSubject($subject_value);
            $data->setContent($message_value);
            $data->insert();

            //$mail_fullname,$mail_sender,$mail_subject,$message
          /*  if($data->mail_info_send_no_attachment($name_value,$email_value,$subject_value,$message_value)){
                $global_success = 'Votre message a été enregistré avec succès. Merci !';
                // Réinitialiser les champs après succès
                $name_value = $email_value = $subject_value = $message_value = '';
                $name_class = $email_class = $subject_class = $message_class = '';
            };*/


            $global_success = 'Votre message a été enregistré avec succès. Merci !';
            // Réinitialiser les champs après succès
            $name_value = $email_value = $subject_value = $message_value = '';
            $name_class = $email_class = $subject_class = $message_class = '';


        } catch (\PDOException $e) {
            $global_error = 'Erreur DB : Échec de l\'enregistrement du message. Veuillez vérifier les paramètres de connexion ou l\'existence de la table `contacts`.';
            // Pour le débogage : $global_error .= $e->getMessage();
        }
    } else {
        $global_error = 'Veuillez corriger les erreurs dans le formulaire.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Contact avec PDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* S'assurer que les messages d'erreur et de succès s'affichent correctement après le rechargement PHP */
        .invalid-feedback.d-block, .valid-feedback.d-block {
            margin-top: 0.25rem;
            display: block !important;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            <div class="card shadow">

                <a href="../index.php" class="btn btn-danger">Retour</a>
                <div class="card-header text-center bg-primary text-white">
                    <h4 class="mb-0">Contactez-nous (PDO)</h4>
                    <p class="mb-0">Vos données seront insérées dans la table `contacts`.</p>
                </div>

                <div class="card-body">

                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="php-email-form">

                        <div class="my-3 text-center">
                            <?php if (!empty($global_error)): ?>
                                <div class="alert alert-danger error-message">
                                    <?php echo $global_error; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($global_success)): ?>
                                <div class="alert alert-success sent-message">
                                    <?php echo $global_success; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">

                            <div class="col-md-6 form-group">
                                <label for="name" class="form-label">Votre Nom</label>
                                <input type="text"
                                       name="name"
                                       class="form-control <?php echo $name_class; ?>"
                                       id="name"
                                       placeholder="Ex: Jean Dupont"
                                       required
                                       value="<?php echo htmlspecialchars($name_value); ?>">

                                <div class="invalid-feedback d-block text-danger">
                                    <?php echo $name_error; ?>
                                </div>

                                <div class="valid-feedback d-block">
                                    <?php echo ($name_class === 'is-valid' ? '<i class="bi bi-check-circle-fill me-1"></i> ' . $name_success : ''); ?>
                                </div>
                            </div>

                            <div class="col-md-6 form-group mt-3 mt-md-0">
                                <label for="email" class="form-label">Votre E-mail</label>
                                <input type="email"
                                       class="form-control <?php echo $email_class; ?>"
                                       name="email"
                                       id="email"
                                       placeholder="Ex: vous@exemple.com"
                                       required
                                       value="<?php echo htmlspecialchars($email_value); ?>">

                                <div class="invalid-feedback d-block text-danger">
                                    <?php echo $email_error; ?>
                                </div>
                                <div class="valid-feedback d-block">
                                    <?php echo ($email_class === 'is-valid' ? '<i class="bi bi-check-circle-fill me-1"></i> ' . $email_success : ''); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="subject" class="form-label">Sujet</label>
                            <input type="text"
                                   class="form-control <?php echo $subject_class; ?>"
                                   name="subject"
                                   id="subject"
                                   placeholder="Le sujet de votre message"
                                   required
                                   value="<?php echo htmlspecialchars($subject_value); ?>">

                            <div class="invalid-feedback d-block text-danger">
                                <?php echo $subject_error; ?>
                            </div>
                            <div class="valid-feedback d-block">
                                <?php echo ($subject_class === 'is-valid' ? '<i class="bi bi-check-circle-fill me-1"></i> ' . $subject_success : ''); ?>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control <?php echo $message_class; ?>"
                                      name="message"
                                      id="message"
                                      rows="5"
                                      placeholder="Votre message ici"
                                      required
                                      maxlength="<?php echo $max_length; ?>" ><?php echo htmlspecialchars($message_value); ?></textarea>

                            <small id="charCount" class="form-text text-muted d-block text-end">
                                <?php echo $max_length; ?> caractères restants
                            </small>

                            <div class="invalid-feedback d-block text-danger">
                                <?php echo $message_error; ?>
                            </div>
                            <div class="valid-feedback d-block">
                                <?php echo ($message_class === 'is-valid' ? '<i class="bi bi-check-circle-fill me-1"></i> ' . $message_success : ''); ?>
                            </div>
                        </div>

                        <div class="text-center form-submit pt-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-database-fill-add me-2"></i> Enregistrer le Message
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Définir les éléments cibles
        const messageInput = document.getElementById('message');
        const charCounter = document.getElementById('charCount');
        // Récupère la valeur 200 depuis l'attribut HTML pour synchronisation
        const maxLength = parseInt(messageInput.getAttribute('maxlength'));

        if (!messageInput || !charCounter || !maxLength) {
            return; // Sortir si les éléments ne sont pas trouvés
        }

        // Fonction de mise à jour du compteur
        function updateCounter() {
            const currentLength = messageInput.value.length;
            let remaining = maxLength - currentLength;

            // S'assurer que le compteur ne descend pas sous zéro pour l'affichage (bien que le navigateur bloque la saisie)
            if (remaining < 0) {
                remaining = 0;
            }

            charCounter.textContent = `${remaining} caractères restants`;

            // Changer la couleur si la limite est proche
            if (remaining <= 10) {
                charCounter.style.color = 'red';
            } else if (remaining <= 50) {
                charCounter.style.color = 'orange';
            } else {
                charCounter.style.color = 'gray';
            }
        }

        // 2. Écouter l'événement de saisie (input)
        messageInput.addEventListener('input', updateCounter);

        // 3. Initialiser le compteur au chargement de la page (important si PHP a réinjecté du texte)
        updateCounter();
    });
</script>

