<?php

class SocialNetwork
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

    // On récupère la liste des réseaux sociaux activés //
    public function getActiveSocialNetworks()
    {        
        $query = $this -> _db -> prepare('SELECT id,network_name,network_url,network_img,network_description FROM social_network WHERE active = 1');        
        $query -> execute();        
        $socialNetworks = $query -> fetchAll();

        return $socialNetworks;
    }

    // On récupère la liste de tous les réseaux sociaux //
    public function getAllSocialNetworks()
    {        
        $query = $this -> _db -> prepare('SELECT id,network_name,network_url,network_img,network_description,active FROM social_network');        
        $query -> execute();        
        $socialNetworks = $query -> fetchAll();

        return $socialNetworks;
    }

    // On fait la mise à jour d'un réseau social par son Id //
    public function updateSocialNetworkById($id,$networkName,$networkUrl,$logo,$networkDescription,$active)
    {
        $query = $this -> _db -> prepare('UPDATE social_network SET network_name=?, network_url=?, network_img=?, network_description=?, active=? WHERE id = ?');
        $socialUpdate = $query -> execute([$networkName,$networkUrl,$logo,$networkDescription,$active,$id]);
        return $socialUpdate;
    }    
}