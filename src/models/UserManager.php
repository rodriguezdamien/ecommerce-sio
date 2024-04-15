<?php
require_once ('User.php');
require_once ('DBManager.php');

class UserManager
{
    private static ?\PDO $cnx = null;

    static function registerNew($params)
    {
        if ($_SERVER['HTTP_METHOD'] != 'POST') {
            throw new Exception('Méthode non autorisée');
        } else if (empty($_POST['mail']) || empty($_POST['password']) || empty($_POST['prenom'] || empty($_POST['nom']))) {
            throw new Exception('Tous les champs sont obligatoires');
        } else if ($_POST['mail'] = filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'email n'est pas valide");
        } else {
            self::$cnx = DBManager::connect();
            $req = 'insert into user(mail,passwdHash,prenom,nom)'
                . 'values (:mail,:password,:prenom,:nom)';
            $result = self::$cnx->prepare($req);
            $result->bindParam(':mail', $_POST['mail'], PDO::PARAM_STR);
            $result->bindParam(':password', password_hash($_POST['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);
            $result->bindParam(':prenom', htmlspecialchars($_POST['prenom']), PDO::PARAM_STR);
            $result->bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
        }
    }
}

?>