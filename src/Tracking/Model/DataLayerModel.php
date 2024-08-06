<?php

namespace Websmid\Taggrs\Tracking\Model;

use Context;

class DataLayerModel{

	private $event = '';

	private $ecommerce = [];

	private $user_data = [];

	public function __construct()
	{
		$customer = Context::getContext()->customer;

		if ( $customer->isLogged() ){
			$this->addToUserBody('email_hashed', hash('sha256', $customer->email));
			$this->addToUserBody('email', $customer->email);
		}
	}

	public function getName()
	{
		return $this->event;
	}

	public function setName(string $name)
	{
		$this->event = $name;
	}

	public function setEcommerceBody(array $newBody)
	{
		$this->ecommerce = $newBody;
	}

	public function addToEcommerceBody(string $name, $value)
	{
		$this->ecommerce[$name] = $value;
	}

	public function setUserBody(array $newUserData)
	{
		$this->user_data = $newUserData;
	}

	public function addToUserBody(string $name, $value)
	{
		$this->user_data[$name] = $value;
	}

	public function getArrayObject()
	{
		return get_object_vars($this);
	}
}