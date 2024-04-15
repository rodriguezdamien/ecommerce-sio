<?php
class Controller
{
    public static function render($view, $params, $scripts)
    {
        // extract($params);
        require_once ('templates/header.php');
        require_once ($view);
        require_once ('templates/footer.php');
    }
}
