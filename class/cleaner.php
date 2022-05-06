<?php

// Nettoyage du nom de fichier image //
class cleaner extends AdminController {

    function cleanerNameFile($stringName)
    {   
        // Tableau avec les caractères possibles //
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', "'", ',', ':', ';', '!', '?', '/', '.', '²', '&', '#', '"', '~', '{', '(', '[', '|', '`', '_', '^', '@', ')', '°', ']', '=', '}', '+', 'µ', '*', '$', '¤', '£', '¨', '%', '<', '>', '§');
        
        // Tableau avec les caractères remplacés par les mêmes sans accents ou par des espaces //
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ');

        // La fonction qui traite la chaine //
        $stringNameCleaned = str_replace($search, $replace, $stringName);

        // On retourne la chaine nettoyée //
        return $stringNameCleaned;
    }
}