<?php

class Contact extends Mainclass
{

    protected   $table = "contact_template";
    protected   $id;
    protected   $fullname;
    protected   $email;
    protected   $subject;



    protected   $leveld;
    protected   $content;
    protected   $statuts;
    protected   $iduser;
    protected   $created_at;
    protected   $updated_at;
    protected   $deleted_at;



    public function valuesdata(){
        return [
            'fullname' => $this->fullname, // OK si NULL
            'email' => $this->email,       // 🛑 DOIT être une chaîne non-NULL
            'content' => $this->content,   // OK si NULL
            'subject' => $this->subject
        ];
    }


    public function __construct()
    {
        //dd('creation de la table');
        if ($this->tableExists($this->table) == false) {
            $create = $this->tablecreate();
            if ($create == false) {
                echo 'Problem lors de l\' insertion ';
            }
        }
    }



    public function findPaginatedAndFilteredmail(string $search_term, int $current_page, int $elements_per_page): array
    {
        // 1. Définition des clauses WHERE et des paramètres pour la requête préparée
        $where_clauses = ["deleted_at IS NULL"];
        $params = [];

        if (!empty($search_term)) {
            $where_clauses[] = "(fullname LIKE :search OR email LIKE :search OR subject LIKE :search OR content LIKE :search)";
            $params['search'] = '%' . $search_term . '%';
        }

        $where_sql = count($where_clauses) > 0 ? "WHERE " . implode(' AND ', $where_clauses) : "";

        // 2. Compter le total (sans limite de pagination)
        $count_sql = "SELECT COUNT(id) AS total FROM " .$this->table . " " . $where_sql;
        $stmt_count = $this->pdoconnect()->prepare($count_sql);
        $stmt_count->execute($params);
        $total_mails = $stmt_count->fetch()['total'];

        // 3. Calculer l'offset de pagination
        $total_pages = ceil($total_mails / $elements_per_page);
        $total_pages = max(1, $total_pages);
        $current_page = max(1, min($current_page, $total_pages));
        $start_index = ($current_page - 1) * $elements_per_page;

        // 4. Récupérer les données pour la page actuelle
        $sql = "SELECT id, fullname, email, subject, content, is_read, created_at 
                FROM " .$this->table. " "
            . $where_sql
            . " ORDER BY created_at DESC LIMIT :start_index, :elements_per_page";

        $stmt = $this->pdoconnect()->prepare($sql);

        // Bind des paramètres de recherche
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        // Bind des paramètres de pagination (types int)
        $stmt->bindValue('start_index', $start_index, PDO::PARAM_INT);
        $stmt->bindValue('elements_per_page', $elements_per_page, PDO::PARAM_INT);

        $stmt->execute();
        $mails = $stmt->fetchAll();

        return [
            'mails' => $mails,
            'total' => $total_mails,
            'totalPages' => $total_pages,
            'currentPage' => $current_page
        ];
}



    public function markStatus(array $ids, int $status): bool
    {
        // Créer une chaîne de marqueurs de position (?, ?, ?)
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "UPDATE " . $this->table . " SET is_read = ?, updated_at = NOW() WHERE id IN ($placeholders)";

        // Les IDs doivent être combinés avec le statut au début
        $params = array_merge([$status], $ids);

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }


    public function markStatus_read($ids,  $status): bool
    {
        $sql = "UPDATE " . $this->table . " SET is_read = ".$status.", updated_at = NOW() WHERE id=".$ids;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute();
    }



    public function deleteMails(array $ids): bool
    {
        // Créer une chaîne de marqueurs de position (?, ?, ?)
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        // Utilisez 'deleted_at' pour le soft delete
        $sql = "UPDATE " . $this->table . " SET deleted_at = NOW() WHERE id IN ($placeholders)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($ids);
    }


    public function deleteMailsDefinitive(array $ids): bool
    {
        if (empty($ids)) {
            return false; // Évite d'exécuter une requête vide
        }

        // Créer une chaîne de marqueurs de position (?, ?, ?)
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        // Requête SQL pour la suppression définitive
        $sql = "DELETE FROM " . $this->table . " WHERE id IN ($placeholders)";

        $stmt = $this->pdo->prepare($sql);

        // La méthode execute retourne un booléen
        return $stmt->execute($ids);
    }


    public function getMailsByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "SELECT fullname, email, subject, content FROM " . $this->table . " WHERE id IN ($placeholders)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($ids);

        return $stmt->fetchAll();
    }





    public function mark_mail_read(int $id){
        // Requête SQL pour sélectionner toutes les colonnes d'une ligne spécifique
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ?";

        // Prépare la requête
        $stmt = $this->pdoconnect()->prepare($sql);

        // Exécute la requête en passant l'ID comme paramètre
        $stmt->execute([$id]);

        // Récupère la première (et unique) ligne trouvée sous forme de tableau associatif
        // Utilisez fetch() car on attend un seul résultat
        return $stmt->fetch();
    }


    private function tablecreate(){
        $this->pdoconnect()->exec("CREATE TABLE IF NOT EXISTS ".$this->table." (
            id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
            fullname varchar(250) NULL,
            email VARCHAR(255) NOT NULL,
            subject VARCHAR(255) NOT NULL,
            content text COLLATE latin1_general_ci  NULL,
            is_read int(100) NULL,
            created_at datetime COLLATE latin1_general_ci NULL,
            updated_at datetime COLLATE latin1_general_ci NULL,
            deleted_at datetime COLLATE latin1_general_ci NULL,
            PRIMARY KEY (id) )
            ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;");
        return true;
    }





    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $fullname
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getLeveld()
    {
        return $this->leveld;
    }

    /**
     * @param mixed $leveld
     */
    public function setLeveld($leveld)
    {
        $this->leveld = $leveld;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getStatuts()
    {
        return $this->statuts;
    }

    /**
     * @param mixed $statuts
     */
    public function setStatuts($statuts)
    {
        $this->statuts = $statuts;
    }

    /**
     * @return mixed
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * @param mixed $iduser
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * @param mixed $deleted_at
     */
    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
    }








}