<?php

require_once dirname(__FILE__) . '/MailChimp.php';

class Blockmailchimp extends Module
{
	protected $mailChimp = null;

	public function __construct()
	{
		$this->name = 'blockmailchimp';
		$this->tab = 'front_office_features';
		$this->version = '1.0';

		parent::__construct();

		$this->displayName = $this->l('Mailchimp signup block');

		if (strlen(Configuration::get('MAILCHIMP_APIKEY')) > 0) {
			$this->mailChimp = new MailChimp(Configuration::get('MAILCHIMP_APIKEY'));
		}
	}

	public function install()
	{
		if (!parent::install() || !Configuration::updateValue('MAILCHIMP_APIKEY', '')
			|| !Configuration::updateValue('MAILCHIMP_LISTID', '')
			|| !$this->registerHook('actionCustomerAccountAdd')
			|| !$this->registerHook('header')
			|| !$this->registerHook('footer')
		) {
			return false;
		}
		return true;
	}

	public function uninstall()
	{
		if (!Configuration::updateValue('MAILCHIMP_APIKEY', '') || !parent::uninstall()) {
			return false;
		}
		return true;
	}

	public function getContent()
	{
		$html = '';
		if (isset($_POST['apikey'])) {
			Configuration::updateValue('MAILCHIMP_APIKEY', $_POST['apikey']);
			if (isset($_POST['listId'])) {
				Configuration::updateValue('MAILCHIMP_LISTID', $_POST['listId']);
			}
			$html .= $this->displayConfirmation($this->l('Settings updated'));
		}
		$lists = [];
		if ($this->mailChimp !== null) {
			$data = $this->mailChimp->call('lists/list');
			if (isset($data['data'])) {
				foreach ($data['data'] as $list) {
					$lists[$list['id']] = $list['name'];
				}
			}
		}
		$this->context->smarty->assign([
			'mailchimp_apikey' => Configuration::get('MAILCHIMP_APIKEY'),
			'mailchimp_listid' => Configuration::get('MAILCHIMP_LISTID'),
			'mailchimp_lists' => $lists,
		]);
		$html .= $this->display(__FILE__, 'admin.tpl');
		return $html;
	}

	public function hookDisplayRightColumn()
	{
		$url = $this->context->link->getModuleLink('blockmailchimp', 'subscribe');
		$this->context->smarty->assign('mailchimp_subscribeUrl', $url);
		return $this->display(__FILE__, 'box.tpl');
	}

	public function hookDisplayLeftColumn()
	{
		return $this->hookDisplayRightColumn();
	}

	public function hookActionCustomerAccountAdd($params)
	{
		$email = $params['newCustomer']->email;
		$newsletter = $params['newCustomer']->newsletter;
		if ($newsletter && Validate::isEmail($email)) {
			// TODO: Subscribe user.
		}
		return true;
	}

	public function hookDisplayHeader($params)
	{
		$this->context->controller->addCSS($this->_path . 'blockmailchimp.css', 'all');
	}

	public function hookDisplayFooter($params)
	{
		$this->context->controller->addJS($this->_path . 'blockmailchimp.js');
	}

	public function subscribeEmail($email)
	{
		$id = Configuration::get('MAILCHIMP_LISTID');
		if (strlen($id) > 0 && $this->mailChimp && Validate::isEmail($email)) {
			$ret = $this->mailChimp->call('lists/subscribe', [
				'id' => $id,
				'email' => [
					'email' => $email,
				],
				'double_optin' => false,
				'update_existing' => true,
			]);
			if ($ret !== false && !isset($ret['error']) || (isset($ret['error']) && $ret['name'] == 'List_AlreadySubscribed')) {
				return true;
			}
		}
		return false;
	}
}
