<?php

class AdminFlowerWorkshopController extends AdminController
{
    public function displayFlowerWorkshop()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
            // Instanciation du modèle //
            $this -> _flowerWorkshop = new FlowerWorkshop();

            // On récupère les infos en BDD //            
            $flowersWorkshopReservations = $this -> _flowerWorkshop -> getFlowersWorkshopWithReservation();
            $flowersWorkshop = $this -> _flowerWorkshop -> getFlowersWorkshop();

            // On affiche la liste des réservations des cours floraux //
            $template = 'views/flowerworkshop/home_workshop';
            require('../../www/7GhtK232c/views/templates/layout.phtml');
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function detailsFlowerWorkshop()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
            // Instanciation du modèle //
            $this -> _flowerWorkshop = new FlowerWorkshop();
            
            if(isset($_POST['submit']) && is_numeric($_POST['idWork']))
            {   
                $idWork = $_POST['idWork'];
                $confirm = 1;

                // On fait la mise à jour de la confirmation en BDD //
                $flowerWorkshopConfirmed = $this -> _flowerWorkshop -> updateConfirmedFlowerWorkshop($idWork,$confirm);

                if($flowerWorkshopConfirmed)
                {
                    $success['success'] = 'Le cours à bien été confirmé aux clients';
                    $_SESSION['success'] = $success;
                    header('location:index.php?page=viewWorkshop');
                    exit();
                }
                else
                {
                    $errors['err'] = 'Une erreur s\est produite lors de l\'enregistrement !';
                    $_SESSION['errors'] = $errors;
                    header('location:index.php?page=viewWorkshop');
                    exit();
                }
            }
            elseif(array_key_exists('idWork',$_GET) && is_numeric($_GET['idWork']))
            {   
                $idWork = $_GET['idWork'];
                // On récupère les infos sur le cours //
                $flowerWorkshop = $this -> _flowerWorkshop -> getDetailsFlowerWorkshop($idWork);

                foreach($flowerWorkshop as $detailsflowersWorkshop)
                {
                    $confirmedFlowerWorkshop = $detailsflowersWorkshop['confirmed'];
                    $canceledFlowerWorkshop = $detailsflowersWorkshop['canceled'];
                }

                // On affiche les détails du cours //                
                $template = 'views/flowerworkshop/details_flower_workshop';
                require('../../www/7GhtK232c/views/templates/layout.phtml');
            }
            else
            {
                header('location:index.php?page=404');
                exit();
            }                    
        }
        else
        {
            header('location:index.php');
            exit();
        } 
    }

    public function createFlowerWorkshop()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
            // Instanciation du modèle //
            $this -> _flowerWorkshop = new FlowerWorkshop();

            if(isset($_POST['submit']))
            {
                // On initialise un tableau vide pour la gestion d'erreur //                
                $errors = [];

                // On initialise les dates et REGEX pour les formats à valider correctement //
                $workDateMin = date('Y-m-d');
                $workDateMax = date('Y-md', strtotime(' + 60 days'));             
                $regexActiveWork = '#^[0-1]{1}$#';

                if(!array_key_exists('workDate', $_POST) || $_POST['workDate'] == '' || $_POST['workDate'] < $workDateMin || $_POST['workDate'] > $workDateMax)
                {
                    $errors['workDate'] = 'Vous devez choisir une date valide !';
                }

                if(!array_key_exists('timeStart', $_POST) || $_POST['timeStart'] == '' || !$_POST['timeStart'] >=10 && !$_POST['timeStart'] <= 21)
                {
                    $errors['timeStart'] = 'Vous devez choisir un horaire valide !';
                }
                
                if(!array_key_exists('timeEnd', $_POST) || $_POST['timeEnd'] == '' || !$_POST['timeEnd'] >=11 && !$_POST['timeEnd'] <= 21)
                {
                    $errors['timeEnd'] = 'Vous devez choisir un horaire valide !';
                }
                
                if(!array_key_exists('maxSpace', $_POST) || $_POST['maxSpace'] == '')
                {
                    $errors['maxSpace'] = 'Vous devez choisir un nombre valide !';
                }

                if($_POST['maxSpace'] < 1 || $_POST['maxSpace'] > 20)
                {
                    $errors['maxSpace'] = 'Vous devez choisir un nombre de participants valide !';
                }

                if(!array_key_exists('priceTax', $_POST) || $_POST['priceTax'] == '' || !is_numeric(number_format($_POST['priceTax'],2)))
                {
                    $errors['priceTax'] = 'Vous devez saisir un nombre valide !';
                }

                if(!array_key_exists('activeWork', $_POST) || $_POST['activeWork'] == '' || !preg_match($regexActiveWork, $_POST['activeWork']))
                {
                    $errors['activeWork'] = 'Vous devez choisir d\'activer ou non le cours !';
                }

                if(!empty($errors))
                {   
                    $_SESSION['errors'] = $errors;
                    header('location:index.php?page=createFlowerWorkshop');
                    exit();
                }   
                else
                {
                    // On enregistre le cours dans la BDD //
                    // On traite la date pour récupérer le jour de la semaine au format numérique //
                    $workDate = new DateTime($_POST['workDate']);
                    $idDay = $workDate->format('w');

                    // On traite la suite du formulaire //
                    $workDate = $_POST['workDate'];
                    $timeStart = $_POST['timeStart'];
                    $timeEnd = $_POST['timeEnd'];
                    $maxSpace = $_POST['maxSpace'];
                    $priceTax = $_POST['priceTax'];
                    $activeWork = $_POST['activeWork'];

                    // Au départ, les places restantes disponibles sont égales au maximum de participants //
                    $spaceAvailable = $_POST['maxSpace'];

                    // On lance l'enregitrement en BDD //
                    $lastIdWork = $this -> _flowerWorkshop -> addFlowerWorkshop($idDay,$workDate,$timeStart,$timeEnd,$maxSpace,$spaceAvailable,$priceTax,$activeWork);                    
                    $flowerWorkCreated = $this -> _flowerWorkshop -> addFlowerWorkshopUser($lastIdWork);

                    if($flowerWorkCreated)
                    {
                        $success['save'] = 'Votre cours à bien été enregistré.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=viewWorkshop');
                        exit();
                    }
                    else
                    {
                        $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=createFlowerWorkshop');
                        exit();
                    }
                }
            }
            else
            {
                // On affiche le template de création //
                $template = 'views/flowerworkshop/add_flower_workshop';
                require('../../www/7GhtK232c/views/templates/layout.phtml');
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }      
    }

    public function editFlowerWorkshop()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _flowerWorkshop = new FlowerWorkshop();

            if(isset($_POST['submit']))
            {
                // On initialise un tableau vide pour la gestion d'erreur //                
                $errors = [];

                // On initialise la REGEX pour le format à valider correctement //
                $regexActiveWork = '#^[0-1]{1}$#';
               
                // On récupère le nombre de particpants déjà inscrits au cours //
                $idWork = $_POST['idWork'];
                $flowerWorkshop = $this -> _flowerWorkshop -> getEditDetailsFlowerWorkshop($idWork);                           
                               
                if(!array_key_exists('maxSpace', $_POST) || $_POST['maxSpace'] == '')
                {
                    $errors['maxSpace'] = 'Vous devez choisir un nombre valide !';
                }

                if($_POST['maxSpace'] < 1 || $_POST['maxSpace'] > 20)
                {
                    $errors['maxSpace'] = 'Vous devez choisir un nombre de participants valide !';
                }

                if($_POST['maxSpace'] < ($flowerWorkshop['max_space'] - $flowerWorkshop['space_available']))
                {
                    $errors['maxSpace'] = 'Vous ne pouvez pas mettre un nombre de participants inférieur aux inscrits à ce  cours !';
                }

                if(!array_key_exists('priceTax', $_POST) || $_POST['priceTax'] == '' || !is_numeric(number_format($_POST['priceTax'],2)))
                {
                    $errors['priceTax'] = 'Vous devez saisir un nombre valide !';
                }

                if(!array_key_exists('activeWork', $_POST) || $_POST['activeWork'] == '' || !preg_match($regexActiveWork, $_POST['activeWork']))
                {
                    $errors['activeWork'] = 'Vous devez choisir d\'activer ou non le cours !';
                }                

                if(!empty($errors))
                {                   
                    $_SESSION['errors'] = $errors;                   
                    header('location:index.php?page=editFlowerWorkshopDetails&idWork='.$_POST['idWork']);
                    exit();
                }
                else
                {
                    // On fait la mise à jour en BDD du cours //
                    $maxSpace = $_POST['maxSpace'];
                    $newSpaceAvailable = $maxSpace - ($flowerWorkshop['max_space'] - $flowerWorkshop['space_available']);
                    $priceTax = $_POST['priceTax'];
                    $activeWork = $_POST['activeWork'];

                    $flowerWorkshopUpdated = $this -> _flowerWorkshop -> updateFlowerWorkshopDetails($idWork,$maxSpace,$newSpaceAvailable,$priceTax,$activeWork);

                    if($flowerWorkshopUpdated)
                    {
                        $success['success'] = 'Votre cours à bien été mis à jour.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=viewWorkshop');
                        exit();
                    }
                    else
                    {
                        $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=viewWorkshop');
                        exit();
                    }                    
                }                
            }
            elseif(array_key_exists('idWork', $_GET) && is_numeric($_GET['idWork']))
            {   
                $idWork = $_GET['idWork'];
                // On récupère les infos en BDD //            
                $flowerWorkshop = $this -> _flowerWorkshop -> getEditDetailsFlowerWorkshop($idWork);

                // On affiche le template de modification //
                $template = 'views/flowerworkshop/edit_flower_workshop';
                require('../../www/7GhtK232c/views/templates/layout.phtml');
            }
            else
            {                
                header('location:index.php?page=404');
                exit();
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function cancelFlowerWorkshop()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
            // Instanciation du modèle //
            $this -> _flowerWorkshop = new FlowerWorkshop();

            if(isset($_POST['cancel']) && is_numeric($_POST['idWork']))
            {
                // On met à jour le cours en BDD //
                $idWork = $_POST['idWork'];
                $this -> _flowerWorkshop -> cancelFlowerWorkshop($idWork);
                $canceledFlowerWorkshop = $this -> _flowerWorkshop -> canceledFlowerWorkshop($idWork);

                if($canceledFlowerWorkshop)
                {
                    $success['success'] = 'Ce cours a bien été annulé.';
                    $_SESSION['success'] = $success;
                    header('location:index.php?page=viewFlowerWorkshopDetails&idWork='.$_POST['idWork']);
                    exit();
                }
                else
                {
                    $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement !';
                    $_SESSION['errors'] = $errors;
                    header('location:index.php?page=viewFlowerWorkshopDetails&idWork='.$_POST['idWork']);
                    exit();
                }
            }
            else
            {
                header('location:index.php?page=404');
                exit();
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }      
    }    

    public function deleteFlowerWorkshop()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
            // Instanciation du modèle //
            $this -> _flowerWorkshop = new FlowerWorkshop();

            if(isset($_POST['submit']) && is_numeric($_POST['idWork']))
            {
                // On lance la suppression en BDD du cours //
                $idWork = $_POST['idWork'];
                $flowerWorkshopDeleted = $this -> _flowerWorkshop -> deleteFlowerWorkshopById($idWork);

                if($flowerWorkshopDeleted)
                {
                    $success['success'] = 'Votre cours a bien été supprimé';
                    $_SESSION['success'] = $success;
                    header('location:index.php?page=viewWorkshop');
                    exit();
                }
                else
                {
                    $errors['err'] = 'Une erreur s\est produite lors de l\'enregistrement !';
                    $_SESSION['errors'] = $errors;
                    header('location:index.php?page=viewWorkshop');
                    exit();
                }
            }
            else
            {
                header('location:index.php?page=404');
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