<?php

class configDb {

    // On déclare les propriétes de la class //
    private $_db;    

    // Constructeur permettant d'instancier la clas PDO pour connexion à la BDD //
    public function __construct()
    {
        try
        {
            // Intanciation de la class PDO avec les infos de connexion à notre BDD //
            $this -> _db = new PDO('mysql:host=localhost;dbname=flower_shop;charset=utf8','root', 'root', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING)); // remettre EXCEPTION //
        }
        catch(Exception $e)
        {
            die('Message : Erreur de connexion à votre base de donnée'.$e->getMessage());
        }        
    }

    // On crée le getter qui permettra de récupérer la propriété //
    public function getConnection()
    {
        return $this -> _db;
    }    
}