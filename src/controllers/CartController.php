<?php

require_once 'src/models/UserManager.php';
require_once 'Controller.php';
require_once 'src/models/Album.php';
require_once 'src/models/AlbumManager.php';

class CartController extends Controller
{
    public static function renderView()
    {
        $params['cart'] = [];
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $params['cart'][] = AlbumManager::getAlbumInfo($item['idAlbum']);
            }
        }
        $scripts = [];
        self::render('templates/front/cart.php', $params, $scripts);
    }

    public static function addItemToCart()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $cart = isset($_SESSION['id']) ? CartManager::GetCart($_SESSION['id']) : new Cart(-1, -1);
        try {
            CartManager::AddCartItem($cart->GetId(), $data['id'], $data['qte']);
            http_response_code(200);
        } catch (Exception $e) {
            http_response_code(400);
            print (json_encode(['error' => $e->getMessage()]));
        }
    }

    public static function getCartContent()
    {
        $cart = isset($_SESSION['id']) ? CartManager::GetCart($_SESSION['id']) : new Cart(-1, -1);
        try {
            $cartItems = CartManager::GetCartItems($cart->GetId());
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
        $cart = isset($_SESSION['id']) ? CartManager::GetCart($_SESSION['id']) : new Cart(-1, -1);
        try {
            if (
                CartManager::TryUpdateCartItem($cart->GetId(), $data['id'], $data['qte'], true)
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
        $cart = isset($_SESSION['id']) ? CartManager::GetCart($_SESSION['id']) : new Cart(-1, -1);
        try {
            if (
                CartManager::RemoveCartItem($cart->GetId(), $data['id'])
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
}
