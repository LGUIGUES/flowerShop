<?php

// Fichiers requis //
require('models/User.php');

class UserController extends LayoutController
{    
    protected $_user;
    protected $_order;   

    public function createAccount()
    {   
        // Instanciation du model //   
        $this -> _user = new User();

        // on test que le formulaire envoyé ne soit pas vide //
        if(isset($_POST['submit']))
        {                
            // On initialise un tableau vide pour la gestion d'erreur //
            $errors = [];            

            // On initialise les REGEX pour les formats à valider correctement //
            $regexGender = '#^[1-2]{1}$#'; // Chiffre 1 à 2 sur 1 position //            
            $regexPassword = '#^.{8,20}$#'; // Tous caractères min 8 et max 20 //

            if(!array_key_exists('gender', $_POST) || $_POST['gender'] == '' || !preg_match($regexGender, $_POST['gender']))
            {
                $errors['gender'] = 'Vous devez choisir une civilité.';
            }

            if(!array_key_exists('lastName', $_POST) || $_POST['lastName'] == '')
            {
                $errors['lastName'] = 'Vous devez saisir un nom.';
            }

            if(!array_key_exists('firstName', $_POST) || $_POST['firstName'] == '')
            {
                $errors['firstName'] = 'Vous devez saisir un prénom.';
            }

            if(!array_key_exists('email', $_POST) || $_POST['email'] == '' || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $errors['email'] = 'Vous devez saisir un email valide.';
            }            
            
            if(!array_key_exists('dateBirthday', $_POST) || $_POST['dateBirthday'] == '')
            {
                $dateBirthday = null;                
            }
            else
            {
                $dateBirthday = $_POST['dateBirthday'];
            }                

            if(!array_key_exists('legal', $_POST) || $_POST['legal'] == '')
            {
                $errors['legal'] = 'Vous devez accepter notre politique de confidentialité.';
            }    

            if(!array_key_exists('password', $_POST) || $_POST['password'] == '' || !preg_match($regexPassword, $_POST['password']))
            {
                $errors['password'] = 'Vous devez saisir un mot de passe valide.';
            }

            if(array_key_exists('email', $_POST))
            {
                // On vérifie que l'adresse mail n'existe pas déjà en BDD //
                $email = $_POST['email'];
                $verifEmail = $this -> _user -> getUserByEmail($email);
                          
                if($verifEmail)
                {  
                    $errors['email'] = 'Cette adresse Email existe déjà.';
                }
            }

            if(!empty($errors))
            {   
                $_SESSION['errors'] = $errors;
                $_SESSION['formData'] = $_POST;                
                header('location:index.php?page=create_account');
                exit();
            }
            else
            {
                // Si tout ok, on enregistre l'utilisateur //
                $idGender = $_POST['gender'];
                $lastName = $_POST['lastName'];
                $firstName = $_POST['firstName'];                            
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $gdprConsent = 1;                        

                // on appel la méthode addUser de la class User en transmettant les paramètres /
                $accountValided = $this -> _user -> addUser($idGender,$lastName,$firstName,$email,$password,$dateBirthday,$gdprConsent);
                    
                // En retour, addUser nous retourne true ou false //
                // on test pour afficher un message au client //
                if($accountValided)
                {
                    $success = 'Votre compte a bien été créé.';
                    $_SESSION['success'] = $success;                    
                    header('location:index.php?page=authentification');
                    exit();                             
                }
                else
                {
                    $errors = 'Une erreur est survenue !';
                    $_SESSION['errors'] = $errors;                
                    header('location:index.php?page=create_account');
                    exit();                    
                }     
            }
        }
        // on appel le template du formulaire de creation de compte //         
        else
        {   // Seo page //
            $title = 'Création de votre compte';
            $description = 'La création de votre espace personnalisé permet le suivi de vos commandes, les réservations à notre atelier floral et vos messages.';

            $template = 'www/views/users/users/create_account';
            require('www/views/templates/layout.phtml');
        }
    }

