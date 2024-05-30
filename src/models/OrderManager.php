<?php

require_once ('Manager.php');
require_once ('Order.php');

class OrderManager extends Manager
{
    private static ?\PDO $cnx = null;

    //     create table if not exists `Commande` (
    //         `id` int not null auto_increment,
    //         `prenomDestinataire` varchar(50) not null default '???',
    //         `nomDestinataire`varchar(50) not null default '???',
    //         `dateHeure` datetime not null default NOW(),
    //         `adresseLivraison` varchar(50) not null,
    //         `complementAdresse` varchar(50) null,
    //         `cpLivraison` varchar(6) not null,
    //         `villeLivraison` varchar(50) not null,
    //         `numeroTel` varchar(13) not null,
    //         `idUser` int not null,
    //         `note` varchar(300) not null default 'Aucune',
    //         primary key(`id`),
    //         foreign key(`idUser`) REFERENCES `User`(`id`)
    // ) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

    /*
     * Récupère la liste des événements
     * @return array tableau d'objets Event
     */

    public static function GetOrder(int $idOrder): Order
    {
        $order = self::GetOrderInfo($idOrder);
        $order->setOrderItems(self::GetOrderItems($idOrder));
        return $order;
    }

    public static function GetOrderInfo(int $idOrder): Order
    {
        self::$cnx = self::connect();
        $req = 'select prenomDestinataire,nomDestinataire,dateHeure,adresseLivraison,complementAdresse,cpLivraison,villeLivraison,numeroTel,mailContact,idUser,note from Commande where id = :id';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':id', $idOrder);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $orderInfo = $result->fetch();
        if ($orderInfo) {
            $order = new Order($idOrder, $orderInfo['prenomDestinataire'], $orderInfo['nomDestinataire'], new DateTime($orderInfo['dateHeure']), $orderInfo['adresseLivraison'], $orderInfo['complementAdresse'], $orderInfo['cpLivraison'], $orderInfo['villeLivraison'], $orderInfo['numeroTel'], $orderInfo['mailContact'], $orderInfo['idUser'], $orderInfo['note']);
        } else {
            throw new Exception('Commande introuvable');
        }
        return $order;
    }

    public static function GetOrderItems(int $idOrder): array
    {
        self::$cnx = self::connect();
        $req = 'select idAlbum,qte from Commander where idCommande = :idCommande';
        $stmt = self::$cnx->prepare($req);
        $stmt->bindValue(':idCommande', $idOrder, PDO::PARAM_INT);
        $stmt->execute();
        $items = [];
        while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $item;
        }
        return $items;
    }

    public static function GetOrderPrice(int $idOrder): float
    {
        self::$cnx = self::connect();
        $req = 'call prixCommande(:idCommande)';
        $stmt = self::$cnx->prepare($req);
        $stmt->bindValue(':idCommande', $idOrder, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $prixTotal = $result['prixTotal'];
        return $prixTotal;
    }
}
