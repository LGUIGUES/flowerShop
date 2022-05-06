<?php

class AdminMessagingController extends AdminController
{
    public function viewMessages()
    {   
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            if(isset($_POST['submit']))
            {   
                if(!array_key_exists('thread', $_POST) || $_POST['thread'] == '' || !is_numeric($_POST['thread']))
                {
                    $errors['thread'] = 'Opération impossible !';
                }
                
                if(!array_key_exists('employee', $_POST) || $_POST['employee'] == '' || !is_numeric($_POST['employee']))
                {
                    $errors['employee'] = 'Opération impossible !';
                }  

                if(!array_key_exists('message', $_POST) || $_POST['message'] == '')
                {
                    $errors['message'] = 'Vous devez saisir votre message !';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['formData'] = $_POST;
                    header('location:index.php?page=answerMessage&idUser='.$_POST['idUser']);
                    exit();
                }
                else
                {
                    // On enregistre la réponse en BDD //
                    $thread = $_POST['thread'];
                    $employee = $_SESSION['employee'];                   
                    $message = $_POST['message'];
                    $answerValided = $this -> _messaging -> addAnswerMessage($thread,$employee,$message);
                   
                    if($answerValided)
                    {   
                        $success['save'] = 'Votre message a bien été envoyé.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=messaging');
                        exit();
                    }
                    else
                    {
                        $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=messaging');
                        exit();
                    }
                }
            }
            elseif(array_key_exists('idThread', $_GET) && is_numeric($_GET['idThread']))
            {
                // Instanciation //
                $this -> _messaging = new Messaging();

                // On récupère tout le fil de messages avec l'id du fil de message //
                $idThread = $_GET['idThread'];
                $detailsMessage = $this -> _messaging -> getThreadMessageByIdThread($idThread);

                foreach($detailsMessage as $messages)
                {
                    $thread = $messages['id_thread_user'];
                    $email = $messages['email'];
                    $nameUser = $messages['firstname'].' '.$messages['lastname'];
                    $idUser = $messages['id_user'];
                }

                // On met à jour la colonne de la notification //
                $notification = 0;
                $this -> _messaging -> updateNotification($notification,$idThread);

                // On affiche le template //
                $template = 'views/messaging/details_message';
                require('../../www/7GhtK232c/views/templates/layout.phtml');
            }
            else
            {
                // On récupère les messages clients et invités //
                $messages = $this -> _messaging -> getAllMessages();
                
                // On affiche le template //
                $template = 'views/messaging/messaging';
                require('../../www/7GhtK232c/views/templates/layout.phtml');                
            }            
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }
    
    public function messageNotification()
    {   
        // on sécurise les pages administrateur //  
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation //
            $this -> _messaging = new Messaging();

            // On récupère les messages //
            $messageNotification = $this -> _messaging -> getMessagesNotification();            
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }
}