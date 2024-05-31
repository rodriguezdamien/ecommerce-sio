<?php
require_once ('Controller.php');
require_once ('src/models/AlbumManager.php');
require_once ('src/models/OrderManager.php');
require_once ('src/models/UserManager.php');

class OrderController extends Controller
{
    public static function renderView($params)
    {
        try {
            $params['view'] = '';
            $scripts = [];
            $params['order'] = OrderManager::getOrder($params['idOrder']);
            if ((!isset($_SESSION['id']) || $params['order']->GetIdUser() != $_SESSION['id']) && !UserManager::CheckAdmin($_SESSION['id'])) {
                header('Location: /account/orders');
                exit();
            } else {
                $orderItems = $params['order']->GetOrderItems();
                foreach ($orderItems as $item) {
                    $params['items'][] = [
                        'album' => AlbumManager::GetAlbumInfo($item['idAlbum']),
                        'qte' => $item['qte']
                    ];
                };
                self::render('templates/front/order.php', $params, $scripts);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
