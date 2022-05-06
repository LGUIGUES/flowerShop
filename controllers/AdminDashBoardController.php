<?php

class AdminDashBoardController extends AdminController
{
    protected $_admin;
    
    public function __construct()
    {
        parent:: __construct();
        $this -> _admin = new AdminAuthentificationController();        
    }

    // On affiche le tableau de bord //
    public function dashBoard()
    {
        // On sécurise les pages Administrateur //
        if($this -> _admin -> isAdminConnect() == true)
        {   
            $this -> _order = new Order();
            // Données pour les statistiques //
            $statistics = $this -> _order -> getAllSales();
            $averageBasket = !empty($statistics['totalSales'] != 0) ? number_format($statistics['totalSales'] / $statistics['numberCustomer'],2) : ' 0 ';

            $this -> _flowerWorkshop = new FlowerWorkshop();
            // Affiche les 5 prochains cours //
            $flowersWorkshop = $this -> _flowerWorkshop -> getFlowersWorkshopDashboard();
            
            // Affiche les 5 dernières commandes //
            $orders = $this -> _order -> getLastOrders();
            
            $template = 'views/home/homeDashboard';            
            require('../../www/7GhtK232c/views/templates/layout.phtml');
        }
        else
        {
            header('location:index.php');
            exit();
        }
    }
    
    public function totalNotifications()
    {   
        // On instancie nos modèles //
        $this -> _messaging = new Messaging();
        $this -> _user = new User();
        $this -> _order = new Order();

        /* On récupère les notifications des nouveaux messages, des nouveaux
        clients et des nouvelles commandes */

        $msgNotification = $this -> _messaging -> getMsgNotification();
        $userNotification = $this -> _user -> getUserNotification();
        $orderNotification = $this -> _order -> getOrderNotification();

        $totalNotifications = $msgNotification[0]+$userNotification[0]+$orderNotification[0];
        
        echo json_encode($totalNotifications);
    }
}