    public function connection()
    {   
        // Instanciation du model //   
        $this -> _user = new User();

        // on test que le formulaire envoyé ne soit pas vide //
        if(isset($_POST['submit']))
        {   
            // On initialise un tableau vide pour la gestion d'erreur //
            $errors = [];

            if(!array_key_exists('email', $_POST) || $_POST['email'] == '' || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $errors['email'] = 'Vous devez saisir un email valide.';
            }

            if(!array_key_exists('password', $_POST) || $_POST['password'] == '')
            {
                $errors['password'] = 'Vous devez saisir un mot de passe.';
            } 
            
            if(array_key_exists('password', $_POST) && $_POST['password'] != '')
            {
                // On continue la connexion de l'utilisateur //
                $email = $_POST['email'];
                $password = $_POST['password'];

                // on appel la méthode verifUser pour récupérer les infos en BDD //
                $logUser = $this -> _user -> getVerifyUser($email);

                if(!password_verify($password, $logUser['passwd']))
                {
                    $errors['password'] = 'Identification impossible !';
                }
            }            

            if(!empty($errors))
            {   
                $_SESSION['errors'] = $errors;
                $_SESSION['formData'] = $_POST;                
                header('location:index.php?page=authentification');
                exit();
            }
            else
            {
                $_SESSION['idUser'] = $logUser['id'];
                $_SESSION['lastName'] = $logUser['lastname'];
                $_SESSION['firstName'] = $logUser['firstname'];                
                header('Location:index.php?page=customer_account&idUser='. $_SESSION['idUser']);
                exit();               
            }                      
        } 
        else
        {   
            // Seo page //
            $title = 'Connexion à votre compte';
            $metadescription = 'Connectez-vous à votre compte pour le suivi de vos commandes, réservations et messages.';

            // on appel le template du formulaire de connexion //
            $template = 'www/views/users/users/connection';
            require('www/views/templates/layout.phtml');
        }        
    }

    public function forgotPassword()
    {   
        // Instanciation du model //   
        $this -> _user = new User();
        
        // on test que le formulaire envoyé ne soit pas vide //
        if(isset($_POST['submit']))
        {   
            // On initialise un tableau vide pour la gestion d'erreur //
            $errors = [];   
            
            if(!array_key_exists('email', $_POST) || $_POST['email'] == '' || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $errors['email'] = 'Vous devez saisir un email valide.';
            }

            if(!empty($errors))
            {   
                $_SESSION['errors'] = $errors;
                $_SESSION['formData'] = $_POST;                
                header('location:index.php?page=forgot_password');
                exit();
            }
            else
            {
                $success = 'Cette fonctionnalité n\'est pas compatible avec l\'IDE';
                $_SESSION['success'] = $success;
                header('location:index.php?page=forgot_password');
                exit();                
            }            
        }
        else
        {   
            // Seo page //
            $title = 'Mot de passe oublié';
            $metadescription = 'Vous avez oublié votre mot de passe ? Pas de panique, remplissez simplement ce formulaire.';

            // on appel le template du formulaire d'oubli de mot de passe //
            $template = 'www/views/users/users/forgot_password';
            require('www/views/templates/layout.phtml');
        }   
    }

