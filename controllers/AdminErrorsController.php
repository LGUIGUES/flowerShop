<?php 

class AdminErrorsController extends AdminController
{
    public function displayErrors()
    {   
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Affichage du template //
            $template = 'views/static/404';
            require('../../www/7GhtK232c/views/templates/layout.phtml');
        }
        else
        {
            header('location:index.php');
            exit();
        } 
    }  
}