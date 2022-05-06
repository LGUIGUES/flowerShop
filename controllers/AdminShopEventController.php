<?php

class AdminShopEventController extends AdminController
{
    public function configurationEvents()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _shopEvent = new ShopEvent();

            // On récupère la liste de tous les évènements //
            $shopEvents = $this -> _shopEvent -> getAllEvents();

            // Autorisation uniquement de certaines balises pour l'affichage //
            $tags = '<p><strong><ol><ul><li><a><em><hr><table><caption><thead><tr><th><tbody><td><s><blockquote><img>';

            // On affiche le template de la liste des évènements de la boutique //
            $template = 'views/shopEvent/home_events';
            require('../../www/7GhtK232c/views/templates/layout.phtml');
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }
    
    public function createShopEvent()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _shopEvent = new ShopEvent();

            // Instanciation Class //
            $this -> _cleaner = new cleaner();

            if(isset($_POST['submit']))
            {
                // On initialise un tableau vide pour la gestion d'erreur //                
                $errors = [];

                // On initialise les REGEX pour les formats à valider correctement //
                $regexValidator = '#^[0-1]{1}$#';
                $eventDateMin = date('Y-m-d');
                
                if(!array_key_exists('activeEvent', $_POST) || $_POST['activeEvent'] == '' || !preg_match($regexValidator, $_POST['activeEvent']))
                {
                    $errors['activeEvent'] = 'Vous devez choisir d\'activer ou non l\'évènement !';
                }

                if(!array_key_exists('titleEvent', $_POST) || $_POST['titleEvent'] == '')
                {
                    $errors['titleEvent'] = 'Vous devez saisir un titre à votre évènement !';
                }

                if(!array_key_exists('textEvent', $_POST) || $_POST['textEvent'] == '')
                {
                    $errors['textEvent'] = 'Vous devez saisir un contenu à votre évènement !';
                }

                if(!array_key_exists('eventDateStart', $_POST) || $_POST['eventDateStart'] == '' || $_POST['eventDateStart'] < $eventDateMin)
                {
                    $errors['eventDateStart'] = 'Vous devez choisir une date valide pour le démarrage de l\'évènement !';
                }

                if(!array_key_exists('eventDateEnd', $_POST) || $_POST['eventDateEnd'] == '' || $_POST['eventDateEnd'] < $eventDateMin || $_POST['eventDateEnd'] < $_POST['eventDateStart'])
                {
                    $errors['eventDateEnd'] = 'Vous devez choisir une date valide pour la fin de l\'évènement !';
                }                

                if(!isset($_FILES['image']) || empty($_FILES['image']))
                {
                    $errors['image'] = 'Vous devez choisir une image !';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['formData'] = $_POST;
                    header('location:index.php?page=createShopEvent');
                    exit();
                }
                else
                {
                    // On traite l'image //                   
                    // Variable gestion image //
                    $imgMaxSize = 8000000; // Image poids max 8Mo

                    if($_FILES['image']['size'] <= $imgMaxSize)
                    {
                        // poids ok, on traite les extensions //
                        // On récupère l'extension du fichier //
                        $fileInfo = pathinfo($_FILES['image']['name']);
                        $extFile = $fileInfo['extension'];
                        
                        // tableau des extensions que l'on autorise //
                        $extValid = ['jpeg','jpg','JPEG','JPG'];

                        // on vérifie que l'extension est valide avec nos choix //
                        if(in_array($extFile, $extValid))
                        {
                            // Pour le référencement naturel, on met le nom de l'évènement dans le nom de la photo et on rajoute un nombre aléatoire pour éviter les doublons //
                            // on passe le nom du fichier et son extension en minuscule //
                            $nameExt = strtolower($_FILES['image']['name']);
                            
                            // on découpe le nom du fichier avec son extension pour récupérer le nom seul du fichier, cela génère un tableau avec le nom et l'extension //
                            $tabName = explode('.',$nameExt);

                            // On envoi le nom de fichier pour nettoyage //
                            $stringName = $_POST['titleEvent'];
                            $stringNameCleaned = $this -> _cleaner -> cleanerNameFile($stringName); 
                            
                            // Le nom du fichier sera le nom du produit en minuscule et on remplace les espaces par des underscores //
                            $nameImg =  strtolower(str_replace(' ','_',$stringNameCleaned));                            
                            
                            // on récupère le nom de l'extension seul //
                            $ext = strtolower(end($tabName));
                            
                            // on rajoute au début du nom, un nombre aléatoire + 1 tiret underscore //
                            $nameImage = rand().'_'.$nameImg; 

                            // on donne le même nom à l'image de l'évènement en grande taille en rajoutant '_max' à la fin //
                            $nameLarge = $nameImage.'_max';

                            // on donne le même nom à l'image de l'évènement en taille moyenne en rajoutant '_med' à la fin //
                            $nameMedium = $nameImage.'_med';

                            // on donne le même nom à la miniature en rajoutant '_min' à la fin //
                            $nameMiniature = $nameImage.'_min';                            

                            // on rajoute l'extension //
                            $nameEventLarge = $nameLarge.'.'.$ext; // Taille image évènement pour front-end //
                            $nameEventMedium = $nameMedium.'.'.$ext; // Taille image évènement dans modification évènement + front-end //                           
                            $nameEventMiniature = $nameMiniature.'.'.$ext; // Miniature pour affichage liste évènements back-office + front-end //

                            // Si il n'y a pas d'erreur //
                            if($_FILES['image']['error'] == 0)
                            {
                                // On définie les tailles des images //
                                $widthMax = 1375;
                                $heightMax = 732;

                                $widthMed = 755;
                                $heightMed = 402;

                                $widthMin = 315;
                                $heightMin = 168;

                                // La source de l'image //
                                $source = imagecreatefromjpeg($_FILES['image']['tmp_name']);

                                // On récupère les dimensions de l'image source //
                                $sourceWidth = imagesx($source);
                                $sourceHeight =imagesy($source); 

                                // On crée une image vide au dimensions
                                $destinationMax = imagecreatetruecolor($widthMax, $heightMax);
                                $destinationMed = imagecreatetruecolor($widthMed, $heightMed);
                                $destinationMin = imagecreatetruecolor($widthMin, $heightMin);

                                // On crée notre image redimensionnée //
                                imagecopyresampled($destinationMax, $source, 0, 0, 0, 0, $widthMax, $heightMax, $sourceWidth, $sourceHeight);
                                imagecopyresampled($destinationMed, $source, 0, 0, 0, 0, $widthMed, $heightMed, $sourceWidth, $sourceHeight);
                                imagecopyresampled($destinationMin, $source, 0, 0, 0, 0, $widthMin, $heightMin, $sourceWidth, $sourceHeight);

                                
                                // On stock définitivement le fichier image dans le répertoire images correspondant //
                                imagejpeg($destinationMax, '../../www/assets/img/event/large/'.$nameEventLarge);                                
                                imagejpeg($destinationMed, '../../www/assets/img/event/medium/'.$nameEventMedium);
                                imagejpeg($destinationMin, '../../www/assets/img/event/miniature/'.$nameEventMiniature);
                            }
                            else
                            {
                                // Message d'erreur en cas de mauvais Upload //
                                $errors['err'] = 'Une erreur s\'est produite !';
                                $_SESSION['errors'] = $errors;
                                $_SESSION['formData'] = $_POST;
                                header('location:index.php?page=createShopEvent');
                                exit();
                            }
                        }
                        else
                        {
                            // Message d'erreur format de fichier //
                            $errors['err'] = 'Fichier manquant ou format non autorisé !';
                            $_SESSION['errors'] = $errors;
                            $_SESSION['formData'] = $_POST;
                            header('location:index.php?page=createShopEvent');
                            exit();
                        }
                    }
                    else
                    {
                        // Message d'erreur sur le poids //
                        $errors['err'] = 'Le poids de l\'image dépasse le poids autorisé !';
                        $_SESSION['errors'] = $errors;
                        $_SESSION['formData'] = $_POST;
                        header('location:index.php?page=createShopEvent');
                        exit();
                    }

                    // On enregistre l'évènement en BDD //
                    $activeEvent = $_POST['activeEvent'];
                    $titleEvent = $_POST['titleEvent'];
                    $textEvent = $_POST['textEvent'];
                    $eventDateStart = $_POST['eventDateStart'];
                    $eventDateEnd = $_POST['eventDateEnd'];                                       

                    $eventValided = $this -> _shopEvent -> addEvent($titleEvent,$textEvent,$eventDateStart,$eventDateEnd,$nameEventLarge,$nameEventMedium,$nameEventMiniature,$activeEvent);
                    
                    // Si tout Ok, on prévient l'utilisateur //
                    if($eventValided)
                    {
                        $success['save'] = 'Votre évènement a bien été ajouté.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=configurationEvent');
                        exit();
                    }
                    else
                    {
                        $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=createShopEvent');
                        exit();
                    }                    
                }
            }
            else
            {
                // On affiche le template de création d'un évènement //
                $template = 'views/shopEvent/create_event';
                require('../../www/7GhtK232c/views/templates/layout.phtml');
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function updateShopEvent()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
            // Instanciation du modèle //
            $this -> _shopEvent = new ShopEvent();

            // Instanciation Class //
            $this -> _cleaner = new cleaner();

            if(isset($_POST['submit']))
            {
                // On initialise un tableau vide pour la gestion d'erreur //
                $errors = [];

                // On initialise la REGEX pour les formats à valider correctement //
                $regexValidator = '#^[0-1]{1}$#';                               
                $eventDateMin = date('Y-m-d');

                // On récupère en BDD les infos de l'évènement //
                $idEvent = $_POST['idEvent'];
                $event = $this -> _shopEvent -> getEventById($idEvent);
                
                // On vérifie que l'id reçu correspond bien au produit de la BDD //
                if($idEvent != $event['id_event'])
                {
                    $errors['err'] = 'Cette opération est impossible !';
                }                
                
                if(!array_key_exists('activeEvent', $_POST) || $_POST['activeEvent'] == '' || !preg_match($regexValidator, $_POST['activeEvent']))
                {
                    $errors['activeEvent'] = 'Vous devez choisir d\'activer ou non l\'évènement !';
                }

                if(!array_key_exists('titleEvent', $_POST) || $_POST['titleEvent'] == '')
                {
                    $errors['titleEvent'] = 'Vous devez saisir un titre à votre évènement !';
                }

                if(!array_key_exists('textEvent', $_POST) || $_POST['textEvent'] == '')
                {
                    $errors['textEvent'] = 'Vous devez saisir un contenu à votre évènement !';
                }

                if(!array_key_exists('eventDateStart', $_POST) || $_POST['eventDateStart'] == '' || $_POST['eventDateStart'] < $eventDateMin)
                {
                    $errors['eventDateStart'] = 'Vous devez choisir une date valide pour le démarrage de l\'évènement !';
                }

                if(!array_key_exists('eventDateEnd', $_POST) || $_POST['eventDateEnd'] == '' || $_POST['eventDateEnd'] < $eventDateMin || $_POST['eventDateEnd'] < $_POST['eventDateStart'])
                {
                    $errors['eventDateEnd'] = 'Vous devez choisir une date valide pour la fin de l\'évènement !';
                }
                
                if(!array_key_exists('replaceImage', $_POST) || $_POST['replaceImage'] == '' || !preg_match($regexValidator, $_POST['replaceImage']))
                {
                    $errors['replaceImage'] = 'Vous devez répondre à cette question par oui ou non !';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['formData'] = $_POST;
                    header('location:index.php?page=updateShopEvent&idEvent='.$_POST['idEvent']);
                    exit();
                }
                else
                {
                    // Si l'utilisateur remplace l'image //
                    if(isset($_FILES['newImage']) && !empty($_FILES['newImage']) && $_POST['replaceImage'] == 1)
                    {   
                        // On récupère les infos sur les images du produit //
                        $infoImagesEvent = $this -> _shopEvent -> getEventById($idEvent);

                        // Vérification et suppression des images //
                        $deleteImageFile = '../../www/assets/img/event/large/'.$infoImagesEvent['img_event_large'];
                        $deleteImageMinFile = '../../www/assets/img/event/medium/'.$infoImagesEvent['img_event_medium'];
                        $deleteImageMaxFile = '../../www/assets/img/event/miniature/'.$infoImagesEvent['img_event_miniature'];

                        if(file_exists($deleteImageFile) && (file_exists($deleteImageMinFile) && (file_exists($deleteImageMaxFile))))
                        {
                            unlink($deleteImageFile);
                            unlink($deleteImageMinFile);
                            unlink($deleteImageMaxFile);
                        }
                        
                        // On traite la nouvelle image //
                        // Variable gestion image //
                        $imgMaxSize = 8000000; // Image poids max 8Mo

                        if($_FILES['newImage']['size'] <= $imgMaxSize)
                        {
                            // poids ok, on traite les extensions //
                            // On récupère l'extension du fichier //
                            $fileInfo = pathinfo($_FILES['newImage']['name']);
                            $extFile = $fileInfo['extension'];
                            
                            // tableau des extensions que l'on autorise //
                            $extValid = ['jpeg','jpg','JPEG','JPG'];

                            // on vérifie que l'extension est valide avec nos choix //
                            if(in_array($extFile, $extValid))
                            {
                                // Pour le référencement naturel, on met le nom de l'évènement dans le nom de la photo et on rajoute un nombre aléatoire pour éviter les doublons //
                                // on passe le nom du fichier et son extension en minuscule //
                                $nameExt = strtolower($_FILES['newImage']['name']);
                                
                                // on découpe le nom du fichier avec son extension pour récupérer le nom seul du fichier, cela génère un tableau avec le nom et l'extension //
                                $tabName = explode('.',$nameExt);

                                // On envoi le nom de fichier pour nettoyage //
                                $stringName = $_POST['titleEvent'];
                                $stringNameCleaned = $this -> _cleaner -> cleanerNameFile($stringName); 
                                
                                // Le nom du fichier sera le nom de l'évènement en minuscule et on remplace les espaces par des underscores //
                                $nameImg =  strtolower(str_replace(' ','_',$stringNameCleaned));    
                                
                                // on récupère le nom de l'extension seul //
                                $ext = strtolower(end($tabName));
                                
                                // on rajoute au début du nom, un nombre aléatoire + 1 tiret underscore //
                                $nameImage = rand().'_'.$nameImg; 

                                // on donne le même nom à l'image de l'évènement en grande taille en rajoutant '_max' à la fin //
                                $nameLarge = $nameImage.'_max';

                                // on donne le même nom à l'image de l'évènement en taille moyenne en rajoutant '_med' à la fin //
                                $nameMedium = $nameImage.'_med';

                                // on donne le même nom à la miniature en rajoutant '_min' à la fin //
                                $nameMiniature = $nameImage.'_min';                            

                                // on rajoute l'extension //
                                $nameEventLarge = $nameLarge.'.'.$ext; // Taille image évènement pour front-end //
                                $nameEventMedium = $nameMedium.'.'.$ext; // Taille image évènement dans modification évènement + front-end //                           
                                $nameEventMiniature = $nameMiniature.'.'.$ext; // Miniature pour affichage liste évènements back-office + front-end //

                                // Si il n'y a pas d'erreur //
                                if($_FILES['newImage']['error'] == 0)
                                {
                                    // On définie les tailles des images //
                                    $widthMax = 1375;
                                    $heightMax = 732;

                                    $widthMed = 755;
                                    $heightMed = 402;

                                    $widthMin = 315;
                                    $heightMin = 168;

                                    // La source de l'image //
                                    $source = imagecreatefromjpeg($_FILES['newImage']['tmp_name']);

                                    // On récupère les dimensions de l'image source //
                                    $sourceWidth = imagesx($source);
                                    $sourceHeight =imagesy($source); 

                                    // On crée une image vide au dimensions
                                    $destinationMax = imagecreatetruecolor($widthMax, $heightMax);
                                    $destinationMed = imagecreatetruecolor($widthMed, $heightMed);
                                    $destinationMin = imagecreatetruecolor($widthMin, $heightMin);

                                    // On crée notre image redimensionnée //
                                    imagecopyresampled($destinationMax, $source, 0, 0, 0, 0, $widthMax, $heightMax, $sourceWidth, $sourceHeight);
                                    imagecopyresampled($destinationMed, $source, 0, 0, 0, 0, $widthMed, $heightMed, $sourceWidth, $sourceHeight);
                                    imagecopyresampled($destinationMin, $source, 0, 0, 0, 0, $widthMin, $heightMin, $sourceWidth, $sourceHeight);

                                    
                                    // On stock définitivement le fichier image dans le répertoire images correspondant //
                                    imagejpeg($destinationMax, '../../www/assets/img/event/large/'.$nameEventLarge);                                
                                    imagejpeg($destinationMed, '../../www/assets/img/event/medium/'.$nameEventMedium);
                                    imagejpeg($destinationMin, '../../www/assets/img/event/miniature/'.$nameEventMiniature);
                                }
                                else
                                {
                                    // Message d'erreur en cas de mauvais Upload //
                                    $errors['err'] = 'Une erreur s\'est produite !';
                                    $_SESSION['errors'] = $errors;
                                    $_SESSION['formData'] = $_POST;
                                    header('location:index.php?page=updateShopEvent&idEvent='.$_POST['idEvent']);
                                    exit();
                                }
                            }
                            else
                            {
                                // Message d'erreur format de fichier //
                                $errors['err'] = 'Fichier manquant ou format non autorisé !';
                                $_SESSION['errors'] = $errors;
                                $_SESSION['formData'] = $_POST;
                                header('location:index.php?page=updateShopEvent&idEvent='.$_POST['idEvent']);
                                exit();                                
                            }
                        }
                        else
                        {
                            // Message d'erreur sur le poids //
                            $errors['err'] = 'Le poids de l\'image dépasse le poids autorisé !';
                            $_SESSION['errors'] = $errors;
                            $_SESSION['formData'] = $_POST;
                            header('location:index.php?page=updateShopEvent&idEvent='.$_POST['idEvent']);
                            exit();                            
                        }

                        // On enregistre l'évènement en BDD //
                        $idEvent = $_POST['idEvent'];
                        $activeEvent = $_POST['activeEvent'];
                        $titleEvent = $_POST['titleEvent'];
                        $textEvent = $_POST['textEvent'];
                        $eventDateStart = $_POST['eventDateStart'];
                        $eventDateEnd = $_POST['eventDateEnd'];  

                        $eventUpdated = $this -> _shopEvent -> updateEventWithImage($idEvent,$titleEvent,$textEvent,$eventDateStart,$eventDateEnd,$nameEventLarge,$nameEventMedium,$nameEventMiniature,$activeEvent);                        

                        // Si tout Ok, on prévient l'utilisateur //
                        if($eventUpdated)
                        {
                            $success['save'] = 'Votre évènement et sa nouvelle image ont bien été mis à jour.';
                            $_SESSION['success'] = $success;
                            header('location:index.php?page=configurationEvent');
                            exit();
                        }
                        else
                        {
                            $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement';
                            $_SESSION['errors'] = $errors;
                            header('location:index.php?page=configurationEvent');
                            exit();
                        }       
                    }
                    else
                    {
                        // On enregistre les modifications en BDD sans modifications sur les donnés images //
                        $idEvent = $_POST['idEvent'];
                        $activeEvent = $_POST['activeEvent'];
                        $titleEvent = $_POST['titleEvent'];
                        $textEvent = $_POST['textEvent'];
                        $eventDateStart = $_POST['eventDateStart'];
                        $eventDateEnd = $_POST['eventDateEnd'];

                        $eventUpdated = $this -> _shopEvent -> updateEvent($idEvent,$titleEvent,$textEvent,$eventDateStart,$eventDateEnd,$activeEvent);

                        // Si tout Ok, on prévient l'utilisateur //
                        if($eventUpdated)
                        {
                            $success['save'] = 'Votre évènement a bien été Mis à jour.';
                            $_SESSION['success'] = $success;
                            header('location:index.php?page=configurationEvent');
                            exit();
                        }
                        else
                        {
                            $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement';
                            $_SESSION['errors'] = $errors;
                            header('location:index.php?page=configurationEvent');
                            exit();
                        }
                    }
                }
            }
            else
            {
                if(array_key_exists('idEvent', $_GET) && is_numeric($_GET['idEvent']))
                {   
                    // On récupère en BDD les infos du produit //
                    $idEvent = $_GET['idEvent'];
                    $event = $this -> _shopEvent -> getEventById($idEvent);
                    
                    // On vérifie que l'id reçu correspond bien au produit de la BDD //
                    if($idEvent != $event['id_event'])
                    {
                        $errors['err'] = 'Cette opération est impossible !';                        
                    }

                    if(!empty($errors))
                    {
                        $_SESSION['errors'] = $errors;                                          
                        header('location:index.php?page=configurationEvent');
                        exit();
                    }
                    else
                    {
                        // On affiche le template de modification du produit //
                        $template = 'views/shopEvent/update_event';
                        require('../../www/7GhtK232c/views/templates/layout.phtml');
                    }
                }
                else
                {
                    header('location:index.php?page=404');
                    exit(); 
                }
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function deleteShopEvent()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
            if(isset($_POST['submit']) && is_numeric($_POST['idEvent']))
           {
                $this -> _shopEvent = new ShopEvent();
               
                // On récupère en BDD les infos de l'évènement //
                $idEvent = $_POST['idEvent'];
                $event = $this -> _shopEvent -> getEventById($idEvent);
                
                // On vérifie que l'id reçu correspond bien à l'évènement en BDD //
                if($idEvent != $event['id_event'])
                {
                    $errors['err'] = 'Cette opération est impossible !';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;                   
                    header('location:index.php?page=configurationEvent');
                    exit();
                }
                else
                {
                    // On récupère les infos sur l'évènement //
                    $event = $this -> _shopEvent -> getEventById($idEvent);

                    // Vérification et suppression des images //    
                    $deleteImgLargeFile = '../../www/assets/img/event/large/'.$event['img_event_large'];
                    $deleteImgMediumFile = '../../www/assets/img/event/medium/'.$event['img_event_medium'];
                    $deleteImgMiniatureFile = '../../www/assets/img/event/miniature/'.$event['img_event_miniature'];

                    if(file_exists($deleteImgLargeFile) && (file_exists($deleteImgMediumFile) && (file_exists($deleteImgMiniatureFile))))
                    {   
                        unlink($deleteImgLargeFile);
                        unlink($deleteImgMediumFile);
                        unlink($deleteImgMiniatureFile);                        

                        // On supprime les infos du produits en BDD //
                        $eventDeleted = $this -> _shopEvent -> deleteEventByid($idEvent);

                        if($eventDeleted)
                        {
                            // Si tout ok, message à l'utilisateur //
                            $success['delete'] = 'Votre évènement et ses images ont bien été supprimé.';
                            $_SESSION['success'] = $success;
                            
                            header('location:index.php?page=configurationEvent');
                            exit();
                        }
                        else
                        {
                            // Sinon, on affiche un message d'erreur //
                            $errors['err'] = 'Une erreur s\'est produite !';
                            $_SESSION['errors'] = $errors;

                            header('location:index.php?page=configurationEvent');
                            exit();
                        }
                    }
                    else
                    {
                        // On affiche un message d'erreur //
                        $errors['err'] = 'Une erreur s\'est produite !';
                        $_SESSION['errors'] = $errors;
        
                        header('location:index.php?page=configurationEvent');
                        exit(); 
                    }
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