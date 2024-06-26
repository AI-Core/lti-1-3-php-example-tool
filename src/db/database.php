<?php
require_once __DIR__ . '/../vendor/autoload.php';
define("TOOL_HOST", ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?: $_SERVER['REQUEST_SCHEME']) . '://' . $_SERVER['HTTP_HOST']);
session_start();

$_SESSION['iss'] = [];
$reg_configs = array_diff(scandir(__DIR__ . '/configs'), array('..', '.', '.DS_Store'));
foreach ($reg_configs as $key => $reg_config) {
    $_SESSION['iss'] = array_merge($_SESSION['iss'], json_decode(file_get_contents(__DIR__ . "/configs/$reg_config"), true));
}

use Packback\Lti1p3\Interfaces\IDatabase;
use Packback\Lti1p3\LtiRegistration;
use Packback\Lti1p3\LtiDeployment;

class Lti13Database implements IDatabase
{
    public static function findIssuer($issuer_url, $client_id = null)
    {
        return $issuer_url;
    }

    public function findRegistrationByIssuer($issuer, $client_id = null)
    {
        if (empty($_SESSION['iss']) || empty($_SESSION['iss'][$issuer])) {
            return false;
        }

        return LtiRegistration::new()
            ->setAuthTokenUrl($_SESSION['iss'][$issuer]['auth_token_url'])
            ->setAuthLoginUrl($_SESSION['iss'][$issuer]['auth_login_url'])
            ->setClientId($_SESSION['iss'][$issuer]['client_id'])
            ->setKeySetUrl($_SESSION['iss'][$issuer]['key_set_url'])
            ->setKid($_SESSION['iss'][$issuer]['kid'])
            ->setIssuer($issuer)
            ->setToolPrivateKey($this->privateKey($issuer));
    }

    private function privateKey($issuer)
    {
        return file_get_contents(__DIR__ . $_SESSION['iss'][$issuer]['private_key_file']);
    }

    public function findDeployment($issuer, $deployment_id, $client_id = null)
    {
        if (!in_array($deployment_id, $_SESSION['iss'][$issuer]['deployment'])) {
            return false;
        }

        return LtiDeployment::new()
            ->setDeploymentId($deployment_id);
    }
}
?>`