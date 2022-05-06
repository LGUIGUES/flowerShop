<?php

class Product
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

    // On enregistre un produit //
    public function addProduct($categoryProduct,$nameProduct,$referenceProduct,$priceTaxProduct,$shortDescriptionProduct,$descriptionProduct,$active)
    {
        $query = $this -> _db -> prepare('INSERT INTO product(category_product,name_product,reference_product,price_tax_product,short_description_product,description_product,active,date_add) VALUES(?,?,?,?,?,?,?,NOW())');
        $productValided = $query -> execute([$categoryProduct,$nameProduct,$referenceProduct,$priceTaxProduct,$shortDescriptionProduct,$descriptionProduct,$active]);

        // on récupère l'id du produit enregistré précédement //
        $lastIdProduct = $this -> _db -> lastInsertId();
        
        // on retourne la valeur pour continuer l'enregistrement des détails en BDD //
        return $lastIdProduct;        
    }

    // On fait la mise à jour d'un produit //
    public function updateProduct($idProduct,$categoryProduct,$nameProduct,$referenceProduct,$priceTaxProduct,$shortDescriptionProduct,$descriptionProduct,$active)
    {
        $query = $this -> _db -> prepare('UPDATE product SET category_product=?,name_product=?,reference_product=?,price_tax_product=?,short_description_product=?,description_product=?,active=?,date_upd=NOW() WHERE id_product = ?');
        $productUpdated = $query -> execute([$categoryProduct,$nameProduct,$referenceProduct,$priceTaxProduct,$shortDescriptionProduct,$descriptionProduct,$active,$idProduct]);

        return $productUpdated;
    }    

    // On récupère la liste de tous les produits //
    public function getListingProducts()
    {
        $query = $this -> _db -> prepare('SELECT product.id_product,product.category_product,category_name,name_product,reference_product,(price_tax_product/1.20) AS price_product,price_tax_product,orientation, description_image,name_miniature,active FROM product INNER JOIN category ON product.category_product = category.id_category INNER JOIN images ON product.id_product = images.id_product');
        $query -> execute();
        $listingProducts = $query -> fetchAll();

        return $listingProducts;

    }
    
    // On récupère les infos d'un produit par son Id //
    public function getProductById($idProduct)
    {
        $query = $this -> _db -> prepare('SELECT product.id_product,product.category_product,category_name,name_product,reference_product,price_tax_product,short_description_product,description_product,orientation,description_image,name_category,name_large,active FROM product INNER JOIN category ON product.category_product = category.id_category INNER JOIN images ON product.id_product = images.id_product WHERE product.id_product = ?');
        $query -> execute([$idProduct]);
        $product = $query -> fetch();

        return $product;

    }
    
    // On récupère la liste des produits par la catégorie //
    public function getProductsByCategory($idCategory)
    {
        $query = $this -> _db -> prepare('SELECT product.id_product,name_product,reference_product,price_tax_product,short_description_product,description_product,orientation,description_image,name_category FROM product INNER JOIN category ON product.category_product = category.id_category INNER JOIN images ON product.id_product = images.id_product WHERE product.category_product = ? AND product.active = 1');
        $query -> execute([$idCategory]);
        $listProducts = $query -> fetchAll();

        return $listProducts;
    }

    // On récupère la liste des produits pour le panier par les Ids présent dans celui-ci //
    public function getCartProductsByIds($idsProducts)
    {
        $query = $this -> _db -> prepare('SELECT product.id_product,product.category_product,category_name,name_product,reference_product,price_tax_product,short_description_product,description_product,orientation,description_image,name_category,name_large,name_miniature,active FROM product INNER JOIN category ON product.category_product = category.id_category INNER JOIN images ON product.id_product = images.id_product WHERE product.id_product IN ('.implode(',',$idsProducts).')');
        $query -> execute();
        $cartProducts = $query -> fetchAll();

        return $cartProducts;        
    }

    // On supprime un produit par son Id //
    public function deleteProductByid($idProduct)
    {
        $query = $this -> _db -> prepare('DELETE FROM product WHERE id_product = ?');
        $productDeleted = $query -> execute([$idProduct]);

        return $productDeleted;
    }
}