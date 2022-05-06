<?php

//Fichier requis //
require('models/Order.php');

class OrderController extends LayoutController
{   
    private $_cartController;
    private $_order;
    private $_user;

    public function cartAddressOrder()
    {
        // on sécurise les pages utilisateur //
        if($this -> userIsConnect() == true)
        {   
            // Instanciation du model //   
            $this -> _user = new User();           

            // On affiche les adresses de l'utilisateur /        
            $idUser = $_SESSION['idUser'];
                    
            // On récupère les infos sur les adresses du client en BDD //
            $addresses = $this -> _user -> getUserAddressesById($idUser);
            
            // Seo page //
            $title = 'Choix d\'une adresse pour votre commande';
            $metadescription = 'Choisissez ou créez une adresse pour la validation de votre commande.';

            // On affiche le template //                
            $template = 'www/views/cart/cart_address_order';
            require('www/views/templates/layout.phtml');        
        }
        else
        {
            header('location:index.php?page=authentification');
            exit();
        }
    }

    public function cartOrder()
    { 
        // on sécurise les pages utilisateur //
        if($this -> userIsConnect() == true)
        {   
            if(isset($_POST['submit']) && !empty($_SESSION['basket']))
            {
                // On initialise un tableau vide pour la gestion d'erreur //
                $errors = [];
                
                if(!array_key_exists('idAddress', $_POST) || $_POST['idAddress'] == '')
                {
                    $errors['idAddress'] = 'Vous devez choisir une adresse !';
                }

                if(!array_key_exists('deliveryMethod', $_POST) || $_POST['deliveryMethod'] == '' || $_POST['deliveryMethod'] != 1)
                {
                    $errors['deliveryMethod'] = 'Vous devez choisir un mode de livraison !';
                }

                if(!array_key_exists('paymentMethod', $_POST) || $_POST['paymentMethod'] == '' || $_POST['paymentMethod'] != 1)
                {
                    $errors['paymentMethod'] = 'Vous devez choisir un moyen de paiement !';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    header('location:index.php?page=cartAddressOrder');
                    exit();
                }
                else
                {
                    // Instanciation des modeles //
                    $this -> _product = new Product();
                    $this -> _order = new Order();
                    $this -> _user = new User();

                    $idsProducts = array_keys($_SESSION['basket']);
                    // On récupère en BDD les infos des produits //
                    $cartProducts = $this -> _product -> getCartProductsByIds($idsProducts);

                    // On récupère l'adresse de l'utilisateur //
                    $idUser = $_SESSION['idUser'];
                    $idAddress = $_POST['idAddress'];
                    $addressUser = $this -> _user -> getUserAddressByIdAddress($idAddress);
                    
                    // On vérifie que l'adresse reçu est bien une adresse de l'utilisateur //
                    if($idAddress == $addressUser['id_address'] && $idUser == $addressUser['id_user'])
                    {
                        // Enregistrement de la commande dans la BDD//
                        $this -> _cartController = new CartController();
                        $totalAmount = $this -> _cartController -> totalCart();
                        $deliveryMethod = $_POST['deliveryMethod'];
                        $paymentMethod = $_POST['paymentMethod'];
                        $orderStatus = 1;                        

                        // On appel la méthode order qui nous retournera l'id de la commande enregistrée en BDD //
                        $lastIdOrder = $this -> _order -> addOrder($idUser,$totalAmount,$idAddress,$deliveryMethod,$paymentMethod,$orderStatus);

                        /* On enregistre l'adresse dans une nouvelle table dans le cas
                        ou l'utilisateur supprime celle-ci de ce compte */
                        $alias = $addressUser['alias'];
                        $lastname = $addressUser['lastname'];
                        $firstname = $addressUser['firstname'];
                        $company = $addressUser['company'];
                        $addresse = $addressUser['addresse'];
                        $addresseComp = $addressUser['address_comp'];
                        $zipCode = $addressUser['zip_code'];
                        $city = $addressUser['city'];
                        $country = $addressUser['country'];
                        $phone = $addressUser['phone'];

                        $addressBilling = $this -> _order -> addAddressBillingOrder($lastIdOrder,$idAddress,$idUser,$alias,$lastname,$firstname,$company,$addresse,$addresseComp,$zipCode,$city,$country,$phone);
                        $statusHistoryOrder = $this -> _order -> addStatusOrderHistory($lastIdOrder,$orderStatus);
                        
                        // On boucle les infos du panier pour récupérer les infos //                
                        foreach($cartProducts as $product)
                        {   
                            $idProduct = $product['id_product'];
                            $quantity = $_SESSION['basket'][$product['id_product']];
                            $unitPriceProduct = $product['price_tax_product'];
    
                            // On lance l'enregistrement du détail de la commande //
                            $orderValided = $this -> _order -> addOrderDetails($lastIdOrder,$idProduct,$quantity,$unitPriceProduct);

                            /* On enregistre les infos du produit dans une nouvelle table dans le cas
                            ou l'administrateur supprime celui-ci */
                            $nameProduct = $product['name_product'];
                            $referenceProduct = $product['reference_product'];

                            $this -> _order -> addOrderDetailsHistory($lastIdOrder,$idProduct,$nameProduct,$referenceProduct);
                        }
                        
                        if($orderValided)
                        {   
                            unset($_SESSION['basket']);
                            $success['save'] = 'Votre commande N° '.$lastIdOrder.' a bien été enregistrée.';
                            $_SESSION['success'] = $success;
                            header('location:index.php?page=customer_account');
                            exit();
                        }
                        else
                        {
                            $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement !';
                            $_SESSION['errors'] = $errors;
                            header('location:index.php?page=viewCart');
                            exit();
                        }
                    }
                    else
                    {
                        $errors['err'] = 'Vous devez choisir une adresse valide !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=cartAddressOrder');
                        exit();
                    }
                }
            }
            else
            {
                $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement !';
                $_SESSION['errors'] = $errors;
                header('location:index.php?page=viewCart');
                exit();
            }                        
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }
    
