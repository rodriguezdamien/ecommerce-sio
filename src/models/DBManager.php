<?php
const HOST = '127.0.0.1';
const PORT = '3306';
const DBNAME = 'db_etudiants';
const CHARSET = 'utf8';
const LOGIN = 'root';
const PASSWORD = '';

class DBManager
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

    /**
     * Réinitialise la base de donnée à son état d'origine.
     * Vraiment origine, retour dans le passé carrément.
     * (Je crois que c'est ça AddFixtures() ?)
     */
    public static function reset()
    {
        if (self::$cnx == null) {
            self::$cnx = self::connect();
        }
        $query = self::$cnx->prepare(file_get_contents(ROOT . '/script.sql'));
        $query->execute();
    }
}
