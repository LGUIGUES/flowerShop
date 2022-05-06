<?php

class Messaging
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

    // On récupère les 5 derniers messages des clients ou des contacts //
    public function getMessagesDashboard()
    {        
        $query = $this -> _db -> prepare('SELECT messaging_thread.id_user, messaging_thread.id_thread_user, lastname, firstname, messaging_thread.email, shop_message, messaging_thread.date_add FROM messaging LEFT JOIN messaging_thread ON messaging.id_thread_user = messaging_thread.id_thread_user LEFT JOIN user ON messaging_thread.id_user = user.id WHERE messaging.id_employee = 0 ORDER BY messaging_thread.date_add DESC LIMIT 5');
        $query -> execute();
        $messages = $query -> fetchAll();

        return $messages;
    }

    // On récupère les 5 derniers messages des clients ou des contacts //
    public function getMessagesNotification()
    {        
        $query = $this -> _db -> prepare('SELECT messaging_thread.id_user, messaging_thread.id_thread_user, msg_notification, lastname, firstname, messaging_thread.email, shop_message, messaging_thread.date_add FROM messaging LEFT JOIN messaging_thread ON messaging.id_thread_user = messaging_thread.id_thread_user LEFT JOIN user ON messaging_thread.id_user = user.id WHERE messaging.id_employee = 0 AND msg_notification = 1 ORDER BY messaging_thread.date_add DESC LIMIT 5');
        $query -> execute();
        $messageNotification = $query -> fetchAll();

        echo json_encode($messageNotification);
    }

    // On fait la mise à jour du statut de la notification de nouveau message //
    public function updateNotification($notification,$idThread)
    {
        $query = $this -> _db -> prepare('UPDATE messaging SET msg_notification=? WHERE id_thread_user = ? AND id_employee = 0');
        $query -> execute([$notification,$idThread]);
    }

    // On compte le nombre de notifications de nouveau message //
    public function getMsgNotification()
    {
        $query = $this -> _db -> prepare('SELECT COUNT(msg_notification) AS msgNotification FROM messaging WHERE id_employee = 0 AND msg_notification = 1');
        $query -> execute();
        $msgNotification = $query -> fetch();

        return $msgNotification;
    }

    // On récupère tous les fils de messages //
    public function getAllMessages()
    {        
        $query = $this -> _db -> prepare('SELECT messaging_thread.id_thread_user, messaging_thread.id_user, firstname, lastname, messaging_thread.email, GROUP_CONCAT(shop_message SEPARATOR ", ") as messages, messaging_thread.date_add FROM messaging_thread LEFT JOIN messaging ON messaging_thread.id_thread_user = messaging.id_thread_user LEFT JOIN user ON messaging_thread.id_user = user.id GROUP BY messaging_thread.id_thread_user ORDER BY messaging_thread.id_thread_user DESC');
        $query -> execute();
        $messages = $query -> fetchAll();

        return $messages;
    }

    // On récupère le fil de message de l'utilisateur //
    public function getThreadMessageByIdThread($idThread)
    {
        $query = $this -> _db -> prepare('SELECT id_message, messaging.id_thread_user, messaging.id_employee, shop_message, messaging.date_add, id_user, user.lastname, user.firstname, messaging_thread.email FROM messaging INNER JOIN messaging_thread ON messaging.id_thread_user = messaging_thread.id_thread_user LEFT JOIN user ON messaging_thread.id_user = user.id LEFT JOIN employee ON messaging.id_employee = employee.id_employee WHERE messaging.id_thread_user = ?');
        $query -> execute([$idThread]);
        $detailsMessage = $query -> fetchAll();

        return $detailsMessage;
    }

    // On enregistre en BDD le fil de message de l'utilisateur //
    public function addThreadUser($idUser,$email) 
    {
        $query = $this -> _db -> prepare('INSERT INTO messaging_thread (id_user,email,date_add) VALUES (?,?,NOW())');        
        $query -> execute([$idUser,$email]);        

        // On récupère l'id du dernier fil qui vient d'être enregistré //
        $lastIdThread = $this -> _db -> lastInsertId();

        // On retourne cette valeur pour la seconde opération d'enregistrement //
        return $lastIdThread;
    }

    // On enregistre un nouveau message //
    public function addMessage($lastIdThread,$message)
    {
        $query = $this -> _db -> prepare('INSERT INTO messaging (id_thread_user,shop_message,date_add) VALUES (?,?,NOW())');        
        
        $messageCreated = $query -> execute([$lastIdThread,$message]);

        return $messageCreated;
    }

    // On récupères les messages de l'utilisateur en BDD //
    public function getMessagesByIdUser($idUser)
    {
        $query = $this -> _db -> prepare('SELECT messaging_thread.id_thread_user, messaging_thread.id_user, firstname, lastname, messaging_thread.email, GROUP_CONCAT(shop_message SEPARATOR ", ") as messages, messaging_thread.date_add FROM messaging_thread LEFT JOIN messaging ON messaging_thread.id_thread_user = messaging.id_thread_user LEFT JOIN user ON messaging_thread.id_user = user.id WHERE messaging_thread.id_user = ? GROUP BY messaging_thread.id_thread_user ORDER BY messaging_thread.id_thread_user DESC');
        $query -> execute([$idUser]);
        $messagesUser = $query -> fetchAll();

        return $messagesUser;
    } 
    
    // On enregistre un message de réponse //
    public function addAnswerMessage($thread,$employee,$message)
    {
        $query = $this -> _db -> prepare('INSERT INTO messaging (id_thread_user,id_employee,shop_message,date_add) VALUES (?,?,?,NOW())');
        
        $answerValided = $query -> execute([$thread,$employee,$message]);

        return $answerValided;
    }
}