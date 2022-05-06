<?php

class ShopService
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

    // On récupère en BDD les services de la boutique //
    public function getShopServices()
    {        
        $query = $this -> _db -> prepare('SELECT id_service, title_service, description_service, active FROM shop_service');

        // On execute la requête //
        $query -> execute();

        // On récupère le résultat de la requête //
        $shopServices = $query -> fetchAll();

        // On retourne les données au controlleur //
        return $shopServices;
    }

    // On récupère en BDD les services de la boutique //
    public function getShopServiceById($idService)
    {        
        $query = $this -> _db -> prepare('SELECT id_service, title_service, description_service, active FROM shop_service WHERE id_service = ?');

        // On execute la requête //
        $query -> execute([$idService]);

        // On récupère le résultat de la requête //
        $shopService = $query -> fetch();

        // On retourne les données au controlleur //
        return $shopService;
    }

    // On enregistre un service //
    public function createShopService($titleService,$descriptionService,$activeService)
    {
        $query = $this -> _db -> prepare('INSERT INTO shop_service(title_service, description_service, active) VALUES (?,?,?)');
        $serviceCreated = $query -> execute([$titleService,$descriptionService,$activeService]);

        return $serviceCreated;

    }

    // On fait la mise à jour d'un service //
    public function updateShopServiceById($idService,$titleService,$descriptionService,$activeService)
    {
        $query = $this -> _db -> prepare('UPDATE shop_service SET title_service=?, description_service=?, active=? WHERE id_service = ?');
        $serviceUpdated = $query -> execute([$titleService,$descriptionService,$activeService,$idService]);

        return $serviceUpdated;

    }

    // On supprime un service avec son Id //
    public function deleteServiceById($idService)
    {
        $query = $this -> _db -> prepare('DELETE FROM shop_service WHERE id_service = ?');
        $serviceDeleted = $query -> execute([$idService]);

        return $serviceDeleted;
    }
}