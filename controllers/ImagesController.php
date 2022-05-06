<?php

// Fichier requis //
require('models/Images.php');
require('models/Product.php');

class ImagesController extends LayoutController
{      
    protected $_images;

    public function viewGalleryImages()
    {   
        // Instanciation des modèles //        
        $this -> _images = new Images();
        $this -> _product = new Product();

        if(array_key_exists('id', $_GET) && is_numeric($_GET['id']))
        {   
            $idCategory = $_GET['id'];
                       
            // On récupère en BDD les infos sur les catégories //
            $infoCategory = $this -> _category -> getInfoByCategory($idCategory);

            // Seo page //
            $title = $infoCategory['title_category'];
            $metadescription = $infoCategory['text_category'];

            if($infoCategory['id_category'] == $_GET['id'])
            {
                if($idCategory == 7000)
                {
                    $imagesCategory = $this -> _images -> getAllImages();  
                }
                else
                {
                    $imagesCategory = $this -> _images -> getImagesByCategory($idCategory);
                }
                if($infoCategory['category_product'] == 1)
                {   
                    $listProducts = $this -> _product -> getProductsByCategory($idCategory);

                    $tags = $this -> authorizedTags;
                    $template = 'www/views/product/products';
                    require('www/views/templates/layout.phtml');                    
                }
                else
                {
                    // Affichage aléatoire des images //
                    shuffle($imagesCategory);

                    $tags = $this -> authorizedTags;
                    $template = 'www/views/gallery/gallery';
                    require('www/views/templates/layout.phtml');
                } 
                
            }            
            else
            {   
                // Erreur 404 - Page non trouvée //
                header('location:index.php?page=404');
                exit();               
            }           
        }
        else
        {   
            // Erreur 404 - Page non trouvée //                
            header('location:index.php?page=404');
            exit();           
        }
    }
}