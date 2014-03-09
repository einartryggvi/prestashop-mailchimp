<?php

class BlockmailchimpSubscribeModuleFrontController extends ModuleFrontController
{
	public function postProcess()
	{
		header('Content-type: application/json');
		$module = $this->module; /** @var $module Blockmailchimp|ModuleCore */
		$email = (isset($_POST['mailchimp_email'])) ? $_POST['mailchimp_email'] : null;
		if ($email && Validate::isEmail($email)) {
			if ($module->subscribeEmail($email)) {
				die(json_encode(['success' => true, 'email' => $email]));
			}
		}
		die(json_encode(['error' => true]));
	}
}