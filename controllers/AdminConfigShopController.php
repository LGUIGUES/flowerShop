<?php

class AdminConfigShopController extends AdminController
{   
    public function configurationShop()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation des modèles //
            $this -> _shop = new Shop();
            $this -> _socialNetwork = new SocialNetwork();
            $this -> _workHour = new WorkHour();
            $this -> _shopService = new ShopService();
            
            // On récupère les infos sur la boutique //
            $shopInformation = $this -> _shop -> getShopInformation();
            $socialNetworks = $this -> _socialNetwork -> getActiveSocialNetworks();
            $workingHours = $this -> _workHour -> getShopWorkHours();
            $shopServices = $this -> _shopService -> getShopServices();

            // On affiche le template //
            $template = 'views/configuration/configuration';
            require('../../www/7GhtK232c/views/templates/layout.phtml');
        }
        else
        {
            header('location:index.php');
            exit();
        }
    }
}