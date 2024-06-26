<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../db/database.php';
require_once __DIR__ . '/../../Lti13Cache.php';


use Packback\Lti1p3\LtiMessageLaunch;
use Packback\Lti1p3\LtiGrade;
use Packback\Lti1p3\LtiLineitem;

$launch = LtiMessageLaunch::fromCache($_REQUEST['launch_id'], new Lti13Database(), new Lti13Cache());
if (!$launch->hasAgs()) {
    throw new Exception("Don't have grades!");
}
$grades = $launch->getAgs();

$score = LtiGrade::new()
    ->setScoreGiven($_REQUEST['score'])
    ->setScoreMaximum(100)
    ->setTimestamp(date(DateTime::ISO8601))
    ->setActivityProgress('Completed')
    ->setGradingProgress('FullyGraded')
    ->setUserId($launch->getLaunchData()['sub']);
$score_lineitem = LtiLineitem::new()
    ->setTag('score')
    ->setScoreMaximum(100)
    ->setLabel('Score')
    ->setResourceId($launch->getLaunchData()['https://purl.imsglobal.org/spec/lti/claim/resource_link']['id']);
$grades->putGrade($score, $score_lineitem);


$time = LtiGrade::new()
    ->setScoreGiven($_REQUEST['time'])
    ->setScoreMaximum(999)
    ->setTimestamp(date(DateTime::ISO8601))
    ->setActivityProgress('Completed')
    ->setGradingProgress('FullyGraded')
    ->setUserId($launch->getLaunchData()['sub']);
$time_lineitem = LtiLineitem::new()
    ->setTag('time')
    ->setScoreMaximum(999)
    ->setLabel('Time Taken')
    ->setResourceId('time' . $launch->getLaunchData()['https://purl.imsglobal.org/spec/lti/claim/resource_link']['id']);
$grades->putGrade($time, $time_lineitem);
echo '{"success" : true}';
?>