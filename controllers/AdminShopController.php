<?php

class AdminShopController extends AdminController
{
    public function updateShopInformation()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _shop = new Shop();

            if(isset($_POST['submit']))
            {
                // On initialise un tableau vide pour la gestion d'erreur //
                $errors = [];

                // On initialise les REGEX pour les formats à valider correctement //
                $regexGender = '#^[1-2]{1}$#'; // Chiffre 1 à 2 sur 1 position //                
                $regexZipCode = '#^[0-9]{5}$#'; // position de 5 chiffres de 0 à 9 //
                $regexPhone = '#^0[1-68]([. ]?[0-9]{2}){4}$#'; // format : 01 02 03 04 05 ou 01.02.03.04.05 //
                $regexSiret = '#^[0-9]{14}$#'; // position de 14 chiffres de 0 à 9 //

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

                if(!array_key_exists('capacity', $_POST) || $_POST['capacity'] == '')
                {
                    $capacity = null;
                }
                else
                {
                    $capacity = $_POST['capacity'];
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

                if(!array_key_exists('siret', $_POST) || $_POST['siret'] == '' || !preg_match($regexSiret, $_POST['siret']))
                {
                    $errors['siret'] = 'Vous devez saisir un numéro de siret valide.';
                }

                if(!array_key_exists('rcs', $_POST) || $_POST['rcs'] == '')
                {
                    $errors['rcs'] = 'Vous devez saisir la ville du RCS.';
                }

                if(!array_key_exists('homePageTeaserTitle', $_POST) || $_POST['homePageTeaserTitle'] == '')
                {
                    $homePageTeaserTitle = null;
                }
                else
                {
                    $homePageTeaserTitle = $_POST['homePageTeaserTitle'];
                }

                if(!array_key_exists('localizationMap', $_POST) || $_POST['localizationMap'] == '')
                {
                    $errors['localizationMap'] = 'Vous devez saisir un lien google map.';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['formData'] = $_POST; 
                    header('location:index.php?page=updateShop');
                    exit();
                }
                else
                {
                    // On récupère les infos du formulaire //
                    $id = $_POST['id'];
                    $idGender = $_POST['gender'];
                    $lastName = $_POST['lastName'];
                    $firstName = $_POST['firstName'];                    
                    $address = $_POST['address'];
                    $zipCode = $_POST['zipCode'];
                    $city = $_POST['city'];
                    $country = $_POST['country'];
                    $phone = $_POST['phone'];
                    $siret = $_POST['siret'];
                    $rcs = $_POST['rcs'];
                    $localizationMap = $_POST['localizationMap'];

                    // On enregistre les infos en BDD //
                    $shopInfoValided = $this -> _shop -> updateShopInformation($id,$idGender,$lastName,$firstName,$capacity,$company,$address,$zipCode,$city,$country,$phone,$siret,$rcs,$homePageTeaserTitle,$localizationMap);

                    if($shopInfoValided)
                    {
                        $success['save'] = 'Vos modifications ont bien été enregistré.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=updateShop');
                        exit();                        
                    }
                    else
                    {
                        $errors['db'] = 'Une erreur s\'est produite !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=updateShop');
                        exit();                        
                    }
                }
            }
            else
            {
                // On récupère les infos sur la boutique //
                $shopInformation = $this -> _shop -> getShopInformation();

                // On affiche le template //
                $template = 'views/configuration/shop/update_shop';
                require('../../www/7GhtK232c/views/templates/layout.phtml');
            }       
        }
        else
        {
            header('location:index.php');
            exit();
        }        
    }
}