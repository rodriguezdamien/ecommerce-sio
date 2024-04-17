<?php
require_once ('Controller.php');
require_once ('src/models/AlbumManager.php');

class HomeController extends Controller
{
    public static function renderView($params)
    {
        $params['view'] = 'home';
        $scripts = ['splide.min.js', 'accueil.js'];
        $params['trending_albums'] = AlbumManager::getRandomAlbums(20);
        $params['preorder_albums'] = AlbumManager::getPreoderAlbums(20);
        self::render('templates/front/home.php', $params, $scripts);
    }
}
