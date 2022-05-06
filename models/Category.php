<?php

class category
{
    // propriétés de la class //
    private $_connectDb;
    private $_db;

    public function __construct()
    {
        // On instancie la class configDb pour la connexion //
        $this -> _connectDb = new configDb();

        // On appel le getter de la class pour faire la connexion //
        $this -> _db = $this -> _connectDb -> getConnection();
    }

    // On récupère les catégories //
    public function getCategorys()
    {
        $query = $this -> _db -> prepare('SELECT id_category, category_name, title_category, text_category, category_product FROM category');
        $query -> execute();
        $categorys = $query -> fetchAll();

        return $categorys;
    }

    // On récupère les images des catégories  //
    public function getImagesCategory()
    {
        $query = $this -> _db -> prepare('SELECT id_category, title_category, name_img_category FROM category WHERE name_img_category IS NOT null');
        $query -> execute();
        $imagesCategorys = $query -> fetchAll();

        return $imagesCategorys;
    }

    // On récupère les categories étant des catégories pour les produits //
    public function getCategoryProduct()
    {
        $query = $this -> _db -> prepare('SELECT id_category, category_name, category_product FROM category WHERE category_product = 1');
        $query -> execute();
        $categorys = $query -> fetchAll();

        return $categorys;
    }

    // On récupère la categorie produit par son Id //
    public function getCategoryProductById($categoryProduct)
    {
        $query = $this -> _db -> prepare('SELECT id_category FROM category WHERE id_category = ? AND category_product = 1');
        $query -> execute([$categoryProduct]);
        $categoryAuthorized = $query -> fetch();

        return $categoryAuthorized;
    }

    // On récupère les infos d'une categorie avec son Id //
    public function getInfoByCategory($idCategory)
    {
        $query = $this -> _db -> prepare('SELECT id_category, title_category, text_category, category_product FROM category WHERE id_category = ?');
        $query -> execute([$idCategory]);
        $infoCategory = $query -> fetch();

        return $infoCategory;

    }    

    // On fait la mise à jour d'une categorie avec son Id //
    public function updateCategoryById($idCategory,$titleCategory,$textCategory)
    {
        $query = $this -> _db -> prepare('UPDATE category SET title_category=?, text_category=? WHERE id_category = ?');
        $categoryUpdated = $query -> execute([$titleCategory,$textCategory,$idCategory]);
        
        return $categoryUpdated;
    }    
}