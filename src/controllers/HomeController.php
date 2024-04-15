<?php
require_once ('Controller.php');

class HomeController extends Controller
{
    public static function renderView($params)
    {
        $params['view'] = 'home';
        $scripts = ['splide.min.js', 'accueil.js'];
        self::render('templates/front/home.php', $params, $scripts);
    }
}
