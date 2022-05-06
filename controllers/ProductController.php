<?php

class ProductController extends LayoutController
{
    public function viewProduct()
    {
        // Instanciation model //
        $this -> _product = new Product();
        $this -> _images = new Images();

        // On récupère les infos du produit avec son id //
        if(array_key_exists('id', $_GET) && is_numeric($_GET['id']))
        {   
            $idProduct = $_GET['id'];
            $product = $this -> _product -> getProductById($idProduct);

            if($product)
            {
                // On affiche le template //
                $tags = $this -> authorizedTags;
                $template = 'www/views/product/product';
                require('www/views/templates/layout.phtml');
            }
            else
            {
                header('location:index.php?page=404');
                exit();
            }
        }
        else
        {
            header('location:index.php?page=404');
            exit();
        }        
    }
}