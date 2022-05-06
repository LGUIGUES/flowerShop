<?php

class Shop
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

    // On récupère en BDD les informations (nom, adresse, téléphone...) de la boutique //
    public function getShopInformation()
    {        
        $query = $this -> _db -> prepare('SELECT id,shop.id_gender,gender.gender_name,lastname,firstname,capacity,company,addresse,zip_code,city,country,phone,siret,rcs,localization_map,homepage_teaser_title FROM shop INNER JOIN gender ON shop.id_gender = gender.id_gender');

        // On execute la requête //
        $query -> execute();

        // On récupère le résultat de la requête //
        $shopInformation = $query -> fetch();

        // On retourne les données au controlleur //
        return $shopInformation;
    }
    
    // On récupère le titre d'accroche pour la Home Page //
    public function getHomepageTeaserTitle()
    {
        $query = $this -> _db -> prepare('SELECT homepage_teaser_title FROM shop');
        $query -> execute();
        $homePageTeaserTitle = $query -> fetch();

        return $homePageTeaserTitle;
    }

    // On fait la mise à jour des inforamtions de la boutique //
    public function updateShopInformation($id,$idGender,$lastName,$firstName,$capacity,$company,$address,$zipCode,$city,$country,$phone,$siret,$rcs,$homepageTeaserTitle,$localizationMap)
    {
        $query = $this -> _db -> prepare('UPDATE shop SET id_gender=?, lastname=?, firstname=?, capacity=?, company=?, addresse=?, zip_code=?, city=?, country=?, phone=?, siret=?, rcs=?, localization_map=?, homepage_teaser_title=? WHERE id = ?');
        $shopInfoValided = $query -> execute([$idGender,$lastName,$firstName,$capacity,$company,$address,$zipCode,$city,$country,$phone,$siret,$rcs,$localizationMap,$homepageTeaserTitle,$id]);

        return $shopInfoValided;
    }
}