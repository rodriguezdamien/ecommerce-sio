<?php
require_once ('Controller.php');

class RegisterController extends Controller
{
    public static function renderView($params)
    {
        $scripts = ['register-form.js'];
        self::render('templates/register.php', $params, $scripts);
    }
}
