<?php

// Fichier requis //
require('models/Contact.php');

class ContactController extends LayoutController
{   
    protected $_contact;
    protected $_user;
    protected $_messaging;

    public function userContact()
    {
        if(isset($_POST['submit']))
        {   
            // On initialise un tableau vide pour la gestion d'erreur //                
            $errors = []; 

            // On vérifie si un utlisateur est connecté //
            if(isset($_SESSION['idUser']))
            {   
                // Instanciation des modeles //
                $this -> _messaging = new Messaging();
                $this -> _user = new User();

                if(!array_key_exists('message', $_POST) || $_POST['message'] == '')
                {
                    $errors['message'] = 'Vous devez saisir un message.';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['formData'] = $_POST;
                    header('location:index.php?page=contact');
                    exit();
                }
                else
                {
                    $idUser = $_POST['idUser'];
                    
                    // On récupère l'adresse e-mail du client //
                    $customer = $this -> _user -> getUserById($idUser);
                    $email = $customer['email'];
                    $message = $_POST['message'];

                    // On enregistre le message en BDD en deux étapes - Le fil et le message //
                    $lastIdThread = $this -> _messaging -> addThreadUser($idUser,$email);                    
                    $messageCreated = $this -> _messaging -> addMessage($lastIdThread,$message);

                    if($messageCreated)
                    {
                        $success['save'] = 'Nous avons bien reçu votre message.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=contact');
                        exit();
                    }
                    else
                    {
                        // Message d'erreur //
                        $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=contact');
                        exit();
                    }
                }                
            }
            else
            {   
                // Instanciation du model //
                $this -> _messaging = new Messaging();
                $this -> _contact = new Contact();

                if(!array_key_exists('email', $_POST) || $_POST['email'] == '' || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                {
                    $errors['email'] = 'Vous devez saisir un email valide.';
                }
                
                if(!array_key_exists('message', $_POST) || $_POST['message'] == '')
                {
                    $errors['message'] = 'Vous devez saisir un message.';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['formData'] = $_POST;
                    header('location:index.php?page=contact');
                    exit();
                }
                else
                {
                    $email = $_POST['email'];
                    $idUser = 0;
                    $message = $_POST['message'];                

                    // On enregistre le message en BDD en deux étapes - Le fil et le message //
                    $lastIdThread = $this -> _messaging -> addThreadUser($idUser,$email);                    
                    $messageCreated = $this -> _messaging -> addMessage($lastIdThread,$message);

                    if($messageCreated)
                    {
                        $success['save'] = 'Nous avons bien reçu votre message.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=contact');
                        exit();
                    }
                    else
                    {
                        // Message d'erreur //
                        $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=contact');
                        exit();
                    }
                }   
            }            
        }
        else
        {   
            // Seo page //
            $title = 'Formulaire de contact de votre magasin';
            $metadescription = 'Pour nous contacter, c\'est simple, remplissez le formulaire ci-dessous et nous vous répondrons dans les meilleurs délais.';
            // On affiche le formulaire //
            $template = 'www/views/contacts/contact_form';
            require('www/views/templates/layout.phtml');            
        }        
    }
}