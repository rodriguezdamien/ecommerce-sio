<?php
require_once ('Manager.php');

class TokenManager extends Manager
{
    private static ?\PDO $cnx = null;

    /*
     * Génère un token de connexion
     * @param int $length longueur du token
     */
    static function generateToken()
    {
        $bytes = random_bytes(40);
        $hex = bin2hex($bytes);
        $currentDate = new DateTime();
        $token = base64_encode($hex . $currentDate->format('m-d-Y-H-s') . 'Maman la télé, GAKU!!' . $hex[5] . $hex[1]);
        return $token;
    }

    /*
     * Génère un token de connexion
     * @param int $length longueur du token
     */
    static function generateTokenId()
    {
        $bytes = random_bytes(10);
        $hex = bin2hex($bytes);
        $currentDate = new DateTime();
        $tokenId = base64_encode($hex[2] . 'DASHiO' . $hex . $hex[3] . $currentDate->format('m-d-Y-H-s') . 'N30V3N1SE' . $hex[1]);
        return $tokenId;
    }

    /*
     * Détruit un token
     * @param string $token token à détruire
     * @return bool true si le token a été détruit, false sinon
     */
    static function destroyToken($tokenId): bool
    {
        $isDestroyed = false;
        self::$cnx = self::connect();
        $req = 'delete from token where tokenId = :tokenId';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':tokenId', $tokenId);
        $result->execute();
        if ($result->rowCount() > 0) {
            $isDestroyed = true;
        }
        return $isDestroyed;
    }

    /*
     * Vérifie la validité d'un token
     * @param string $token token à vérifier
     * @return bool true si le token est valide, false sinon
     */
    static function checkTokenValidity($tokenId, $token): bool
    {
        $isValid = false;
        self::$cnx = self::connect();
        $req = 'select tokenHash,dateExpiration from token where tokenId = :tokenId';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':tokenId', $tokenId, PDO::PARAM_STR);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        if ($tokenInfo = $result->fetch()) {
            $currentDate = new DateTime();
            $expirationDate = new DateTime($tokenInfo['dateExpiration']);
            if ($currentDate < $expirationDate) {
                if (password_verify($token, $tokenInfo['tokenHash'])) {
                    $isValid = true;
                }
            }
        }
        return $isValid;
    }

    public static function destroyUsersToken($userId): bool
    {
        self::$cnx = self::connect();
        $req = 'delete from token where idUser = :idUser';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':idUser', $userId);
        if ($result->execute())
            return true;
    }
}
