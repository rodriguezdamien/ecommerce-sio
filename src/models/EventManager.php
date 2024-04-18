<?php

require_once ('Manager.php');
require_once ('Event.php');

class EventManager extends Manager
{
    private static ?\PDO $cnx = null;

    /*
     * Récupère la liste des événements
     * @return array tableau d'objets Event
     */
    public static function GetEvents(): array
    {
        $events = [];
        self::$cnx = self::connect();
        $req = 'select * from event';
        $result = self::$cnx->query($req);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        while ($event = $result->fetch(PDO::FETCH_ASSOC)) {
            $events[] = new Event($event['id'], $event['nom'], $event['description']);
        }
        return $events;
    }
}
