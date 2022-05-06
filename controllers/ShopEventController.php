<?php

class ShopEventController extends LayoutController
{   
    public function viewShopEvents()
    {
        // Instanciation modèle //
        $this -> _shopEvent = new ShopEvent();

        // On récupère tous les évènements en BDD //
        $shopEvents = $this -> _shopEvent -> getAllEvents();

        // Seo page //
        $title = 'Evènements en cours chez votre fleuriste';
        $metadescription = 'Retrouvez ici les évènement en cours chez votre fleuriste.';

        $tags = $this -> authorizedTags;
        
        $template = 'www/views/shop_event/events';
        require('www/views/templates/layout.phtml');        
    }
}