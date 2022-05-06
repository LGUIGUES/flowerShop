<?php
// On démarre la session //
session_start();

// Fichiers requis //
require('../../config/configDb.php');
require('../../controllers/AdminController.php');
require('../../controllers/AdminAuthentificationController.php');
require('../../controllers/AdminDashBoardController.php');
require('../../controllers/AdminCategoryController.php');
require('../../controllers/AdminImagesController.php');
require('../../controllers/AdminMessagingController.php');
require('../../controllers/AdminConfigShopController.php');
require('../../controllers/AdminShopController.php');
require('../../controllers/AdminSocialNetworkController.php');
require('../../controllers/AdminWorkHourController.php');
require('../../controllers/AdminContentController.php');
require('../../controllers/AdminShopServiceController.php');
require('../../controllers/AdminShopEventController.php');
require('../../controllers/AdminHomeController.php');
require('../../controllers/AdminUserController.php');
require('../../controllers/AdminProductController.php');
require('../../controllers/AdminFlowerWorkshopController.php');
require('../../controllers/AdminOrderController.php');
require('../../controllers/AdminErrorsController.php');

// On lance le controller pour la connexion administrateur //
if(isset($_SESSION['idAdmin']))
{   
    // On gère les pages d'actions //
    if(array_key_exists('page',$_GET))
    {
        switch($_GET['page'])
        {    
            // Gestion du Dashboard //    
            case 'dashBoard' :
                $adminDashBoardController = new AdminDashBoardController();
                $adminDashBoardController -> dashBoard();
                break;

            // Gestion des catégories //    
            case 'category' :
                $adminCategoryController = new AdminCategoryController();
                $adminCategoryController -> updateCategory();
                break;
                
            // Gestion des images de la galerie //    
            case 'gallery' :
                $adminImagesController = new AdminImagesController();
                $adminImagesController -> viewCategorys();
                break;
            case 'addImage' :
                $adminImagesController = new AdminImagesController();
                $adminImagesController -> addImageGallery();
                break;
            case 'updateImage' :
                $adminImagesController = new AdminImagesController();
                $adminImagesController -> updateImageGallery();
                break;            
            
            // Gestion configuration boutique //    
            case 'configuration' :
                $adminConfigShopController = new AdminConfigShopController();
                $adminConfigShopController -> configurationShop();
                break;
            case 'updateShop' :
                $adminShopController = new AdminShopController();
                $adminShopController -> updateShopInformation();
                break;
            case 'updateSocial' :
                $adminSocialNetworkController = new AdminSocialNetworkController();
                $adminSocialNetworkController -> updateSocialNetworkInformation();
                break;
            case 'updateWorkHour' :
                $adminWorkHourController = new AdminWorkHourController();
                $adminWorkHourController -> updateWorkHours();
                break;

            // Gestion des pages de contenu //    
            case 'contentPages' :
                $adminContentController = new AdminContentController();
                $adminContentController -> configurationContentPage();
                break;
            case 'createPage' :
                $adminContentController = new AdminContentController();
                $adminContentController -> createContentPage();
                break;
            case 'updatePage' :
                $adminContentController = new AdminContentController();
                $adminContentController -> updateContentPage();
                break;
            case 'deletePage' :
                $adminContentController = new AdminContentController();
                $adminContentController -> deleteContentPage();
                break;
            
            // Gestion des messages //    
            case 'messaging' :
                $adminMessagingController = new AdminMessagingController();
                $adminMessagingController -> viewMessages();
                break;
                
            case 'detailsMessage' :
                $adminMessagingController = new AdminMessagingController();
                $adminMessagingController -> viewMessages();
                break;
                
            case 'answerMessage' :
                $adminMessagingController = new AdminMessagingController();
                $adminMessagingController -> viewMessages();
                break;    
            
            // Gestion Services //
            case 'createShopService' :
                $adminShopServiceController = new AdminShopServiceController();
                $adminShopServiceController -> createShopService();
                break;
            case 'updateShopService' :
                $adminShopServiceController = new AdminShopServiceController();
                $adminShopServiceController -> updateShopService();
                break;            
            case 'deleteShopService' :
                $adminShopServiceController = new AdminShopServiceController();
                $adminShopServiceController -> deleteShopService();
                break;
                
            // Gestion Evènement boutique //    
            case 'configurationEvent' :
                $adminShopEventController = new AdminShopEventController();
                $adminShopEventController  -> configurationEvents();
                break;
            case 'createShopEvent' :
                $adminShopEventController = new AdminShopEventController();
                $adminShopEventController -> createShopEvent();
                break;
            case 'updateShopEvent' :
                $adminShopEventController = new AdminShopEventController();
                $adminShopEventController -> updateShopEvent();
                break;
            case 'deleteShopEvent' :
                $adminShopEventController = new AdminShopEventController();
                $adminShopEventController -> deleteShopEvent();
                break;

            // Gestion page d'accueil //
            case 'configurationHomePage' :
                $adminHomeController = new AdminHomeController();
                $adminHomeController -> configurationHomePage();
                break;    
            
            // Gestion des clients //
            case 'viewUsers' :
                $adminUserController = new AdminUserController();
                $adminUserController -> displayUsers();
                break;
            case 'viewDetailsUser' :
                $adminUserController = new AdminUserController();
                $adminUserController -> displayDetailsUser();
                break;   
            
            // Gestion des produits //
            case 'viewProducts' :
                $adminProductController = new AdminProductController();
                $adminProductController -> displayProducts();
                break;

            case 'createProduct' :
                $adminProductController = new AdminProductController();
                $adminProductController -> createProduct();
                break;
            
            case 'editProduct' :
                $adminProductController = new AdminProductController();
                $adminProductController -> editProduct();
                break;
                
            case 'deleteProduct' :
                $adminProductController = new AdminProductController();
                $adminProductController -> deleteProduct();
                break;        

            // Gestion des ateliers //
            case 'viewWorkshop' :
                $adminFlowerWorkshopController = new AdminFlowerWorkshopController();
                $adminFlowerWorkshopController -> displayFlowerWorkshop();
                break;
            
            case 'createFlowerWorkshop' :
                $adminFlowerWorkshopController = new AdminFlowerWorkshopController();
                $adminFlowerWorkshopController -> createFlowerWorkshop();
                break;

            case 'editFlowerWorkshopDetails' :
                $adminFlowerWorkshopController = new AdminFlowerWorkshopController();
                $adminFlowerWorkshopController -> editFlowerWorkshop();
                break;
            
            case 'viewFlowerWorkshopDetails' :
                $adminFlowerWorkshopController = new AdminFlowerWorkshopController();
                $adminFlowerWorkshopController -> detailsFlowerWorkshop();
                break;

            case 'deleteFlowerWorkshop' :
                $adminFlowerWorkshopController = new AdminFlowerWorkshopController();
                $adminFlowerWorkshopController -> deleteFlowerWorkshop();
                break;
                
            case 'cancelFlowerWorkshop' :
                $adminFlowerWorkshopController = new AdminFlowerWorkshopController();
                $adminFlowerWorkshopController -> cancelFlowerWorkshop();
                break;    
                
            // Gestion des commandes //
            case 'viewOrders' :
                $adminOrderController = new AdminOrderController();
                $adminOrderController -> displayOrders();
                break;
            
            case 'viewOrderDetails' :
                $adminOrderController = new AdminOrderController();
                $adminOrderController -> displayDetailsOrders();
                break;
                
            case 'actionStatusOrder' :
                $adminOrderController = new AdminOrderController();
                $adminOrderController -> actionStatusOrder();
                break;
            
            // Gestion notifications //
            case 'messageNotification' :
                $adminMessagingController = new AdminMessagingController();
                $adminMessagingController -> messageNotification();
                break;

            case 'customerNotification' :
                $adminUserController = new AdminUserController();
                $adminUserController -> userNotification();
                break; 
                
            case 'orderNotification' :
                $adminOrderController = new AdminOrderController();
                $adminOrderController -> orderNotification();
                break;
                
            case 'totalNotifications' :   
                $adminDashBoardController = new AdminDashBoardController();
                $adminDashBoardController -> totalNotifications();
                break;

            // Gestion administrateur //
            case 'profil' :
                $adminAuthentificationController = new AdminAuthentificationController();
                $adminAuthentificationController -> updateProfilAdmin();
                break;
            case 'disconnectionAdmin' :
                $adminAuthentificationController = new AdminAuthentificationController();
                $adminAuthentificationController -> disconnectionAdmin();
                break;    

            // Gestion erreur 404 
            case '404' :
                $adminErrorsController = new AdminErrorsController();
                $adminErrorsController -> displayErrors();
                break;
            default :
                $adminErrorsController = new AdminErrorsController();
                $adminErrorsController -> displayErrors();
        }
    }
    else
    {   // On affiche le tableau de bord //
        $adminDashBoardController = new AdminDashBoardController();
        $adminDashBoardController -> dashBoard();
    }
}
else
{     
    if(array_key_exists('page',$_GET))
    {
        switch($_GET['page'])
        {
            case 'createAdmin' :
                $adminAuthentificationController = new AdminAuthentificationController();
                $adminAuthentificationController -> createAdmin();
                break;
            case 'logAdmin' :
                $adminAuthentificationController = new AdminAuthentificationController();
                $adminAuthentificationController -> logAdmin();
                break;
            case 'forgot_password' :
                $adminAuthentificationController = new AdminAuthentificationController();
                $adminAuthentificationController -> forgotPassword();
                break;
            default :
            $adminErrorsController = new AdminErrorsController();
            $adminErrorsController -> displayErrors();
        }
    }
    else
    {   
        $adminAuthentificationController = new AdminAuthentificationController();
        $adminAuthentificationController -> adminConnection();
    }
}