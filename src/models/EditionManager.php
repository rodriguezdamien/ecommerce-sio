<?php

require_once ('Manager.php');
require_once ('Edition.php');

class EditionManager extends Manager
{
    private static ?\PDO $cnx = null;

    /*
     * Récupère la liste des éditions
     * @return array tableau d'objets Edition
     */
    public static function GetEditions(): array
    {
        $editions = [];
        self::$cnx = self::connect();
        $req = 'select idEvent,numEdition,annee from edition_event order by numEdition desc';
        $result = self::$cnx->prepare($req);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        while ($edition = $result->fetch()) {
            $editions[] = new Edition($edition['idEvent'], $edition['numEdition'], $edition['annee']);
        }
        return $editions;
    }
}
