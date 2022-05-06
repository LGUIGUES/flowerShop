<?php

class FlowerWorkshopController extends LayoutController
{   
    private $_user;
    protected $_flowerWorkshop;

    public function myBookingsFlowerWorkshop()
    {   
        if($this -> userIsConnect() == true)
        {   
            // Instanciation du model //   
            $this -> _user = new User();
            $this -> _flowerWorkshop = new FlowerWorkshop(); 

            // On récupère en BDD les infos sur les cours de l'utilisateur //
            $idUser = $_SESSION['idUser'];

            $flowersWorkshopUser = $this -> _flowerWorkshop -> getFlowersWorkshopUserById($idUser);

            // Seo page //
            $title = 'Vos réservations à notre atelier floral';
            $metadescription = 'Retrouvez vos réservations à nos cours d\'art floral.';

            // On affiche le template //               
            $template = 'www/views/users/flowerworkshop/flowers_works';
            require('www/views/templates/layout.phtml');
        }
        else
        {
            header('location:index.php');
            exit();
        }
    }

    public function registrationFlowerWorkshop()
    {
        if($this -> userIsConnect() == true)
        {   
            // Instanciation du model //   
            $this -> _user = new User();
            $this -> _flowerWorkshop = new FlowerWorkshop();

            if(isset($_POST['submit']))
            {
                // On initialise un tableau vide pour la gestion d'erreur //                
                $errors = [];                

                /* On vérifie que bookingDay correspondent bien à un cours existant en BDD
                et on récupère le nombre de places disponibles  - On vérifie également que
                l'utilisateur ne soit pas déjà inscrit à ce cours */
                $idWork = $_POST['bookingDay'];
                $verifBookingDay = $this -> _flowerWorkshop -> getFlowerWorkshopById($idWork);
                
                $verifBookingUser = $this -> _flowerWorkshop -> getFlowerWorkshopUser($idWork);
                
                if($idWork != $verifBookingDay['id_work'])
                {
                    $errors['bookingDay'] = 'Ce cours n\'existe pas !';                    
                }

                if($verifBookingDay['space_available'] == 0)
                {
                    $errors['bookingDay'] = 'Plus de place dans ce cours !'; 
                }

                if($idWork == $verifBookingUser['id_work'] && $_SESSION['idUser'] == $verifBookingUser['id_user'])
                {
                    $errors['bookingDay'] = 'Vous êtes déjà inscrit à ce cours !';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    header('location:index.php?page=registrationFlowerWorkshop');
                    exit();
                }
                else
                {
                    // On enregistre l'inscription du client en BDD //
                    // On récupère l'id de l'utilisateur //
                    $idUser = $_SESSION['idUser'];                    

                    $flowerWorkshopValided = $this -> _flowerWorkshop -> registrationUserFlowerWorkshop($idWork,$idUser);

                    // On met à jour le nombre de place disponible restante et les cours vides correspondant //
                    $newAvailableSpace = $verifBookingDay['space_available'] - 1;
                    $this -> _flowerWorkshop -> updateAvailableSpaceFlowerWorkshopById($idWork,$newAvailableSpace);
                    $this -> _flowerWorkshop -> deleteFlowerWorkshopDetails($idWork);

                    if($flowerWorkshopValided)
                    {
                        $success['save'] = 'Votre inscription est bien enregistrée.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=myBookingsFlowerWorkshop');
                        exit();
                    }
                    else
                    {
                        // Message d'erreur //
                        $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=registrationFlowerWorkshop');
                        exit();
                    }
                }
            }
            else
            {   
                // On récupère en BDD les infos de l'utilisateur //
                $idUser = $_SESSION['idUser'];
                $customer = $this -> _user -> getUserById($idUser);

                // On récupère en BDD les cours disponibles //
                $flowersWorkshop = $this -> _flowerWorkshop -> getAllFlowersWorkshopForUser();

                // Seo page //
                $title = 'Inscription à un cours floral';
                $metadescription = 'Formulaire d\'inscription à un cours floral.';

                // On affiche le template //               
                $template = 'www/views/users/flowerworkshop/form_flowers_works';
                require('www/views/templates/layout.phtml');
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }
    }

    public function deleteFlowerWorkshop()
    {
        if($this -> userIsConnect() == true)
        {   
            // Instanciation du model //   
            $this -> _user = new User();
            $this -> _flowerWorkshop = new FlowerWorkshop(); 

            if(isset($_POST['submit']) && is_numeric($_POST['idWork']))
            {   
                // On initialise un tableau vide pour la gestion d'erreur //
                $errors = [];
                
                /* On récupère le nombre de places disponibles et les infos du cours */
                $idWork = $_POST['idWork'];
                $flowerWorkshop = $this -> _flowerWorkshop -> getFlowerWorkshopById($idWork);
                //$verifBookingUser = $this -> _flowerWorkshop -> getFlowerWorkshopUser($idWork);
                
                // On vérifie que l'id reçu correspond bien au cours dans la BDD //
                if($idWork != $flowerWorkshop['id_work'])
                {
                    $errors['err'] = 'Cette opération est impossible !';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;                   
                    header('location:index.php?page=myBookingsFlowerWorkshop');
                    exit();
                }
                else
                {
                    // On supprime la réservation du cours en BDD //
                    $idUser = $_SESSION['idUser'];
                    $flowerWorkshopDeleted = $this -> _flowerWorkshop -> deleteUserFlowerWorkshop($idWork,$idUser);
                    
                    // On met à jour le nombre de place disponible restante et les cours vides correspondant //
                    $newAvailableSpace = $flowerWorkshop['space_available'] + 1;
                    $this -> _flowerWorkshop -> updateAvailableSpaceFlowerWorkshopById($idWork,$newAvailableSpace);
                    $this -> _flowerWorkshop -> addFlowerWorkshopDetails($idWork);

                    if($flowerWorkshopDeleted)
                    {
                        $success['delete'] = 'Ce cours a bien été supprimé.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=myBookingsFlowerWorkshop');
                        exit();
                    }
                    else
                    {   
                        // Message d'erreur //
                        $errors['err'] = 'Une erreur s\'est produite lors de l\'enregistrement !';
                        $_SESSION['errors'] = $errors; 
                        header('location:index.php?page=myBookingsFlowerWorkshop');
                        exit();
                    }
                }
            }
            else
            {
                header('location:index.php?page=404');
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