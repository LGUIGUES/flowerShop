<?php 

// Fichiers requis //
require('../../models/Admin.php');
require('../../models/User.php');
require('../../models/Category.php');
require('../../models/Images.php');
require('../../models/Messaging.php');
require('../../models/Shop.php');
require('../../models/SocialNetwork.php');
require('../../models/WorkHour.php');
require('../../models/Content.php');
require('../../models/ShopService.php');
require('../../models/ShopEvent.php');
require('../../models/Product.php');
require('../../models/FlowerWorkshop.php');
require('../../models/Order.php');

require('../../class/cleaner.php');

class AdminController
{
    protected $_admin;
    protected $_user;    
    protected $_category;
    protected $_images;
    protected $_messaging;
    protected $_shop;
    protected $_socialNetwork;
    protected $_workHour;
    protected $_content;
    protected $_shopService;
    protected $_shopEvent;
    protected $_product;
    protected $_flowerWorkshop;
    protected $_order;
    
    protected $_cleaner;
    
    public function __construct()
    {      
        $this -> _admin = new Admin();
        $this -> _messaging = new Messaging();
    }

    public function isAdminConnect()
    {
        return isset($_SESSION['idAdmin']);       
    }

    public function disconnectionAdmin()
    {
        if(isset($_SESSION['idAdmin']))
        {
            session_destroy();            
            header('Location:index.php');
            exit();
        } 
    }
}