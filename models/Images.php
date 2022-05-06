<?php

class Images
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

    // On récupère la liste des images par catégories //
    public function getImagesByCategory($idCategory)
    {
        $query = $this -> _db -> prepare('SELECT id, id_category, name_large, description_image, name_category, orientation FROM images WHERE id_category = ?');
        $query -> execute([$idCategory]);
        $imagesCategory = $query -> fetchAll();

        return $imagesCategory;

    }

    // On récupère une image par son Id //
    public function getImageById($idImage)
    {
        $query = $this -> _db -> prepare('SELECT id_category, description_image, name_large, name_category, orientation FROM images WHERE id = ?');
        $query -> execute([$idImage]);
        $infoImage = $query -> fetch();

        return $infoImage;
    }
    
    // On récupère toutes les images //
    public function getAllImages()
    {   
        $query = $this -> _db -> prepare('SELECT id, id_category, name_large, description_image, name_category, orientation FROM images WHERE id_product = 0');
        $query -> execute();
        $imagesCategory = $query -> fetchAll();

        return $imagesCategory;

    }
    
    // On récupère les infos sur les images pour les statistiques //
    public function infoImages()
    {
        $query = $this -> _db -> prepare('SELECT category_name, COUNT(name_large) AS nbrImg, COUNT(description_image) AS nbrDesc, COUNT(name_category) AS nbrMin FROM images INNER JOIN category ON images.id_category = category.id_category GROUP BY category_name DESC');
        $query -> execute();
        $infoCategory = $query -> fetchAll();

        return $infoCategory;
    }

    // On récupère les infos d'une image de produit par son Id //
    public function getInfoImagesProductById($idProduct)
    {
        $query = $this -> _db -> prepare('SELECT id_category,name_large,name_category,name_miniature,orientation FROM images WHERE id_product = ?');
        $query -> execute([$idProduct]);
        $infoImagesProduct = $query -> fetch();

        return $infoImagesProduct;
    }

    // On ajoute une image pour la galerie //
    public function addImage($idProduct,$idCategory,$nameImage,$description,$nameMiniature,$orientation)
    {
        $query = $this -> _db -> prepare('INSERT INTO images(id_product,id_category,name_large,description_image,name_category,orientation,date_add) VALUES(?,?,?,?,?,?,NOW())');
        $imageValided = $query -> execute([$idProduct,$idCategory,$nameImage,$description,$nameMiniature,$orientation]);

        return $imageValided;
    }

    // On ajoute une image produit //
    public function addImageProduct($idProduct,$categoryProduct,$nameLarge,$descriptionImage,$nameCategory,$nameMiniature,$orientationImage)
    {
        $query = $this -> _db -> prepare('INSERT INTO images(id_product,id_category,name_large,description_image,name_category,name_miniature,orientation,date_add,date_upd) VALUES(?,?,?,?,?,?,?,NOW(),NOW())');
        $imageProduct = $query -> execute([$idProduct,$categoryProduct,$nameLarge,$descriptionImage,$nameCategory,$nameMiniature,$orientationImage]);

        return $imageProduct;
    }

    // On fait la mise à jour d'une image produit //
    public function updateImageProduct($idProduct,$categoryProduct,$nameLarge,$descriptionImage,$nameCategory,$nameMiniature,$orientation)
    {
        $query = $this -> _db -> prepare('UPDATE images SET id_category=?,name_large=?,description_image=?,name_category=?,name_miniature=?,orientation=?,date_upd=NOW() WHERE id_product = ?');
        $imageProduct = $query -> execute([$categoryProduct,$nameLarge,$descriptionImage,$nameCategory,$nameMiniature,$orientation,$idProduct]);

        return $imageProduct;
    }

    // On fait la mise à jour d'une image de la galerie avec son Id //
    public function updateImageById($id,$idCategory,$description)
    {
        $query = $this -> _db -> prepare('UPDATE images SET id_category=?, description_image=?, date_upd=NOW() WHERE id = ?');
        $imageUpdated = $query -> execute([$idCategory,$description,$id]);

        return $imageUpdated;

    }

    // On supprime une image de la galerie avec son Id //
    public function deleteImageById($idImage)
    {
        $query = $this -> _db -> prepare('DELETE FROM images WHERE id = ?');
        $deletedImage = $query -> execute([$idImage]);

        return $deletedImage;
    }

    // On supprime une image produit par l'Id du produit //
    public function deleteImageProductByIdProduct($idProduct)
    {
        $query = $this -> _db -> prepare('DELETE FROM images WHERE id_product = ?');
        $imageProductDeleted = $query -> execute([$idProduct]);

        return $imageProductDeleted;
    }
}