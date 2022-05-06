<?php

class AdminOrderController extends AdminController
{
    public function displayOrders()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
            // Instanciation du modèle //
            $this -> _order = new Order();

            // Affichage des commandes //
            $statOrders = $this -> _order -> getAllSales();
            $orders = $this -> _order -> getAllOrders();            

            // On affiche la liste des produits de la boutique //
            $template = 'views/order/home_orders';
            require('../../www/7GhtK232c/views/templates/layout.phtml');
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function displayDetailsOrders()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
            // instanciation du modele //
            $this -> _order = new Order();
               
            // On récupère les infos sur la commande avec son Id //
            if(array_key_exists('idOrder', $_GET) && is_numeric($_GET['idOrder']))
            {
                // On vérifie que l'id reçu correspond à une commande de l'utilisateur //               
                $idOrder = $_GET['idOrder'];                 
                $verifOrder = $this -> _order -> getOrderByIdOrder($idOrder);
                
                if($_GET['idOrder'] == $verifOrder['id_order'])
                {   
                    // On met à jour la colonne de la notification //
                    $notification = 0;
                    $this -> _order -> updateNotification($notification,$idOrder);

                    $order = $this -> _order -> getOrderDetails($idOrder);
                    $orderHistory = $this -> _order -> getOrderHistoryByIdOrder($idOrder);                    
                    
                    foreach($orderHistory as $orderStatus)
                    {
                        $oldStatus = $orderStatus['id_status_order'];
                    }

                    $statusOrder = $this -> _order -> getStatusOrder();
                    
                    foreach($order as $detailsOrder)
                    {
                        $idOrder = $detailsOrder['id_order'];
                        $dateOrder = $detailsOrder['date_add'];
                    }

                    // On récupère l'adresse utilisée lors de la commande //
                    $addressUser = $this -> _order -> getAddressUserByIdOrder($idOrder);                    

                    // On affiche le template des historiques de commande //
                    $template = 'views/order/order_details';
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

    public function actionStatusOrder()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
            if(isset($_POST['submit']))
            {
                // instanciation du modele //
                $this -> _order = new Order();

                // On initialise un tableau vide pour la gestion d'erreur //
                $errors = [];

                // On initialise les REGEX pour les formats à valider correctement //
                $regexValidator = '#^[1-5]{1}$#'; // Chiffre 1 à 5 sur 1 position //
                
                if(!array_key_exists('oldStatus', $_POST) || $_POST['oldStatus'] == '' || !preg_match($regexValidator, $_POST['oldStatus']))
                {
                    $errors['oldStatus'] = 'Vous ne pouvez pas modifier cet état de commande.';
                }
                 
                if(!array_key_exists('newStatus', $_POST) || $_POST['newStatus'] == '' || !preg_match($regexValidator, $_POST['newStatus']))
                {
                    $errors['newStatus'] = 'Vous devez choisir un état de commande.';
                }

                if(!empty($errors))
                {
                    header('location:index.php?page=404');
                    exit();
                }
                else
                {
                    // On ajoute le nouveau status dans l'historique de commande //
                    $idOrder = $_POST['idOrder'];                   
                    $newStatus = $_POST['newStatus'];
                    
                    // On vérifie si le statut n'existe pas déjà sur la commande //
                    $orderStatus = $this -> _order -> verifOrderStatus($idOrder,$newStatus);

                    if($newStatus == $orderStatus['id_status_order'])
                    {
                        $errors['err'] = 'Cet état de commande existe déjà !';
                        $_SESSION['errors'] = $errors;                        
                        header('location:index.php?page=viewOrderDetails&idOrder='.$_POST['idOrder']);
                        exit();
                    }
                    else
                    {
                        $this -> _order -> updateCurrentOrderStatus($idOrder,$newStatus);
                        $newStatusValided = $this -> _order -> addNewStatusHistoryOrderByIdOrder($idOrder,$newStatus);

                        if($newStatusValided)
                        {   
                            $success['save'] = 'Nouveau statut de commande validé';
                            $_SESSION['success'] = $success;
                            header('location:index.php?page=viewOrderDetails&idOrder='.$_POST['idOrder']);
                            exit();
                        }
                        else
                        {                              
                            $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement !';
                            $_SESSION['errors'] = $errors;
                            header('location:index.php?page=viewOrders');
                            exit();
                        }
                    }
                }
            }            
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function orderNotification()
    {   
        // on sécurise les pages administrateur //  
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation //
            $this -> _order = new Order();

            // On récupère les nouvelles commandes //
            $orderNotification = $this -> _order -> getOrdersNotification();          
        }
        else
        {
            header('location:index.php');
            exit();
        }      
    }
}