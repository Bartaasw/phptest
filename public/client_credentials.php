<?php

use Laminas\Diactoros\Stream;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use App\Repositories\AccessTokenRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ScopeRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

include __DIR__ . '/../vendor/autoload.php';
error_reporting( E_ALL ^ E_DEPRECATED );

$app = new App([
    'settings'                 => [
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
    ],
    AuthorizationServer::class => function () {
        // Init our repositories
        $clientRepository = new ClientRepository(); // instance of ClientRepositoryInterface
        $scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface

        // Path to public and private keys
        $privateKey = 'file://' . __DIR__ . '/../key/private.key';
        //$privateKey = new CryptKey('file://path/to/private.key', 'passphrase'); // if private key has a pass phrase

        // Setup the authorization server
        $server = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            'lxZFUEsBCJ2Yb14IF2ygAHI5N4+ZAUXXaSeeJm6+twsUmIen'
        );

        // Enable the client credentials grant on the server
        $server->enableGrantType(
            new \League\OAuth2\Server\Grant\ClientCredentialsGrant(),
            new \DateInterval('PT1H') // access tokens will expire after 1 hour
        );

        return $server;
    },
]);

$app->post('/access_token', function (ServerRequestInterface $request, ResponseInterface $response) use ($app) {
    /* @var \League\OAuth2\Server\AuthorizationServer $server */
    $server = $app->getContainer()->get(AuthorizationServer::class);

    try {
        // Try to respond to the request
        return $server->respondToAccessTokenRequest($request, $response);
    } catch (OAuthServerException $exception) {
        // All instances of OAuthServerException can be formatted into a HTTP response
        return $exception->generateHttpResponse($response);
    } catch (\Exception $exception) {
        // Unknown exception
        $body = new Stream('php://temp', 'r+');
        $body->write($exception->getMessage());

        return $response->withStatus(500)->withBody($body);
    }
});

$app->run();
