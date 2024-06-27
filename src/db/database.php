<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();
define("TOOL_HOST", ($_SERVER['REQUEST_SCHEME']) . '://' . $_SERVER['HTTP_HOST']);

// Add platform in environment variables to session
$platformIssuer = getenv('PLATFORM_ISSUER');
$platformClientId = getenv('PLATFORM_CLIENT_ID');
$platformLoginUrl = getenv('PLATFORM_LOGIN_URL');
$platformTokenUrl = getenv('PLATFORM_TOKEN_URL');
$platformJwksUrl = getenv('PLATFORM_JWKS_URL');
$platformDeploymentId = getenv('PLATFORM_DEPLOYMENT_ID');

$_SESSION['iss'] = [];
$_SESSION['iss'][$platformIssuer] = [
    "client_id" => $platformClientId,
    "auth_login_url" => $platformLoginUrl,
    "auth_token_url" => $platformTokenUrl,
    "key_set_url" => $platformJwksUrl,
    "private_key_file" => "/private.key",
    "deployment" => [$platformDeploymentId]
];

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