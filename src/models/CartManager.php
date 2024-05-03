<?php

require_once 'src/models/Cart.php';
require_once 'src/models/Manager.php';

class CartManager extends Manager
{
    private static ?\PDO $cnx = null;

    public static function GetCart(int $userId): Cart
    {
        self::$cnx = self::connect();
        $req = 'select Cart(:userId) as id';
        $stmt = self::$cnx->prepare($req);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($result = $stmt->fetch()) {
            $cart = new Cart($result['id'], $userId);
        } else {
            throw New Exception('Impossible de récupérer le panier.');
        }
        return $cart;
    }

    public static function AddCartItem(int $idCart, int $idAlbum, int $qte): bool
    {
        $isAdded = false;
        if ($idCart == -1 && AlbumManager::getAlbumInfo($idAlbum)->GetQte() >= $qte) {
            if (isset($_SESSION['cart'])) {
                if (self::TryUpdateCartItem(-1, $idAlbum, $qte)) {
                    throw new Exception("L'album est déjà dans le panier, mais la quantité a été mise à jour.");
                }
            }
            else
                $_SESSION['cart'] = [];
            $_SESSION['cart'][] = ['idAlbum' => $idAlbum, 'qte' => $qte];
        } else {
            self::$cnx = self::connect();
            $req = 'call AddItemToCart(:idCart, :idAlbum, :qte)';
            $stmt = self::$cnx->prepare($req);
            $stmt->bindValue(':idCart', $idCart, PDO::PARAM_INT);
            $stmt->bindValue(':idAlbum', $idAlbum, PDO::PARAM_INT);
            $stmt->bindValue(':qte', $qte, PDO::PARAM_INT);
            if ($stmt->execute()) {
                if (self::TryUpdateCartItem($idCart, $idAlbum, $qte)) {
                    throw new Exception("L'album est déjà dans le panier, mais la quantité a été mise à jour.");
                }
                $_SESSION['cart'][] = ['idAlbum' => $idAlbum, 'qte' => $qte];
                $isAdded = true;
            }
        }
        return $isAdded;
    }

    public static function TryUpdateCartItem(int $idCart, int $idAlbum, int $qte, bool $inDatabase = false): bool
    {
        $isUpdated = false;
        $i = 0;
        while ($isUpdated == false && $i < count($_SESSION['cart'])) {
            if ($_SESSION['cart'][$i]['idAlbum'] == $idAlbum) {
                $_SESSION['cart'][$i]['qte'] == $qte;
                $isUpdated = true;
            }
            $i++;
        }
        if ($isUpdated == true && $idCart != -1 && $inDatabase == true) {
            self::$cnx = self::connect();
            $req = 'call AddItemToCart(:idCart, :idAlbum, :qte)';
            $stmt = self::$cnx->prepare($req);
            $stmt->bindValue(':idCart', $idCart, PDO::PARAM_INT);
            $stmt->bindValue(':idAlbum', $idAlbum, PDO::PARAM_INT);
            $stmt->bindValue(':qte', $qte, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                throw new Exception('Impossible de mettre à jour le panier.');
            }
        }
        return $isUpdated;
    }

    public static function RemoveCartItem(int $idCart, int $idAlbum): bool
    {
        $isRemoved = false;
        $i = 0;
        while ($isRemoved == false && $i < count($_SESSION['cart'])) {
            if ($_SESSION['cart'][$i]['idAlbum'] == $idAlbum) {
                array_splice($_SESSION['cart'], $i, 1);
                $isRemoved = true;
            }
            $i++;
        }
        if ($isRemoved == true && $idCart != -1) {
            self::$cnx = self::connect();
            $req = 'delete from cartItem where idCart = :idCart and idAlbum = :idAlbum';
            $stmt = self::$cnx->prepare($req);
            $stmt->bindValue(':idCart', $idCart, PDO::PARAM_INT);
            $stmt->bindValue(':idAlbum', $idAlbum, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                throw new Exception("Impossible de supprimer l'article du panier.");
            }
        }
        return $isRemoved;
    }

    public static function GetCartItems(int $idCart): array
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if ($idCart == -1) {
            $items = $_SESSION['cart'];
        } else {
            self::$cnx = self::connect();
            $req = 'select idAlbum, qte from cartItem where idCart = :idCart';
            $stmt = self::$cnx->prepare($req);
            $stmt->bindValue(':idCart', $idCart, PDO::PARAM_INT);
            $stmt->execute();
            $items = [];
            while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $item;
            }
        }
        return $items;
    }
}
