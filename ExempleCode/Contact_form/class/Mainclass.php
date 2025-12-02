<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;




class Mainclass
{


    protected $table;
    protected $id;
    protected $pdo;
    protected $sql_queries;
    protected $search_term;



    protected function tableExists($table) {
        try {
            $result = $this->pdoconnect()->query("SELECT 1 FROM $table LIMIT 1");
        } catch (\Exception $e) {
            return FALSE;
        }
        return $result !== FALSE;
    }


    public function pdoconnect(){
        $host = $_ENV['MYSQL_HOST'] ?? 'localhost';
        $dbname = $_ENV['MYSQL_DBNAME'] ?? 'ntpsams_2025';
        $user = $_ENV['MYSQL_USER'] ?? 'root';
        $pwd = $_ENV['MYSQL_PWD'] ?? '';
        $utf= $_ENV['MYSQL_UTF'] ?? 'utf8';

        if(!isset($this->pdo)){
            $this->pdo = new \PDO('mysql:host='.$host.';dbname='.$dbname.';charset='.$utf, $user,$pwd);
            return $this->pdo;
        }else{
            return $this->pdo;
        }

    }




    public function insert() {
        // 1. Appel de la fonction pour obtenir les données brutes
        $raw_data = $this->valuesdata();

        if (empty($raw_data)) {
            error_log("Tentative d'insertion sans données. La fonction valuesdata() a retourné un tableau vide.");
            return false;
        }

        $set_placeholders = [];
        $data_to_bind = [];

        // 2. Construction de la requête SQL et du tableau de liaison (Binding)
        foreach ($raw_data as $column => $value) {
            // Construction du placeholder SQL: fullname = :fullname
            $set_placeholders[] = "$column = :$column";

            // Construction du tableau de liaison: [':fullname' => 'Alice']
            // C'est l'étape cruciale pour PDO!
            $data_to_bind[":$column"] = $value;
        }

        // 3. Construction de la requête SQL finale
        $set_clause = implode(', ', $set_placeholders);

        // Ajout de la colonne d'horodatage
        $sql = "INSERT INTO $this->table SET " . $set_clause . ", created_at = NOW()";


        // 4. Exécution de la requête préparée
        try {
            // Assurez-vous que $this->pdo est initialisé dans le constructeur!
            $stmt = $this->pdo->prepare($sql);

            // Exécution avec le tableau de liaison qui contient les deux-points (':')
            $success = $stmt->execute($data_to_bind);

            return $success;

        } catch (\PDOException $e) {
            // Gérer les erreurs (ex: violation NOT NULL)
            error_log("Erreur PDO lors de l'insertion : " . $e->getMessage());
            // Vous pouvez afficher l'erreur pour le débogage si nécessaire:
            // echo "Erreur PDO : " . $e->getMessage();
            return false;
        }
    }


    public function readall(){
        $stmt = $this->pdoconnect()->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();

    }

    public function readall_not_read(){
        // Utiliser COUNT(*) pour optimiser le compte
    $sql = "SELECT COUNT(*) AS total FROM {$this->table} WHERE is_read is NULL AND deleted_at IS NULL";

    try {
        // 1. Préparer la requête SQL
        $stmt = $this->pdoconnect()->prepare($sql);

        // 2. Exécuter la requête (pas de paramètres à lier ici)
        $stmt->execute();

        // 3. Récupérer le résultat du COUNT(*). fetchColumn() est le plus rapide.
        // Il lit la valeur de la colonne 'total'.
        $count = $stmt->fetchColumn();

        // 4. S'assurer que le résultat est un entier
        return (int)$count;

    } catch (\PDOException $e) {
        // En cas d'erreur de base de données (ex: table inexistante)
        // Vous pouvez logger l'erreur ici
        return 0;
    }
    }


