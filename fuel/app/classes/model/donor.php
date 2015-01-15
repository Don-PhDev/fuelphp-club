<?php

class Model_Donor extends \Orm\Model
{
	protected static $_table_name = 'donors';

	protected static $_properties = array(
		'id',
		'name',
		'image_filename',
		'organization_name',
		'organization_logo',
		'organization_text',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array('\Orm\Observer_CreatedAt', '\Orm\Observer_UpdatedAt');

    protected static $_has_many = array(
        'donations' => array(
            'key_from' => 'id',
	    	'model_to' => 'Model_Donors_Nonprofits_Donation',
	    	'key_to' => 'donor_id',
	        'cascade_save' => true,
	        'cascade_delete' => true,
        ),
    );

    protected static $_many_many = array(
        'nonprofits' => array(
            'key_through_from' => 'donor_id',
            'table_through' => 'donors_nonprofits',
            'key_through_to' => 'nonprofit_id',
            'model_to' => 'Model_Nonprofit',
            'cascade_save' => true,
        ),
    );

    public static function validate($factory = null)
    {
        $val = Validation::forge($factory);

        $val->add_field('name', "Name", 'required|min_length[3]|max_length[45]');
        $val->add_field('organization_name', "Organization", 'required|min_length[3]|max_length[45]');
        $val->add_field('about', "About", 'required|min_length[45]');

        return $val;
    }

	public static function validate_donation($factory = null)
	{
		$val = Validation::forge($factory);

		$val->add_field('recipient', "Recipient", 'required|min_length[3]|max_length[45]');
		$val->add_field('amount', "Amount", 'required|numeric_min[10000]|numeric_max[10000000]');

		return $val;
	}

    private static function _build_donation_data($result)
    {
        $data = array();

        foreach ($result as $row) {
            $arr_amount = explode('~', $row['csv_amount']);
            $arr_created_at = explode('~', $row['csv_created_at']);
            $arr_nonprofit = explode('~', $row['csv_nonprofit']);

            $arr_consolidated = array();

            foreach ($arr_created_at as $n => $created_at) {
                $arr_consolidated[$created_at] = array(
                    'nonprofit_name' => $arr_nonprofit[$n],
                    'created_at' => $created_at,
                    'amount' => $arr_amount[$n],
                );
            }

            ksort($arr_consolidated);

            $data[] = array(
                'name' => $row['name'],
                'image_filename' => $row['image_filename'],
                'organization_text' => $row['organization_text'],
                'donations' => $arr_consolidated,
            );
        }

        return $data;
    }

    public static function get_matching_donor($search_donor)
    {
        $sql = "
            SELECT d.name,
                d.image_filename,
                d.organization_text,
                GROUP_CONCAT(dl.amount SEPARATOR '~') AS csv_amount,
                GROUP_CONCAT(dl.created_at SEPARATOR '~') AS csv_created_at,
                GROUP_CONCAT(np.name SEPARATOR '~') AS csv_nonprofit
            FROM donors AS d
                LEFT JOIN donors_nonprofits_donations AS dl ON dl.donor_id = d.id
                LEFT JOIN nonprofits AS np ON np.id = dl.nonprofit_id
            WHERE d.name LIKE '$search_donor%'
            GROUP BY d.id
            ORDER BY d.name
        ";

        $result = DB::query($sql)->execute();
        return self::_build_donation_data($result);
    }
    
    public static function get_matching_donation_with_amount($search_amount)
    {
        $sql = "
            SELECT d.name,
                d.image_filename,
                d.organization_text,
                GROUP_CONCAT(dl.amount SEPARATOR '~') AS csv_amount,
                GROUP_CONCAT(dl.created_at SEPARATOR '~') AS csv_created_at,
                GROUP_CONCAT(np.name SEPARATOR '~') AS csv_nonprofit
            FROM donors AS d
                LEFT JOIN donors_nonprofits_donations AS dl ON dl.donor_id = d.id
                LEFT JOIN nonprofits AS np ON np.id = dl.nonprofit_id
            WHERE dl.amount = '$search_amount%'
            GROUP BY d.id
            ORDER BY d.name
        ";

        $result = DB::query($sql)->execute();
        return self::_build_donation_data($result);
    }

    public static function get_matching_donor_and_amount($search_donor, $search_amount)
    {
        $sql = "
            SELECT d.name,
                d.image_filename,
                d.organization_text,
                GROUP_CONCAT(dl.amount SEPARATOR '~') AS csv_amount,
                GROUP_CONCAT(dl.created_at SEPARATOR '~') AS csv_created_at,
                GROUP_CONCAT(np.name SEPARATOR '~') AS csv_nonprofit
            FROM donors AS d
                LEFT JOIN donors_nonprofits_donations AS dl ON dl.donor_id = d.id
                LEFT JOIN nonprofits AS np ON np.id = dl.nonprofit_id
            WHERE d.name LIKE '$search_donor%' 
                AND dl.amount = '$search_amount%'
            GROUP BY d.id
            ORDER BY d.name
        ";

        $result = DB::query($sql)->execute();
        return self::_build_donation_data($result);
    }
}

// eof
