<?php

App::uses('BasicAuthenticate', 'Controller/Component/Auth');
App::uses('Utility', 'Xml');

class WNetGameAuthenticate extends BasicAuthenticate {

    public function authenticate(CakeRequest $request, CakeResponse $response) {
		// $data = $request->input('Xml::build', array('return' => 'domdocument'));

		$data = file_get_contents('php://input');
		$data = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $data);
		$data = new SimpleXMLElement($data, 0);
		$data = Xml::toArray($data);

		if (isset($data['PKT']['Method']['Auth']['@Login']) ) {
			$username = $data['PKT']['Method']['Auth']['@Login'];
		}

		if (isset($data['PKT']['Method']['Auth']['@Login']) ) {
			$password = $data['PKT']['Method']['Auth']['@Password'];
		}

		return array('username' => $username, 'password' => $password);
	}
}
