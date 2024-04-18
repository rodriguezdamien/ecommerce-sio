<?php
require_once ('Controller.php');
require_once ('src/models/AlbumManager.php');
require_once ('src/models/EventManager.php');
require_once ('src/models/EditionManager.php');

class ResultsController extends Controller
{
    public static function renderView($params)
    {
        $params['view'] = 'results';
        $scripts = ['filter.js'];
        $params['results'] = AlbumManager::SearchAlbumsByQuery(
            isset($_GET['query']) ? $_GET['query'] : '',
            isset($_GET['event']) ? $_GET['event'] : null,
            isset($_GET['edition']) ? $_GET['edition'] : null
        );
        $params['events'] = EventManager::GetEvents();
        $params['editions'] = EditionManager::GetEditions();
        self::render('templates/front/results.php', $params, $scripts);
    }
}
