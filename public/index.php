<?php
require_once __DIR__ . '/../vendor/autoload.php';
use \Defuse\Crypto\Key;
use League\OAuth2\Server\AuthorizationServer;
use App\Repositories\ClientRepository;
use App\Repositories\AccessTokenRepository;
use App\Repositories\ScopeRepository;

$clientRepository = new ClientRepository();
$accessTokenRepository = new AccessTokenRepository();
$scopeRepository = new ScopeRepository();
//var_dump((Key::createNewRandomKey()->saveToAsciiSafeString()));
var_dump(\Defuse\Crypto\Crypto::encrypt('cokolwiek', Key::loadFromAsciiSafeString("def00000c099a231f2264ec0aedd426b1b4d461d0935f4e50135957ec953d22871c12abd62a79ea021efa4c8bb09c4c699e28cf741f2d9a77ab9d671cf3d652ddf7aa626")));

//$server = new AuthorizationServer(
//    $clientRepository,
//    $accessTokenRepository,
//    $scopeRepository,
//    'file://' . __DIR__ . '/../key/private.key',
//    \Defuse\Crypto\Crypto::encrypt('lxZFUEsBCJ2Yb14IF2ygAHI5N4+ZAUXXaSeeJm6+twsUmIen', 'Key::createNewRandomKey()')
//);

echo "dupa";
