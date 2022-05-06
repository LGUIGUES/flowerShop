<?php

// Fichiers requis //
require('models/Shop.php');
require('models/SocialNetwork.php');
require('models/Category.php');
require('models/Content.php');

class LayoutController
{   
    protected $_shopInformation;    
    protected $_socialNetwork;
    protected $_category;    
    protected $_content;    
    protected $authorizedTags;    
    
    public function __construct()
    {   
        $this -> _shopInformation = new Shop();       
        $this -> _socialNetwork = new SocialNetwork();        
        $this -> _category = new Category();                        
        
        $this -> _content = new Content(); // Données dynamic dans le footer //        
        
        // On appel la méthode du model Shop pour afficher les infos dans le Footer //
        $this -> _shopInformation = $this -> _shopInformation -> getShopInformation();        
        
        // Autorisation uniquement de certaines balises dans les pages personnalisées de l'administrateur //
        $this -> authorizedTags = '<p><strong><ol><ul><li><a><em><hr><table><caption><thead><tr><th><tbody><td><s><blockquote><img>';
        
        // Initialisation d'un panier pour la session //
        if(!isset($_SESSION['basket']))
        {
            $_SESSION['basket'] = [];
        } 
    }
    
    // On vérifie si l'utilisateur est connecté à sa session //
    public function userIsConnect()
    {
        return isset($_SESSION['idUser']);
    }
}