<?php
class User {

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

    // on enregistre en bdd l'utilisateur //
    public function addUser($idGender,$lastName,$firstName,$email,$password,$dateBirthday,$gdprConsent)
    {
        $query = $this -> _db -> prepare('INSERT INTO user(id_gender,lastname, firstname, email, passwd, birthday, gdpr_consent, date_add, date_upd) VALUES (?,?,?,?,?,?,?,NOW(),NOW())');
        
        // on execute la requête et on récupère dans $dbReply la valeur true ou false de l'enregistrement //
        $dbReply = $query -> execute([$idGender,$lastName,$firstName,$email,$password,$dateBirthday,$gdprConsent]);
        
        // on retourne la variable $dbReply pour afficher le message ok ou erreur //
        return $dbReply; 
    }

    // On met à jour les données de l'utilisateur //
    public function updateUser($idUser,$idGender,$lastName,$firstName,$email,$password,$dateBirthday,$gdprConsent)
    {
        $query = $this -> _db -> prepare('UPDATE user SET id_gender=?, lastname=?, firstname=?, email=?, passwd=?, birthday=?, gdpr_consent=?, date_upd=NOW() WHERE id = ?');

        $userUpdated = $query -> execute([$idGender,$lastName,$firstName,$email,$password,$dateBirthday,$gdprConsent,$idUser]);

        return $userUpdated;
    }

    // On sélectionne tous les clients //
    public function getAllUsers()
    {
        $query = $this -> _db -> prepare('SELECT id, gender_name, lastname, firstname, email, date_add FROM user INNER JOIN gender ON user.id_gender = gender.id_gender ORDER BY id DESC');
        $query -> execute(); 
        $users = $query -> fetchAll();
        
        return $users;
    }

    // Pour la notification des nouveaux clients //
    public function getCustomersNotification() 
    {
        $query = $this -> _db -> prepare('SELECT id,lastname,firstname,date_add FROM user WHERE user_notification = 1 ORDER BY id DESC LIMIT 5');
        $query -> execute(); 
        $customerNotification = $query -> fetchAll();
        
        echo json_encode($customerNotification);
    }

    // on sélectionne un utilisateur en fonction de son email //
    public function getUserByEmail($email)
    {
        $query = $this -> _db -> prepare('SELECT email FROM user WHERE email = ?');
        $query -> execute([$email]); 
        $email = $query -> fetch();
        
        return $email;
    }

    // On vérifie la connexion de l'utilisateur //
    public function getVerifyUser($email) {
        
        $query = $this -> _db -> prepare('SELECT id, lastname, firstname, email, passwd FROM user WHERE email = ?');
        $query -> execute([$email]);
        $logUser = $query -> fetch();
        
        return $logUser;
    }

    // On vérifie l'ancien mot de passe avec l'id utilisateur //
    // Sert lors de la modification des infos utilisateurs //
    public function getVerifPassword($idUser)
    {
        $query = $this -> _db -> prepare('SELECT passwd FROM user WHERE id = ?');
        $query -> execute([$idUser]);
        $confirmPass = $query -> fetch();

        return $confirmPass;
    }

    // On récupère les infos du client avec son id //
    public function getUserById($idUser)
    {
        $query = $this -> _db -> prepare('SELECT gender_name, user.id_gender, lastname, firstname, email, birthday, date_add FROM user INNER JOIN gender ON user.id_gender = gender.id_gender WHERE user.id = ?');
        $query -> execute([$idUser]);
        $customer = $query -> fetch();        
       
        return $customer;
    }    

    // On récupère les adresses du client avec son id //
    public function getUserAddressesById($idUser)
    {
        $query = $this -> _db -> prepare('SELECT id_address, alias, lastname, firstname, company, addresse, address_comp, zip_code, city, country, phone FROM addresse WHERE id_user = ?');
        $query -> execute([$idUser]);
        $address = $query -> fetchAll();

        return $address;
    }

    // On récupère l'adresse du client avec l'id de l'adresse //
    public function getUserAddressByIdAddress($id)
    {
        $query = $this -> _db -> prepare('SELECT id_address, id_user, alias, lastname, firstname, company, addresse, address_comp, zip_code, city, country, phone FROM addresse WHERE id_address = ?');
        $query -> execute([$id]);
        $address = $query -> fetch();

        return $address;
    }

    // On met à jour l'adresse de l'utilisateur avec l'id //
    public function updateUserAddress($id,$alias,$lastName,$firstName,$company,$address,$addressComp,$zipCode,$city,$country,$phone)
    {
        $query = $this -> _db -> prepare('UPDATE addresse SET alias=?, lastname=?, firstname=?, company=?, addresse=?, address_comp=?, zip_code=?, city=?, country=?, phone=? WHERE id_address = ?');
        $addressUpdated = $query -> execute([$alias,$lastName,$firstName,$company,$address,$addressComp,$zipCode,$city,$country,$phone,$id]);

        return $addressUpdated;
    }
    
    // On supprime l'adresse de la BDD avec l'id //
    public function deleteAddressById($id)
    {
        $query = $this -> _db -> prepare('DELETE FROM addresse WHERE id_address = ?');
        $addressDeleted = $query -> execute([$id]);

        return $addressDeleted;
    }

    // On ajoute une nouvelle adresse à l'utilisateur en BDD //
    public function addAdressByUserId($idUser,$alias,$lastName,$firstName,$company,$address,$addressComp,$zipCode,$city,$country,$phone)
    {
        $query = $this -> _db -> prepare('INSERT INTO addresse (id_user,alias,lastname,firstname,company,addresse,address_comp,zip_code,city,country,phone) VALUE (?,?,?,?,?,?,?,?,?,?,?)');
        $addressAdd = $query -> execute([$idUser,$alias,$lastName,$firstName,$company,$address,$addressComp,$zipCode,$city,$country,$phone]);

        return $addressAdd;
    }
    
    // Statistiques : Age moyen des clients //
    public function getAverageUserAge()
    {
        $query = $this -> _db -> prepare('SELECT AVG(DATEDIFF(NOW(),birthday))/365 AS averageAge FROM user WHERE birthday IS NOT NULL');
        $query -> execute();
        $averageUserAge = $query -> fetch();

        return $averageUserAge;
    }

    // Statistiques : Répartition des hommes //
    public function getGenderDistribution()
    {
        $query = $this -> _db -> prepare('SELECT gender_name, COUNT(gender_name) as genre FROM user INNER JOIN gender ON user.id_gender = gender.id_gender WHERE user.id_gender = 1 GROUP BY gender_name');
        $query -> execute();
        $genderDistribution = $query -> fetch();

        return $genderDistribution;
    }

    // Statistiques : Nombre de clients //
    public function getNumberCustomers()
    {
        $query = $this -> _db -> prepare('SELECT COUNT(id) as total FROM user');
        $query -> execute();
        $numberCustomers = $query -> fetch();

        return $numberCustomers;
    }

    // Pour notifications nouveaux clients //
    public function getUserNotification()
    {
        $query = $this -> _db -> prepare('SELECT COUNT(user_notification) AS userNotification FROM user WHERE user_notification = 1');
        $query -> execute();
        $userNotification = $query -> fetch();

        return $userNotification;
    }

    // Mise à jour statut nouveau client //
    public function updateNotification($notification,$idUser)
    {
        $query = $this -> _db -> prepare('UPDATE user SET user_notification=? WHERE id = ? AND user_notification = 1');
        $query -> execute([$notification,$idUser]);
    }
}