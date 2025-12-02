<?php
include 'Master.php';
class PostRecordsSeeder extends Master
{
    public static function seedDatabase(int $count = 1000): void
    {
        parent::connect();
        $pdo = parent::getPdo();

        $countExisting = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();

        if ($countExisting >= $count) {
            echo "✅ Base de données déjà remplie : {$countExisting} records trouvés. Aucune insertion nécessaire.\n";
            return;
        }

        $statuses = ['Publié', 'Brouillon', 'Archivé', 'En attente'];
        $authors = ['Admin', 'Support', 'Client', 'Développeur'];

        // Vider la table si elle contient des données pour une insertion propre
        if ($countExisting > 0) {
            $pdo->exec("TRUNCATE TABLE posts");
            $countExisting = 0;
        }

        $records_to_insert = $count - $countExisting;

        echo "🚀 Insertion de {$records_to_insert} records...\n";
        $pdo->beginTransaction();

        $stmt = self::connect()->prepare("
            INSERT INTO posts (title, views, likes, status, author, date) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        for ($i = 1; $i <= $records_to_insert; $i++) {
            $title = ($i % 100 == 0) ? "Étude spéciale du Référencement" : "Record de Post N°{$i} : Taux de Lecture";
            $views = rand(100, 15000);
            $likes = rand(5, 1500);
            $status = $statuses[array_rand($statuses)];
            $author = $authors[array_rand($authors)];
            $date = date('Y-m-d', strtotime("-{$i} days"));

            $stmt->execute([$title, $views, $likes, $status, $author, $date]);
        }

        $pdo->commit();
        echo "🎉 Insertion réussie de {$records_to_insert} records.\n";
    }
}


// ====================================================================
// SCRIPT D'EXÉCUTION DU SEEDER
// ====================================================================

$count_records = 5000;

echo "--- Lancement du générateur de records (Master/Seeder) ---\n";

// Appel statique pour exécuter la génération de données
PostRecordsSeeder::seedDatabase(count: $count_records);

echo "---------------------------------------------------------\n";
echo "Opération de seeding terminée.\n";