    public function update(): bool {
        // 1. Appel de setValues pour obtenir les données (fullname, email, etc.)
        $raw_data = $this->valuesdata();

        // Vérification essentielle
        if (empty($raw_data) || $this->getId() === null) {
            error_log("Tentative de mise à jour sans données ou sans ID.");
            return false;
        }

        $set_placeholders = [];
        $data_to_bind = [];

        // 2. Construction de la requête SET et du tableau de liaison
        foreach ($raw_data as $column => $value) {
            // Ex: fullname = :fullname
            $set_placeholders[] = "$column = :$column";
            $data_to_bind[":$column"] = $value;
        }

        // 3. Ajout des marqueurs spécifiques à la mise à jour
        $set_clause = implode(', ', $set_placeholders);

        // Ajout de la mise à jour de l'horodatage et de la clause WHERE
        $sql = "UPDATE {$this->table} 
            SET " . $set_clause . ", updated_at = NOW() 
            WHERE id = :id";

        // 4. Ajouter l'ID à la liaison des données (très important pour la clause WHERE)
        $data_to_bind[":id"] = $this->getId();

        // 5. Exécution
        try {
            $stmt = $this->pdo->prepare($sql);
            $success = $stmt->execute($data_to_bind);

            // Optionnel: vérifier si des lignes ont été affectées
            if ($success && $stmt->rowCount() === 0) {
                error_log("Mise à jour réussie mais aucune ligne affectée (ID inexistant?). ID: " . $this->getId());
            }

            return $success;

        } catch (\PDOException $e) {
            error_log("Erreur PDO lors de la mise à jour : " . $e->getMessage());
            return false;
        }
    }


    public function formaterDatesAvecFuseaux(string $timestamp_utc): array
    {
        // 1. Définir le fuseau horaire de l'heure stockée (Base de données)
        $timezone_base = new \DateTimeZone('UTC');

        // 2. Créer l'objet DateTime à partir du timestamp, en spécifiant qu'il est en UTC
        $date = new \DateTime($timestamp_utc, $timezone_base);

        // --- Format Français (Paris, Europe/Paris = UTC+1 en Hiver, UTC+2 en Été) ---
        // En Novembre (2025), Paris est en Heure d'Hiver (UTC+1)
        $timezone_fr = new \DateTimeZone('Europe/Paris');
        $date->setTimezone($timezone_fr); // Applique le fuseau horaire de Paris

        $formatter_fr = new \IntlDateFormatter(
            'fr_FR',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::NONE,
            'Europe/Paris',
            \IntlDateFormatter::GREGORIAN,
            'EEEE dd MMM. yyyy'
        );

        $dateFormatted_fr = ucfirst($formatter_fr->format($date));
        // Correction du mois en majuscule (comme demandé précédemment)
        $dateFormatted_fr = preg_replace_callback('/ ([a-z])/', fn($matches) => ' ' . strtoupper($matches[1]), $dateFormatted_fr, 1);

    $resultat_fr = $dateFormatted_fr . ', ' . $date->format('g:i A');

    // --- Format Anglais (New York, America/New_York = UTC-5 en Hiver) ---
    // En Novembre (2025), New York est en Heure d'Hiver (UTC-5)
    $timezone_en = new \DateTimeZone('America/New_York');
    $date->setTimezone($timezone_en); // Applique le fuseau horaire de New York

    $resultat_en = $date->format('l d M.') . ' ' . $date->format('Y, g:i A');

    return [
        'base' => $timestamp_utc . ' (UTC)',
        'fr' => $resultat_fr,
        'us' => $resultat_en,
    ];
}