    public function accountUser()
    {          
        // on sécurise les pages utilisateur //
        if($this -> userIsConnect() == true)
        {   
            // Instanciation du model //   
            $this -> _user = new User();

            if(array_key_exists('idUser',$_GET) && is_numeric($_GET['idUser']) && $_GET['idUser'] == $_SESSION['idUser'] || isset($_SESSION['idUser']))
            {   
                // Seo page //
                $title = 'Votre espace personnalisé';
                $metadescription = 'Cet espace vous permet de modifier vos informations personnelles, vos adresses, de suivre vos commandes et réservation et consulter vos messages.';

                // on appel le template pour affichage //
                $template = 'www/views/users/users/customer_account';
                require('www/views/templates/layout.phtml');
            }
            // si l'id de l'utilisateur ne correspond pas, retour à l'index //
            else
            {
                header('location:index.php');
                exit();
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }
    }

    public function updateUserIdentity()
    {           
        // on sécurise les pages utilisateur //
        if($this -> userIsConnect() == true)
        {   
            // Instanciation du model //   
            $this -> _user = new User();

            // On récupère les infos du client en BDD //
            $idUser = $_SESSION['idUser'];
            $customer = $this -> _user -> getUserById($idUser);          
            
            // On traite le formulaire //
            if(isset($_POST['submit']))
            {   
                // On initialise un tableau vide pour la gestion d'erreur //
                $errors = [];
                
                // On initialise les REGEX pour les formats à valider correctement //
                $regexGender = '#^[1-2]{1}$#'; // Chiffre 1 à 2 sur 1 position //               
                $regexPassword = '#^.{8,20}$#'; // Tous caractères min 8 et max 20 //

                if(!array_key_exists('gender', $_POST) || $_POST['gender'] == '' || !preg_match($regexGender, $_POST['gender']))
                {
                    $errors['gender'] = 'Vous devez choisir une civilité.';
                }            

                if(!array_key_exists('lastName', $_POST) || $_POST['lastName'] == '')
                {
                    $errors['lastName'] = 'Vous devez saisir un nom.';
                }

                if(!array_key_exists('firstName', $_POST) || $_POST['firstName'] == '')
                {
                    $errors['firstName'] = 'Vous devez saisir un prénom.';
                }

                if(!array_key_exists('email', $_POST) || $_POST['email'] == '' || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                {
                    $errors['email'] = 'Vous devez saisir un email valide.';
                }            
                
                if(!array_key_exists('dateBirthday', $_POST) || $_POST['dateBirthday'] == '')
                {
                    $dateBirthday = null;                
                }
                else
                {
                    $dateBirthday = $_POST['dateBirthday'];
                }                

                if(!array_key_exists('legal', $_POST) || $_POST['legal'] == '')
                {
                    $errors['legal'] = 'Vous devez accepter notre politique de confidentialité.';
                }    

                if(!array_key_exists('password', $_POST) || $_POST['password'] == '' || !preg_match($regexPassword, $_POST['password']))
                {
                    $errors['password'] = 'Vous devez saisir votre mot de passe.';
                }

                if(array_key_exists('email', $_POST))
                {                   
                    // On vérifie le couple email et id de l'utilisateur //
                    $emailUser = $this -> _user -> getUserById($idUser);

                    if($_POST['email'] == $emailUser['email'] && $idUser)
                    {
                        $email = $_POST['email'];
                    }
                    else
                    {
                        // On vérifie que l'adresse mail n'existe pas déjà en BDD //
                        $email = $_POST['email'];
                        $verifEmail = $this -> _user -> getUserByEmail($email);
                                
                        if($verifEmail)
                        {  
                            $errors['email'] = 'Cette adresse Email existe déjà.';
                        }
                    }                
                }
                
                if(array_key_exists('newPassword', $_POST) && $_POST['newPassword'] != '' && preg_match($regexPassword, $_POST['password']))
                {
                    $password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
                }
                else
                {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }

                if(array_key_exists('password', $_POST) && $_POST['password'] != '')
                {
                    // On vérifie le mot de passe avec celui de la BDD //
                    $confirmPass = $this -> _user -> getVerifPassword($idUser);
                    
                    if(!password_verify($_POST['password'], $confirmPass['passwd']))
                    {
                        $errors['password'] = 'Identification impossible.';
                    }                
                }

                if(!empty($errors))
                {   
                    $_SESSION['errors'] = $errors;                
                    header('location:index.php?page=identity');
                    exit();
                }
                else
                {   
                    // On lance la mise à jour en BDD //
                    $idGender = $_POST['gender'];
                    $lastName = $_POST['lastName'];
                    $firstName = $_POST['firstName'];
                    $gdprConsent = 1;
                    
                    $userUpdated = $this -> _user -> updateUser($idUser,$idGender,$lastName,$firstName,$email,$password,$dateBirthday,$gdprConsent);                
                    
                    // Si tout c'est bien déroulé, on affiche un message au client //
                    if($userUpdated)
                    {
                        // On récupère les infos mise à jour en BDD //
                        $customer = $this -> _user -> getUserById($idUser);
                        $_SESSION['lastName'] = $customer['lastname'];
                        $_SESSION['firstName'] = $customer['firstname'];

                        $success['save'] = 'Vos informations ont bien été mise à jour.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=identity');
                        exit();
                    }
                    // Sinon message d'erreur //
                    else
                    {
                        $errors['db'] = 'Une erreur est survenue lors de l\'enregistrement !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=identity');
                        exit();                        
                    }
                }
            }    
            // Sinon on affiche les infos de l'utilisateur /
            else
            {   
                // Seo page //
                $title = 'Modifications de vos informations personnelles';
                $metadescription = 'Vous pouvez modifier vos informations personnelles depuis ce formulaire.';

                // On affiche le template //            
                $template = 'www/views/users/users/identity';
                require('www/views/templates/layout.phtml');
            }        
        }
        else
        {
            header('location:index.php');
            exit();
        }         
    }

