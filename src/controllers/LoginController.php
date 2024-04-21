<?php
require_once ('Controller.php');
require_once ('src/models/UserManager.php');

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
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    CartManager::AddCartItem($user->GetCart()->GetId(), $item['idAlbum'], $item['qte']);
                }
            } else {
                $_SESSION['cart'] = CartManager::GetCartItems($user->GetCart()->GetId());
            }
            header('Location: /');
        } catch (Exception $ex) {
            $params['error'] = $ex->getMessage();
            self::renderView($params);
        }
    }
}
