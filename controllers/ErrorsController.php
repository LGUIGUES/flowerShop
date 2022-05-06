<?php 

class ErrorsController extends LayoutController
{
    public function displayErrors()
    {   
        // Affichage du template //
        $template = 'www/views/statics/404';
        require('www/views/templates/layout.phtml');
    }
}