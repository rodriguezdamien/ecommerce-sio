<?php
require_once ('Controller.php');
require_once ('src/models/AlbumManager.php');

class ProductController extends Controller
{
    public static function renderView($params)
    {
        $params['view'] = 'product';
        $scripts = ['splide.min.js', 'product.js'];
        $params['product'] = AlbumManager::getAlbumInfo($params['id']);
        $params['albums'] = AlbumManager::getRandomAlbums(10);
        $params['songs'] = AlbumManager::getAlbumSongs($params['id']);
        self::render('templates/front/product.php', $params, $scripts);
    }
}
