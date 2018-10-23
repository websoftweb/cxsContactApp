<?php 

	session_start();
	include_once "data_factory.php";

	if($_POST['tag'] && $_POST['contactID']) {
		$url = "https://api.engagor.com/".$_SESSION['me_accounts']['id']."/inbox/contact/".$_POST['contactID'];
		$handle = curl_init($url);

		curl_setopt($handle, CURLOPT_POST, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS,
            "updates="."{\"tags\":[\"".$_POST['tag']."\"]}"."&&access_token=".$_SESSION['access_token']);
		curl_setopt($handle, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/x-www-form-urlencoded')                                                                       
		);
		
		//set to return the response
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		
		//execute
		$response = (curl_exec( $handle ));
		
		//close connection
		curl_close($handle);
	}
	// configuration (see https://developers.engagor.com/applications)
	// set up your application so the redirect_uri runs this script
	$client_id = 'YOUR-CLIENT-ID';
	$client_secret = 'YOUR-CLIENT-SECRET';
	$scope = 'accounts_read accounts_write';
	

	if (!$_SESSION['access_token']) {
	
		$code = isset($_REQUEST['code']) ? $_REQUEST['code'] : null; // or, check your database for an existing token
	
		if (isset($_GET['error']) && $_GET['error'] && $_GET['error'] === 'access_denied') {
			echo 'The user did not authorize your application to use your CX Social account.';
			exit();
		}
	
		if (empty($code)) {
			$_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
			$authorize_url = 'https://app.engagor.com/oauth/authorize/?client_id='
			. $client_id . '&state=' . $_SESSION['state'] . '&response_type=code';
			if ($scope) {
				$authorize_url .= '&scope=' . urlencode($scope);
			}
	
			echo("<script> top.location.href='" . $authorize_url . "'; </script>"); // forward to auth page
			exit();
		}
	
		if (isset($_REQUEST['state']) && $_REQUEST['state'] !== $_SESSION['state']) {
			echo 'The state does not match. You may be a victim of CSRF.';
			exit();
		}
	
		$token_url = 'https://app.engagor.com/oauth/access_token/?'
			. 'client_id=' . $client_id . '&client_secret=' . $client_secret
			. '&grant_type=authorization_code' . '&code=' . $code;
	
		$response = @file_get_contents($token_url);
		$params = json_decode($response, true); // do error checking with json_last_error()
	
		if (!$params['access_token']) {
			echo 'We could not validate your access token.';
			exit();
		}
		
		$accounts_resource = 'https://api.engagor.com/me/accounts/?access_token='. $_SESSION['access_token'];
		$accounts_response = dataFactory($accounts_resource);
		foreach($accounts_response as $accounts_response_item) {
			$_SESSION['me_accounts'] = $accounts_response_item;
		}
				
		// you will want to save the access_token and refresh_token (+ details)
		// in the session and/or database for re-use later 
		$_SESSION['access_token'] = $params['access_token'];
		$_SESSION['refresh_token'] = $params['refresh_token'];
	
	}
	
	include_once "contacts_view.php";

?>