<?php

class Order
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

    // Récupère le statut de la commande //
    public function getStatusOrder()
    {
        $query = $this -> _db -> prepare('SELECT id_status,name_status FROM status_order');
        $query -> execute();
        $statusOrder = $query -> fetchAll();

        return $statusOrder;
    }

    // Enregistre une commande //
    public function addOrder($idUser,$totalAmount,$idAddress,$deliveryMethod,$paymentMethod,$orderStatus)
    {
        $query = $this -> _db -> prepare('INSERT INTO orders(id_user,total_amount,id_address_billing,delivery_method,payment_method,order_status,date_add) VALUE (?,?,?,?,?,?,NOW())');
        $query -> execute([$idUser,$totalAmount,$idAddress,$deliveryMethod,$paymentMethod,$orderStatus]);

        // On récupère l'id de la commande qui vient d'être enregistrée //
        $lastIdOrder = $this -> _db -> lastInsertId();

        // On retourne cette valeur pour la seconde opération d'enregistrement //
        return $lastIdOrder;
    }

    // Enregistre les détails de la commande //
    public function addOrderDetails($lastIdOrder,$idProduct,$quantity,$unitPriceProduct)
    {
        $query = $this -> _db -> prepare('INSERT INTO order_detail(id_order,id_product,quantity,unit_price_product) VALUE(?,?,?,?)');
        $orderValided = $query -> execute([$lastIdOrder,$idProduct,$quantity,$unitPriceProduct]);
        
        return $orderValided;
    }

    // Eregistre les détails de la commande dans une table --> Dans le cas ou l'admin supprime un produit //
    public function addOrderDetailsHistory($lastIdOrder,$idProduct,$nameProduct,$referenceProduct)
    {
        $query = $this -> _db -> prepare('INSERT INTO order_detail_history(id_order,history_id_product,history_name_product,history_reference_product) VALUE(?,?,?,?)');
        $query -> execute([$lastIdOrder,$idProduct,$nameProduct,$referenceProduct]);
    }

    // Enregistre l'adresse de l'utilisateur dans une table --> Dans le cas ou l'utilisateur supprime l'adresse //
    public function addAddressBillingOrder($lastIdOrder,$idAddress,$idUser,$alias,$lastname,$firstname,$company,$addresse,$addresseComp,$zipCode,$city,$country,$phone)
    {
        $query = $this -> _db -> prepare('INSERT INTO order_address(id_order,id_address,id_user,alias,lastname,firstname,company,addresse,address_comp,zip_code,city,country,phone) VALUE(?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $addresseBilling = $query -> execute([$lastIdOrder,$idAddress,$idUser,$alias,$lastname,$firstname,$company,$addresse,$addresseComp,$zipCode,$city,$country,$phone]);

        return $addresseBilling;
    }

    // Enregistre le statut de la commande dans une table pour historique //
    public function addStatusOrderHistory($lastIdOrder,$orderStatus)
    {
        $query = $this -> _db -> prepare('INSERT INTO order_history(id_order,id_status_order,date_add) VALUE(?,?,NOW())');
        $statusHistoryOrder = $query -> execute([$lastIdOrder,$orderStatus]);

        return $statusHistoryOrder;
    }

    // Enregistre le nouveau statut de la commande //
    public function addNewStatusHistoryOrderByIdOrder($idOrder,$newStatus)
    {
        $query = $this -> _db -> prepare('INSERT INTO order_history(id_order,id_status_order,date_add) VALUE(?,?,NOW())');
        $newStatusValided = $query -> execute([$idOrder,$newStatus]);

        return $newStatusValided;
    }

    // Mise à jour du statut courant de la commande //
    public function updateCurrentOrderStatus($idOrder,$newStatus)
    {
        $query = $this -> _db -> prepare('UPDATE orders SET current_status=? WHERE id_order = ?');
        $query -> execute([$newStatus,$idOrder]);        
    }

    // Renvoi l'adresse de l'utilisateur utilisée lors de la commande //
    public function getAddressUserByIdOrder($idOrder)
    {
        $query = $this -> _db -> prepare('SELECT email,order_address.lastname,order_address.firstname,company,addresse,address_comp,zip_code,city,country,phone FROM order_address INNER JOIN user ON order_address.id_user = user.id WHERE id_order = ?');
        $query -> execute([$idOrder]);
        $addressUser = $query -> fetch();

        return $addressUser;
    }

    // Renvoi les commandes de l'utilisateurs //
    public function getOrdersByIdUser($idUser)
    {
        $query = $this -> _db -> prepare('SELECT orders.id_order,date_add,total_amount,name_payment,name_status,SUM(quantity) AS numberProduct FROM orders INNER JOIN method_payment ON orders.payment_method = method_payment.id_payment_method INNER JOIN status_order ON orders.current_status = status_order.id_status INNER JOIN order_detail ON orders.id_order = order_detail.id_order WHERE id_user = ? GROUP BY orders.id_order HAVING SUM(quantity) ORDER BY date_add DESC');
        $query -> execute([$idUser]);
        $orders = $query -> fetchAll();
        
        return $orders;
    }

    // Vérifie si le N° de la commande correspond bien à une commande de l'utilisateur //
    public function getOrderByIdOrder($idOrder)
    {
        $query = $this -> _db -> prepare('SELECT id_order, id_user FROM orders WHERE id_order = ?');
        $query -> execute([$idOrder]);
        $verifOrder = $query -> fetch();

        return $verifOrder;
    }

    // Renvoi les détails d'une commande de l'utilisateur //
    public function getOrderDetails($idOrder)
    {   
        $query = $this -> _db -> prepare('SELECT orientation,name_miniature,description_image,order_detail.id_order,total_amount,name_delivery,name_payment,orders.date_add,history_name_product,history_reference_product,quantity,order_detail.unit_price_product FROM order_detail LEFT JOIN images ON order_detail.id_product = images.id_product INNER JOIN orders ON order_detail.id_order = orders.id_order LEFT JOIN order_detail_history ON order_detail.id_order = order_detail_history.id_order INNER JOIN method_delivery ON orders.delivery_method = method_delivery.id_delivery_method INNER JOIN method_payment ON orders.payment_method = method_payment.id_payment_method WHERE order_detail.id_order = ? AND order_detail.id_product = order_detail_history.history_id_product AND order_detail.id_order = order_detail_history.id_order');
        $query -> execute([$idOrder]);
        $order = $query -> fetchAll();

        return $order;
    }   

    // Renvoi l'historique d'une commande de l'utilisateur //
    public function getOrderHistoryByIdOrder($idOrder)
    {
        $query = $this -> _db -> prepare('SELECT id_order,id_status_order,name_status,order_history.date_add FROM order_history INNER JOIN status_order ON order_history.id_status_order = status_order.id_status WHERE id_order = ? ORDER BY order_history.date_add DESC');
        $query -> execute([$idOrder]);
        $orderHistory = $query -> fetchAll();

        return $orderHistory;
    }
    
    // Vérifie si le statut de la commandes existe déjà sur la commande //
    public function verifOrderStatus($idOrder,$newStatus)
    {
        $query = $this -> _db -> prepare('SELECT id_order,id_status_order FROM order_history WHERE id_order = ? AND id_status_order = ?');
        $query -> execute([$idOrder,$newStatus]);
        $orderStatus = $query -> fetch();

        return $orderStatus;
    }    

    // Renvoi le nombre total et le montant total des commandes de l'utilisateur pour les statistiques //
    public function getStatOrdersByIdUser($idUser)
    {
        $query = $this -> _db -> prepare('SELECT COUNT(id_order) AS numberOrders, SUM(total_amount) AS totalSales FROM orders WHERE id_user = ?');
        $query -> execute([$idUser]);
        $statUserOrders = $query -> fetch();

        return $statUserOrders;
    }

    // Renvoi les infos de toutes les commandes //
    public function getAllOrders()
    {
        $query = $this -> _db -> prepare('SELECT orders.id_order,id_user,CONCAT(SUBSTR(firstname,1,1), ".", lastname) AS customer,total_amount,name_delivery,name_payment,name_status,orders.date_add FROM orders LEFT JOIN status_order ON status_order.id_status = orders.current_status INNER JOIN user ON orders.id_user = user.id INNER JOIN method_delivery ON orders.delivery_method = method_delivery.id_delivery_method INNER JOIN method_payment ON orders.payment_method = method_payment.id_payment_method ORDER BY orders.id_order DESC');
        $query -> execute();
        $orders = $query -> fetchAll();

        return $orders;
    }

    // Pour la notification des nouveaux clients //
    public function getOrdersNotification()
    {
        $query = $this -> _db -> prepare('SELECT id_order,firstname,lastname,total_amount FROM orders INNER JOIN user ON orders.id_user = user.id WHERE order_notification = 1 ORDER BY id_order DESC LIMIT 5');
        $query -> execute();
        $orderNotification = $query -> fetchAll();

        echo json_encode($orderNotification);
    }

    // Renvoi les 5 dernières commandes //
    public function getLastOrders()
    {
        $query = $this -> _db -> prepare('SELECT orders.id_order,id_user,SUM(quantity) AS numberProduct,CONCAT(SUBSTR(firstname,1,1), ".", lastname) AS customer,total_amount,orders.date_add FROM orders INNER JOIN user ON orders.id_user = user.id INNER JOIN order_detail ON order_detail.id_order = orders.id_order WHERE orders.id_order GROUP BY orders.id_order DESC LIMIT 5');
        $query -> execute();
        $orders = $query -> fetchAll();

        return $orders;
    }

    // Statistiques : nbr de cde, total vente, nombre de client pour calculer panier moyen //
    public function getAllSales()
    {
        $query = $this -> _db -> prepare('SELECT COUNT(id_order) AS numberOrders, SUM(total_amount) AS totalSales, COUNT(id_user) as numberCustomer FROM orders INNER JOIN user ON id_user = id AND current_status NOT LIKE 5');
        $query -> execute();
        $statistics = $query -> fetch();

        return $statistics;
    }
    
    // Statistiques : Nombre total de commande //
    public function getAverageOrdersByUser()
    {
        $query = $this -> _db -> prepare('SELECT COUNT(id_order) as total FROM orders');
        $query -> execute();
        $averageOrdersByUser = $query -> fetch();

        return $averageOrdersByUser;
    }

    // Renvoi le détails des produits achetés par l'utilisateur //
    public function getProductsPurchasedOrdersByIdUser($idUser)
    {
        $query = $this -> _db -> prepare('SELECT orders.date_add,name_product,quantity FROM order_detail INNER JOIN product ON order_detail.id_product = product.id_product INNER JOIN orders ON order_detail.id_order = orders.id_order WHERE orders.id_user = ?');
        $query -> execute([$idUser]);
        $productsPurchased = $query -> fetchAll();

        return $productsPurchased;
    }

    // Renvoi le nombre de produit total acheté par l'utilisateur //
    public function getStatProductsPurchasedByIdUser($idUser)
    {   
        $query = $this -> _db -> prepare('SELECT SUM(quantity) AS numberProducts FROM order_detail INNER JOIN orders ON order_detail.id_order = orders.id_order WHERE id_user = ?');
        $query -> execute([$idUser]);
        $statUserTotalProductsPurchased = $query -> fetch();

        return $statUserTotalProductsPurchased;
    }

    // Pour notifications nouvelles commandes //
    public function getOrderNotification()
    {
        $query = $this -> _db -> prepare('SELECT COUNT(order_notification) AS orderNotification FROM orders WHERE order_notification = 1');
        $query -> execute();
        $orderNotification = $query -> fetch();

        return $orderNotification;
    }

     // Mise à jour statut nouvelle commande //
     public function updateNotification($notification,$idOrder)
     {
         $query = $this -> _db -> prepare('UPDATE orders SET order_notification=? WHERE id_order = ? AND order_notification = 1');
         $query -> execute([$notification,$idOrder]);
     }
}