    public function formaterDateEnFrancais(string $timestamp, string $timezone = 'Europe/Paris'): string
    {
        // 1. Créer un objet DateTime à partir de la chaîne (ex: '2025-11-18 10:30:00')
        $date = new \DateTime($timestamp);
        $date->setTimezone(new \DateTimeZone($timezone));

        // 2. Formater la partie temporelle (Heure:Minute AM/PM)
        // Le format 'g:i A' donne 10:30 AM/PM.
        $timeFormatted = $date->format('g:i A');

        // 3. Formater la partie jour/mois/année avec la bonne localisation (Mardi 18 Nov. 2025)
        $formatter = new \IntlDateFormatter(
            'fr_FR', // Localisation française
            \IntlDateFormatter::FULL, // Style complet pour le jour (Mardi)
            \IntlDateFormatter::NONE, // Pas de style de temps ici
            $timezone,
            \IntlDateFormatter::GREGORIAN,
            'EEEE dd MMM. yyyy' // Format personnalisé (Mardi 18 Nov. 2025)
        );

        $dateFormatted = ucfirst($formatter->format($date));

        // 4. Assembler les deux parties
        return $dateFormatted . ', ' . $timeFormatted;
    }

    public function formatTimestampEnglish(string $timestamp, string $timezone = 'America/New_York'): string
    {
        try {
            // 1. Create a DateTime object from the input string
            $date = new \DateTime($timestamp);

            // Optional: Set the timezone
            $date->setTimezone(new \DateTimeZone($timezone));

            // 2. Define the desired format string:
            // F: Full month name (November)
            // d: Day of the month (18)
            // Y: Full year (2025)
            // l: Full day of the week (Tuesday)
            // g: Hour (1-12)
            // i: Minutes with leading zeros
            // A: AM or PM (uppercase)

            // Note: We use \M. for the short month name with a period,
            // which requires the "M" format code and then adding the period manually.

            $formattedDate = $date->format('l d M. Y, g:i A');

            // Optional: Correct the day abbreviation if needed (e.g., if you prefer "Nov" over "Nov.")
            // Since we want 'Nov.', the format 'M.' is the closest we can get
            // without manual string replacement. If 'M' gives 'Nov', you can simply append the period:

            // Let's refine the format slightly for the required output:
            return $date->format('l d M') . '. ' . $date->format('Y, g:i A');

        } catch (\Exception $e) {
            // Handle potential errors (e.g., invalid timestamp string)
            return "Invalid Date";
        }
    }


