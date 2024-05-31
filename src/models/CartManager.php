<?php

require_once 'src/models/Manager.php';

class CartManager extends Manager
{
    private static ?\PDO $cnx = null;

    public static function AddCartItem(int $idUser, int $idAlbum, int $qte): bool
    {
        $isAdded = false;
        if ($idUser == -1 && AlbumManager::GetAlbumInfo($idAlbum)->GetQte() >= $qte) {
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
            $req = 'call AddItemToCart(:idUser, :idAlbum, :qte)';
            $stmt = self::$cnx->prepare($req);
            $stmt->bindValue(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->bindValue(':idAlbum', $idAlbum, PDO::PARAM_INT);
            $stmt->bindValue(':qte', $qte, PDO::PARAM_INT);
            if ($stmt->execute()) {
                if (!self::TryUpdateCartItem($idUser, $idAlbum, $qte, false))
                    $_SESSION['cart'][] = ['idAlbum' => $idAlbum, 'qte' => $qte];
                $isAdded = true;
            }
        }
        print_r($_SESSION['cart']);
        return $isAdded;
    }

    public static function TryUpdateCartItem(int $idUser, int $idAlbum, int $qte, bool $inDatabase = false): bool
    {
        $isUpdated = false;
        $i = 0;
        while ($isUpdated == false && $i < count($_SESSION['cart'])) {
            if ($_SESSION['cart'][$i]['idAlbum'] == $idAlbum) {
                $_SESSION['cart'][$i]['qte'] = $qte;
                $isUpdated = true;
            }
            $i++;
        }
        if ($isUpdated == true && $idUser != -1 && $inDatabase == true) {
            self::$cnx = self::connect();
            $req = 'call AddItemToCart(:idCart, :idAlbum, :qte)';
            $stmt = self::$cnx->prepare($req);
            $stmt->bindValue(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->bindValue(':idAlbum', $idAlbum, PDO::PARAM_INT);
            $stmt->bindValue(':qte', $qte, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                throw new Exception('Impossible de mettre à jour le panier dans la base de données.');
            }
        }
        return $isUpdated;
    }

    public static function RemoveCartItem(int $idUser, int $idAlbum): bool
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
        if ($isRemoved == true && $idUser != -1) {
            self::$cnx = self::connect();
            $req = 'delete from Cart where idUser = :idUser and idAlbum = :idAlbum';
            $stmt = self::$cnx->prepare($req);
            $stmt->bindValue(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->bindValue(':idAlbum', $idAlbum, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                throw new Exception("Impossible de supprimer l'article du panier.");
            }
        }
        return $isRemoved;
    }

    public static function GetCartItems(int $idUser): array
    {
        // Récupération d'un panier sans compte si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if ($idUser == -1) {
            $items = $_SESSION['cart'];
        } else {
            self::$cnx = self::connect();
            $req = 'select idAlbum, qte from Cart where idUser = :idUser';
            $stmt = self::$cnx->prepare($req);
            $stmt->bindValue(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->execute();
            $items = [];
            while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $item;
            }
        }
        return $items;
    }

    public static function replaceCart(int $idUser, array $items)
    {
        $cartBackup = $_SESSION['cart'];
        $_SESSION['cart'] = [];
        try {
            if ($idUser != -1) {
                self::$cnx = self::connect();
                self::$cnx->beginTransaction();
                $req = 'delete from cart where idUser = :idUser';
                $stmt = self::$cnx->prepare($req);
                $stmt->bindValue(':idUser', $idUser, PDO::PARAM_INT);
                if (!$stmt->execute()) {
                    throw new Exception('Impossible de vider le panier.');
                }
            }
            foreach ($items as $item) {
                self::AddCartItem($idUser, $item['idAlbum'], $item['qte']);
            }
            if ($idUser != -1) {
                self::$cnx->commit();
            }
        } catch (PDOException $ex) {
            $_SESSION['cart'] = $cartBackup;
            self::$cnx->rollBack();
            if ($ex->getCode() == 45001)
                throw new Exception('La quantité ajouté au panier est supérieur au stock disponible.');
            else
                throw new Exception($ex->getMessage());
        } catch (Exception $ex) {
            $_SESSION['cart'] = $cartBackup;
            throw new Exception($ex->getMessage());
        }
    }

    /*
     * Transfère le panier d'une session sans compte à un utilisateur
     */
    public static function TransferGuestToUserCart($idUser)
    {
        try {
            self::$cnx = self::connect();
            self::$cnx->beginTransaction();
            $cartItems = $_SESSION['cart'];
            foreach ($cartItems as $item) {
                self::$cnx = self::connect();
                $req = 'call AddItemToCart(:idUser, :idAlbum, :qte)';
                $stmt = self::$cnx->prepare($req);
                $idAlbum = $item['idAlbum'];
                $qte = $item['qte'];
                $stmt->bindValue(':idUser', $idUser, PDO::PARAM_INT);
                $stmt->bindValue(':idAlbum', $idAlbum, PDO::PARAM_INT);
                $stmt->bindValue(':qte', $qte, PDO::PARAM_INT);
                $stmt->execute();
            }
            self::$cnx->commit();
        } catch (Exception $ex) {
            self::$cnx->rollBack();
            throw new Exception('Impossible de transférer le panier.');
        }
    }

    public static function getCartTotal(): float
    {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += AlbumManager::GetAlbumInfo($item['idAlbum'])->GetPrixValue() * $item['qte'];
        }
        return $total;
    }
}
