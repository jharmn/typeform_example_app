<?php


class Authorize extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
	}

	public function index()
	{
		$provider = new League\OAuth2\Client\Provider\GenericProvider([
		    'clientId'                => 'AbyPdCe1qfWRSq5nJMNFvHfx9Qzgxjkb2QwSkuBrxsmT',    // The client ID assigned to you by the provider
		    'clientSecret'            => 'GxoooZ9rwoHyxa9BqWm5YNSHhqzeYMLwMcX6je7fmCRf',   // The client password assigned to you by the provider
		    'redirectUri'             => 'https://restart_typeform.ngrok.io/index.php/Authorize/index',
		    'urlAuthorize'            => 'https://api.typeform.com/oauth/authorize',
		    'urlAccessToken'          => 'https://api.typeform.com/oauth/token',
		    'urlResourceOwnerDetails' => 'https://api.typeform.com/forms'
		]);
		$scopes = 'forms:read forms:write responses:read webhooks:read';
		// If we don't have an authorization code then get one
		if (!isset($_GET['code'])) {

		    // Fetch the authorization URL from the provider; this returns the
		    // urlAuthorize option and generates and applies any necessary parameters
		    // (e.g. state).
		    $authorizationUrl = $provider->getAuthorizationUrl(['scope' => $scopes]);

		    // Get the state generated for you and store it to the session.
		    $_SESSION['oauth2state'] = $provider->getState();

		    // Redirect the user to the authorization URL.
		    header('Location: ' . $authorizationUrl);
		    exit;

		// Check given state against previously stored one to mitigate CSRF attack
		} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

		    if (isset($_SESSION['oauth2state'])) {
			unset($_SESSION['oauth2state']);
		    }
		    
		    exit('Invalid state');

		} else {

		    try {

			// Try to get an access token using the authorization code grant.
			$accessToken = $provider->getAccessToken('authorization_code', [
			    'code' => $_GET['code']
			]);

			// TODO: Maybe store that access token in a db
			$_SESSION['access_token'] = $accessToken->getToken();

			// Grab redirect from query param
			parse_str(parse_url($_SERVER['REQUEST_URI'])["query"], $query);
			if (isset($query['redirect'])) {
				redirect($query['redirect'], 'auto');	
			} else {
				redirect('/Welcome/index', 'auto');
			}

		    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

			// Failed to get the access token or user details.
			exit($e->getMessage());

		    }

		}
	}
} 
