<?php

class AdminShopServiceController extends AdminController
{   
    public function createShopService()
    {   
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _shopService = new ShopService();

            if(isset($_POST['submit']))
            {
                // On initialise un tableau vide pour la gestion d'erreur //                
                $errors = [];

                // On initialise les REGEX pour les formats à valider correctement //
                $regexActiveService = '#^[0-1]{1}$#'; // Chiffre 0 à 1 sur 1 position //

                // On vérifie que le titre est bien présent //                
                if(!array_key_exists('titleService', $_POST) || $_POST['titleService'] == '')
                {
                    $errors['titleService'] = 'Vous devez saisir un titre à votre service.';
                }

                // On vérife que le choix de l'activation du service soit bien présent //
                if(!array_key_exists('activeService', $_POST) || $_POST['activeService'] == '' || !preg_match($regexActiveService, $_POST['activeService']))
                {
                    $errors['activeService'] = 'Vous devez choisir d\'activer ou non votre service.';
                }

                if(!empty($errors))
                    {
                        $_SESSION['errors'] = $errors;
                        $_SESSION['formData'] = $_POST;                    
                        header('location:index.php?page=createShopService');
                        exit();
                    }
                    else
                    {
                        // On enregistre le nouveau service en BDD //
                        $titleService = $_POST['titleService'];
                        $descriptionService = $_POST['descriptionService'];
                        $activeService = $_POST['activeService'];

                        $serviceCreated = $this -> _shopService -> createShopService($titleService,$descriptionService,$activeService);

                        if($serviceCreated)
                        {
                            // Si tout ok, on prévient l'utilisateur //
                            $success['save'] = 'Votre nouveau service a bien été créé.';
                            $_SESSION['success'] = $success;
                            header('location:index.php?page=configuration');
                            exit();
                        }
                        else
                        {
                            // Message d'erreur //
                            $errors['err'] = 'Une erreur s\'est produite lors de la mise à jour.';
                            $_SESSION['errors'] = $errors;
                            header('location:index.php?page=configuration');
                            exit();
                        }
                    }
                                
            }
            else
            {
                // On affiche le template de création de service //
                $template = 'views/service/create_service';
                require('../../www/7GhtK232c/views/templates/layout.phtml');
            }
        }
        else
        {
            header('location:index.php');
            exit();
        } 
    }

    public function updateShopService()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _shopService = new ShopService();

            if(array_key_exists('idService', $_GET) && is_numeric($_GET['idService']))
            {
                if(isset($_POST['submit']))
                {   
                    // On initialise un tableau vide pour la gestion d'erreur //                
                    $errors = [];

                    // On initialise les REGEX pour les formats à valider correctement //
                    $regexActiveService = '#^[0-1]{1}$#'; // Chiffre 0 à 1 sur 1 position //                    

                    // On vérifie que l'id reçu correspond bien à un service en BDD //
                    $idService = $_GET['idService'];

                    // On appel la méthode du model //
                    $shopService = $this -> _shopService -> getShopServiceById($idService);

                    if($shopService['id_service'] != $_GET['idService'])
                    {
                        $errors['err'] = 'Cette page n\'existe pas !';                   
                    }

                    // On vérifie que le titre est bien présent //                
                    if(!array_key_exists('titleService', $_POST) || $_POST['titleService'] == '')
                    {
                        $errors['titleService'] = 'Vous devez saisir un titre à votre service.';
                    }

                    // On vérife que le choix de l'activation du service soit bien présent //
                    if(!array_key_exists('activeService', $_POST) || $_POST['activeService'] == '' || !preg_match($regexActiveService, $_POST['activeService']))
                    {
                        $errors['activeService'] = 'Vous devez choisir d\'activer ou non votre service.';
                    }

                    if(!empty($errors))
                    {
                        $_SESSION['errors'] = $errors;                      
                        header('location:index.php?page=updateShopService&idService='.$_GET['idService']);
                        exit();
                    }
                    else
                    {
                        // On enregistre la modification en BDD //
                        $titleService = $_POST['titleService'];
                        $descriptionService = $_POST['descriptionService'];
                        $activeService = $_POST['activeService'];

                        $serviceUpdated = $this -> _shopService -> updateShopServiceById($idService,$titleService,$descriptionService,$activeService);

                        if($serviceUpdated)
                        {
                            // Si tout ok, on prévient l'utilisateur //
                            $success['save'] = 'Votre service a bien été mis à jour.';
                            $_SESSION['success'] = $success;
                            header('location:index.php?page=updateShopService&idService='.$_GET['idService']);
                            exit();
                        }
                        else
                        {
                            // Message d'erreur //
                            $errors['err'] = 'Une erreur s\'est produite lors de la mise à jour.';
                            $_SESSION['errors'] = $errors;
                            header('location:index.php?page=configuration');
                            exit();
                        }
                    }
                }
                else
                {   
                    // On récupère en BDD les infos du service //
                    $idService = $_GET['idService'];
                    $shopService = $this -> _shopService -> getShopServiceById($idService);

                    // On affiche le template de modification du service //
                    $template = 'views/service/update_service';
                    require('../../www/7GhtK232c/views/templates/layout.phtml');
                }                
            }
            else
            {
                // Retour à l'index //               
                header('location:index.php');
                exit();
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function deleteShopService()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _shopService = new ShopService();
            
            if(array_key_exists('idService', $_GET) && is_numeric($_GET['idService']))
            {
                // On initialise un tableau vide pour la gestion d'erreur //                
                $errors = [];                           

                // On vérifie que l'id reçu correspond bien à un service en BDD //
                $idService = $_GET['idService'];

                // On appel la méthode du model //
                $shopService = $this -> _shopService -> getShopServiceById($idService);

                if($shopService['id_service'] != $_GET['idService'])
                {
                    $errors['err'] = 'Cette page n\'existe pas !';                   
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;                      
                    header('location:index.php?page=configuration');
                    exit();
                }
                else
                {
                    // On supprime la page en BDD //
                    $serviceDeleted = $this -> _shopService -> deleteServiceById($idService);

                    if($serviceDeleted)
                    {
                        // Si tout est ok, message à l'utilisateur //
                        $success['save'] = 'Votre service a bien été supprimé.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=configuration');
                        exit();
                    }
                    else
                    {
                        // Message d'erreur //
                        $errors['err'] = 'Une erreur s\'est produite lors de la suppression.';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=configuration');
                        exit();
                    }
                }
            }
            else
            {
                // Retour à l'index //               
                header('location:index.php');
                exit();
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }
}