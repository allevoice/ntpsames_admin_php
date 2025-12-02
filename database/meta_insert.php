<?php

require_once '../classes/Mainclass.php';

class Metaisnert extends Mainclass {

    protected $table = "meta_tags";

    // Propriétés publiques pour les données
    public $long_description;
    public $meta_elements;

    public function __construct()
    {
        // 1. Définir la description longue et l'array (ne peut pas être fait directement dans la déclaration)
        $this->setMetaElements();

        // 2. Vérifier et créer la table
        if (!$this->tableExists($this->table)) {
            $create = $this->tablecreate();
            if (!$create) {
                // Utiliser une exception ou une erreur plus claire
                die('Erreur fatale : Problème lors de la création de la table meta_tags.');
            }
        }
    }

    /**
     * Définit la description longue et l'array des meta tags.
     */
    private function setMetaElements()
    {
        $this->long_description = "Welcome to Bright Spark Center of Learning, In this engaging 5-minute animated film, discover how we empower learners of all ages to achieve their dreams through innovative teaching and compassionate guidance.\n\n"
            . "Our mission is to boost confidence and improve skills by creating personalized learning experiences that lead to success. Join us as we explore the culture of excellence at Bright Spark, where every step taken is a step toward a brighter future. Watch as our animated characters embody the spirit of learning and growth, demonstrating the transformative power of education.\n\n"
            . "Bright Spark Center Of Learning has provided and will continue to provide proven tutoring and test prep programs. We have empowered countless students to build confidence, achieve their goals, and excel academically. Our certified tutors, experienced in all academic subjects, are dedicated to helping your student reach their fullest potential.\n\n"
            . "Don&amp;#039;t forget to like, share, and comment below! #BrightSpark #Learning #Education #Empowerment #Animation #AchieveYourDreams\n\n ";

        // Remplissage de l'array
        $this->meta_elements = [
            // Correction des noms des clés de l'array: 'type' et 'name'
            ['type' => 'Standard/SEO', 'name' => 'robots', 'content' => 'index'],
            ['type' => 'Standard/SEO', 'name' => 'description', 'content' => $this->long_description],
            ['type' => 'Open Graph (FB)', 'name' => 'og:type', 'content' => 'website'],
            ['type' => 'Twitter Card', 'name' => 'twitter:card', 'content' => 'summary'],
            ['type' => 'Open Graph (FB)', 'name' => 'og:url', 'content' => 'https://www.brightsparkcol.com'],
            ['type' => 'Open Graph (FB)', 'name' => 'og:site_name', 'content' => 'brightsparkcol'],
            ['type' => 'Open Graph (FB)', 'name' => 'og:image', 'content' => './images/logo/logo.jpg'],
            ['type' => 'Twitter Card', 'name' => 'twitter:domain', 'content' => 'brightsparkcol.com'],
            ['type' => 'Twitter/OG', 'name' => 'twitter:title', 'content' => 'Bright Spark Center of Learning'],
            ['type' => 'Twitter/OG', 'name' => 'twitter:description', 'content' => $this->long_description],
        ];
    }

    /**
     * Crée la table meta_tags si elle n'existe pas.
     */
    private function tablecreate()
    {
        // ⚠️ Correction de la structure de la table pour utiliser 'name' et 'type'
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(100) NOT NULL UNIQUE, -- Clé unique pour le nom du tag (ex: 'robots')
            typedr VARCHAR(50) NULL,             -- La catégorie (ex: 'Standard/SEO')
            content TEXT COLLATE latin1_general_ci NOT NULL,
            statuts INT(11) NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL,
            PRIMARY KEY (id) )
            ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";

        $this->pdoconnect()->exec($sql);
        return true;
    }

    /**
     * Insère tous les meta tags de l'array en utilisant une transaction PDO.
     * @return array Résultat de l'opération (succès/échec).
     */
    public function insertauto()
    {
        $pdo = $this->pdoconnect();
        $count = 0;

        try {
            // Démarrer la transaction : toutes les insertions doivent réussir ou échouer
            $pdo->beginTransaction();

            $current_datetime = date('Y-m-d H:i:s');

            // Correction de la requête préparée pour utiliser 'type' et 'name'
            $stmt = $pdo->prepare("INSERT INTO {$this->table} 
                (typedr, title, content,created_at) 
                VALUES (:typedr, :title, :content,:created_at)");

            foreach ($this->meta_elements as $item) {
                // Liaison des paramètres avec les noms des clés de l'array
                $stmt->bindParam(':typedr', $item['type']);
                $stmt->bindParam(':title', $item['name']);
                $stmt->bindParam(':content', $item['content']);
                $stmt->bindParam(':created_at', $current_datetime);

                $stmt->execute();
                $count++;
            }

            // Valider la transaction
            $pdo->commit();
            return ['success' => true, 'message' => "$count meta tags insérés avec succès."];

        } catch (\PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $pdo->rollBack();
            // Retourner l'erreur détaillée
            return ['success' => false, 'message' => "Erreur PDO lors de l'insertion : " . $e->getMessage()];
        }
    }
}


// ====================================================================
// EXÉCUTION
// ====================================================================

$data = new Metaisnert();

// Exécuter l'insertion
$send = $data->insertauto();

// Afficher le résultat
print_r($send);