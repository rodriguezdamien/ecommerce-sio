<?php
require_once ('Controller.php');
require_once ('src/models/UserManager.php');

class RegisterController extends Controller
{
    public static function renderView($params)
    {
        $scripts = ['register-form.js'];
        if (isset($params['err'])) {
            switch ($params['err']) {
                case 405:
                    $params['error'] = 'Une erreur est survenue.';
                    break;
            }
        }
        self::render('templates/user/register.php', $params, $scripts);
    }

    public static function sendForm($params)
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: /register?err=405');
            exit;
        } else {
            try {
                if (UserManager::registerNew()) {
                    header('Location: /login');
                } else {
                    $params['error'] = 'Erreur lors de la création du compte. Veuillez réessayer.';
                    self::renderView($params);
                };
            } catch (PDOException $ex) {
                if ($ex->getCode() == 23000) {
                    $params['error'] = "L'adresse mail est déjà utilisée.";
                } else {
                    $params['error'] = $ex->getMessage();
                }
                self::renderView($params);
            }
        }
    }
}
