<?php


class Master
{

    protected $table='posts';
    protected $id;
    protected $pdo;
    protected $sql_queries;
    protected $search_term;




    public function pdoconnect(){

        if(!isset($this->pdo)){
            $this->pdo = new \PDO('mysql:host=localhost;dbname=records_db;charset=utf8', 'root', '');
            return $this->pdo;
        }else{
            return $this->pdo;
        }

    }




    public function readAll(){
        // 1. Récupération et nettoyage du terme de recherche
        $this->search_term = $_GET['search'] ?? '';

        // Initialisation de la requête SQL et des paramètres
        $sql = "SELECT * FROM {$this->table}"; // Utilisez $this->table ici
        $params = [];

        // 2. Construction de la clause WHERE si un terme de recherche existe
        if (!empty($this->search_term)) {
            // Ajout de la clause WHERE pour la recherche
            $sql .= " WHERE title LIKE ?";

            // Les wildcards (%) doivent être ajoutés aux termes de recherche, pas à la chaîne SQL
            $search_param = "%" . $this->search_term . "%";
            $params = [$search_param];
        }

        // 3. Préparation et Exécution de la REQUÊTE COMPLÈTE

        // IMPORTANT : Utilisez la chaîne $sql que nous venons de construire !
        $query = $this->pdoconnect()->prepare($sql);

        // Exécutez avec les paramètres seulement s'il y en a.
        // PDO gère l'exécution avec ou sans paramètres de manière sécurisée.
        $query->execute($params);

        return $query->fetchAll();
    }



    /**
     * @return mixed
     */
    public function getSearchTerm()
    {
        return $this->search_term;
    }

    /**
     * @param mixed $search_term
     */
    public function setSearchTerm($search_term)
    {
        $this->search_term = $search_term;
    }






}