    public function traduireDateFixe(string $timestamp_source): array
    {
        // --- Définition des traductions ---
        // Les traductions se basent sur les noms courts ('D' et 'M') générés par DateTime en anglais.
        $jours_fr = [
            'Mon' => 'Lundi', 'Tue' => 'Mardi', 'Wed' => 'Mercredi',
            'Thu' => 'Jeudi', 'Fri' => 'Vendredi', 'Sat' => 'Samedi', 'Sun' => 'Dimanche'
        ];
        $mois_fr = [
            'Jan' => 'Jan.', 'Feb' => 'Fév.', 'Mar' => 'Mar.',
            'Apr' => 'Avr.', 'May' => 'Mai', 'Jun' => 'Juin',
            'Jul' => 'Juil.', 'Aug' => 'Aoû.', 'Sep' => 'Sep.',
            'Oct' => 'Oct.', 'Nov' => 'Nov.', 'Dec' => 'Déc.'
        ];
        // Pour l'espagnol (ES)
        $jours_es = [
            'Mon' => 'Lunes', 'Tue' => 'Martes', 'Wed' => 'Miércoles',
            'Thu' => 'Jueves', 'Fri' => 'Viernes', 'Sat' => 'Sábado', 'Sun' => 'Domingo'
        ];
        $mois_es = [
            'Jan' => 'Ene.', 'Feb' => 'Feb.', 'Mar' => 'Mar.',
            'Apr' => 'Abr.', 'May' => 'May.', 'Jun' => 'Jun.',
            'Jul' => 'Jul.', 'Aug' => 'Ago.', 'Sep' => 'Sep.',
            'Oct' => 'Oct.', 'Nov' => 'Nov.', 'Dec' => 'Dic.'
        ];

        // 1. Créer l'objet DateTime à partir de la chaîne d'entrée
        $date_obj = new \DateTime($timestamp_source);

        // 2. Décomposer les éléments en anglais pour la traduction
        $jour_court_en = $date_obj->format('D'); // Ex: 'Sat'
        $mois_court_en = $date_obj->format('M'); // Ex: 'Nov'

        // Le reste du format est invariant (chiffres et heure AM/PM)
        $format_chiffres_et_heure = $date_obj->format(' d Y, g:i A'); // Ex: ' 29 2025, 4:26 AM'

        // --- Génération et traduction des formats ---

        $resultats = [];

        // Format Anglais (US/EN)
        // L'anglais utilise les noms par défaut, seule la ponctuation est ajoutée/ajustée.
        $jour_long_en = $date_obj->format('l'); // Ex: 'Saturday'
        $mois_long_en = $mois_court_en; // Ex: 'Nov'
        $resultats['us'] = $jour_long_en . $format_chiffres_et_heure;
        // Ajout du point après le mois et ajustement de l'espace
        $resultats['us'] = str_replace($mois_long_en, $mois_long_en . '.', $resultats['us']);

        // Format Français (FR)
        $jour_fr = $jours_fr[$jour_court_en] ?? $jour_court_en;
        $mois_fr = $mois_fr[$mois_court_en] ?? $mois_court_en;
        $resultats['fr'] = $jour_fr . $format_chiffres_et_heure;
        // Remplacement du mois et ajout du point
        $resultats['fr'] = str_replace($mois_court_en, $mois_fr . '.', $resultats['fr']);
        // Mise en Majuscule de la première lettre (Samedi -> Samedi)
        $resultats['fr'] = ucfirst($resultats['fr']);


        // Format Espagnol (ES)
        $jour_es = $jours_es[$jour_court_en] ?? $jour_court_en;
        $mois_es = $mois_es[$mois_court_en] ?? $mois_court_en;
        $resultats['es'] = $jour_es . $format_chiffres_et_heure;
        // Remplacement du mois et ajout du point
        $resultats['es'] = str_replace($mois_court_en, $mois_es . '.', $resultats['es']);
        // Pas de majuscule pour les jours en espagnol (Lunes, Martes, etc.)

        return $resultats;
    }

    public function getEtiquetteTemporelle(string $timestamp_source)
        {
            // 1. Créer l'objet DateTime à partir du timestamp complet
            $date_obj = new \DateTime($timestamp_source);

            // 🎯 CORRECTION : N'EXTRAIRE QUE LA DATE (Y-m-d) POUR LES COMPARAISONS
            $date_entree_seule = $date_obj->format('Y-m-d');

            // --- Dates de référence (qui sont aussi au format Y-m-d) ---
            $aujourdhui = (new \DateTime())->format('Y-m-d');
            $hier = (new \DateTime('yesterday'))->format('Y-m-d');

            // 2. Calculer la différence entre les dates (pour days_ago)
            // Pour le calcul de la différence, vous pouvez utiliser les objets DateTime complets
            $diff = (new \DateTime())->diff($date_obj);
            $days_ago = $diff->days;

            // Seulement si la date est dans le passé et récemment
            if ($diff->invert === 1) {
                if ($date_entree_seule === $aujourdhui) {
                    return 'Aujourd\'hui';
                }

                // Cas spécifique pour J = 1 (Hier)
                elseif ($date_entree_seule === $hier) {
                    return 'Hier'; // C'est le résultat souhaité pour 1 jour
                }

                // Cas générique pour J = 2, 3, ou 4
                // 🎯 Condition corrigée : $days_ago DOIT être strictement supérieur à 1
                elseif ($days_ago > 1 && $days_ago <= 4) {
                    // Cette ligne ne sera exécutée que si days_ago est 2, 3, ou 4
                    return "Il y a {$days_ago} jours";
                }else{
                    return 'plus de jours';
                }
            }

            return null; // Retourne null si aucune étiquette spéciale n'est nécessaire
        }




