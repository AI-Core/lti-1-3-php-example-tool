<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../db/database.php';

use Packback\Lti1p3\LtiMessageLaunch;
use Packback\Lti1p3\LtiLineitem;

$launch = LtiMessageLaunch::fromCache($_REQUEST['launch_id'], new Lti13Database());
if (!$launch->hasNrps()) {
    throw new Exception("Don't have names and roles!");
}
if (!$launch->hasAgs()) {
    throw new Exception("Don't have grades!");
}
$ags = $launch->getAgs();

$score_lineitem = LtiLineitem::new()
    ->setTag('score')
    ->setScoreMaximum(100)
    ->setLabel('Score')
    ->setResourceId($launch->getLaunchData()['https://purl.imsglobal.org/spec/lti/claim/resource_link']['id']);
$scores = $ags->getGrades($score_lineitem);

$time_lineitem = LtiLineitem::new()
    ->setTag('time')
    ->setScoreMaximum(999)
    ->setLabel('Time Taken')
    ->setResourceId('time' . $launch->getLaunchData()['https://purl.imsglobal.org/spec/lti/claim/resource_link']['id']);
$times = $ags->getGrades($time_lineitem);

$members = $launch->getNrps()->getMembers();

$scoreboard = [];

foreach ($scores as $score) {
    $result = ['score' => $score['resultScore']];
    foreach ($times as $time) {
        if ($time['userId'] === $score['userId']) {
            $result['time'] = $time['resultScore'];
            break;
        }
    }
    foreach ($members as $member) {
        if ($member['user_id'] === $score['userId']) {
            $result['name'] = $member['name'];
            break;
        }
    }
    $scoreboard[] = $result;
}
echo json_encode($scoreboard);
?>