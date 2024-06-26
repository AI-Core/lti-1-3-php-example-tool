<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db/database.php';

use Packback\Lti1p3\LtiOidcLogin;

LtiOidcLogin::new(new Lti13Database())
    ->doOidcLoginRedirect(TOOL_HOST . "/game.php")
    ->doRedirect();
?>