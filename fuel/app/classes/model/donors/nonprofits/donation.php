<?php

class Model_Donors_Nonprofits_Donation extends \Orm\Model
{
	protected static $_table_name = 'donors_nonprofits_donations';

	protected static $_properties = array(
		'id',
		'donor_id',
		'nonprofit_id',
		'amount',
		'created_at',
	);

	protected static $_observers = array('\Orm\Observer_CreatedAt');

    protected static $_belongs_to = array(
        'nonprofit' => array(
            'key_from' => 'nonprofit_id',
            'model_to' => 'Model_Nonprofit',
            'key_to' => 'id',
        )
    );
}
