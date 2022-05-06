<?php

// Fichiers requis //
require('models/FlowerWorkshop.php');
require('models/WorkHour.php');
require('models/ShopService.php');
require('models/ShopEvent.php');

class HomeController extends LayoutController
{   
    protected $_flowerWorkshop;
    protected $_workHour;
    protected $_shopService;
    protected $_shop;
    protected $_content;
    protected $_category;
    protected $_shopEvent;

    public function shopPresentation()
    {   
        // Instanciation des modèles //
        $this -> _flowerWorkshop = new FlowerWorkshop();
        $this -> _workHour = new WorkHour();
        $this -> _shopService = new ShopService();
        $this -> _shop = new Shop();
        $this -> _content = new Content();
        $this -> _category = new Category();
        $this -> _shopEvent = new ShopEvent();

        // Titre d'accroche //       
        $homePageTeaserTitle = $this -> _shop -> getHomepageTeaserTitle();

        // Jours et horaires boutique //
        $workingingHours = $this -> _workHour -> getShopWorkHours();
        
        foreach($workingingHours as $work)
        {
            if($work['active'] == 1)
            {   
                $hours = $work['hour_morning_start'].' à '.$work['hour_morning_end'].' et de '.$work['hour_afternoon_start'].' à '.$work['hour_afternoon_end'];
                $endDay = $work['day'];
                if($work['start_day'] == 1)
                {
                    $startDay = $work['day'];                    
                }                            
            }
            if($work['active'] == 2)
            {
                $hourWeek = $work['hour_morning_start'].' à '.$work['hour_morning_end'];
                $dayWeek = $work['day'];
            }            
        }
        
        // Gestion bloc text personnalisable - Position 2 //
        $idLocation = 1;
        $tags = $this -> authorizedTags;
        $customizeText = $this -> _content -> getCustomHomeBlocByPosition($idLocation);       

        // Gestion du bloc services //
        $shopServices = $this -> _shopService -> getShopServices();        

        // Gestion du bloc cours floral //
        $floralWorkshopInformations = $this -> _flowerWorkshop -> getFlowersWorkshopForHome();

        // Gestion images des categories //
        $imagesCategorys = $this -> _category -> getImagesCategory();

        // Gestion bloc évènement //
        $event = $this -> _shopEvent -> getEventHome();
        
        // SEO - Titre et métadescription //
        $title = 'FlowerShop - Votre fleuriste';
        $metadescription = 'Le fleuriste FlowerShop vous accueille dans son nouvel espace. Vous pourrez trouver un large choix de fleurs (traditionnelles et exotiques),des plantes, vertes et fleuries, d\'intérieur ou d\'extérieur, des vases, des cadeaux et de la décoration originale...';

        // On appel le template pour affichage //        
        $template = 'www/views/home/home';
        require('www/views/templates/layout.phtml');
    }
}