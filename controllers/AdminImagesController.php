<?php

class AdminImagesController extends AdminController
{
    public function viewCategorys()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation des modèles //
            $this -> _category = new Category();
            $this -> _images = new Images();

            // On récupère en BDD les informations //       
            $infoImages = $this -> _images -> infoImages();
        
            // On affiche le template //
            $template = 'views/gallery/gallery';
            require('../../www/7GhtK232c/views/templates/layout.phtml');  
        }
        else
        {
            header('location:index.php');
            exit();
        }          
    }
    
    public function addImageGallery()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
             // Instanciation des modèles //
             $this -> _category = new Category();
             $this -> _images = new Images();

            // On récupère en BDD la liste des catégories //
            $categorys = $this -> _category -> getCategorys();
            
            if(isset($_POST['submit']) && (!empty($_POST)))
            {            
                // On traite le formulaire //
                if(isset($_POST['category']))
                {
                    $idCategory = $_POST['category'];

                    if(isset($_POST['description']))
                    {
                        $description = $_POST['description'];

                        if(isset($_POST['orientation']))
                        {
                            $orientation = $_POST['orientation'];                        
                        }
                        else
                        {   
                            // Message d'erreur orientation vide //
                            $message = 'Vous devez choisir une orientation.';
                            $_POST['message'] = $message;

                            $template = 'views/gallery/add_image';
                            require('../../www/7GhtK232c/views/templates/layout.phtml');
                            return;
                        }
                    }
                    else
                    {   
                        // Message d'erreur description vide //
                        $message = 'Vous devez indiquez une description courte.';
                        $_POST['message'] = $message;

                        $template = 'views/gallery/add_image';
                        require('../../www/7GhtK232c/views/templates/layout.phtml');
                        return;
                    }
                }
                else
                {   
                    // Message d'erreur catégorie vide //                
                    $message = 'Vous devez choisir une catégorie.';
                    $_POST['message'] = $message;

                    $template = 'views/gallery/add_image';
                    require('../../www/7GhtK232c/views/templates/layout.phtml');
                    return;                
                }

                // On traite l'image //
                if(!empty($_FILES['image']))
                {
                    // on vérifie le poids de l'image //
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
                            // Pour le référencement naturel, on met le titre du produit dans le nom de la photo et on rajoute un nombre aléatoire pour éviter les doublons //
                            // on passe le nom du fichier et son extension en minuscule //
                            $nameExt = strtolower($_FILES['image']['name']);
                            
                            // on découpe le nom du fichier avec son extension pour récupérer le nom seul du fichier, cela génère un tableau avec le nom et l'extension //
                            $tabName = explode('.',$nameExt);
                            
                            // Le nom du fichier sera le nom donné //
                            $nameImg =  $tabName[0];
                            
                            // on récupère le nom de l'extension seul //
                            $ext = strtolower(end($tabName));
                            
                            // on rajoute au début du nom, un nombre aléatoire + 1 tiret // et on ajoute l'extension //
                            $nameImage = rand().'-'.$nameImg; //.'.'.$ext;

                            // on donne le même nom à la miniature en rajoutant '_min' à la fin //
                            $nameMiniature = $nameImage.'_min';

                            // on rajoute l'extension //
                            $nameImage = $nameImage.'.'.$ext;
                            $nameMiniature = $nameMiniature.'.'.$ext;

                            // Si il n'y a pas d'erreur //
                            if($_FILES['image']['error'] == 0)
                            {
                                /* On définie les tailles des images : principale et miniature
                                en mode paysage sera l'inverse en mode portrait */
                                $width = 800;
                                $height = 600;

                                $widthMin = 375;
                                $heightMin = 250;

                                // La source de l'image //
                                $source = imagecreatefromjpeg($_FILES['image']['tmp_name']);

                                // On récupère les dimensions de l'image source //
                                $sourceWidth = imagesx($source);
                                $sourceHeight =imagesy($source);

                                // On détermine la dimensions la plus grande pour faire un paysage ou un portrait //
                                if($sourceWidth > $sourceHeight)
                                {
                                    // Si la largeur est plus grande que la hauteur -> format paysage //
                                    $widthLandscape = $width;
                                    $heightLandscape = $height;

                                    $widthMinLandscape = $widthMin;
                                    $heightMinLandscape = $heightMin;

                                    // On crée une image vide au dimensions
                                    $destinationLandscape = imagecreatetruecolor($widthLandscape, $heightLandscape);
                                    $destinationMinLandscape = imagecreatetruecolor($widthMinLandscape, $heightMinLandscape);

                                    // On crée notre image redimensionnée //
                                    imagecopyresampled($destinationLandscape, $source, 0, 0, 0, 0, $widthLandscape, $heightLandscape, $sourceWidth, $sourceHeight);
                                    imagecopyresampled($destinationMinLandscape, $source, 0, 0, 0, 0, $widthMinLandscape, $heightMinLandscape, $sourceWidth, $sourceHeight);

                                    
                                    // On stock définitivement le fichier image dans le répertoire images correspondant //                                
                                    imagejpeg($destinationLandscape, '../../www/assets/img/gallery/large/'.$orientation.'/'.$nameImage);
                                    imagejpeg($destinationMinLandscape, '../../www/assets/img/gallery/category/'.$orientation.'/'.$nameMiniature);
                                }
                                else
                                {
                                    $widthPortrait = $height;
                                    $heightPortrait = $width;

                                    $widthMinPortrait = $heightMin;
                                    $heightMinPortrait = $widthMin;

                                    $destinationPortrait = imagecreatetruecolor($widthPortrait, $heightPortrait);
                                    $destinationMinPortrait = imagecreatetruecolor($widthMinPortrait, $heightMinPortrait);

                                    // On crée notre image redimensionnée //
                                    imagecopyresampled($destinationPortrait, $source, 0, 0, 0, 0, $widthPortrait, $heightPortrait, $sourceWidth, $sourceHeight);
                                    imagecopyresampled($destinationMinPortrait, $source, 0, 0, 0, 0, $widthMinPortrait, $heightMinPortrait, $sourceWidth, $sourceHeight);

                                    // On stock définitivement le fichier image dans le répertoire images correspondant //                                
                                    imagejpeg($destinationPortrait, '../../www/assets/img/gallery/large/'.$orientation.'/'.$nameImage);
                                    imagejpeg($destinationMinPortrait, '../../www/assets/img/gallery/category/'.$orientation.'/'.$nameMiniature);
                                }                            
                            }
                            else
                            {
                                // Message d'erreur en cas de mauvais Upload //
                                $message = 'Une erreur s\'est produite !';
                                $_POST['description'] = $description;
                                $_POST['message'] = $message;

                                $template = 'views/gallery/add_image';
                                require('../../www/7GhtK232c/views/templates/layout.phtml');
                                return;
                            }
                        }
                        else
                        {
                            // Message d'erreur format de fichier //
                            $message = 'le format de fichier n\'est pas bon !';                            
                            $_POST['description'] = $description;
                            $_POST['message'] = $message;

                            $template = 'views/gallery/add_image';
                            require('../../www/7GhtK232c/views/templates/layout.phtml');
                            return;
                        }
                    }
                    else
                    {
                        // Message d'erreur sur le poids //
                        $message = 'Le poids de l\'image dépasse le poids autorisé !';                        
                        $_POST['description'] = $description;
                        $_POST['message'] = $message;

                        $template = 'views/gallery/add_image';
                        require('../../www/7GhtK232c/views/templates/layout.phtml');
                        return;
                    }
                }
                else
                {
                    // Message d'erreur en cas de mauvais Upload //
                    $message = 'Vous devez choisir une image !';
                    $_POST['description'] = $description;
                    $_POST['message'] = $message;

                    $template = 'views/gallery/add_image';
                    require('../../www/7GhtK232c/views/templates/layout.phtml');
                    return;
                }
                
                // On finalise l'enregistrement //
                $idProduct = 0;
                $imageValided = $this -> _images -> addImage($idProduct,$idCategory,$nameImage,$description,$nameMiniature,$orientation);
                
                // Si tout ok, on affiche un message à l'utilisateur /            
                if($imageValided)
                {
                    $message = 'L\'image à bien été enregistrée';
                    $_POST['message'] = $message;

                    $template = 'views/gallery/add_image';
                    require('../../www/7GhtK232c/views/templates/layout.phtml');
                }
                else
                {
                    // Message d'erreur //
                    $message = 'Une erreur s\'est produite !';                            
                    $_POST['message'] = $message;

                    $template = 'views/gallery/add_image';
                    require('../../www/7GhtK232c/views/templates/layout.phtml');                
                }
            }
            // Sinon on affiche le template de visualisation des categories //
            else
            {
                // On affiche le template //            
                $template = 'views/gallery/add_image';
                require('../../www/7GhtK232c/views/templates/layout.phtml');
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function updateImageGallery()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        { 
            // Instanciation des modèles //
            $this -> _category = new Category();
            $this -> _images = new Images();

            // On traite le bouton de suppression d'image //
            if(isset($_POST['delete']))
            {   
                $idImage = $_POST['idImage'];
                $this -> deleteImageGallery($idImage);                
            }

            // On récupère en BDD la liste des catégories //
            $categorys = $this -> _category -> getCategorys();
                
            // On traite le formulaire //
            if(isset($_POST['update']))
            {   
                // On traite les données reçus //
                $id = $_POST['idImage'];
                $idCategory = $_POST['idCategory'];
                $description = $_POST['imageDescription'];
                
                if(empty($_POST['idCategory']) && (empty($_POST['imageDescription'])))
                {
                    $message = 'Attention : Aucunes modifications apportées à vos images !';
                    $_POST['message'] = $message;

                    $template = 'views/gallery/update_image';
                    require('../../www/7GhtK232c/views/templates/layout.phtml');
                }
                else
                {   
                    // On traite les données reçus //
                    $id = $_POST['idImage'];
                    $idCategory = $_POST['idCategory'];
                    $description = $_POST['imageDescription'];

                    // On enregistre les infos en BDD //
                    $imageUpdated = $this -> _images -> updateImageById($id,$idCategory,$description);

                    if($imageUpdated)
                    {
                        // Si tout ok, on informe l'utilisateur //
                        $message = 'Votre image a bien été mise à jour.';
                        $_POST['message'] = $message;

                        $template = 'views/gallery/update_image';
                        require('../../www/7GhtK232c/views/templates/layout.phtml');
                    }
                    else
                    {
                        // Message d'erreur //
                        $message = 'Une erreur s\'est produite !';
                        $_POST['message'] = $message;

                        $template = 'views/gallery/update_image';
                        require('../../www/7GhtK232c/views/templates/layout.phtml');
                    }
               }
            }
            else
            {   
                if(array_key_exists('id',$_GET) && is_numeric($_GET['id']))
                {
                    // On récupère les infos de la catégorie //
                    $idCategory = $_GET['id'];
                    $imagesCategory = $this -> _images -> getImagesByCategory($idCategory);               

                    // On affiche le template //
                    $template = 'views/gallery/update_image';
                    require('../../www/7GhtK232c/views/templates/layout.phtml');
                }
                else
                {                   
                    // On affiche le template //
                    $template = 'views/gallery/update_image';
                    require('../../www/7GhtK232c/views/templates/layout.phtml');                
                }            
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }    
    }
    
    public function deleteImageGallery($idImage)
    {
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation des modèles //             
            $this -> _images = new Images();            
                
            // On récupère les infos sur l'image //
            $infoImage = $this -> _images -> getImageById($idImage);

            // Vérification et suppression des images //    
            $deleteImgFile = '../../www/assets/img/gallery/large/'.$infoImage['orientation'].'/'. $infoImage['name_large'];
            $deleteImgMinFile = '../../www/assets/img/gallery/category/'.$infoImage['orientation'].'/'. $infoImage['name_category'];

            if(file_exists($deleteImgFile) && (file_exists($deleteImgMinFile)))
            {
                unlink($deleteImgFile);
                unlink($deleteImgMinFile);

                // On supprime en BDD les infos de cette image //
                $deletedImage = $this -> _images -> deleteImageById($idImage);

                if($deletedImage)
                {
                    // Si tout ok, message à l'utilisateur //
                    $message = 'Votre image et sa miniature ont bien été supprimé.';
                    $_POST['message'] = $message;                    
                    
                    header('location:index.php?page=updateImage&message='.$message);
                    exit();
                }
                else
                {
                    // Sinon, on affiche un message d'erreur //
                    $message = 'Une erreur s\'est produite !';
                    $_POST['message'] = $message;

                    $template = 'views/gallery/update_image';
                    require('../../www/7GhtK232c/views/templates/layout.phtml');
                }
            }
            else
            {
                // On affiche un message d'erreur //
                $message = 'Une erreur s\'est produite !';
                $_POST['message'] = $message;

                $template = 'views/gallery/update_image';
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