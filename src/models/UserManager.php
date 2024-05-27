<?php
require_once ('User.php');
require_once ('Manager.php');
require_once ('TokenManager.php');

class UserManager extends Manager
{
    private static ?\PDO $cnx = null;

    /*
     * Enregistre un nouvel utilisateur
     * @return bool true si l'utilisateur a été enregistré, false sinon
     * @throws Exception si une erreur survient lors de l'enregistrement
     */
    static function registerNew(): bool
    {
        $hasRegistered = false;
        // Limite les caractères utilisables dans les noms et prénoms, pour éviter des noms bizarre et des potentielles injections.
        $characterRegex = '/^[a-zA-Z.\'\- ]+$/';
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new Exception("Mauvaise méthode, quelqu'un a essayé de sauter une étape ?");
        } else if (empty($_POST['mail']) || empty($_POST['password']) || empty($_POST['prenom'] || empty($_POST['nom']))) {
            throw new Exception('Tous les champs sont obligatoires');
        } else if (!$_POST['mail'] = filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'email n'est pas valide : " . $_POST['mail']);
        } else if (!preg_match('/^[a-zA-Z.\'\- ]+$/', trim($_POST['nom'])) || !preg_match('/^[a-zA-Z.\'\- ]+$/', trim($_POST['prenom']))) {
            throw new Exception('Le nom ou le prénom contient des caractères interdit.');
        } else {
            self::$cnx = self::connect();
            $req = 'insert into user(mail,passwdHash,prenom,nom)'
                . 'values (:mail,:password,:prenom,:nom)';
            $result = self::$cnx->prepare($req);
            $result->bindParam(':mail', $_POST['mail'], PDO::PARAM_STR);
            $passwdHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $result->bindParam(':password', $passwdHash, PDO::PARAM_STR);
            $result->bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
            $result->bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
            // Si la création du compte (ajout dans la base de donnée) s'est bien effectué
            if ($result->execute()) {
                $hasRegistered = true;
            }
        }
        return $hasRegistered;
    }

    /*
     * Connexion d'un utilisateur
     * @return User l'utilisateur connecté
     * @throws Exception si une erreur survient lors de la connexion
     */
    static function login(): User
    {
        self::$cnx = self::connect();
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new Exception("Mauvaise méthode, quelqu'un a essayé de sauter une étape ?");
        } else if (empty($_POST['mail']) || empty($_POST['password'])) {
            throw new Exception('Tous les champs sont obligatoires');
        } else if (!$_POST['mail'] = filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'email n'est pas valide : " . $_POST['mail']);
        } else {
            $req = 'select passwdHash from user where mail = :mail';
            $result = self::$cnx->prepare($req);
            $result->bindParam(':mail', $_POST['mail']);
            if ($result->execute()) {
                $result->setFetchMode(PDO::FETCH_ASSOC);
                $userInfo = $result->fetch();
                if ($userInfo != false && password_verify($_POST['password'], $userInfo['passwdHash'])) {
                    $user = self::getUserInfo($_POST['mail']);
                    // Création d'un token d'une durée d'un an
                    $newToken = TokenManager::generateToken();
                    $newTokenId = TokenManager::generateTokenId();
                    $expirationDateUnix = time() + 12 * 30 * 24 * 3600;
                    $req = 'insert into token (tokenId,tokenHash,idUser,dateExpiration)'
                        . 'values (:tokenId,:tokenHash,:idUser,:dateExpiration)';
                    $result = self::$cnx->prepare($req);
                    $result->bindParam(':tokenId', $newTokenId, PDO::PARAM_STR);
                    $result->bindParam(':tokenHash', password_hash($newToken, PASSWORD_DEFAULT), PDO::PARAM_STR);
                    $result->bindParam(':idUser', $user->getId(), PDO::PARAM_INT);
                    $result->bindParam(':dateExpiration', date('Y-m-d H:i:s', $expirationDateUnix), PDO::PARAM_STR);
                    if ($result->execute()) {
                        setcookie('ut', $newToken, $expirationDateUnix, '/', null, false, true);
                        setcookie('ui', $newTokenId, $expirationDateUnix, '/', null, false, true);
                    } else {
                        throw new Exception('Erreur lors de la création du token');
                    }
                } else {
                    throw new Exception('Email ou mot de passe incorrect.');
                }
            }
        }
        return $user;
    }

    /*
     * Récupère les informations d'un utilisateur
     * @param string $mail email de l'utilisateur
     * @return User|null l'utilisateur si il existe, null sinon
     */
    static function getUserInfo($mail): ?User
    {
        $user = false;
        self::$cnx = self::connect();
        if (!$mail = filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'email n'est pas valide : " . $_POST['mail']);
        }
        $req = 'select id,prenom,nom,mail,idRole,phone,dateNaissance from user where mail = :mail';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':mail', $mail);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        if ($userInfo = $result->fetch()) {
            $user = new User($userInfo['id'], $userInfo['prenom'], $userInfo['nom'], $userInfo['mail'], $userInfo['dateNaissance'], $userInfo['phone']);
        }
        return $user;
    }

    /*
     * Récupère un utilisateur à partir de son token
     * @param string $token token de l'utilisateur
     * @return User|null l'utilisateur si il existe, null sinon
     */
    static function getUserByToken($tokenId, $token): ?User
    {
        $user = null;
        self::$cnx = self::connect();
        $req = 'select id,prenom,nom,mail,idRole,phone,dateNaissance,token.tokenId,token.tokenHash '
            . 'from user '
            . 'join token on user.id = token.idUser '
            . 'where token.tokenId = :tokenId ';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':tokenId', $tokenId);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        if ($userInfo = $result->fetch()) {
            if (password_verify($token, $userInfo['tokenHash'])) {
                $user = new User($userInfo['id'], $userInfo['prenom'], $userInfo['nom'], $userInfo['mail'], $userInfo['dateNaissance'], $userInfo['phone']);
            }
        }
        return $user;
    }
}

?>
