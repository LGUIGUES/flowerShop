<?php

class CartController extends LayoutController
{
    public function createCart()
    {   
        // Instanciation du model //
        $this -> _product = new Product();        
       
        if(isset($_GET['idProduct']) && is_numeric($_GET['idProduct']))
        {   
            $idProduct = $_GET['idProduct'];             
            $product = $this -> _product -> getProductById($idProduct);
           
            if(empty($product))
            {   
                $errors['err'] = 'Ce produit n\'existe pas !';
                $_SESSION['errors'] = $errors;
                header('location:index.php?page=viewCart');
                exit();
            }
            $this -> addCartProduct($idProduct);
            header('location:index.php?page=viewCart');
            exit();
        }
        elseif(isset($_POST['submit']) && !empty($_POST['idProduct']) && !empty($_POST['addCartQty']))
        {
            $idProduct = $_POST['idProduct'];
            $product = $this -> _product -> getProductById($idProduct);
            $productQty = $_POST['addCartQty'];

            if(empty($product))
            {   
                $errors['err'] = 'Ce produit n\'existe pas !';
                $_SESSION['errors'] = $errors;
                header('location:index.php?page=viewCart');
                exit();
            }            
            $this -> addCartCardProduct($idProduct,$productQty);            
            header('location:index.php?page=viewCart');
            exit();            
        }
        else
        {   
            $errors['err'] = 'Vous n\'avez pas ajouter de produit à votre panier !';
            $_SESSION['errors'] = $errors;
            header('location:index.php?page=viewCart');
            exit();            
        }       
    }

    public function addCartProduct($idProduct)
    {   
        // On test pour voir si il y a déjà un produit dans le panier //
        if(isset($_SESSION['basket'][$idProduct]))
        {
            // On incrémente l'ajout d'un nouveau produit //
            $_SESSION['basket'][$idProduct]++;
        }
        else
        {   
            // La première fois, on initialise l'ajout pour 1 produit //
            $_SESSION['basket'][$idProduct] = 1;
        }        
    }

    public function addCartCardProduct($idProduct,$productQty)
    {   
        // On test pour voir si il y a déjà un produit dans le panier //
        if(isset($_SESSION['basket'][$idProduct]))
        {
            // On incrémente l'ajout d'un nouveau produit //
            $_SESSION['basket'][$idProduct] = $_SESSION['basket'][$idProduct]+$productQty;
        }
        else
        {   
            // La première fois, on initialise l'ajout pour 1 produit //
            $_SESSION['basket'][$idProduct] = $productQty;
        }        
    }

    public function delCartProduct()
    {   
        if(isset($_POST['submit']) && !empty($_POST))
        {   
            $idProduct = $_POST['idProduct'];
            unset($_SESSION['basket'][$idProduct]);
            
            $success['msg'] = 'Produit supprimé avec succès.';
            $_SESSION['success'] = $success;
            header('location:index.php?page=viewCart');
            exit();
        }
        else
        {
            $errors['err'] = 'Cette action est impossible !';
            $_SESSION['errors'] = $errors;
            header('location:index.php?page=viewCart');
            exit();
        }        
    }

    public function totalCart()
    {
        // Instanciation du model //
        $this -> _product = new Product();

        $totalBasket = 0;
        $idsProducts = array_keys($_SESSION['basket']);
        if(empty($idsProducts))
        {   
            // Si pas de produit, on envoie un tableau vide //
            $cartProducts = [];
        }
        else
        {
            // On récupère en BDD les infos des produits //
            $cartProducts = $this -> _product -> getCartProductsByIds($idsProducts);     
        }
        // On récupère les ids de nos produits et on calcul le total du panier //
        foreach($cartProducts as $product)
        {
            $totalBasket += $product['price_tax_product'] * $_SESSION['basket'][$product['id_product']];
        }

        return number_format($totalBasket,2,'.','');
    }

    public function cartProductQty()
    {
        $totalQtyCartProduct = array_sum($_SESSION['basket']);
        
        return $totalQtyCartProduct;        
    }

    public function cartProductQtyJson()
    {   
        $totalQtyCartProduct = array_sum($_SESSION['basket']);

        echo json_encode($totalQtyCartProduct);
    }    

    public function viewCart()
    {   
        // Instanciation du model //
        $this -> _product = new Product();

        // On contrôle que le panier ne soit pas vide pour l'affichage //
        $idsProducts = array_keys($_SESSION['basket']);        
        
        if(empty($idsProducts))
        {   
            // Si pas de produit, on envoie un tableau vide //
            $cartProducts = [];
        }
        else
        {
            // On récupère en BDD les infos des produits //
            $cartProducts = $this -> _product -> getCartProductsByIds($idsProducts);     
        }
        
        // On affiche le panier //
        $template = 'www/views/cart/cart';
        require('www/views/templates/layout.phtml');
    }    
}