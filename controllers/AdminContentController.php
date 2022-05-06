<?php

class AdminContentController extends AdminController
{
    public function configurationContentPage()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _content = new Content();

            // On récupère en BDD la liste des pages existantes //
            $contentPages = $this -> _content -> getContentPages();

            // On affiche le template des pages de contenu //
            $template = 'views/content/home_content';
            require('../../www/7GhtK232c/views/templates/layout.phtml');
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function createContentPage()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _content = new Content();

            if(isset($_POST['submit']))
            { 
                // On initialise un tableau vide pour la gestion d'erreur //                
                $errors = [];

                // On initialise les REGEX pour les formats à valider correctement //
                $regexActivePage = '#^[0-1]{1}$#'; // Chiffre 0 à 1 sur 1 position //
                $regexBlockPosition = '#^[1-6]{1}$#'; // Chiffre 1 à 6 sur 1 position //                

                // On vérifie que le choix du block est bien présent //
                if(!array_key_exists('blockLinkPage', $_POST) || $_POST['blockLinkPage'] == '' || !preg_match($regexBlockPosition, $_POST['blockLinkPage']))
                {
                    $errors['blockLinkPage'] = 'Vous devez choisir un emplacement pour votre page !';
                }

                // On vérife que le choix de l'activation de la page soit bien présent //
                if(!array_key_exists('activePage', $_POST) || $_POST['activePage'] == '' || !preg_match($regexActivePage, $_POST['activePage']))
                {
                    $errors['activePage'] = 'Vous devez choisir d\'activer ou non votre page !';
                }

                if(!array_key_exists('titleContent', $_POST) || $_POST['titleContent'] == '')
                {
                    $errors['titleContent'] = 'Vous devez saisir un titre à votre page !';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['formData'] = $_POST;
                    header('location:index.php?page=createPage');
                    exit();
                }
                else
                {
                    // On enregistre la page dans la BDD //
                    $titleContent = $_POST['titleContent'];
                    $textContent = $_POST['textContent'];
                    $activePage = $_POST['activePage'];
                    $blockLinkPage = $_POST['blockLinkPage'];
                    $deletable = 1;                    

                    // On appel la méthode du model //
                    $pageCreated = $this -> _content -> createContentPage($titleContent,$textContent,$deletable,$activePage,$blockLinkPage);

                    if($pageCreated)
                    {
                        // Si tout est ok, message à l'utilisateur //
                        $success['save'] = 'Votre page à bien été enregistrée.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=contentPages');
                        exit();
                    }
                    else
                    {
                        // Message d'erreur //
                        $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?createPage');
                        exit();
                    }
                }
            }
            else
            {
                // On affiche le template de création de page //
                $blocksLocation = $this -> _content -> getBlocksLocation();
                $template = 'views/content/create_page';
                require('../../www/7GhtK232c/views/templates/layout.phtml');
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function updateContentPage()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _content = new Content();

            if(isset($_POST['submit']))
            {  
                // On initialise un tableau vide pour la gestion d'erreur //                
                $errors = [];

                // On initialise les REGEX pour les formats à valider correctement //
                $regexActivePage = '#^[0-1]{1}$#'; // Chiffre 0 à 1 sur 1 position //
                $regexBlockPosition = '#^[1-6]{1}$#'; // Chiffre 1 à 6 sur 1 position // 
                
                // On vérifie que l'id de la page est bien présent //                
                if(!array_key_exists('idPage', $_GET) || $_GET['idPage'] == '')
                {
                    $errors['pageContent'] = 'Cette page n\'existe pas !';
                }
                
                // On vérifie que la position du bloc et le titre de la page n'ont pas été modifié pour les pages de bases et le bloc d'accueil de base//
                if(!array_key_exists('deletable', $_GET) || is_numeric($_GET['deletable'] == 0) && is_numeric($_GET['blockLinkPage'] == 6) || is_numeric($_GET['blockLinkPage'] == 1))
                {
                    $errors['titlePage'] = 'Cette opération est impossible !';
                }

                // Pour les autres pages, on vérifie que le titre est bien présent //                
                if(!array_key_exists('titleContent', $_POST) || $_POST['titleContent'] == '' && is_numeric($_GET['deletable']) != 0)
                {
                    $errors['titleContent'] = 'Vous devez saisir un titre à votre page !';
                }

                // On vérifie que le choix du block est bien présent //
                if(!array_key_exists('blockLinkPage', $_POST) || $_POST['blockLinkPage'] == '' || !preg_match($regexBlockPosition, $_POST['blockLinkPage']))
                {
                    $errors['blockLinkPage'] = 'Vous devez choisir un emplacement pour votre page !';
                }

                // On vérife que le choix de l'activation de la page soit bien présent //
                if(!array_key_exists('activePage', $_POST) || $_POST['activePage'] == '' || !preg_match($regexActivePage, $_POST['activePage']))
                {
                    $errors['activePage'] = 'Vous devez choisir d\'activer ou non votre page !';
                }

                // On vérifie que l'id reçu correspond bien à une page en BDD //
                $idPage = $_GET['idPage'];
                // On appel la méthode du model //
                $page = $this -> _content -> getContentPageById($idPage);

                if($page['id_page'] != $_GET['idPage'])
                {
                    $errors['err'] = 'Cette modification est impossible !';                   
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['formData'] = $_POST;
                    header('location:index.php?page=updatePage&idPage='.$_GET['idPage'].'&deletable='.$_GET['deletable']);
                    exit();
                }
                else
                {
                    // Pour les pages de bases, on lance une mise à jour dans la BDD uniquement sur le contenu //
                    if($_POST['deletable'] == 0)
                    {   
                        $idPage = $_GET['idPage'];
                        $textContent = $_POST['textContent'];
                        $activePage = $_POST['activePage'];
                        $pageUpdated = $this -> _content -> updateContentFixedPage($idPage,$textContent,$activePage);

                        if($pageUpdated)
                        {
                            // Si tout est ok, message à l'utilisateur //
                            $success['save'] = 'Votre page a bien été mise à jour.';
                            $_SESSION['success'] = $success;
                            header('location:index.php?page=contentPages');
                            exit();
                        }
                        else
                        {
                            // Si tout est ok, message à l'utilisateur //
                            $success['save'] = 'Votre page a bien été mise à jour.';
                            $_SESSION['success'] = $success;
                            header('location:index.php?page=contentPages');
                            exit();
                        }
                    }
                    elseif($_POST['deletable'] == 1)
                    {
                        // Pour les autres pages, on lance une mise à jour du titre, du contenu et de la position dans le footer //
                        $idPage = $_GET['idPage'];
                        $titleContent = $_POST['titleContent'];
                        $textContent = $_POST['textContent'];
                        $activePage = $_POST['activePage'];
                        $blockLinkPage = $_POST['blockLinkPage'];
                        $pageUpdated = $this -> _content -> updateContentPage($idPage,$titleContent,$textContent,$activePage,$blockLinkPage);

                        if($pageUpdated)
                        {
                            // Si tout est ok, message à l'utilisateur //
                            $success['save'] = 'Votre page à bien été mise à jour.';
                            $_SESSION['success'] = $success;
                            header('location:index.php?page=contentPages');
                            exit();
                        }
                        else
                        {
                            // Message d'erreur //
                            $errors['err'] = 'Une erreur s\'est produite lors de la mise à jour !';
                            $_SESSION['errors'] = $errors;
                            header('location:index.php?page=updatePage&idPage='.$_GET['idPage'].'&deletable='.$_GET['deletable']);
                            exit();
                        }                        
                    }
                    else
                    {
                        // Message d'erreur //
                        $errors['err'] = 'Cette opération est impossible !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=contentPages');
                        exit();
                    }
                }
            }
            else
            {
                // On affiche le template de mise à jour de la page //
                if(array_key_exists('idPage', $_GET) && is_numeric($_GET['idPage']))
                {                       
                    // On vérifie que l'id reçu correspond bien à une page en BDD //
                    $idPage = $_GET['idPage'];

                    // On appel la méthode du model //
                    $page = $this -> _content -> getContentPageById($idPage);

                    if($page['id_page'] != $_GET['idPage'])
                    {
                        $errors['err'] = 'Cette page n\'existe pas !';                   
                    }

                    if(!empty($errors))
                    {
                        $_SESSION['errors'] = $errors;                      
                        header('location:index.php?page=contentPages');
                        exit();
                    }
                    else
                    {   
                        // On affiche le template de modification de page //
                        $page = $this -> _content -> getContentPageById($idPage);
                        $blocksLocation = $this -> _content -> getBlocksLocation();

                        $template = 'views/content/update_page';
                        require('../../www/7GhtK232c/views/templates/layout.phtml');
                    }                   
                }
                else
                {                    
                    // Message d'erreur //
                    $errors['err'] = 'Cette page n\'existe pas !';
                    $_SESSION['errors'] = $errors;
                    header('location:index.php?page=contentPages');
                    exit();
                }
            }            
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    public function deleteContentPage()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _content = new Content();
            
            if(array_key_exists('idPage', $_GET) && is_numeric($_GET['idPage']))
            {
                // On initialise un tableau vide pour la gestion d'erreur //                
                $errors = [];

                /* On vérifie que l'id reçu correspond bien à une page en BDD
                et que ce n'est pas une page non supprimable */
                $idPage = $_GET['idPage'];
                // On appel la méthode du model //
                $page = $this -> _content -> getContentPageById($idPage);

                if($page['id_page'] != $_GET['idPage'] || $page['deletable'] == 0)
                {
                    $errors['err'] = 'Cette suppression est impossible !';                   
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;                   
                    header('location:index.php?page=contentPages');
                    exit();
                }
                else
                {
                    // On lance la suppression de la page en BDD //
                    $deletedPage = $this -> _content -> deleteContentPageById($idPage);

                    if($deletedPage)
                    {
                        // Si tout est ok, on prévient l'utilisateur //
                        $success['deleted'] = 'Votre page a bien été supprimée.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=contentPages');
                        exit();
                    }
                    else
                    {
                        // Message à l'utilisateur //
                        $errors['err'] = 'Une erreur s\'est produite lors de la suppression ! ';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=contentPages');
                        exit();
                    }
                }
            }
            else
            {
                // Message d'erreur //
                $errors['err'] = 'Cette page n\'existe pas !';
                $_SESSION['errors'] = $errors;
                header('location:index.php?page=contentPages');
                exit();
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }
}