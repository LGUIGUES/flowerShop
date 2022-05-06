<?php

class StaticPagesController extends LayoutController
{  
    public function viewStaticPage()
    {   
        if(array_key_exists('id', $_GET) && is_numeric($_GET['id']))
        {   
            $idPage = $_GET['id'];
                       
            // On récupère en BDD les infos sur la page //
            $content = $this -> _content -> getStaticPageContent($idPage);               

            if($content)
            {  
                $tags = $this -> authorizedTags;

                // Seo page //
                $title = $content['title_content'];
                $metadescription = $content['title_content'];
                
                $template = 'www/views/statics/static_page';
                require('www/views/templates/layout.phtml');
            }
            else
            {
                // Erreur 404 - Page non trouvée //
                header('location:index.php?page=404');
                exit();               
            }            
        }
        else
        {
            // Erreur 404 - Page non trouvée //
            header('location:index.php?page=404');
            exit();
        }
    }    
    
    public function jsonModal()
    {   
        $idPage = 3;

        // On récupère en BDD les infos sur la page //
        $legalNotice = $this -> _content -> jsonModal($idPage); 
    }
}