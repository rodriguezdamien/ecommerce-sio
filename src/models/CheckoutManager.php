<?php

require_once ('Manager.php');

class CheckoutManager extends Manager
{
    private static ?\PDO $cnx = null;

    public static function CreateOrder(array $orderInfo, int $idUser): int
    {
        try {
            $idUser = $_SESSION['id'];
            self::$cnx = self::connect();
            $req = 'call CartToCommande(:idUserCommande, :prenomDest, :nomDest, :adresseLivr, :complementAdresseLivr, :cpLivr, :villeLivr, :numTel, :mailContact)';
            $stmt = self::$cnx->prepare($req);
            $stmt->bindValue(':idUserCommande', $idUser, PDO::PARAM_INT);
            $stmt->bindValue(':prenomDest', $orderInfo['first-name'], PDO::PARAM_STR);
            $stmt->bindValue(':nomDest', $orderInfo['last-name'], PDO::PARAM_STR);
            $stmt->bindValue(':adresseLivr', $orderInfo['adress'], PDO::PARAM_STR);
            $stmt->bindValue(':complementAdresseLivr', isset($orderInfo['adress-2']) ? $orderInfo['adress-2'] : '', PDO::PARAM_STR);
            $stmt->bindValue(':cpLivr', $orderInfo['postal'], PDO::PARAM_STR);
            $stmt->bindValue(':villeLivr', $orderInfo['city'], PDO::PARAM_STR);
            $stmt->bindValue(':numTel', $orderInfo['phone'], PDO::PARAM_STR);
            $stmt->bindValue(':mailContact', $orderInfo['mail'], PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['idCommande'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        // CREATE PROCEDURE CartToCommande(
        //     idUserCommande INT,
        //     prenomDest VARCHAR(50),
        //     nomDest VARCHAR(50),
        //     adresseLivr VARCHAR(50),
        //     complementAdresseLivr VARCHAR(50),
        //     cpLivr VARCHAR(6),
        //     villeLivr VARCHAR(50),
        //     numTel VARCHAR(13))
    }
}
