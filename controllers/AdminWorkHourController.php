<?php

class AdminWorkHourController extends AdminController
{
    public function updateWorkHours()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _workHour = new WorkHour();
            
            // On récupère les infos sur les horaires d'ouvertures //
            $workingHours = $this -> _workHour -> getShopWorkHours();
        
            if(isset($_POST['submit']))
            {   
                // On initialise un tableau vide pour la gestion d'erreur //
                $errors = [];
                
                // On initialise les REGEX pour le format time de l'input et la value de active //
                $timeRegex = '/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/'; // De 00:00 à 23:59 //
                $activeRegex = '#^[0-3]{1}$#';
                $startDayRegex = '#^[0-1]{1}$#';

                if(!array_key_exists('startDay', $_POST) || $_POST['startDay'] == '' || !preg_match($startDayRegex, $_POST['startDay']))
                {
                    $errors['startDay'] = 'Vous devez choisir le premier jour d\'ouverture de la semaine !';
                }
                
                if(!array_key_exists('active', $_POST) || $_POST['active'] == '' || !preg_match($activeRegex, $_POST['active']))
                {
                    $errors['active'] = 'Vous devez choisir une option dans la liste !';
                }

                if(array_key_exists('hourMorningStart', $_POST) && !preg_match($timeRegex, $_POST['hourMorningStart']))
                {
                    $errors['hourMorningStart'] = 'Vous devez saisir un horaire !';
                }
                
                if(array_key_exists('hourMorningEnd', $_POST) && !preg_match($timeRegex, $_POST['hourMorningEnd']))
                {
                    $errors['hourMorningEnd'] = 'Vous devez saisir un horaire !';
                }
                
                if(array_key_exists('hourAfternoonStart', $_POST) && !preg_match($timeRegex, $_POST['hourAfternoonStart']))
                {
                    $errors['hourAfternoonStart'] = 'Vous devez saisir un horaire !';
                }

                if(array_key_exists('hourAfternoonEnd', $_POST) && !preg_match($timeRegex, $_POST['hourAfternoonEnd']))
                {
                    $errors['hourAfternoonEnd'] = 'Vous devez saisir un horaire !';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    header('location:index.php?page=updateWorkHour');
                    exit();
                }
                else
                {
                    // On traite le formulaire //
                    $id = $_POST['id'];
                    $startDay = $_POST['startDay'];
                    $active = $_POST['active'];
                    $hourMorningStart = $_POST['hourMorningStart'];
                    $hourMorningEnd = $_POST['hourMorningEnd'];
                    $hourAfternoonStart = $_POST['hourAfternoonStart'];
                    $hourAfternoonEnd = $_POST['hourAfternoonEnd'];
                    
                    // On remet à Zéro la colonne startDay //
                    $reset = 0;
                    $this -> _workHour -> updateStarDayColumn($reset);

                    // On enregistre les modifications en BDD //
                    $hoursUpdated = $this -> _workHour -> updateWorkHoursById($id,$startDay,$hourMorningStart,$hourMorningEnd,$hourAfternoonStart,$hourAfternoonEnd,$active);

                    // On informe l'utilisateur si tout ok ou message d'erreur //
                    if($hoursUpdated)
                    {
                        $success['save'] = 'Vos modifications ont bien été enregistré.';
                        $_SESSION['success'] = $success;
                        header('location:index.php?page=updateWorkHour');
                        exit();                        
                    }   
                    else
                    {   
                        $errors['err'] = 'Une erreur s\'est produite !';
                        $_SESSION['errors'] = $errors;
                        header('location:index.php?page=updateWorkHour');
                        exit();                        
                    }
                } 
            }
            else
            {
                // On affiche le template //                
                $template = 'views/configuration/workhour/update_workHour';
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