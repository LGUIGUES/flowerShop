<?php

class AdminCategoryController extends AdminController
{   
    public function updateCategory()
    {
        // on sécurise les pages administrateur //
        if($this -> isAdminConnect() == true)
        {   
            // Instanciation du modèle //
            $this -> _category = new Category();
            
            // On récupère la liste des catégories //
            $categorys = $this -> _category -> getCategorys();

            if(isset($_POST['submit']))
            {   
                // On initialise un tableau vide pour la gestion d'erreur //
                $errors = [];

                // On initialise la regex de validation //
                $regexIdcategory = '#^[1000,2000,3000,4000,5000,6000,7000]{4}$#';
                
                if(!array_key_exists('category', $_POST) || $_POST['category'] == '' || !preg_match($regexIdcategory, $_POST['category']))
                {
                    $errors['category'] = 'Vous devez choisir une catégorie valide.';
                }

                if(!array_key_exists('titleCategory', $_POST) || $_POST['titleCategory'] == '')
                {
                    $errors['titleCategory'] = 'Vous devez saisir un nom pour la catégorie.';
                }

                if(!empty($errors))
                {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['formData'] = $_POST;
                    header('location:index.php?page=category');
                    exit();
                }
                else
                {
                    // On traite le formulaire //
                    $idCategory = $_POST['category'];
                    $titleCategory = $_POST['titleCategory'];
                    $textCategory = $_POST['textCategory'];

                    // On lance la mise à jour de la catégorie en BDD //
                    $categoryUpdated = $this -> _category -> updateCategoryById($idCategory,$titleCategory,$textCategory);

                    // Si tout ok, on affiche un message à l'utilisateur //
                    if($categoryUpdated)
                    {
                        $success['save'] = 'La catégorie a bien été mise à jour.';
                        $_SESSION['success'] = $success;

                        header('location:index.php?page=category');
                        exit();
                    }
                    else
                    {
                        // Message d'erreur //
                        $errors = 'Une erreur s\'est produite !';
                        $_SESSION['errors'] = $errors;

                        header('location:index.php?page=category');
                        exit();
                    }
                }                
            }
            else
            {
                // On affiche le template //                
                $template = 'views/category/category';
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