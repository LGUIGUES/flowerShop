<?php

class FlowerWorkshop
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

    // Enregistrement d'un cours floral //
    public function addFlowerWorkshop($idDay,$workDate,$timeStart,$timeEnd,$maxSpace,$spaceAvailable,$priceTax,$activeWork)
    {
        $query = $this -> _db -> prepare('INSERT INTO flower_workshop(id_day,work_date,time_start,time_end,max_space,space_available,price_tax,active) VALUES(?,?,?,?,?,?,?,?)');
        $query -> execute([$idDay,$workDate,$timeStart,$timeEnd,$maxSpace,$spaceAvailable,$priceTax,$activeWork]);

        // On récupère l'id du dernier cours qui vient d'être enregistré //
        $lastIdWork = $this -> _db -> lastInsertId();

        // On retourne cette valeur pour la seconde opération d'enregistrement //
        return $lastIdWork;        
    }

    // Enregistrement du cours floral pour la liste des cours disponibles à l'utilisateur //
    public function addFlowerWorkshopUser($lastIdWork)
    {
        $query = $this -> _db -> prepare('INSERT INTO flower_workshop_user(id_work) VALUES(?)');
        $flowerWorkCreated = $query -> execute([$lastIdWork]);

        return $flowerWorkCreated;  
    }

    // On réupère la liste des cours floraux //
    public function getFlowersWorkshopForHome()
    {       
        $query = $this -> _db -> prepare('SELECT name_shop_day,time_start,time_end FROM flower_workshop INNER JOIN shop_days ON flower_workshop.id_day = shop_days.id_day WHERE active != 0 LIMIT 2');

        // On execute la requête //
        $query -> execute();

        // On récupère le résultat de la requête //
        $floralWorkshopInformations = $query -> fetchAll();

        // On retourne les données au controlleur //
        return $floralWorkshopInformations;
    }
    
    // On réupère la liste des cours floraux avec des inscrits pour affichage //
    public function getFlowersWorkshopWithReservation()
    {        
        $query = $this -> _db -> prepare('SELECT id_work,work_date,time_start,time_end,max_space,COUNT(id_user) as guests,active,cancel FROM flower_workshop NATURAL JOIN flower_workshop_user WHERE id_user GROUP BY id_work ORDER BY work_date ASC');

        // On execute la requête //
        $query -> execute();

        // On récupère le résultat de la requête //
        $flowersWorkshopReservations = $query -> fetchAll();

        // On retourne les données au controlleur //
        return  $flowersWorkshopReservations;
    }

    // On récupère la liste des cours floraux //
    public function getFlowersWorkshop()
    {        
        $query = $this -> _db -> prepare('SELECT id_work,work_date,time_start,time_end,max_space,space_available,active FROM flower_workshop NATURAL JOIN flower_workshop_user WHERE max_space = space_available GROUP BY id_work ORDER BY work_date ASC');

        // On execute la requête //
        $query -> execute();

        // On récupère le résultat de la requête //
        $flowersWorkshop = $query -> fetchAll();

        // On retourne les données au controlleur //
        return  $flowersWorkshop;
    }

    // On récupère la liste des cours floraux pour affichage dans le Dashboard //
    public function getFlowersWorkshopDashboard()
    {
        $query = $this -> _db -> prepare('SELECT id_work,work_date,time_start,time_end,max_space,COUNT(id_user) as guests,active FROM flower_workshop NATURAL JOIN flower_workshop_user WHERE id_work GROUP BY id_work ORDER BY work_date ASC LIMIT 5');

        // On execute la requête //
        $query -> execute();

        // On récupère le résultat de la requête //
        $flowersWorkshop = $query -> fetchAll();

        // On retourne les données au controlleur //
        return  $flowersWorkshop;
    }

    // On récupère la liste des cours floraux pour le choix des utilisateurs //
    public function getAllFlowersWorkshopForUser()
    {        
        $query = $this -> _db -> prepare('SELECT id_work,name_shop_day,work_date,time_start,time_end,space_available,price_tax,active FROM flower_workshop INNER JOIN shop_days ON flower_workshop.id_day = shop_days.id_day WHERE active != 0 AND space_available != 0 AND cancel != 1 ORDER BY work_date ASC');

        // On execute la requête //
        $query -> execute();

        // On récupère le résultat de la requête //
        $flowersWorkshop = $query -> fetchAll();

        // On retourne les données au controlleur //
        return  $flowersWorkshop;
    }

    // On récupère la liste des cours floraux de l'utilisateur //
    public function getFlowersWorkshopUserById($idUser)
    {
        $query = $this -> _db -> prepare('SELECT flower_workshop_user.id_work,name_shop_day,work_date,time_start,time_end,price_tax,confirmed,canceled,active FROM flower_workshop_user INNER JOIN flower_workshop ON flower_workshop_user.id_work = flower_workshop.id_work INNER JOIN shop_days ON flower_workshop.id_day = shop_days.id_day WHERE id_user = ? AND flower_workshop.id_work = flower_workshop_user.id_work AND active != 0 ORDER BY work_date ASC');
        $query -> execute([$idUser]);
        $flowersWorkshopUser = $query -> fetchAll();

        return $flowersWorkshopUser;
    }

    // On récupère un cours floral avec son Id pour vérifier les places disponible //
    public function getFlowerWorkshopById($idWork)
    {
        $query = $this -> _db -> prepare('SELECT id_work, space_available FROM flower_workshop WHERE id_work = ?');
        $query -> execute([$idWork]);
        $verifBookingDay = $query -> fetch();
        
        return $verifBookingDay;
    }

    // On récupère un cours floral avec son id pour vérifier si l'utilisateur n'est pas déjà inscrit sur ce cours //
    public function getFlowerWorkshopUser($idWork)
    {
        $query = $this -> _db -> prepare('SELECT id_work, id_user FROM flower_workshop_user WHERE id_work = ?');
        $query -> execute([$idWork]);
        $verifBookingUser = $query -> fetch();
        
        return $verifBookingUser;
    }

    // On récupère les infos sur un cours floral avec son Id pour affichage //
    public function getDetailsFlowerWorkshop($idWork)
    {
        $query = $this -> _db -> prepare('SELECT id_user,firstname,lastname,work_date,time_start,time_end,price_tax,confirmed,canceled,flower_workshop_user.date_upd FROM flower_workshop INNER JOIN flower_workshop_user ON flower_workshop.id_work = flower_workshop_user.id_work INNER JOIN user ON flower_workshop_user.id_user = user.id WHERE flower_workshop.id_work = ?');
        $query -> execute([$idWork]);
        $flowerWorkshop = $query -> fetchAll();
        
        return $flowerWorkshop;
    }

    // On récupère les infos sur un cours floral avec son Id pour édition // 
    public function getEditDetailsFlowerWorkshop($idWork)
    {
        $query = $this -> _db -> prepare('SELECT id_work,work_date,time_start,time_end,max_space,space_available,price_tax,active,confirmed FROM flower_workshop NATURAL JOIN flower_workshop_user WHERE id_work = ? GROUP BY confirmed');

        // On execute la requête //
        $query -> execute([$idWork]);

        // On récupère le résultat de la requête //
        $flowerWorkshop = $query -> fetch();

        // On retourne les données au controlleur //
        return  $flowerWorkshop;
    }

    // On enregistre la réservation d'un cours floral par un utilisateur //
    public function registrationUserFlowerWorkshop($idWork,$idUser)
    {
        $query = $this -> _db -> prepare('INSERT INTO flower_workshop_user (id_work,id_user) VALUES(?,?)');
        $flowerWorkshopValided = $query -> execute([$idWork,$idUser]);
        
        return $flowerWorkshopValided;
    }

    // On met à jour les places disponibles sur un cours floral //
    public function updateAvailableSpaceFlowerWorkshopById($idWork,$newAvailableSpace)
    {
        $query = $this -> _db -> prepare('UPDATE flower_workshop SET space_available=? WHERE id_work = ?');
        $query -> execute([$newAvailableSpace,$idWork]);
    }

    // On met à jour le statut de confirmation d'un cours floral //
    public function updateConfirmedFlowerWorkshop($idWork,$confirm)
    {
        $query = $this -> _db -> prepare('UPDATE flower_workshop_user SET confirmed=?,date_upd=NOW() WHERE id_work = ?');
        $flowerWorkshopConfirmed = $query -> execute([$confirm,$idWork]);

        return $flowerWorkshopConfirmed;
    }

    // On met à jour les infos d'un cours floral après son édition pour modifications //
    public function updateFlowerWorkshopDetails($idWork,$maxSpace,$newSpaceAvailable,$priceTax,$activeWork)
    {
        $query = $this -> _db -> prepare('UPDATE flower_workshop SET max_space=?,space_available=?,price_tax=?,active=? WHERE id_work = ?');
        $flowerWorkshopUpdated = $query -> execute([$maxSpace,$newSpaceAvailable,$priceTax,$activeWork,$idWork]);

        return $flowerWorkshopUpdated;
    }

    // On supprime un cours floral dans la table gérant les réservations utilisateur si pas d'utilisateurs inscrits //
    public function deleteFlowerWorkshopDetails($idWork)
    {
        $query = $this -> _db -> prepare('DELETE FROM flower_workshop_user WHERE id_work = ? AND id_user = 0');
        $query -> execute([$idWork]);
    }
    
    // On supprime un cours floral avec son Id //
    public function deleteFlowerWorkshopById($idWork)
    {
        $query = $this -> _db -> prepare('DELETE FROM flower_workshop WHERE id_work = ?');
        $flowerWorkshopDeleted = $query -> execute([$idWork]);

        return $flowerWorkshopDeleted;
    }

    // On supprime un cours floral dans la table gérant les réservations utilisateur si le cours n'a pas été confirmé //
    public function deleteUserFlowerWorkshop($idWork,$idUser)
    {
        $query = $this -> _db -> prepare('DELETE FROM flower_workshop_user WHERE id_work = ? AND id_user = ? AND confirmed != 1');
        $flowerWorkshopDeleted = $query -> execute([$idWork,$idUser]);

        return $flowerWorkshopDeleted;
    }
    
    // On ajoute l'id du cours dans la table gérant les réservations utilisateur //
    public function addFlowerWorkshopDetails($idWork)
    {
        $query = $this -> _db -> prepare('INSERT INTO flower_workshop_user (id_work) VALUES(?)');
        $query -> execute([$idWork]);
    }

    // On met à jour le statut d'un cours floral par son Id //
    public function cancelFlowerWorkshop($idWork)
    {
        $query = $this -> _db -> prepare('UPDATE flower_workshop SET cancel=1 WHERE id_work = ?');
        $query -> execute([$idWork]);
    }

    // On met à jour le statut d'un cours floral dans la table gérant les réservations utilisateur //
    public function canceledFlowerWorkshop($idWork)
    {
        $query = $this -> _db -> prepare('UPDATE flower_workshop_user SET canceled=1,date_upd=NOW() WHERE id_work = ?');
        $canceledFlowerWorkshop = $query -> execute([$idWork]);

        return $canceledFlowerWorkshop;
    }
}