<?php
require_once ('Controller.php');
require_once ('src/models/UserManager.php');
require_once ('src/models/AlbumManager.php');

class AccountSettingsController extends Controller
{
    public static function renderView($params)
    {
        if (!isset($_SESSION['id']))
            header('Location: /login');
        if (!isset($params['tab']))
            $params['tab'] = 'info';
        else if ($params['tab'] == 'orders') {
            $params['orders'] = UserManager::GetUserOrders($_SESSION['id']);
            foreach ($params['orders'] as $order) {
                $orderItems = $order->GetOrderItems();
                $i = 0;
                while ($i < 3 && $i < count($orderItems)) {
                    $params['ordersItems'][$order->GetId()][] = AlbumManager::getAlbumInfo($orderItems[$i]['idAlbum']);
                    $i++;
                }
            }
        }
        $scripts = ['account-settings.js'];
        self::render('templates/user/account-settings.php', $params, $scripts);
    }

    public static function updateInfo($params)
    {
        if (!isset($_SESSION['id']))
            header('Location: /login');
        $params['tab'] = 'info';
        try {
            $mailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
            if (!isset($_POST['first-name']) || !isset($_POST['last-name']) || !isset($_POST['mail']))
                throw new Exception('Veuillez remplir tous les champs.');
            if (!preg_match($mailRegex, $_POST['mail']))
                throw new Exception('Adresse mail invalide.');
            UserManager::updateInfo($_SESSION['id'], $_POST['first-name'], $_POST['last-name'], $_POST['mail']);
            $params['success'] = 'Vos informations ont bien été mises à jour.';
        } catch (Exception $e) {
            $params['error'] = $e->getMessage();
        }
        self::renderView($params);
    }

    public static function changePassword($params)
    {
        if (!isset($_SESSION['id']))
            header('Location: /login');
        $params['tab'] = 'change-password';
        try {
            if (!isset($_POST['old-password']) || !isset($_POST['new-password']) || !isset($_POST['confirm-password']))
                throw new Exception('Veuillez remplir tous les champs.');
            if ($_POST['new-password'] !== $_POST['confirm-password'])
                throw new Exception('Le nouveau mot de passe et la confirmation ne correspondent pas.');
            if (!UserManager::checkPassword($_SESSION['id'], $_POST['old-password']))
                throw new Exception("L'ancien mot de passe est incorrect.");
            UserManager::changePassword($_SESSION['id'], $_POST['new-password']);
            session_destroy();
            header('Location: /login?changed=200');
        } catch (Exception $e) {
            $params['error'] = $e->getMessage();
            self::renderView($params);
        }
    }

    public static function disconnect($params)
    {
        session_destroy();
        if (!TokenManager::destroyToken($_COOKIE['ui']))
            header('Location: /woops');
        setcookie('ui', '', -1, '/', null, false, true);
        setcookie('ut', '', -1, '/', null, false, true);
        header('Location: /');
    }
}
