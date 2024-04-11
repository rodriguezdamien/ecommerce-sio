<?php
require_once ('Controller.php');

class LoginController extends Controller
{
    public static function renderView($params)
    {
        $params['view'] = 'login';
        $scripts = [];
        self::render('templates/login.php', $params, $scripts);
    }
}
