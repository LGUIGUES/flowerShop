<?php

class AdminAuthentificationController extends AdminController
{    
    public function adminConnection()
    {
        /* Pour la toute première connexion, on vérifie si un administrateur existe
        dans la BDD, sinon, on lance la création d'un administrateur */
        $dbReply = $this -> _admin -> getEmptyAdmin();
        
        if(empty($dbReply['id_admin']))
        {
            // On lance la création de l'administrateur //
            $createAdmin = $this -> createAdmin();
        }
        else
        {
            // Sinon on lance la page de connexion administrateur //
            $logAdmin = $this -> logAdmin();                       
        }
    }    

    public function createAdmin()
    {
        // on test que le formulaire envoyé ne soit pas vide //
        if(isset($_POST['submit']))
        {   
            // On initialise un tableau vide pour la gestion d'erreur //
            $errors = [];            

            // On initialise les REGEX pour les formats à valider correctement //            
            $regexPassword = '#^.{8,20}$#'; // Tous caractères min 8 et max 20 //

            if(!array_key_exists('lastname', $_POST) || $_POST['lastname'] == '')
            {
                $errors['lastname'] = 'Vous devez saisir un nom.';
            }

            if(!array_key_exists('firstname', $_POST) || $_POST['firstname'] == '')
            {
                $errors['firstname'] = 'Vous devez saisir un prénom.';
            }

            if(!array_key_exists('email', $_POST) || $_POST['email'] == '' || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $errors['email'] = 'Vous devez saisir un email valide.';
            }

            if(!array_key_exists('password', $_POST) || $_POST['password'] == '' || !preg_match($regexPassword, $_POST['password']))
            {
                $errors['password'] = 'Vous devez saisir un mot de passe valide.';
            }

            if(!array_key_exists('repassword', $_POST) || $_POST['repassword'] == '' || !preg_match($regexPassword, $_POST['repassword']))
            {
                $errors['password'] = 'Vous devez saisir le même mot de passe.';
            }
            
            if($_POST['password'] != $_POST['repassword'])
            {
                $errors['password'] = 'Les mots de passe ne correspondent pas !';
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
                $lastname = $_POST['lastname'];
                $firstname = $_POST['firstname'];
                $email = $_POST['email'];

                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                
                // On attribue le numéro employee //
                $employee = 1;

                // on appel la méthode addAdmin de la class Admin //
                $accountValided = $this -> _admin -> addAdmin($lastname,$firstname,$email,$password,$employee);

                var_dump($accountValided);
                
                // En retour, addAdmin nous retourne True ou False //
                // On test $validation pour afficher un message à l'utilisateur //
                if($accountValided)
                {
                    $success = 'Votre compte a bien été crée.';
                    $_SESSION['success'] = $success;
                    header('location:index.php?page=logAdmin');
                    exit();
                }
                else
                {
                    // Message d'erreur //
                    $errors = 'Une erreur est survenue !';
                    $_SESSION['errors'] = $errors;                
                    header('location:index.php?page=createAdmin');
                    exit();  
                }
            } 
        }
        // On affiche le template de création de compte //
        $template = 'views/admin/create_account';        
        require('../../www/7GhtK232c/views/templates/layout_login.phtml');
    }

    public function logAdmin()
    {
        // On vérifie que le formulaire envoyé ne soit pas vide //
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

                // on appel la méthode de vérification de l'administrateur de la class Admin //
                // En retour, $logAdmin contiendra les infos liées à cette email //
                $logAdmin = $this -> _admin -> getVerificationAdmin($email);

                if(!password_verify($password, $logAdmin['passwd']))
                {
                    $errors['password'] = 'Identification impossible !';
                }
            }    

            if(!empty($errors))
            {   
                $_SESSION['errors'] = $errors;
                $_SESSION['formData'] = $_POST;                
                header('location:index.php?page=logAdmin');
                exit();
            }
            else
            {
                /* On redirige l'admin vers le tableau de bord */
                $_SESSION['idAdmin'] = $logAdmin['id_admin'];
                $_SESSION['employee'] = $logAdmin['id_employee'];
                $_SESSION['lastname'] = $logAdmin['lastname'];
                $_SESSION['firstname'] = $logAdmin['firstname'];
                header('location:index.php?page=dashBoard');
                exit();
            }
        }
        // on appel le template de connexion //
        $template = 'views/admin/login_account';               
        require('../../www/7GhtK232c/views/templates/layout_login.phtml');
    }
    
    public function forgotPassword()
    {
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
                $success = 'Cette fonctionnalité est en cours de développement.';
                $_SESSION['success'] = $success;
                header('location:index.php?page=forgot_password');
                exit();
            }            
        }
        else
        {
            // on appel le template du formulaire d'oubli de mot de passe //
            $template = 'views/admin/forgot_password';
            require('../../www/7GhtK232c/views/templates/layout_login.phtml');            
        }   
    }

    public function updateProfilAdmin()
    {
        // on appel le template de connexion //
        $template = 'views/admin/profil';               
        require('../../www/7GhtK232c/views/templates/layout.phtml');
    }
}