<?php
// demarrage de session //
session_start();

// Fichiers requis //
require('config/configDb.php');
require('controllers/LayoutController.php');
require('controllers/UserController.php');
require('controllers/HomeController.php');
require('controllers/StaticPagesController.php');
require('controllers/ImagesController.php');
require('controllers/ContactController.php');
require('controllers/ServicesController.php');
require('controllers/ProductController.php');
require('controllers/CartController.php');
require('controllers/OrderController.php');
require('controllers/FlowerWorkshopController.php');
require('controllers/MessagingController.php');
require('controllers/ShopEventController.php');
require('controllers/ErrorsController.php');

if(array_key_exists('page',$_GET))
{   
    switch($_GET['page'])
    {   
        // Partie Gallerie Photos //
        case 'category' :
            $imagesController = new ImagesController();
            $imagesController -> viewGalleryImages($_GET['id']);
            break;
        
        // Partie produits //
        case 'viewProduct' :
            $productController = new ProductController();
            $productController -> viewProduct($_GET['id']);
            break;
            
        // Partie panier //
        case 'createCart' :
            $cartController = new CartController();
            $cartController -> createCart();
            break;

        case 'delCartProduct' :
            $cartController = new CartController();
            $cartController -> delCartProduct();
            break;            
        
        case 'viewCart' :
            $cartController = new CartController();
            $cartController -> viewCart();
            break;
         
        case 'cart' :
            $cartController = new CartController();
            $cartController -> cartProductQtyJson();
            break;        
       
        // Partie commande //
        case 'cartAddressOrder' :
            $orderController = new OrderController();
            $orderController -> cartAddressOrder();
            break;

        case 'cartOrder' :
            $orderController = new OrderController();
            $orderController -> cartOrder();
            break;    

        // Partie pages static //    
        case 'static' :
            $staticPagesController = new StaticPagesController();
            $staticPagesController -> viewStaticPage();
            break;
         
        case 'modal' :
            $staticPagesController = new StaticPagesController();
            $staticPagesController -> jsonModal();
            break;
            
        case 'shopEvents' :
            $shopEventController = new ShopEventController();
            $shopEventController -> viewShopEvents();
            break;    
        
        // Partie page service //
        case 'services' :
            $servicesController = new ServicesController();
            $servicesController -> viewServices();
            break;    

        // Partie pages contacts //    
        case 'contact' :
            $contactController = new ContactController();
            $contactController -> userContact();
            break;

        // Partie authentification utilisateurs //    
        case 'authentification' :
            $userController = new UserController();
            $userController -> connection();                    
            break;      
        case 'create_account' :
            $userController = new UserController();
            $userController -> createAccount();
            break;
        case 'forgot_password' :
            $userController = new UserController();
            $userController -> forgotPassword();
            break;  
        case 'disconnection' :
            $userController = new UserController();
            $userController -> userDisconnection();
            break;
        
        // Partie gestion compte utilisateur //
        case 'customer_account' :
            $userController = new UserController();
            $userController -> accountUser();                    
            break;
        case 'identity' :
            $userController = new UserController();
            $userController -> updateUserIdentity();
            break;
        case 'userAddress' :
            $userController = new UserController();
            $userController -> userAddress();
            break;
        case 'updateUserAddress' :
            $userController = new UserController();
            $userController -> updateUserAddress();
            break;    
        case 'deleteUserAddress' :
            $userController = new UserController();
            $userController -> deleteUserAddress();
            break;
        case 'addUserAddress' :
            $userController = new UserController();
            $userController -> addUserAddress();
            break;
        case 'myOrders' :
            $orderController = new OrderController();
            $orderController -> historyOrders();
            break;
        case 'viewOrderDetails' :
            $orderController = new OrderController();
            $orderController -> orderDetails();
            break;                  
        
        // Partie cours floral //
        case 'myBookingsFlowerWorkshop' :
            $flowerWorkshopController = new FlowerWorkshopController();
            $flowerWorkshopController -> myBookingsFlowerWorkshop();
            break;
        case 'registrationFlowerWorkshop' :    
            $flowerWorkshopController = new FlowerWorkshopController();
            $flowerWorkshopController -> registrationFlowerWorkshop();
            break;
        case 'deleteFlowerWorkshop' :    
            $flowerWorkshopController = new FlowerWorkshopController();
            $flowerWorkshopController -> deleteFlowerWorkshop();
            break;    
            
        // Partie Message utilisateurs //        
        case 'myMessages' :
            $messagingController = new MessagingController();
            $messagingController -> displayMessageUser();
            break;
        case 'detailsMessageUser' :
            $messagingController = new MessagingController();
            $messagingController -> detailsMessageUserById();
            break;
            
        // Page erreur 404 
        case '404' :
            $errorsController = new ErrorsController();
            $errorsController -> displayErrors();
            break;        
        default :
            $errorsController = new ErrorsController();
            $errorsController -> displayErrors();          
    }
}
else
{   
    $homeController = new HomeController();      
    $homeController -> shopPresentation();
}