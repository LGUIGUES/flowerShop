<?php

class ServicesController extends LayoutController
{   
    protected $_shopService;

    public function viewServices()
    {   
        // Instanciation du model //
        $this -> _shopService = new ShopService();
        
        // On récupère les infos en BDD //
        $idPage = 6;
        $contentServices = $this -> _content -> getStaticPageContent($idPage);
        $shopServices = $this -> _shopService -> getShopServices();        

        // Seo page //
        $title = 'Les services proposés par votre fleuriste';
        $metadescription = 'Votre fleuriste vous propose tout une gamme de service pour votre satisfaction.';

        // On appel le template de la page //
        $tags = $this -> authorizedTags;
        $template = 'www/views/services/services';
        require('www/views/templates/layout.phtml');
    }
}