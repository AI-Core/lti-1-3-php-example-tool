<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db/database.php';
require_once __DIR__ . '/../Lti13Cache.php';
require_once __DIR__ . '/../Lti13Cookie.php';


use GuzzleHttp\Client;
use Packback\Lti1p3\LtiMessageLaunch;
use Packback\Lti1p3\LtiServiceConnector;

header('X-Frame-Options: ALLOWALL');
error_reporting(E_ERROR | E_PARSE);

$database = new Lti13Database();
$cache = new Lti13Cache();
$cookie = new Lti13Cookie();
$client = new Client();
$serviceConnector = new LtiServiceConnector($cache, $client);
$serviceConnector->setDebuggingMode(true);

$launch = LtiMessageLaunch::new($database, $cache, $cookie, $serviceConnector)
    ->initialize($_POST);

?>
<link href="static/breakout.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Gugi" rel="stylesheet"><?php

if ($launch->isDeepLinkLaunch()) {
    ?>
    <div class="dl-config">
        <h1>Pick a Difficulty</h1>
        <ul>
            <li><a href="<?= TOOL_HOST ?>/configure.php?diff=easy&launch_id=<?= $launch->getLaunchId(); ?>">Easy</a></li>
            <li><a href="<?= TOOL_HOST ?>/configure.php?diff=normal&launch_id=<?= $launch->getLaunchId(); ?>">Normal</a>
            </li>
            <li><a href="<?= TOOL_HOST ?>/configure.php?diff=hard&launch_id=<?= $launch->getLaunchId(); ?>">Hard</a></li>
        </ul>
    </div>
    <?php
    die;
}
?>

<div id="game-screen">
    <div style="position:absolute;width:1000px;margin-left:-500px;left:50%; display:block">
        <div id="scoreboard" style="position:absolute; right:0; width:200px; height:486px">
            <h2 style="margin-left:12px;">Scoreboard</h2>
            <table id="leadertable" style="margin-left:12px;">
            </table>
        </div>
        <canvas id="breakoutbg" width="800" height="500" style="position:absolute;left:0;border:0;">
        </canvas>
        <canvas id="breakout" width="800" height="500" style="position:absolute;left:0;">
        </canvas>
    </div>
</div>
<script>
    // Set game difficulty if it has been set in deep linking
    var curr_diff = "<?= $launch->getLaunchData()['https://purl.imsglobal.org/spec/lti/claim/custom']['difficulty'] ?: 'normal'; ?>";
    var curr_user_name = "<?= $launch->getLaunchData()['name']; ?>";
    var launch_id = "<?= $launch->getLaunchData(); ?>";
</script>
<script type="text/javascript" src="static/breakout.js" charset="utf-8"></script>