<?php
class Controller_Donor extends Controller_Base
{
	public function action_index()
	{
        $donors = null;

        if ($_GET)
        {
            $search_donor = trim(Input::get('search_donor'));
            $search_amount = trim(Input::get('search_amount'));

            if ($search_donor && $search_amount)
                $donors = Model_Donor::get_matching_donor_and_amount($search_donor, $search_amount);

            else if ($search_donor)
                $donors = Model_Donor::get_matching_donor($search_donor);

            else if ($search_amount)
                $donors = Model_Donor::get_matching_donation_with_amount($search_amount);

            /**
             * Sticky form
             */
            $this->value['search_donor'] = $search_donor;
            $this->value['search_amount'] = $search_amount;
        }

		$this->reddo('content/donor_search.twig', array(
            'donors' => $donors,
            'getted' => (Input::get('go') == '1'),
		));
	}
}
