<?php

class WorkHour
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

    // On récupère en BDD les informations (nom, adresse, téléphone...) de la boutique //
    public function getShopWorkHours()
    {        
        $query = $this -> _db -> prepare('SELECT id,start_day,`day`,
                                        TIME_FORMAT(hour_morning_start, "%H:%i") AS hour_morning_start,
                                        TIME_FORMAT(hour_morning_end, "%H:%i") AS hour_morning_end,  
                                        TIME_FORMAT(hour_afternoon_start, "%H:%i") AS hour_afternoon_start,
                                        TIME_FORMAT(hour_afternoon_end, "%H:%i") AS hour_afternoon_end,
                                        active FROM work_hour');

        // On execute la requête //
        $query -> execute();

        // On récupère le résultat de la requête //
        $workingHours = $query -> fetchAll();

        // On retourne les données au controlleur //
        return $workingHours;
    }

    // On remet à zéro la colonne du jour de départ de la semaine //
    public function updateStarDayColumn($reset)
    {
        $query = $this -> _db -> prepare('UPDATE work_hour SET start_day=?');
        $query -> execute([$reset]);
    }

    // On fait la mise à jour d'un jour de la semaine avec son Id //
    public function updateWorkHoursById($id,$startDay,$hourMorningStart,$hourMorningEnd,$hourAfternoonStart,$hourAfternoonEnd,$active)
    {
        $query = $this -> _db -> prepare('UPDATE work_hour SET start_day=?, hour_morning_start=?, hour_morning_end=?, hour_afternoon_start=?, hour_afternoon_end=?, active=? WHERE id=?');
        $hoursUpdated = $query -> execute([$startDay,$hourMorningStart,$hourMorningEnd,$hourAfternoonStart,$hourAfternoonEnd,$active,$id]);
        
        return $hoursUpdated; 
    }    
}