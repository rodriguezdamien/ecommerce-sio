<?php
const HOST = '127.0.0.1';
const PORT = '3306';
const DBNAME = 'gakudb';
const CHARSET = 'utf8';
const LOGIN = 'gaku_admin';
const PASSWORD = 'stopl0okingatp4sswd!';

class Manager
{
    /**
     * Classe de gestion de la base de donnée (?)
     * principalement utilisé pour la connexion à la base de donnée.
     */
    private static ?\PDO $cnx = null;

    /**
     * Connexion à la base de donnée
     * @throws Exception levé si une erreur survient lors de la tentative de connexion.
     */
    public static function connect()
    {
        if (self::$cnx == null) {
            try {
                $dsn = 'mysql:host=' . HOST . ';port=' . PORT . ';dbname=' . DBNAME . ';charset=' . CHARSET;
                self::$cnx = new PDO($dsn, LOGIN, PASSWORD);
                self::$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $ex) {
                die('Erreur : ' . $ex->getMessage());
            }
        }
        return self::$cnx;
    }
}