    public function mail_info_send_no_attachment($mail_fullname,$mail_sender,$mail_subject,$message){
        $header_message = "Form contact Message";

        $html_content = '
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>'.$header_message.'</title>
            </head>
            <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f4f4f4;">
                    <tr>
                        <td align="center" style="padding: 20px 0;">
                            <table border="0" cellpadding="0" cellspacing="0" width="600" 
                            style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                <tr>
                                    <td align="center" style="padding: 30px 20px 20px 20px; background-color: #007bff; border-radius: 8px 8px 0 0;">
                                        <h1 style="color: #ffffff; margin: 0; font-size: 24px;">'.$header_message.'</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 40px 30px; color: #333333; font-size: 15px; line-height: 1.6;">
                                        <h2 style="color: #007bff; margin-top: 0;">'.$mail_subject.'</h2>
                                        <p>Message with **'.$mail_fullname.'**</p>
                                        <p>Message from the contact page of our website</p>
                                        <table border="0" cellpadding="10" cellspacing="0" width="100%" style="border: 1px solid #eeeeee; margin: 20px 0; background-color: #f9f9f9;">
                                            
                                        
                                        <tr>
                                                <td width="30%" style="color: #555555; border-bottom: 1px solid #eeeeee;">
                                                <strong>Subject :</strong></td>
                                                <td width="70%" style="border-bottom: 1px solid #eeeeee;">'.$mail_subject.'</td>
                                            </tr> 
                                        <tr>
                                                <td width="30%" style="color: #555555; border-bottom: 1px solid #eeeeee;">
                                                <strong>Full name :</strong></td>
                                                <td width="70%" style="border-bottom: 1px solid #eeeeee;">'.$mail_fullname.'</td>
                                            </tr> 
                                        <tr>
                                                <td width="30%" style="color: #555555; border-bottom: 1px solid #eeeeee;">
                                                <strong>Mail Address :</strong></td>
                                                <td width="70%" style="border-bottom: 1px solid #eeeeee;">'.$mail_sender.'</td>
                                            </tr> 
                                        <tr>
                                                <td width="30%" style="color: #555555; border-bottom: 1px solid #eeeeee;">
                                                <strong>Message :</strong></td>
                                                <td width="70%" style="border-bottom: 1px solid #eeeeee;">'.$message.'</td>
                                            </tr> 
                                            


                                            <tr>
                                                <td style="color: #555555;"><strong>Date :</strong></td>
                                                <td>' . date('Y-m-d H:i:s') . '</td>
                                            </tr>
                                        </table>
                                        
                                    </td>
                                </tr>
                               
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            </html>';


        $mail = new PHPMailer(true);


            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'mail.ntpsams.com';                     //Set the SMTP server to send through Change
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'rodney@dev23ntp.ntpsams.com';                     //SMTP Username Change
            $mail->Password   = '%irFVkNEqHKZa-nL';                               //SMTP password Change


            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Utiliser TLS (ou ENCRYPTION_SMTPS pour le port 465)
            $mail->Port       = 587;                                    // Port TLS/STARTTLS


            // Destinataires
            $mail->setFrom('no-reply@votre-site.com', $header_message);
            $mail->addAddress('rodney@dev23ntp.ntpsams.com', $header_message); // Ajouter un destinataire

            // Contenu
            $mail->SMTPDebug = 0; // Définit le niveau de débogage à désactivé permet de masquer les information du serveur
            $mail->isHTML(true);                                        // Définir le format de l'e-mail à HTML
            $mail->CharSet = 'UTF-8';                                   // Gérer les accents
            $mail->Subject = $header_message;

            // Corps HTML
            $mail->Body    = $html_content;

            // Corps en Texte Brut (fallback pour les clients ne supportant pas HTML)
            $mail->AltBody = 'n\\\\'.$mail_fullname.'  n\\\\'.$mail_sender.'  n\\\\'.$mail_subject.'  n\\\\'.$message.'  n\\\\'.date('Y-m-d H:i:s').'  n\\\\';


            if($mail->send()){
                return true;
            }else{
                return false;
            }
    }







}