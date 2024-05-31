<?php
require_once 'src/models/UserManager.php';
require_once 'Controller.php';
require_once 'src/models/Album.php';
require_once 'src/models/AlbumManager.php';
require_once 'src/models/CartManager.php';

class CartController extends Controller
{
    public static function renderView()
    {
        $params['cart'] = [];
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $params['cart'][] = array(
                    'album' => AlbumManager::GetAlbumInfo($item['idAlbum']),
                    'qte' => $item['qte']
                );
            }
            $params['total'] = CartManager::GetCartTotal();
        }
        // Si le panier n'est pas vide, on charge le script cart.js (Pas besoin du script si il n'y a rien dans le panier)
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0)
            $scripts = ['cart.js'];
        else
            $scripts = [];
        self::render('templates/front/cart.php', $params, $scripts);
    }

    public static function addItemToCart()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $idUser = isset($_SESSION['id']) ? $_SESSION['id'] : -1;
        try {
            CartManager::AddCartItem($idUser, $data['id'], $data['qte']);
            http_response_code(200);
        } catch (Exception $e) {
            http_response_code(400);
            print (json_encode(['error' => $e->getMessage()]));
        }
    }

    public static function getCartContent()
    {
        $idUser = isset($_SESSION['id']) ? $_SESSION['id'] : -1;
        try {
            $cartItems = CartManager::GetCartItems($idUser);
            http_response_code(200);
            print (json_encode($cartItems));
        } catch (Exception $e) {
            http_response_code(400);
            print (json_encode(['error' => $e->getMessage()]));
        }
    }

    public static function updateItemInCart()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $idUser = isset($_SESSION['id']) ? $_SESSION['id'] : -1;
        try {
            if (
                CartManager::TryUpdateCartItem($idUser, $data['id'], $data['qte'])
            ) {
                http_response_code(200);
            } else {
                throw new Exception("L'album n'est pas dans le panier.");
            }
        } catch (Exception $e) {
            http_response_code(400);
            print (json_encode(['error' => $e->getMessage()]));
        }
    }

    public static function removeItemFromCart()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $idUser = isset($_SESSION['id']) ? $_SESSION['id'] : -1;
        try {
            if (
                CartManager::RemoveCartItem($idUser, $data['id'])
            ) {
                http_response_code(200);
            } else {
                throw new Exception("L'album n'est pas dans le panier.");
            }
        } catch (Exception $e) {
            http_response_code(400);
            print (json_encode(['error' => $e->getMessage()]));
        }
    }

    public static function updateCart()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $idUser = isset($_SESSION['id']) ? $_SESSION['id'] : -1;
        try {
            CartManager::replaceCart($idUser, $data);
            http_response_code(200);
        } catch (Exception $e) {
            http_response_code(400);
            print (json_encode(['error' => $e->getMessage()]));
        }
    }
}
