<?php

class AdminProductController extends AdminController
{   
    public function displayProducts()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
            // Instanciation du modèle //
            $this -> _product = new Product();

            // On récupère les infos en BDD //
            $listingProducts = $this -> _product -> getListingProducts();

            // On affiche la liste des produits de la boutique //
            $template = 'views/product/home_products';
            require('../../www/7GhtK232c/views/templates/layout.phtml');

        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function createProduct()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation des modèles //
            $this -> _category = new Category();
            $this -> _product = new Product();
            $this -> _images = new Images();

            // Instanciation Class //
            $this -> _cleaner = new cleaner();

            if(isset($_POST['submit']))
            {                   
                // On initialise un tableau vide pour la gestion d'erreur //                
                $errors = [];

                // On initialise les REGEX pour les formats à valider correctement //
                $regexValidator = '#^[0-1]{1}$#';                            

                if(!array_key_exists('categoryProduct', $_POST) || $_POST['categoryProduct'] == '')
                {
                    $errors['categoryProduct'] = 'Vous devez choisir une catégorie !';
                }

                // On vérifie si la catégorie est conforme à la liste //
                $categoryProduct = $_POST['categoryProduct'];
                $categoryAuthorized = $this -> _category -> getCategoryProductById($categoryProduct);

                if($categoryAuthorized['id_category'] != $_POST['categoryProduct'])
                {
                    $errors['categoryProduct'] = 'Cette catégorie n\'est pas autorisée pour les produits !';
                }

                if(!array_key_exists('nameProduct', $_POST) || $_POST['nameProduct'] == '')
                {
                    $errors['nameProduct'] = 'Vous devez saisir un nom à votre produit !';
                }

                if(!array_key_exists('shortDescriptionProduct', $_POST) || $_POST['shortDescriptionProduct'] == '')
                {
                    $errors['shortDescriptionProduct'] = 'Vous devez saisir une description courte à votre produit !';
                }

                if(!array_key_exists('orientation', $_POST) || $_POST['orientation'] == '' || !preg_match($regexValidator, $_POST['orientation']))
                {
                    $errors['orientation'] = 'Vous devez choisir une orientation valide pour l\'image !';
                }

                if(!array_key_exists('descriptionImage', $_POST) || $_POST['descriptionImage'] == '' || strlen($_POST['descriptionImage']) > 40)
                {
                    $errors['descriptionImage'] = 'Vous devez saisir une description valide pour l\'image !';
                }
               
                if(!isset($_FILES['image']) || empty($_FILES['image']))
                {
                    $errors['image'] = 'Vous devez choisir une image !';
                }

                if(!array_key_exists('priceTaxProduct', $_POST) || $_POST['priceTaxProduct'] == '' || !is_numeric($_POST['priceTaxProduct']))
                {
                    $errors['priceTaxProduct'] = 'Vous devez saisir un prix de vente !';
                }

                if(!array_key_exists('activeProduct', $_POST) || $_POST['activeProduct'] == '' || !preg_match($regexValidator, $_POST['activeProduct']))
                {
                    $errors['activeProduct'] = 'Vous devez choisir l\'état de votre produit (activé/désactivé) !';
                }

                // On donne un nom à l'orientation de l'image //
                if($_POST['orientation'] == 0)
                {
                    $orientation = 'portrait';
                }
                elseif($_POST['orientation'] == 1)
                {
                    $orientation = 'landscape';    
                }
                else
                {
                    $errors['orientation'] = 'Vous devez choisir une orientation valide !';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['formData'] = $_POST;
                    header('location:index.php?page=createProduct');
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
                            // Pour le référencement naturel, on met le nom du produit dans le nom de la photo et on rajoute un nombre aléatoire pour éviter les doublons //
                            // on passe le nom du fichier et son extension en minuscule //
                            $nameExt = strtolower($_FILES['image']['name']);
                            
                            // on découpe le nom du fichier avec son extension pour récupérer l'extension, cela génère un tableau avec le nom et l'extension //
                            $tabName = explode('.',$nameExt);

                            // On envoi le nom de fichier pour nettoyage //
                            $stringName = $_POST['nameProduct'];
                            $stringNameCleaned = $this -> _cleaner -> cleanerNameFile($stringName); 
                            
                            // Le nom du fichier sera le nom du produit en minuscule et on remplace les espaces par des underscores //
                            $nameImg =  strtolower(str_replace(' ','_',$stringNameCleaned));
                            
                            // on récupère le nom de l'extension seul //
                            $ext = strtolower(end($tabName));
                            
                            // on rajoute au début du nom, un nombre aléatoire + 1 tiret underscore //
                            $nameImage = rand().'_'.$nameImg; 

                            // on donne le même nom à l'image produit en grande taille en rajoutant '_max' à la fin //
                            $nameLarge = $nameImage.'_max';

                            // on donne le même nom à la miniature en rajoutant '_min' à la fin //
                            $nameMiniature = $nameImage.'_min';                            

                            // on rajoute l'extension //
                            $nameLarge = $nameLarge.'.'.$ext; // Taille image du produit fiche produit //
                            $nameCategory = $nameImage.'.'.$ext; // Taille image du produit dans categories //                           
                            $nameMiniature = $nameMiniature.'.'.$ext; // Miniature pour affichage catalogue administration //

                            // Si il n'y a pas d'erreur //
                            if($_FILES['image']['error'] == 0)
                            {
                                /* On définie les tailles des images : principale et miniature
                                en mode paysage sera l'inverse en mode portrait */
                                $widthMax = 800;
                                $heightMax = 600;

                                $width = 375;
                                $height = 250;

                                $widthMin = 60;
                                $heightMin = 45;

                                // La source de l'image //
                                $source = imagecreatefromjpeg($_FILES['image']['tmp_name']);

                                // On récupère les dimensions de l'image source //
                                $sourceWidth = imagesx($source);
                                $sourceHeight =imagesy($source);

                                // On détermine la dimensions la plus grande pour faire un paysage ou un portrait //
                                if($sourceWidth > $sourceHeight)
                                {
                                    // Si la largeur est plus grande que la hauteur -> format paysage //
                                    $widthMaxLandscape = $widthMax;
                                    $heightMaxLandscape = $heightMax;

                                    $widthLandscape = $width;
                                    $heightLandscape = $height;

                                    $widthMinLandscape = $widthMin;
                                    $heightMinLandscape = $heightMin;

                                    // On crée une image vide au dimensions
                                    $destinationMaxLandscape = imagecreatetruecolor($widthMaxLandscape, $heightMaxLandscape);
                                    $destinationLandscape = imagecreatetruecolor($widthLandscape, $heightLandscape);
                                    $destinationMinLandscape = imagecreatetruecolor($widthMinLandscape, $heightMinLandscape);

                                    // On crée notre image redimensionnée //
                                    imagecopyresampled($destinationMaxLandscape, $source, 0, 0, 0, 0, $widthMaxLandscape, $heightMaxLandscape, $sourceWidth, $sourceHeight);
                                    imagecopyresampled($destinationLandscape, $source, 0, 0, 0, 0, $widthLandscape, $heightLandscape, $sourceWidth, $sourceHeight);
                                    imagecopyresampled($destinationMinLandscape, $source, 0, 0, 0, 0, $widthMinLandscape, $heightMinLandscape, $sourceWidth, $sourceHeight);

                                    
                                    // On stock définitivement le fichier image dans le répertoire images correspondant //
                                    imagejpeg($destinationMaxLandscape, '../../www/assets/img/product/large/'.$orientation.'/'.$nameLarge);                                
                                    imagejpeg($destinationLandscape, '../../www/assets/img/product/category/'.$orientation.'/'.$nameCategory);
                                    imagejpeg($destinationMinLandscape, '../../www/assets/img/product/miniature/'.$orientation.'/'.$nameMiniature);
                                }
                                else
                                {   
                                    $widthMaxPortrait = $heightMax;
                                    $heightMaxPortrait = $widthMax;
                                    
                                    $widthPortrait = $height;
                                    $heightPortrait = $width;

                                    $widthMinPortrait = $heightMin;
                                    $heightMinPortrait = $widthMin;

                                    $destinationMaxPortrait = imagecreatetruecolor($widthMaxPortrait, $heightMaxPortrait);
                                    $destinationPortrait = imagecreatetruecolor($widthPortrait, $heightPortrait);
                                    $destinationMinPortrait = imagecreatetruecolor($widthMinPortrait, $heightMinPortrait);

                                    // On crée notre image redimensionnée //
                                    imagecopyresampled($destinationMaxPortrait, $source, 0, 0, 0, 0, $widthMaxPortrait, $heightMaxPortrait, $sourceWidth, $sourceHeight);
                                    imagecopyresampled($destinationPortrait, $source, 0, 0, 0, 0, $widthPortrait, $heightPortrait, $sourceWidth, $sourceHeight);
                                    imagecopyresampled($destinationMinPortrait, $source, 0, 0, 0, 0, $widthMinPortrait, $heightMinPortrait, $sourceWidth, $sourceHeight);

                                    // On stock définitivement le fichier image dans le répertoire images correspondant //
                                    imagejpeg($destinationMaxPortrait, '../../www/assets/img/product/large/'.$orientation.'/'.$nameLarge);                                
                                    imagejpeg($destinationPortrait, '../../www/assets/img/product/category/'.$orientation.'/'.$nameCategory);
                                    imagejpeg($destinationMinPortrait, '../../www/assets/img/product/miniature/'.$orientation.'/'.$nameMiniature);
                                }                            
                            }
                            else
                            {
                                // Message d'erreur en cas de mauvais Upload //
                                $errors['err'] = 'Une erreur s\'est produite !';
                                $_SESSION['errors'] = $errors;
                                $_SESSION['formData'] = $_POST;
                                header('location:index.php?page=createProduct');
                                exit(); 
                            }
                        }
                        else
                        {
                            // Message d'erreur format de fichier //
                            $errors['err'] = 'Fichier manquant ou format non autorisé !';
                            $_SESSION['errors'] = $errors;
                            $_SESSION['formData'] = $_POST;
                            header('location:index.php?page=createProduct');
                            exit(); 
                        }
                    }
                    else
                    {
                        // Message d'erreur sur le poids //
                        $errors['err'] = 'Le poids de l\'image dépasse le poids autorisé !';
                        $_SESSION['errors'] = $errors;
                        $_SESSION['formData'] = $_POST;
                        header('location:index.php?page=createProduct');
                        exit(); 
                    }

