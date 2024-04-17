<?php
require_once ('Controller.php');
require_once ('src/models/UserManager.php');

class AccountSettingsController extends Controller
{
    public static function renderView($params)
    {
        $scripts = [];
        self::render('templates/user/account-settings.php', $params, $scripts);
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
