<?php
// Fichier : process_bulk_actions.php
include '../class/Mainclass.php';
include '../class/Contact.php';

$repository = new Contact();


// Vérifier si le formulaire a été soumis via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $mailIds = $_POST['mail_ids'] ?? [];





    if (empty($action) || empty($mailIds)) {
        // Redirection avec un message d'erreur si aucune action/sélection
        header('Location: index.php?error=no_action_or_selection');
        exit;
    }

    $count = count($mailIds);
    $successMessage = '';

    try {
        switch ($action) {
            case 'mark_read':
                // Implémentez la méthode markAsRead dans votre MailRepository
                $repository->markStatus($mailIds, 1);
                $successMessage = "{$count} mails marqués comme lus.";
                break;

            case 'mark_unread':
                // Implémentez la méthode markStatus dans votre MailRepository
                $repository->markStatus($mailIds, 0);
                $successMessage = "{$count} mails marqués comme non lus.";
                break;

            case 'delete':
                // Implémentez la méthode deleteMails dans votre MailRepository
                $repository->deleteMails($mailIds);
                $successMessage = "{$count} mails supprimés.";
                break;

            case 'transfer':
                // ⚠️ DEVRAIT ÊTRE RÉCUPÉRÉ DU FORMULAIRE, EX: $_POST['target_email']
                $targetEmail = 'transfert_admin@votredomaine.com';

                if (!filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
                    header('Location: index.php?error=' . urlencode("Adresse email cible invalide."));
                    exit;
                }

                $mailsToTransfer = $repository->getMailsByIds($mailIds);
                $successfulTransfers = 0;

                foreach ($mailsToTransfer as $mail) {
                    $original_sender = htmlspecialchars($mail['fullname'] ?? $mail['email']);
                    $original_email = $mail['email'];
                    $original_subject = htmlspecialchars($mail['subject']);
                    $original_content = htmlspecialchars($mail['content']);

                    // Construction du contenu de l'email transféré
                    $subject = "Fwd: {$original_subject}";
                    $message = "--- Message Original de {$original_sender} ({$original_email}) ---\n\n";
                    $message .= $original_content;

                    // Entêtes pour que le transfert semble venir de votre serveur
                    $headers = 'From: votre_service_mail@votredomaine.com' . "\r\n" .
                        'Reply-To: ' . $original_email . "\r\n" . // Permet de répondre à l'expéditeur original
                        'Content-Type: text/plain; charset=UTF-8';

                    // Tente l'envoi
                    if (mail($targetEmail, $subject, $message, $headers)) {
                        $successfulTransfers++;
                    }
                }

                if ($successfulTransfers > 0) {
                    $successMessage = "{$successfulTransfers} mails transférés à {$targetEmail}.";
                } else {
                    $successMessage = "Erreur: Aucun mail n'a pu être transféré.";
                }

            default:
                $successMessage = "Action inconnue.";
        }

        // Redirection vers la page d'index avec le message de succès
        header('Location: index.php?success=' . urlencode($successMessage));
        exit;

    } catch (Exception $e) {
        // Gérer les erreurs de base de données
        header('Location: index.php?error=' . urlencode("Erreur DB: " . $e->getMessage()));
        exit;
    }
} else {
    // Si l'accès est direct, rediriger
    header('Location: index.php');
    exit;
}