    public function historyOrders()
    {
        // on sécurise les pages utilisateur //
        if($this -> userIsConnect() == true)
        {   
            // instanciation du modele //
            $this -> _order = new Order();
               
            // On récupère les infos sur les commandes //
            $idUser = $_SESSION['idUser'];
            $orders = $this -> _order -> getOrdersByIdUser($idUser);

            // Seo page //
            $title = 'Historique de vos commandes';
            $metadescription = 'Retrouvez l\'historique de vos commandes et accédé aux détails de celle-ci.';

            // On affiche le template des historiques de commande //
            $template = 'www/views/users/order/history_orders';
            require('www/views/templates/layout.phtml');
        }
        else
        {
            header('location:index.php');
            exit();
        } 
    }
    
    public function orderDetails()
    {
        // on sécurise les pages utilisateur //
        if($this -> userIsConnect() == true)
        {   
            // instanciation du modele //
            $this -> _order = new Order();
               
            // On récupère les infos sur la commande avec son Id //
            if(array_key_exists('idOrder', $_GET) && is_numeric($_GET['idOrder']))
            {   
                // On vérifie que l'id reçu correspond à une commande de l'utilisateur //                
                $idOrder = $_GET['idOrder'];

                $verifOrder = $this -> _order -> getOrderByIdOrder($idOrder);               

                if($idOrder == $verifOrder['id_order'])
                {                    
                    $order = $this -> _order -> getOrderDetails($idOrder);
                    $orderHistory = $this -> _order -> getOrderHistoryByIdOrder($idOrder);

                    foreach($order as $detailsOrder)
                    {
                        $idOrder = $detailsOrder['id_order'];
                        $dateOrder = $detailsOrder['date_add'];
                        $delivery = $detailsOrder['name_delivery'];
                        $payment = $detailsOrder['name_payment'];
                    }

                    // On récupère l'adresse utilisée lors de la commande //
                    $addressUser = $this -> _order -> getAddressUserByIdOrder($idOrder);

                    // Seo page //
                    $title = 'Détails d\'une commande';
                    $metadescription = 'Voici le contenu de votre commande.';

                    // On affiche le template des historiques de commande //
                    $template = 'www/views/users/order/order_details';
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
        else
        {
            header('location:index.php');
            exit();
        } 
    }
}