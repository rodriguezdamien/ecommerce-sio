<?php
require_once ('src/controllers/homeController.php');
define('ROOT', __DIR__);
define('DEFAULT_CONTROLLER', '');
define('DEFAULT_ACTION', '');

$params = array();
foreach ($_GET as $key => $value) {
    if (($key != 'controller') && ($key != 'action')) {
        $params[$key] = $value;
    }
}

if (isset($_GET) && !empty($_GET)) {
    extract($_GET);
    $controller .= 'Controller';
    $filename = ROOT . '/src/controllers/' . $controller . '.php';
    if (file_exists($filename)) {
        require_once ROOT . '/src/controllers/' . $controller . '.php';
        if (method_exists($controller, $action)) {
            try {
                $controller::$action($params);
            } catch (Exception $ex) {
                echo 'Erreur : ' . $ex->getMessage();
            }
        } else {
            print_r("La méthode n'existe pas");
        }
    } else {
        print_r("Le fichier n'existe pas");
    }
} else {
    homeController::renderView($params);
}
