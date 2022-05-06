<?php

class AdminHomeController extends AdminController
{
    public function configurationHomePage()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // On affiche le template de modification de la page d'accueil //
            $template = 'views/configuration/home/home';
            require('../../www/7GhtK232c/views/templates/layout.phtml');
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }
}