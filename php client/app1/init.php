<?php
	require "vendor\autoload.php";

	 $provider = new League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => 'demo',    // The client ID assigned to you by the provider
    'clientSecret'            => 'demo',   // The client password assigned to you by the provider
    'redirectUri'             => 'https://localhost/app1/callback.php',
    'urlAuthorize'            => 'http://localhost:9999/uaa/oauth/authorize',
    'urlAccessToken'          => 'http://localhost:9999/uaa/oauth/token',
    'urlResourceOwnerDetails' => 'http://localhost:9999/uaa/oauth/check_token'
  ]);
?>