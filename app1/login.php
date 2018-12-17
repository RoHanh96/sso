<?php
    require "vendor\autoload.php";

    // use league\oauth2\client\src;

    $provider = new League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => 'demo',    // The client ID assigned to you by the provider
    'clientSecret'            => 'demo',   // The client password assigned to you by the provider
    'redirectUri'             => 'https://localhost/app1/callback.php',
    'urlAuthorize'            => 'http://localhost:9999/uaa/oauth/authorize',
    'urlAccessToken'          => 'http://localhost:9999/uaa/oauth/token',
    'urlResourceOwnerDetails' => 'http://localhost:9999/uaa/oauth/check_token'
    ]);

    if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $authorizationUrl = $provider->getAuthorizationUrl();
    var_dump($authorizationUrl);

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo $accessToken->getToken() . "\n";
        echo $accessToken->getRefreshToken() . "\n";
        echo $accessToken->getExpires() . "\n";
        echo ($accessToken->hasExpired() ? 'expired' : 'not expired') . "\n";

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        var_export($resourceOwner->toArray());

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
        $request = $provider->getAuthenticatedRequest(
            'GET',
            'http://localhost:9999/uaa/oauth/check_token',
            $accessToken
        );

    } catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());
    }
}
?>