    public function userAddress()
    {                
        // on sécurise les pages utilisateur //
        if($this -> userIsConnect() == true)
        {   
            // Instanciation du model //   
            $this -> _user = new User();

            // On affiche les adresses de l'utilisateur /        
            $idUser = $_SESSION['idUser'];
                    
            // On récupère les infos sur les adresses du client en BDD //
            $addresses = $this -> _user -> getUserAddressesById($idUser);
            
            // Seo page //
            $title = 'Vos adresses';
            $metadescription = 'Voici les adresses enregistrées dans votre compte.';

            // On affiche le template //                
            $template = 'www/views/users/address/address';
            require('www/views/templates/layout.phtml');        
        }
        else
        {
            header('location:index.php');
            exit();
        }
    }

    // On ajoute une adresse de l'utilisateur //
    public function addUserAddress()
    {           
        // on sécurise les pages utilisateur //
        if($this -> userIsConnect() == true)
        {   
            // Instanciation du model //   
            $this -> _user = new User();

            if(isset($_POST['submit']))
            {
                // On initialise un tableau vide pour la gestion d'erreur //
                $errors = [];
                
                // On initialise les REGEX pour les formats à valider correctement //               
                $regexZipCode = '#^[0-9]{5}$#'; // position de 5 chiffres de 0 à 9 //
                $regexPhone = '#^0[1-68]([. ]?[0-9]{2}){4}$#'; // format : 01 02 03 04 05 ou 01.02.03.04.05 //

                if(!array_key_exists('alias', $_POST) || $_POST['alias'] == '')
                {
                    $alias = 'Mon adresse';                
                }
                else
                {
                    $alias = $_POST['alias'];
                }

                if(!array_key_exists('lastName', $_POST) || $_POST['lastName'] == '')
                {
                    $errors['lastName'] = 'Vous devez saisir un nom.';
                }

                if(!array_key_exists('firstName', $_POST) || $_POST['firstName'] == '')
                {
                    $errors['firstName'] = 'Vous devez saisir un prénom.';
                }

                if(!array_key_exists('company', $_POST) || $_POST['company'] == '')
                {
                    $company = null;                
                }
                else
                {
                    $company = $_POST['company'];
                }

                if(!array_key_exists('address', $_POST) || $_POST['address'] == '')
                {
                    $errors['address'] = 'Vous devez saisir une adresse.';
                }

                if(!array_key_exists('addressComp', $_POST) || $_POST['addressComp'] == '')
                {
                    $addressComp = null;                
                }
                else
                {
                    $addressComp = $_POST['addressComp'];
                }

                if(!array_key_exists('zipCode', $_POST) || $_POST['zipCode'] == '' || !preg_match($regexZipCode, $_POST['zipCode']))
                {
                    $errors['zipCode'] = 'Vous devez saisir un code postal.';
                }

                if(!array_key_exists('city', $_POST) || $_POST['city'] == '')
                {
                    $errors['city'] = 'Vous devez saisir une ville.';
                }

                if(!array_key_exists('country', $_POST) || $_POST['country'] == '')
                {
                    $errors['country'] = 'Vous devez saisir un pays.';
                }

                if(!array_key_exists('phone', $_POST) || $_POST['phone'] == '' || !preg_match($regexPhone, $_POST['phone']))
                {
                    $errors['phone'] = 'Vous devez saisir un numéro de téléphone.';
                }

                if(!empty($errors))
                {   
                    $_SESSION['errors'] = $errors;                
                    header('location:index.php?page=addUserAddress');
                    exit();
                }
                else
                {   
                    // On récupère les infos du formulaire //
                    $idUser = $_SESSION['idUser'];
                    $lastName = $_POST['lastName'];
                    $firstName = $_POST['firstName'];
                    $address = $_POST['address'];
                    $zipCode = $_POST['zipCode'];
                    $city = $_POST['city'];
                    $country = $_POST['country'];
                    $phone = $_POST['phone'];

                    // On lance l'enregistrement en BDD //
                    $addressAdd = $this -> _user -> addAdressByUserId($idUser,$alias,$lastName,$firstName,$company,$address,$addressComp,$zipCode,$city,$country,$phone);

                    if($addressAdd)
                    {
                        // Si tout ok, on affiche un message à l'utilisateur //
                        $success['save'] = 'Votre nouvelle adresse à bien été ajoutée.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=addUserAddress');
                        exit();   
                    }
                    else
                    {
                        // Message d'erreur //
                        $errors['db'] = 'Une erreur s\'est produite !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=addUserAddress');
                        exit();
                    }
                }
            }
            else
            {   
                // Seo page //
                $title = 'Ajouter une adresse';
                $metadescription = 'Vous pouvez ajouter une adresse à votre compte depuis ce formulaire.';

                // on affiche le template //
                $template = 'www/views/users/address/add_address';            
                require('www/views/templates/layout.phtml');            
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }
    }

    public function updateUserAddress()
    {   
        if($this -> userIsConnect() == true)
        {   
            // Instanciation du model //   
            $this -> _user = new User();

            if(isset($_POST['submit']))
            {   
                // On initialise un tableau vide pour la gestion d'erreur //
                $errors = [];
                
                // On initialise les REGEX pour les formats à valider correctement //               
                $regexZipCode = '#^[0-9]{5}$#'; // position de 5 chiffres de 0 à 9 //
                $regexPhone = '#^0[1-68]([. ]?[0-9]{2}){4}$#'; // format : 01 02 03 04 05 ou 01.02.03.04.05 //               

                if(!array_key_exists('alias', $_POST) || $_POST['alias'] == '')
                {
                    $alias = 'Mon adresse';                
                }
                else
                {
                    $alias = $_POST['alias'];
                }

                if(!array_key_exists('lastName', $_POST) || $_POST['lastName'] == '')
                {
                    $errors['lastName'] = 'Vous devez saisir un nom.';
                }

                if(!array_key_exists('firstName', $_POST) || $_POST['firstName'] == '')
                {
                    $errors['firstName'] = 'Vous devez saisir un prénom.';
                }

                if(!array_key_exists('company', $_POST) || $_POST['company'] == '')
                {
                    $company = null;                
                }
                else
                {
                    $company = $_POST['company'];
                }

                if(!array_key_exists('address', $_POST) || $_POST['address'] == '')
                {
                    $errors['address'] = 'Vous devez saisir une adresse.';
                }

                if(!array_key_exists('addressComp', $_POST) || $_POST['addressComp'] == '')
                {
                    $addressComp = null;                
                }
                else
                {
                    $addressComp = $_POST['addressComp'];
                }

                if(!array_key_exists('zipCode', $_POST) || $_POST['zipCode'] == '' || !preg_match($regexZipCode, $_POST['zipCode']))
                {
                    $errors['zipCode'] = 'Vous devez saisir un code postal valide.';
                }

                if(!array_key_exists('city', $_POST) || $_POST['city'] == '')
                {
                    $errors['city'] = 'Vous devez saisir une ville.';
                }

                if(!array_key_exists('country', $_POST) || $_POST['country'] == '')
                {
                    $errors['country'] = 'Vous devez saisir un pays.';
                }

                if(!array_key_exists('phone', $_POST) || $_POST['phone'] == '' || !preg_match($regexPhone, $_POST['phone']))
                {
                    $errors['phone'] = 'Vous devez saisir un numéro de téléphone valide.';
                }

                if(!empty($errors))
                {   
                    $_SESSION['errors'] = $errors;                     
                    header('location:index.php?page=updateUserAddress&id='.$_GET['id']);
                    exit();                    
                }
                else
                {   
                    // On récupère les infos du formulaire //
                    $id = $_GET['id'];
                    $lastName = $_POST['lastName'];
                    $firstName = $_POST['firstName'];
                    $address = $_POST['address'];
                    $zipCode = $_POST['zipCode'];
                    $city = $_POST['city'];
                    $country = $_POST['country'];
                    $phone = $_POST['phone'];

                    // On vérifie que l'Id reçu correspond bien à une adresse du client //
                    $id = $_GET['id'];

                    // On vérifie si l'id existe en bdd et que c'est le bon client //
                    $verifId = $this -> _user -> getUserAddressByIdAddress($id);

                    if($verifId['id_user'] == $_SESSION['idUser'] && $verifId['id_address'] == $_GET['id'])
                    {
                        // On lance la méthode de MAJ en BDD  //
                        $addressUpdated = $this -> _user -> updateUserAddress($id,$alias,$lastName,$firstName,$company,$address,$addressComp,$zipCode,$city,$country,$phone);

                        if($addressUpdated)            
                        {               
                            // Si tout ok, message pour l'utilisateur //                
                            $success['save'] = 'Votre adresse a bien été mise à jour.';
                            $_SESSION['success'] = $success;                
                            header('location:index.php?page=updateUserAddress&id='.$_GET['id']);
                            exit();               
                        }
                        else
                        {
                            // Message d'erreur //
                            $errors['err'] = 'Une erreur s\'est produite !';
                            $_SESSION['err'] = $errors;
                            header('location:index.php?page=updateUserAddress&id='.$_GET['id']);
                            exit();                 
                        }      
                    }
                    else
                    {
                        // Sinon message d'erreur //            
                        $errors['err'] = 'Mise à jour Impossible !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=updateUserAddress&id='.$_GET['id']);
                        exit();
                    }                     
                }
            }
            else
            {
                // On vérifie l'id de l'adresse envoyé dans l'url //
                if(array_key_exists('id',$_GET) && is_numeric($_GET['id']))
                {
                    $id = $_GET['id'];
                    // On récupère les infos sur l'adresse correspondant à l'id //
                    $address = $this -> _user -> getUserAddressByIdAddress($id);

                    // On vérifie que l'id correspond bien à une adresse du client //
                    if($address['id_user'] == $_SESSION['idUser'] && $address['id_address'] == $_GET['id'])
                    {   
                        // Seo page //
                        $title = 'Modification d\'une adresse';
                        $metadescription = 'Vous pouvez mettre à jour une adresse de votre compte avec ce formulaire.';

                        // On affiche le template //            
                        $template = 'www/views/users/address/update_address';
                        require('www/views/templates/layout.phtml');
                    }
                    else
                    {
                        // Sinon message d'erreur //            
                        $errors['err'] = 'Affichage impossible !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=userAddress');
                        exit();
                    }
                }
            }    
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }

    // On supprime l'adresse en BDD avec l'id //
    public function deleteUserAddress()
    {           
        if($this -> userIsConnect() == true)
        {   
            // Instanciation du model //   
            $this -> _user = new User();

            if(array_key_exists('id',$_GET) && is_numeric($_GET['id']))
            {   
                // On vérifie que l'Id reçu correspond bien à une adresse du client //
                $id = $_GET['id'];

                // On vérifie si l'id existe en bdd et que c'est le bon client //
                $verifId = $this -> _user -> getUserAddressByIdAddress($id);

                if($verifId['id_user'] == $_SESSION['idUser'] && $verifId['id_address'] == $_GET['id'])
                {
                    // On lance la suppression en BDD //
                    $addressDeleted = $this -> _user -> deleteAddressById($id);

                    if($addressDeleted)
                    {
                        // Si tout ok, on affiche un message à l'utilisateur //
                        $success['save'] = 'Votre adresse a bien été supprimée.'; 
                        $_SESSION['success'] = $success;       
                        header('location:index.php?page=userAddress');
                        exit(); 
                    }
                    else
                    {
                        // On affiche un message d'erreur
                        $errors['db'] = 'Une erreur s\'est produite !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=userAddress');
                        exit();
                    }
                }
                else
                {
                    // Sinon message d'erreur //            
                    $errors['err'] = 'Suppression impossible !';
                    $_SESSION['errors'] = $errors;
                    header('location:index.php?page=userAddress');
                    exit();                    
                }                
            }
            else
            {
                // Sinon message d'erreur //            
                $errors['err'] = 'Une erreur s\'est produite !';
                $_SESSION['errors'] = $errors;
                header('location:index.php?page=userAddress');
                exit();                 
            }
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }
    
    // On déconnecte l'utilisateur de sa session //
    public function userDisconnection()
    {
        if(isset($_SESSION['idUser']))
        {
            session_destroy();
            header('Location:index.php');
            exit();
        }
    }
}