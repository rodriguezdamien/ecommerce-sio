<?php
require_once ('Controller.php');
require_once ('src/models/AlbumManager.php');
require_once ('src/models/OrderManager.php');
require_once ('src/models/UserManager.php');
require_once ('src/models/CartManager.php');

class BackOfficeController extends Controller
{
    public static function renderView($params)
    {
        if (!UserManager::CheckAdmin($_SESSION['id']))
            header('Location: /404');
        if (!isset($params['tab']))
            $params['tab'] = 'products';
        if (!isset($params['page']))
            $params['page'] = 1;
        switch ($params['tab']) {
            case 'products':
                // j'ai encore découvert un truc, quelle aventure
                $params['products'] = AlbumManager::GetAlbums($params['limit'] ?? 10, $params['page'] ?? 1);
                $params['total'] = AlbumManager::GetAlbumsCount();
                foreach ($params['products'] as $product) {
                    $params['qteInCart'][$product->GetId()] = AlbumManager::GetQteInCart($product->GetId());
                }
                break;
            case 'orders':
                $params['orders'] = OrderManager::GetOrders($params['limit'] ?? 10, $params['page'] ?? 1);
                foreach ($params['orders'] as $order) {
                    $orderItems = $order->GetOrderItems();
                    $i = 0;
                    while ($i < 3 && $i < count($orderItems)) {
                        $params['ordersItems'][$order->GetId()][] = AlbumManager::getAlbumInfo($orderItems[$i]['idAlbum']);
                        $i++;
                    }
                }
                $params['total'] = OrderManager::GetOrdersCount();
                break;
            case 'users':
                $params['users'] = UserManager::GetAllUsers($params['limit'] ?? 10, $params['page'] ?? 1);
                $params['total'] = UserManager::GetUsersCount();
                foreach ($params['users'] as $user) {
                    // Note pour plus tard : Prévoir où mettre les fonctions pour éviter un manque de cohérence comme ici
                    $params['usersCartCount'][$user->GetId()] = count(CartManager::GetCartItems($user->GetId()));
                    $params['usersOrdersCount'][$user->GetId()] = count(UserManager::GetUserOrders($user->GetId()));
                }
                break;
        }
        $params['nbPages'] = ceil($params['total'] / ($params['limit'] ?? 10));
        $params['view'] = 'BackOffice';
        $scripts = [];
        self::render('templates/admin/back-office.php', $params, $scripts);
    }
}
