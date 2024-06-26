<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db/database.php';
require_once __DIR__ . '/../Lti13Cache.php';
require_once __DIR__ . '/../Lti13Cookie.php';


use Packback\Lti1p3\LtiOidcLogin;
use Packback\Lti1p3\Redirect;

$authLoginUrl = LtiOidcLogin::new(new Lti13Database(), new Lti13Cache(), new Lti13Cookie())
    ->getRedirectUrl(TOOL_HOST . "/game.php", []);

header('Location: ' . $authLoginUrl, true, 302);
?>