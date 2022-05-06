<?php

class Admin
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

    public function getEmptyAdmin()
    {
        // On recherche dans la BDD si il existe un Administrateur //
        $query = $this -> _db -> prepare('SELECT id_admin FROM employee');

        // On execute la requête //
        $query -> execute();

        // On récupère le résultat de la requête //
        $dbReply = $query -> fetch();

        // On retourne au controlleur la réponse de la BDD //
        return $dbReply;
    }

    public function addAdmin($lastname,$firstname,$email,$password,$employee)
    {   
        // On prepare la requête d'insertion dans la bdd //
        $query = $this -> _db -> prepare('INSERT INTO employee(lastname,firstname,email,passwd,id_employee,date_creation) VALUES (?,?,?,?,?,NOW())');
        
        // On récupère dans $validation le retour de l'insertion (true ou false) //
        $validation = $query -> execute([$lastname,$firstname,$email,$password,$employee]);

        // On retourne la réponse pour afficher un message à l'utilisateur //
        return $validation;
    }

    public function getAdminByEmail($email)
    {
        // On prepare la requête de recherche d'un adminstrateur dans la BDD avec son Email //
        $query = $this -> _db -> prepare('SELECT email FROM employee WHERE email = ?');
        
        // On execute la requête //
        $query -> execute([$email]);

        // On récupère l'email si il existe ou la valeur false // 
        $validEmail = $query -> fetch();

        /* On retourne $validEmail pour afficher un message à l'utilisateur 
        ou pour continuer le traitement de connexion */
        return $validEmail;
    }

    public function getVerificationAdmin($email)
    {
        // On vérifie la connexion de l'administrateur //
        $query = $this -> _db -> prepare('SELECT id_admin, lastname, firstname, email, passwd, id_employee FROM employee WHERE email = ?');

        // On execute la requête //
        $query -> execute([$email]);

        // On récupère la ligne complète en BDD de cet administrateur //
        $logAdmin = $query -> fetch();

        // On retourne $logAdmin au controlleur pour verifier l'authentification //
        return $logAdmin;
    }
}