<?php
require_once ('Controller.php');
require_once ('src/models/UserManager.php');
require_once ('src/models/CartManager.php');

class LoginController extends Controller
{
    public static function renderView($params)
    {
        $scripts = [];
        if (isset($params['err'])) {
            switch ($params['err']) {
                case 405:
                    $params['error'] = 'Une erreur est survenue.';
                    break;
            }
        }
        self::render('templates/user/login.php', $params, $scripts);
    }

    public static function login($params)
    {
        try {
            $user = UserManager::login();
            $_SESSION['id'] = $user->GetId();
            $_SESSION['prenom'] = $user->GetPrenom();
            $_SESSION['nom'] = $user->GetNom();
            $_SESSION['mail'] = $user->GetMail();
            $_SESSION['phone'] = $user->GetPhone();
            $_SESSION['dateNaissance'] = $user->GetDateNaissance();
            if (isset($_SESSION['cart']))
                CartManager::TransferGuestToUserCart($_SESSION['id']);
            $_SESSION['cart'] = CartManager::GetCartItems($_SESSION['id']);
            header('Location: /');
        } catch (Exception $ex) {
            $params['error'] = $ex->getMessage();
            self::renderView($params);
        }
    }

    static function tryAutoConnect()
    {
        // On vérifie si un token est présent dans les cookies
        if (isset($_COOKIE['ut']) && isset($_COOKIE['ui'])) {
            require_once ('src/models/TokenManager.php');
            // Si le token présent n'est pas valide, on le détruit
            if (!TokenManager::checkTokenValidity($_COOKIE['ui'], $_COOKIE['ut'])) {
                TokenManager::destroyToken($_COOKIE['ui']);
                setcookie('ui', '', -1, '/', null, false, true);
                setcookie('ut', '', -1, '/', null, false, true);
            } else {
                require_once ('src/models/UserManager.php');
                $user = UserManager::getUserByToken($_COOKIE['ui'], $_COOKIE['ut']);
                if ($user != null) {
                    $_SESSION['id'] = $user->GetId();
                    $_SESSION['prenom'] = $user->GetPrenom();
                    $_SESSION['nom'] = $user->GetNom();
                    $_SESSION['mail'] = $user->GetMail();
                    $_SESSION['phone'] = $user->GetPhone();
                    $_SESSION['dateNaissance'] = $user->GetDateNaissance();
                    $_SESSION['cart'] = CartManager::GetCartItems($_SESSION['id']);
                }
            }
        }
    }
}
