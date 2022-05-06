<?php

// Fichiers requis //
require('models/Messaging.php');

class MessagingController extends LayoutController
{   
    private $_messaging;

    public function displayMessageUser()
    {           
        if($this -> userIsConnect() == true)
        {   
            // Instanciation du model //   
            $this -> _messaging = new Messaging();

            $idUser = $_SESSION['idUser'];
            // on récupère en BDD les messages de l'utilisateur //
            $messagesUser = $this -> _messaging -> getMessagesByIdUser($idUser);

            // Seo page //
            $title = 'Voici votre messagerie personnel';
            $metadescription = 'Vous pouvez suivre et consulter les messages que vous nous envoyés.';

            // on affiche le template //
            $template ='www/views/users/message/message_user';
            require('www/views/templates/layout.phtml');
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }
    
    public function detailsMessageUserById()
    {           
        if($this -> userIsConnect() == true)
        {   
            // Instanciation du model //   
            $this -> _messaging = new Messaging();

            if(array_key_exists('idThread',$_GET) && is_numeric($_GET['idThread']))
            {
                $idThread = $_GET['idThread'];                

                // On récupère tout le fil de messages avec l'id de l'utilisateur //
                $detailsMessage = $this -> _messaging -> getThreadMessageByIdThread($idThread);

                foreach($detailsMessage as $messages)
                {
                    $thread = $messages['id_thread_user'];                    
                }
                 
                // Seo page //
                $title = 'Votre message en détail';
                $metadescription = 'Consultez le détail de votre message.';

                // On affiche le template //
                $template = 'www/views/users/message/details_message_user';
                require('www/views/templates/layout.phtml');                        
            }
            else
            {
                // On affiche un message d'erreur //
                $message = 'Une erreur s\'est produite !';
                $_POST['message'] = $message;
                $template = 'www/views/users/message/message_user';
                require('www/views/templates/layout.phtml');
            } 
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }
}