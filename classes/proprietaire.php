<?php

require 'Database.php';

class proprietaire
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getLieux()
    {
        $connexion = $this->db->connectDb();
        $q = $connexion->prepare("SELECT * FROM lieux");
        $q->execute();
        return $q->fetchAll();
    }

    public function getTypesEmplacement()
    {
        $connexion = $this->db->connectDb();
        $q = $connexion->prepare("SELECT * FROM types_emplacement");
        $q->execute();
        return $q->fetchAll();
    }

    public function getOptions()
    {
        $connexion = $this->db->connectDb();
        $q = $connexion->prepare("SELECT * FROM options");
        $q->execute();
        return $q->fetchAll();
    }

}
?>