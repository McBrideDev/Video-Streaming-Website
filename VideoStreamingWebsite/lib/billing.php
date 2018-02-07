<?php
require_once ''.$_SERVER['DOCUMENT_ROOT'].'/lib/paymentwall.php';
		Paymentwall_Config::getInstance()->set(array(
		    'private_key' => 't_b6176e584b61ff7ab6897c076a37d4'
		));

		$parameters = $_POST;
		$cardInfo = array(
		    'email' => $parameters['email'],
		    'amount' => 9.99,
		    'currency' => 'USD',
		    'token' => $parameters['brick_token'],
		    'fingerprint' => $parameters['brick_fingerprint'],
		    'description' => 'Order #123'
		);

		$charge = new Paymentwall_Charge();
		$charge->create($cardInfo);
		//var_dump($charge);
		$response = $charge->getPublicData();

		if ($charge->isSuccessful()) {
		    if ($charge->isCaptured()) {
		       // deliver a product
		    } elseif ($charge->isUnderReview()) {
		        // decide on risk charge
		    }
		} else {
		    $errors = json_decode($response, true);
		}

		echo $response;
		

