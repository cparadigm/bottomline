<?php

$this->startSetup();

try {

	/**
	 * Add quote/order item column: 'subscription period'
	 */
	
	$this->addAttribute(
		'order_item',
		'subscription_period',
		array(
			'type' => 'int'
		)
	);

	$this->addAttribute(
		'quote_item',
		'subscription_period',
		array(
			'type' => 'int'
		)
	);

	$this->addAttribute(
		'invoice_item',
		'subscription_period',
		array(
			'type'		=> 'int'
		)
	);
	
	/**
	 * Add quote/order item column: 'subscription item'
	 */
	
	$this->addAttribute(
		'order_item',
		'is_subscription',
		array(
			'type'		=> 'int',
			'default'	=> 1
		)
	);

	$this->addAttribute(
		'quote_item',
		'is_subscription',
		array(
			'type'		=> 'int',
			'default'	=> 1
		)
	);

	$this->addAttribute(
		'invoice_item',
		'is_subscription',
		array(
			'type'		=> 'int',
			'default'	=> 1
		)
	);

}
catch( Exception $e ) {
	echo $e->getMessage();
}

$this->endSetup();
