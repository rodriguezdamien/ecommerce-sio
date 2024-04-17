<?php
session_start();

// Connexion automatique de l'utilisateur si un token valide est présent dans les cookies
// On commence en vérifiant si un id utilisateur est présent dans les informations de session
if (!isset($_SESSION['id'])) {
    require_once ('src/models/UserManager.php');
    UserManager::tryAutoConnect();
}

define('ROOT', __DIR__);
define('DEFAULT_CONTROLLER', '');
define('DEFAULT_ACTION', '');
define('CDN_URL', 'http://cdn.gaku.local');

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
    require_once ('src/controllers/homeController.php');
    homeController::renderView($params);
}
