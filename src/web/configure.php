<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db/database.php';
require_once __DIR__ . '/../Lti13Cache.php';

use PackBack\Lti1p3\LtiMessageLaunch;
use Packback\Lti1p3\LtiDeepLinkResource;

$launch = LtiMessageLaunch::fromCache($_REQUEST['launch_id'], new Lti13Database(), new Lti13Cache());
if (!$launch->isDeepLinkLaunch()) {
    throw new Exception("Must be a deep link!");
}
$resource = LtiDeepLinkResource::new()
    ->setUrl(TOOL_HOST . "/game.php")
    ->setCustomParams(['difficulty' => $_REQUEST['diff']])
    ->setTitle('Breakout ' . $_REQUEST['diff'] . ' mode!');
$launch->getDeepLink()
    ->outputResponseForm([$resource]);
?>