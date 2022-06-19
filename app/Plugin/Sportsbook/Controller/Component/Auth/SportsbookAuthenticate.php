<?php

App::uses('BasicAuthenticate', 'Controller/Component/Auth');

class SportsbookAuthenticate extends BasicAuthenticate {

    public function authenticate(CakeRequest $request, CakeResponse $response) {
		$username = "";
		$pass = "";

		$httpAuthorization = $request->header('Authorization');
		if (strlen($httpAuthorization) > 0 && strpos($httpAuthorization, 'Basic') !== false) {
			list($username, $pass) = explode(':', base64_decode(substr($httpAuthorization, 6)));
		}

		return array('username' => $username, 'password' => $pass);
	}
}
