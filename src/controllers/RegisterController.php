<?php
require_once ('Controller.php');
require_once ('src/models/UserManager.php');

class RegisterController extends Controller
{
    public static function renderView($params)
    {
        $scripts = ['register-form.js'];
        self::render('templates/user/register.php', $params, $scripts);
    }

    public static function sendForm($params)
    {
        if ($_SERVER['HTTP_METHOD'] != 'POST') {
            header('Location: /register?error=405');
        } else {
            try {
                UserManager::registerNew($params);
                header('Location: /login?registered=true');
            } catch (Exception $ex) {
                header('Location: /register?error=500');
            }
        }
    }
}