                    // On enregistre le produit en BDD //
                    $nameProduct = $_POST['nameProduct'];
                    $referenceProduct = $_POST['referenceProduct'];
                    $priceTaxProduct = $_POST['priceTaxProduct'];
                    $shortDescriptionProduct = $_POST['shortDescriptionProduct'];
                    $descriptionProduct = $_POST['descriptionProduct'];
                    $descriptionImage = $_POST['descriptionImage'];
                    $active = $_POST['activeProduct'];                   

                    $lastIdProduct = $this -> _product -> addProduct($categoryProduct,$nameProduct,$referenceProduct,$priceTaxProduct,$shortDescriptionProduct,$descriptionProduct,$active);
                    
                    $idProduct = $lastIdProduct;

                    $productValided = $this -> _images -> addImageProduct($idProduct,$categoryProduct,$nameLarge,$descriptionImage,$nameCategory,$nameMiniature,$orientation);
                    
                    // Si tout Ok, on prévient l'utilisateur //
                    if($productValided)
                    {
                        $success['save'] = 'Votre nouveau produit a bien été ajouté.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=viewProducts');
                        exit();
                    }
                    else
                    {
                        $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=viewProducts');
                        exit();
                    }                    
                }
            }
            else
            {   
                // On récupère la liste des catégories //
                $categorys = $this -> _category -> getCategoryProduct();

                // On affiche le template de création de produit //
                $template = 'views/product/create_product';
                require('../../www/7GhtK232c/views/templates/layout.phtml');
            } 
        }   
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function editProduct()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _category = new Category();
            $this -> _product = new Product();
            $this -> _images = new Images();

            // Instanciation Class //
            $this -> _cleaner = new cleaner();

            if(isset($_POST['submit']))
            {
                // On initialise un tableau vide pour la gestion d'erreur //
                $errors = [];

                // On initialise la REGEX pour les formats à valider correctement //
                $regexValidator = '#^[0-1]{1}$#';                               
                
                // On récupère en BDD les infos du produit //
                $idProduct = $_POST['idProduct'];
                $product = $this -> _product -> getProductById($idProduct);
                
                // On vérifie que l'id reçu correspond bien au produit de la BDD //
                if($idProduct != $product['id_product'])
                {
                    $errors['err'] = 'Cette opération est impossible !';
                }

                if(!array_key_exists('categoryProduct', $_POST) || $_POST['categoryProduct'] == '')
                {
                    $errors['categoryProduct'] = 'Vous devez choisir une catégorie !';
                }

                // On vérifie si la catégorie est conforme à la liste //
                $categoryProduct = $_POST['categoryProduct'];
                $categoryAuthorized = $this -> _category -> getCategoryProductById($categoryProduct);

                if($categoryProduct != $categoryAuthorized['id_category'])
                {
                    $errors['categoryProduct'] = 'Cette catégorie n\'est pas autorisée pour les produits !';
                }

                if(!array_key_exists('nameProduct', $_POST) || $_POST['nameProduct'] == '')
                {
                    $errors['nameProduct'] = 'Vous devez saisir un nom à votre produit !';
                }

                if(!array_key_exists('shortDescriptionProduct', $_POST) || $_POST['shortDescriptionProduct'] == '')
                {
                    $errors['shortDescriptionProduct'] = 'Vous devez saisir une description courte à votre produit !';
                }

                if(!array_key_exists('orientation', $_POST) || $_POST['orientation'] == '' || !preg_match($regexValidator, $_POST['orientation']))
                {
                    $errors['orientation'] = 'Vous devez choisir une orientation valide pour l\'image !';
                }

                if(!array_key_exists('descriptionImage', $_POST) || $_POST['descriptionImage'] == '' || strlen($_POST['descriptionImage']) > 40)
                {
                    $errors['descriptionImage'] = 'Vous devez saisir une description valide pour l\'image !';
                }
                
                if(!array_key_exists('replaceImage', $_POST) || $_POST['replaceImage'] == '' || !preg_match($regexValidator, $_POST['replaceImage']))
                {
                    $errors['replaceImage'] = 'Vous devez répondre à cette question par oui ou non !';
                }

                if(!array_key_exists('priceTaxProduct', $_POST) || $_POST['priceTaxProduct'] == '' || !is_numeric($_POST['priceTaxProduct']))
                {
                    $errors['priceTaxProduct'] = 'Vous devez saisir un prix de vente !';
                }

                if(!array_key_exists('activeProduct', $_POST) || $_POST['activeProduct'] == '' || !preg_match($regexValidator, $_POST['activeProduct']))
                {
                    $errors['activeProduct'] = 'Vous devez choisir l\'état de votre produit (activé/désactivé) !';
                }

                // On donne un nom à l'orientation de l'image //
                if($_POST['orientation'] == 0)
                {
                    $orientation = 'portrait';
                }
                elseif($_POST['orientation'] == 1)
                {
                    $orientation = 'landscape';    
                }
                else
                {
                    $errors['orientation'] = 'Vous devez choisir une orientation valide !';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;                   
                    header('location:index.php?page=editProduct&idProduct='.$_POST['idProduct']);
                    exit();
                }
                else
                {
                    // Si l'utilisateur remplace l'image //
                    if(isset($_FILES['newImage']) && !empty($_FILES['newImage']) && $_POST['replaceImage'] == 1)
                    {
                        // On récupère les infos sur les images du produit //
                        $infoImagesProduct = $this -> _images -> getInfoImagesProductById($idProduct);

                        // Vérification et suppression des images //
                        $deleteImageFile = '../../www/assets/img/product/category/'.$infoImagesProduct['orientation'].'/'.$infoImagesProduct['name_category'];
                        $deleteImageMinFile = '../../www/assets/img/product/miniature/'.$infoImagesProduct['orientation'].'/'.$infoImagesProduct['name_miniature'];
                        $deleteImageMaxFile = '../../www/assets/img/product/large/'.$infoImagesProduct['orientation'].'/'.$infoImagesProduct['name_large'];

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
                                // Pour le référencement naturel, on met le nom du produit dans le nom de la photo et on rajoute un nombre aléatoire pour éviter les doublons //
                                // on passe le nom du fichier et son extension en minuscule //
                                $nameExt = strtolower($_FILES['newImage']['name']);
                                
                                // on découpe le nom du fichier avec son extension pour récupérer le nom seul du fichier, cela génère un tableau avec le nom et l'extension //
                                $tabName = explode('.',$nameExt);

                                // On envoi le nom de fichier pour nettoyage //
                                $stringName = $_POST['nameProduct'];
                                $stringNameCleaned = $this -> _cleaner -> cleanerNameFile($stringName); 
                                
                                // Le nom du fichier sera le nom du produit en minuscule et on remplace les espaces par des underscores //
                                $nameImg =  strtolower(str_replace(' ','_',$stringNameCleaned));
                                
                                // on récupère le nom de l'extension seul //
                                $ext = strtolower(end($tabName));
                                
                                // on rajoute au début du nom, un nombre aléatoire + 1 tiret underscore //
                                $nameImage = rand().'_'.$nameImg; 

                                // on donne le même nom à l'image produit en grande taille en rajoutant '_max' à la fin //
                                $nameLarge = $nameImage.'_max';

                                // on donne le même nom à la miniature en rajoutant '_min' à la fin //
                                $nameMiniature = $nameImage.'_min';                            

                                // on rajoute l'extension //
                                $nameLarge = $nameLarge.'.'.$ext; // Taille image du produit fiche produit //
                                $nameCategory = $nameImage.'.'.$ext; // Taille image du produit dans categories //                           
                                $nameMiniature = $nameMiniature.'.'.$ext; // Miniature pour affichage catalogue administration //

                                // Si il n'y a pas d'erreur //
                                if($_FILES['newImage']['error'] == 0)
                                {
                                    /* On définie les tailles des images : principale et miniature
                                    en mode paysage sera l'inverse en mode portrait */
                                    $widthMax = 800;
                                    $heightMax = 600;

                                    $width = 375;
                                    $height = 250;

                                    $widthMin = 60;
                                    $heightMin = 45;

                                    // La source de l'image //
                                    $source = imagecreatefromjpeg($_FILES['newImage']['tmp_name']);

                                    // On récupère les dimensions de l'image source //
                                    $sourceWidth = imagesx($source);
                                    $sourceHeight = imagesy($source);

                                    // On détermine la dimensions la plus grande pour faire un paysage ou un portrait //
                                    if($sourceWidth > $sourceHeight)
                                    {
                                        // Si la largeur est plus grande que la hauteur -> format paysage //
                                        $widthMaxLandscape = $widthMax;
                                        $heightMaxLandscape = $heightMax;

                                        $widthLandscape = $width;
                                        $heightLandscape = $height;

                                        $widthMinLandscape = $widthMin;
                                        $heightMinLandscape = $heightMin;

                                        // On crée une image vide au dimensions
                                        $destinationMaxLandscape = imagecreatetruecolor($widthMaxLandscape, $heightMaxLandscape);
                                        $destinationLandscape = imagecreatetruecolor($widthLandscape, $heightLandscape);
                                        $destinationMinLandscape = imagecreatetruecolor($widthMinLandscape, $heightMinLandscape);

                                        // On crée notre image redimensionnée //
                                        imagecopyresampled($destinationMaxLandscape, $source, 0, 0, 0, 0, $widthMaxLandscape, $heightMaxLandscape, $sourceWidth, $sourceHeight);
                                        imagecopyresampled($destinationLandscape, $source, 0, 0, 0, 0, $widthLandscape, $heightLandscape, $sourceWidth, $sourceHeight);
                                        imagecopyresampled($destinationMinLandscape, $source, 0, 0, 0, 0, $widthMinLandscape, $heightMinLandscape, $sourceWidth, $sourceHeight);

                                        
                                        // On stock définitivement le fichier image dans le répertoire images correspondant //
                                        imagejpeg($destinationMaxLandscape, '../../www/assets/img/product/large/'.$orientation.'/'.$nameLarge);                                
                                        imagejpeg($destinationLandscape, '../../www/assets/img/product/category/'.$orientation.'/'.$nameCategory);
                                        imagejpeg($destinationMinLandscape, '../../www/assets/img/product/miniature/'.$orientation.'/'.$nameMiniature);
                                    }
                                    else
                                    {   
                                        $widthMaxPortrait = $heightMax;
                                        $heightMaxPortrait = $widthMax;
                                        
                                        $widthPortrait = $height;
                                        $heightPortrait = $width;

                                        $widthMinPortrait = $heightMin;
                                        $heightMinPortrait = $widthMin;

                                        $destinationMaxPortrait = imagecreatetruecolor($widthMaxPortrait, $heightMaxPortrait);
                                        $destinationPortrait = imagecreatetruecolor($widthPortrait, $heightPortrait);
                                        $destinationMinPortrait = imagecreatetruecolor($widthMinPortrait, $heightMinPortrait);

                                        // On crée notre image redimensionnée //
                                        imagecopyresampled($destinationMaxPortrait, $source, 0, 0, 0, 0, $widthMaxPortrait, $heightMaxPortrait, $sourceWidth, $sourceHeight);
                                        imagecopyresampled($destinationPortrait, $source, 0, 0, 0, 0, $widthPortrait, $heightPortrait, $sourceWidth, $sourceHeight);
                                        imagecopyresampled($destinationMinPortrait, $source, 0, 0, 0, 0, $widthMinPortrait, $heightMinPortrait, $sourceWidth, $sourceHeight);

                                        // On stock définitivement le fichier image dans le répertoire images correspondant //
                                        imagejpeg($destinationMaxPortrait, '../../www/assets/img/product/large/'.$orientation.'/'.$nameLarge);                                
                                        imagejpeg($destinationPortrait, '../../www/assets/img/product/category/'.$orientation.'/'.$nameCategory);
                                        imagejpeg($destinationMinPortrait, '../../www/assets/img/product/miniature/'.$orientation.'/'.$nameMiniature);
                                    }                            
                                }
                                else
                                {
                                    // Message d'erreur en cas de mauvais Upload //
                                    $errors['err'] = 'Une erreur s\'est produite !';
                                    $_SESSION['errors'] = $errors;
                                    $_SESSION['formData'] = $_POST;
                                    header('location:index.php?page=editProduct&idProduct='.$_POST['idProduct']);
                                    exit();
                                }
                            }
                            else
                            {
                                // Message d'erreur format de fichier //
                                $errors['err'] = 'Fichier manquant ou format non autorisé !';
                                $_SESSION['errors'] = $errors;
                                $_SESSION['formData'] = $_POST;
                                header('location:index.php?page=editProduct&idProduct='.$_POST['idProduct']);
                                exit();                                
                            }
                        }
                        else
                        {
                            // Message d'erreur sur le poids //
                            $errors['err'] = 'Le poids de l\'image dépasse le poids autorisé !';
                            $_SESSION['errors'] = $errors;
                            $_SESSION['formData'] = $_POST;
                            header('location:index.php?page=editProduct&idProduct='.$_POST['idProduct']);
                            exit();                            
                        }

                        // On enregistre les modifications en BDD //
                        $nameProduct = $_POST['nameProduct'];
                        $referenceProduct = $_POST['referenceProduct'];
                        $priceTaxProduct = $_POST['priceTaxProduct'];
                        $shortDescriptionProduct = $_POST['shortDescriptionProduct'];
                        $descriptionProduct = $_POST['descriptionProduct'];
                        $descriptionImage = $_POST['descriptionImage'];
                        $active = $_POST['activeProduct'];

                        $productUpdated = $this -> _product -> updateProduct($idProduct,$categoryProduct,$nameProduct,$referenceProduct,$priceTaxProduct,$shortDescriptionProduct,$descriptionProduct,$active);

                        $imageProduct = $this -> _images -> updateImageProduct($idProduct,$categoryProduct,$nameLarge,$descriptionImage,$nameCategory,$nameMiniature,$orientation);

                        // Si tout Ok, on prévient l'utilisateur //
                        if($productUpdated)
                        {
                            $success['save'] = 'Votre produit et sa nouvelle image ont bien été mis à jour.';
                            $_SESSION['success'] = $success;
                            header('location:index.php?page=viewProducts');
                            exit();
                        }
                        else
                        {
                            $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement';
                            $_SESSION['errors'] = $errors;
                            header('location:index.php?page=viewProducts');
                            exit();
                        }       
                    }
                    else
                    {
                        // On enregistre les modifications en BDD sans modifications sur les donnés images //
                        $nameProduct = $_POST['nameProduct'];
                        $referenceProduct = $_POST['referenceProduct'];
                        $priceTaxProduct = $_POST['priceTaxProduct'];
                        $shortDescriptionProduct = $_POST['shortDescriptionProduct'];
                        $descriptionProduct = $_POST['descriptionProduct'];                        
                        $active = $_POST['activeProduct'];

                        $productUpdated = $this -> _product -> updateProduct($idProduct,$categoryProduct,$nameProduct,$referenceProduct,$priceTaxProduct,$shortDescriptionProduct,$descriptionProduct,$active);

                        // Si tout Ok, on prévient l'utilisateur //
                        if($productUpdated)
                        {
                            $success['save'] = 'Votre produit a bien été Mis à jour.';
                            $_SESSION['success'] = $success;
                            header('location:index.php?page=viewProducts');
                            exit();
                        }
                        else
                        {
                            $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement';
                            $_SESSION['errors'] = $errors;
                            header('location:index.php?page=viewProducts');
                            exit();
                        }
                    }
                }
            }
            else
            {
                if(array_key_exists('idProduct', $_GET) && is_numeric($_GET['idProduct']))
                {   
                    // On récupère en BDD les infos du produit //
                    $idProduct = $_GET['idProduct'];                
                    $categorys = $this -> _category -> getCategoryProduct();
                    $product = $this -> _product -> getProductById($idProduct);
                    
                    // On vérifie que l'id reçu correspond bien au produit de la BDD //
                    if($idProduct != $product['id_product'])
                    {
                        $errors['err'] = 'Cette opération est impossible !';                        
                    }

                    if(!empty($errors))
                    {
                        $_SESSION['errors'] = $errors;                                          
                        header('location:index.php?page=viewProducts');
                        exit();
                    }
                    else
                    {
                        // On affiche le template de modification du produit //
                        $template = 'views/product/edit_product';
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

    public function deleteProduct()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
           if(isset($_POST['submit']) && is_numeric($_POST['idProduct']))
           {
                $this -> _product = new Product();
                $this -> _images= new Images();

                // On récupère en BDD les infos du produit //
                $idProduct = $_POST['idProduct'];
                $product = $this -> _product -> getProductById($idProduct);
                
                // On vérifie que l'id reçu correspond bien au produit de la BDD //
                if($idProduct != $product['id_product'])
                {
                    $errors['err'] = 'Cette opération est impossible !';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;                   
                    header('location:index.php?page=viewProducts');
                    exit();
                }
                else
                {
                    // On récupère les infos sur l'image //
                    $infoImage = $this -> _images -> getInfoImagesProductById($idProduct);

                    // Vérification et suppression des images //    
                    $deleteImgCategoryFile = '../../www/assets/img/product/category/'.$infoImage['orientation'].'/'. $infoImage['name_category'];
                    $deleteImgLargeFile = '../../www/assets/img/product/large/'.$infoImage['orientation'].'/'. $infoImage['name_large'];
                    $deleteImgMinFile = '../../www/assets/img/product/miniature/'.$infoImage['orientation'].'/'. $infoImage['name_miniature'];

                    if(file_exists($deleteImgCategoryFile) && (file_exists($deleteImgLargeFile) && (file_exists($deleteImgMinFile))))
                    {   
                        unlink($deleteImgCategoryFile);
                        unlink($deleteImgLargeFile);
                        unlink($deleteImgMinFile);

                        // On supprime en BDD les infos de cette image //                   
                        $imageProductDelete = $this -> _images -> deleteImageProductByIdProduct($idProduct);

                        // On supprime les infos du produits en BDD //
                        $productDeleted = $this -> _product -> deleteProductByid($idProduct);

                        if($productDeleted)
                        {
                            // Si tout ok, message à l'utilisateur //
                            $success['delete'] = 'Votre produit et ses images ont bien été supprimé.';
                            $_SESSION['success'] = $success;
                            
                            header('location:index.php?page=viewProducts');
                            exit();
                        }
                        else
                        {
                            // Sinon, on affiche un message d'erreur //
                            $errors['err'] = 'Une erreur s\'est produite !';
                            $_SESSION['errors'] = $errors;

                            header('location:index.php?page=viewProducts');
                            exit();
                        }
                    }
                    else
                    {
                        // On affiche un message d'erreur //
                        $errors['err'] = 'Une erreur s\'est produite !';
                        $_SESSION['errors'] = $errors;
        
                        header('location:index.php?page=viewProducts');
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