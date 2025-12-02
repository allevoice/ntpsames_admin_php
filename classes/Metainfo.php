<?php

/**
 * Created by PhpStorm.
 * User: rj027
 * Date: 11/30/2025
 * Time: 10:10 PM
 */
class Metainfo extends Mainclass
{
    protected   $table = "meta_tags";


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


    private function tablecreate(){
        //dd('Table');
        $this->pdoconnect()->exec("CREATE TABLE IF NOT EXISTS ".$this->table." (
            id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
            title varchar(250) NULL,
            typedr varchar(250) NULL,
            content text COLLATE latin1_general_ci  NULL,
            statuts int(11) NULL,
            created_at datetime COLLATE latin1_general_ci NULL,
            updated_at datetime COLLATE latin1_general_ci NULL,
            PRIMARY KEY (id) )
            ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;");
        return true;
    }




}