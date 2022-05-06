<?php

class ShopEvent
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

    // On ajoute un évènement //
    public function addEvent($titleEvent,$textEvent,$eventDateStart,$eventDateEnd,$nameEventLarge,$nameEventMedium,$nameEventMiniature,$activeEvent) {

        $query = $this -> _db -> prepare('INSERT INTO shop_event(title_shop_event,description_shop_event,event_start,event_end,img_event_large,img_event_medium,img_event_miniature,active,date_add,date_upd) VALUES(?,?,?,?,?,?,?,?,NOW(),NOW())');
        $eventValided = $query -> execute([$titleEvent,$textEvent,$eventDateStart,$eventDateEnd,$nameEventLarge,$nameEventMedium,$nameEventMiniature,$activeEvent]);
        
        return $eventValided;
    }

    // On fait la mise à jour d'un évènement avec modification de son image //
    public function updateEventWithImage($idEvent,$titleEvent,$textEvent,$eventDateStart,$eventDateEnd,$nameEventLarge,$nameEventMedium,$nameEventMiniature,$activeEvent)
    {
        $query = $this -> _db -> prepare('UPDATE shop_event SET title_shop_event=?,description_shop_event=?,event_start=?,event_end=?,img_event_large=?,img_event_medium=?,img_event_miniature=?,active=?,date_upd=NOW() WHERE id_event = ?');
        $eventUpdated = $query -> execute([$titleEvent,$textEvent,$eventDateStart,$eventDateEnd,$nameEventLarge,$nameEventMedium,$nameEventMiniature,$activeEvent,$idEvent]);

        return $eventUpdated;
    }
    
    // On fait la mise à jour d'un évènement sans modification de son image //
    public function updateEvent($idEvent,$titleEvent,$textEvent,$eventDateStart,$eventDateEnd,$activeEvent)
    {
        $query = $this -> _db -> prepare('UPDATE shop_event SET title_shop_event=?,description_shop_event=?,event_start=?,event_end=?,active=?,date_upd=NOW() WHERE id_event = ?');
        $eventUpdated = $query -> execute([$titleEvent,$textEvent,$eventDateStart,$eventDateEnd,$activeEvent,$idEvent]);

        return $eventUpdated;
    }

    // On supprime un évènement avec son id //
    public function deleteEventByid($idEvent)
    {
        $query = $this -> _db -> prepare('DELETE FROM shop_event WHERE id_event = ?');
        $eventDeleted = $query -> execute([$idEvent]);

        return $eventDeleted;
    }

    // On récupère la liste de tous les évènements //
    public function getAllEvents()
    {
        $query = $this -> _db -> prepare('SELECT id_event,title_shop_event,description_shop_event,event_start,event_end,img_event_large,img_event_medium,img_event_miniature,active FROM shop_event ORDER BY event_start ASC');
        $query -> execute();
        $shopEvents = $query -> fetchAll();

        return $shopEvents;
    }

    // On récupère les infos d'un évènement avec son Id //
    public function getEventById($idEvent) {
        
        $query = $this -> _db -> prepare('SELECT id_event,title_shop_event,description_shop_event,event_start,event_end,img_event_large,img_event_medium,img_event_miniature,active FROM shop_event WHERE id_event = ?');
        $query -> execute([$idEvent]);
        $event = $query -> fetch();

        return $event;
    }

    // On récupère la liste des évènements pour la Home Page //
    public function getEventHome()
    {
        $query = $this -> _db -> prepare('SELECT id_event,title_shop_event,description_shop_event,event_start,event_end,img_event_large,active FROM shop_event ORDER BY event_start ASC');
        $query -> execute();
        $event = $query -> fetch();

        return $event;
    }
}