<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db/database.php';
require_once __DIR__ . '/../Lti13Cache.php';
require_once __DIR__ . '/../Lti13Cookie.php';

use Packback\Lti1p3\LtiOidcLogin;

error_reporting(E_ERROR | E_PARSE);


$authLoginUrl = LtiOidcLogin::new(new Lti13Database(), new Lti13Cache(), new Lti13Cookie())
    ->getRedirectUrl("http://localhost:9001/game.php", $_POST);


header('Location: ' . $authLoginUrl, true, 302);
exit();
