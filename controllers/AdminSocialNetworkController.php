<?php

class AdminSocialNetworkController extends AdminController
{
    public function updateSocialNetworkInformation()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _socialNetwork = new SocialNetwork();

            if(isset($_POST['submit']))
            {   
                // On initialise un tableau vide pour la gestion d'erreur //
                $errors = [];                
                
                if(!array_key_exists('networkName', $_POST) || $_POST['networkName'] == '')
                {
                    $errors['networkName'] = 'Vous devez saisir un nom pour le réseau social.';
                }

                if(!array_key_exists('networkUrl', $_POST) || $_POST['networkUrl'] == '' || !filter_var($_POST['networkUrl'], FILTER_VALIDATE_URL))
                {
                    $errors['networkUrl'] = 'Vous devez saisir un lien valide vers la page du réseau social.';
                }

                if(!array_key_exists('networkDescription', $_POST) || $_POST['networkDescription'] == '' || strlen($_POST['networkDescription']) > 40)
                {
                    $errors['networkDescription'] = 'Vous devez saisir une description valide pour le logo.';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['formData'] = $_POST;
                    header('location:index.php?page=updateSocial');
                    exit();
                }
                else
                {
                    // Si tout ok, on récupère les infos du formulaire //
                    $id = $_POST['id'];
                    $logo = $_POST['logo'];
                    $networkName = $_POST['networkName'];
                    $networkUrl = $_POST['networkUrl'];
                    $networkDescription = $_POST['networkDescription'];
                    $active = $_POST['active'];

                    // On fait la mise à jour des infos dans la BDD //
                    $socialUpdate = $this -> _socialNetwork -> updateSocialNetworkById($id,$networkName,$networkUrl,$logo,$networkDescription,$active);

                    if($socialUpdate)
                    {
                        // Si tout est ok, on affiche un message à l'utilisateur //
                        // On récupère en BDD les infos mises à jour //
                        $socialNetworks = $this -> _socialNetwork -> getAllSocialNetworks();

                        $success['save'] = 'Vos informations ont bien été mises à jour.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=updateSocial');
                        exit();
                    }
                    else
                    {
                        // Message d'erreur //
                        $errors['err'] = 'Une erreur s\'est produite !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=updateSocial');
                        exit();
                    }
                }
            }
            else
            {
                // On récupère les infos sur les réseaux sociaux//
                $socialNetworks = $this -> _socialNetwork -> getAllSocialNetworks();
                
                // On affiche le template //
                $template = 'views/configuration/social/update_social';
                require('../../www/7GhtK232c/views/templates/layout.phtml');
            }            
        }
        else
        {
            header('location:index.php');
            exit();
        }       
    }
}