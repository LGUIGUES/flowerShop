<?php

class Content
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

    // Liste les blocs de postion autorisée pour les pages de contenu //
    public function getBlocksLocation()
    {
        $query = $this -> _db -> prepare('SELECT id_location,name_location FROM content_location WHERE authorized_use = 1');
        $query -> execute();
        $blocksLocation = $query -> fetchAll();

        return $blocksLocation;
    }
    
    // Liste les pages de contenu //
    public function getContentPages()
    {
        $query = $this -> _db -> prepare('SELECT id_page, title_content, text_content, deletable, active, name_location, date_add, date_upd FROM content INNER JOIN content_location ON content.id_location = content_location.id_location');
        $query -> execute();
        $contentPages = $query -> fetchAll();

        return $contentPages;
    }

    // On récupère une page de contenu avec son Id //
    public function getContentPageById($idPage)
    {
        $query = $this -> _db -> prepare('SELECT id_page, title_content, text_content, deletable, active, content.id_location, name_location FROM content INNER JOIN content_location ON content.id_location = content_location.id_location WHERE id_page = ?');
        $query -> execute([$idPage]);
        $page = $query -> fetch();

        return $page;
    }

    // Enregistrement d'une page de contenu //
    public function createContentPage($titleContent,$textContent,$deletable,$activePage,$blockLinkPage)
    {
        $query = $this -> _db -> prepare('INSERT INTO content(title_content, text_content, deletable, active, id_location, date_add, date_upd) VALUES (?,?,?,?,?,NOW(),NOW())');
        $pageCreated = $query -> execute([$titleContent,$textContent,$deletable,$activePage,$blockLinkPage]);

        return $pageCreated;
    }

    // Mise à jour d'une page de contenu de base //
    public function updateContentFixedPage($idPage,$textContent,$activePage)
    {
        $query = $this -> _db -> prepare('UPDATE content SET text_content=?, active=?, date_upd=NOW() WHERE id_page = ?');
        $pageUpdated = $query -> execute([$textContent,$activePage,$idPage]);

        return $pageUpdated;
    }

    // Mise à jour d'une page de contenu //
    public function updateContentPage($idPage,$titleContent,$textContent,$activePage,$blockLinkPage)
    {
        $query = $this -> _db -> prepare('UPDATE content SET title_content=?, text_content=?, active=?, id_location=?, date_upd=NOW() WHERE id_page = ?');
        $pageUpdated = $query -> execute([$titleContent,$textContent,$activePage,$blockLinkPage,$idPage]);

        return $pageUpdated;
    }

    // Suppression d'une page de contenu avec son Id //
    public function deleteContentPageById($idPage)
    {
        $query = $this -> _db -> prepare('DELETE FROM content WHERE id_page = ?');
        $deletedPage = $query -> execute([$idPage]);

        return $deletedPage;
    }    
    
    // On récupère une page de contenu avec on Id //
    public function getStaticPageContent($idPage)
    {
        $query = $this -> _db -> prepare('SELECT title_content, text_content FROM content WHERE id_page = ?');
        $query -> execute([$idPage]);
        $staticPage = $query -> fetch();

        return $staticPage;
    }

    // Exporte une page de contenu par son Id pour affichage en modal //
    public function jsonModal($idPage)
    {
        $query = $this -> _db -> prepare('SELECT title_content, text_content FROM content WHERE id_page = ?');
        $query -> execute([$idPage]);
        $legalNotice = $query -> fetch();

        echo json_encode($legalNotice);
    }

    // On récupère en BDD le texte du bloc présentation // 
    public function getCustomHomeBlocByPosition($idLocation)
    {        
        $query = $this -> _db -> prepare('SELECT id_page,title_content, text_content FROM content WHERE id_location = ?');

        // On execute la requête //
        $query -> execute([$idLocation]);

        // On récupère le résultat de la requête //
        $customizeText = $query -> fetch();

        // On retourne les données au controlleur //
        return $customizeText;        
    }

    // On récupère la liste des titres de page de contenu pour les liens dans le Footer //
    public function getLinksFooterByLocation($idLocation)
    {
        $query = $this -> _db -> prepare('SELECT id_page, title_content, active FROM content WHERE id_location = ? AND active = 1');
        $query -> execute([$idLocation]);
        $linksFooter = $query -> fetchAll();

        return $linksFooter;
    }    
}