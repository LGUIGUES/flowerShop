<?php

class Contact
{
    // propriétés de la class //
    private $_connectDb;
    private $_db;

    public function __construct()
    {
        // On instancie la class configDb pour la connexion //
        $this -> _connectDb = new configDb();

        // On appel le getter de la class pour faire la connexion //
        $this -> _db = $this -> _connectDb -> getConnection();
    }
    
    // On ajoute un contact en BDD //
    public function addContact($emailGuest,$messageGuest)
    {
    $query = $this -> _db -> prepare('INSERT INTO contacts(emailGuest,messageGuest,dateMessageGuest) VALUES (?,?,NOW())');
        
        $messageAdd = $query -> execute([$emailGuest,$messageGuest]);

        return $messageAdd;
    }
}