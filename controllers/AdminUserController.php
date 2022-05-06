<?php

class AdminUserController extends AdminController
{
    public function displayUsers()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _user = new User();
            $this -> _order = new Order();
            
            // On récupère les infos pour les statistiques //
            $averageUserAge = $this -> _user -> getAverageUserAge();
            $genderDistribution = $this -> _user -> getGenderDistribution();
            $numberCustomers = $this -> _user -> getNumberCustomers();
            $averageOrdersByUser = $this -> _order -> getAverageOrdersByUser();

            // On récupère la liste des clients en BDD //
            $users = $this -> _user -> getallUsers();

            // On affiche la liste des clients de la boutique //
            $template = 'views/user/home_users';
            require('../../www/7GhtK232c/views/templates/layout.phtml');
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function displayDetailsUser()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {
            // Instanciation du modèle //
            $this -> _user = new User();
            $this -> _order = new Order();
            $this -> _messaging = new Messaging();
            $this -> _flowerWorkshop = new FlowerWorkshop();

            if(array_key_exists('id', $_GET) && is_numeric($_GET['id']))
            {
                $idUser = $_GET['id'];
                // On récupère les infos en BDD //
                $user = $this -> _user -> getUserById($idUser);
                $addresses = $this -> _user -> getUserAddressesById($idUser);
                $orders = $this -> _order -> getOrdersByIdUser($idUser);
                $productsPurchased = $this -> _order -> getProductsPurchasedOrdersByIdUser($idUser);
                $statUserOrders = $this -> _order -> getStatOrdersByIdUser($idUser);
                $statUserTotalProductsPurchased = $this -> _order -> getStatProductsPurchasedByIdUser($idUser);
                $messagesUser = $this -> _messaging -> getMessagesByIdUser($idUser);
                $flowersWorkshopUser = $this -> _flowerWorkshop -> getFlowersWorkshopUserById($idUser);
                
                // On met à jour la colonne de la notification //
                $notification = 0;
                $this -> _user -> updateNotification($notification,$idUser);

                // On affiche les détails du client //
                $template = 'views/user/details_user';
                require('../../www/7GhtK232c/views/templates/layout.phtml');
            }
            else
            {
                $errors['err'] = 'Cette opération est impossible !';
                $_SESSION['errors'] = $errors;
                header('location:index.php?page=viewUsers');
                exit(); 
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function userNotification()
    {   
        // on sécurise les pages administrateur //  
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation //
            $this -> _user = new User();

            // On récupère les nouveaux clients //
            $customerNotification = $this -> _user -> getCustomersNotification();          
        }
        else
        {
            header('location:index.php');
            exit();
        } 
    }
}