<?php
require_once ('Controller.php');
require_once ('src/models/AlbumManager.php');
require_once ('src/models/OrderManager.php');

class OrderController extends Controller
{
    public static function renderView($params)
    {
        try {
            $params['view'] = '';
            $scripts = [];
            $params['order'] = OrderManager::getOrder($params['idOrder']);
            if ($params['order']->GetIdUser() != $_SESSION['id']) {
                header('Location: /');
                exit();
            } else {
                $orderItems = $params['order']->GetOrderItems();
                $params['total'] = OrderManager::GetOrderPrice($params['order']->GetId());
                foreach ($orderItems as $item) {
                    $params['items'][] = [
                        'album' => AlbumManager::getAlbumInfo($item['idAlbum']),
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
