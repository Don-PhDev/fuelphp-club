<?php
/**
 * myrules.php
 */

class MyRules
{
    // note this is a static method
    public static function _validation_unique($val, $options)
    {
        list($table, $field) = explode('.', $options);

        $result = DB::select("LOWER (\"$field\")")
            ->where($field, '=', Str::lower($val))
            ->from($table)->execute();

        if ($result->count() == 0)
            return true;

        Validation::active()->set_message('unique', '"' . $val . '" was already taken by another user.');
        return false;
    }

    /**
     * Should be the same
     */
    private $first_same = null;

    public function _validation_should_be_the_same($val)
    {
        $val = trim($val);

        if (is_null($this->first_same))
        {
            $this->first_same = $val;
            return true;
        }

        if ($this->first_same != $val)
        {
            Validation::active()->set_message('should_be_the_same', ':label should be the same');
            return false;
        }
        
        return true;
    }

    /**
     * atleast_one_but_not_both
     */
	private $first_of_one_but_not_both = null;

	public function _validation_atleast_one_but_not_both($val)
	{
        $val = (double) $val;

		if (is_null($this->first_of_one_but_not_both))
		{
			$this->first_of_one_but_not_both = $val;
			return true;
		}

        if ($this->first_of_one_but_not_both && $val || ! $this->first_of_one_but_not_both && ! $val)
        {
            Validation::active()->set_message('atleast_one_but_not_both', 'Either :label must be supplied but not both.');
            return false;
        }
        
        if ($val) return true;
	}

    private $user_edit_id = null;
    public function _validation_must_not_duplicate_existing_user($val)
    {
        $val = trim($val);

        if (is_null($this->user_edit_id))
        {
            $this->user_edit_id = $val;
            return true;
        }

        $conflict = Model_User::query()
            ->where(array('id', '!=', $this->user_edit_id))
            ->where('username', $val)
            ->count();

        if (0 == $conflict)
            return true;

        Validation::active()->set_message('must_not_duplicate_existing_user', 'Username "' . $val . '" was already taken by another user.');
        return false;
    }

    private $email_edit_id = null;
    public function _validation_must_not_duplicate_existing_email($val)
    {
        $val = trim($val);

        if (is_null($this->email_edit_id))
        {
            $this->email_edit_id = $val;
            return true;
        }

        $conflict = Model_User::query()
            ->where(array('id', '!=', $this->email_edit_id))
            ->where('email', $val)
            ->count();

        if (0 == $conflict)
            return true;

        Validation::active()->set_message('must_not_duplicate_existing_email', 'E-mail "' . $val . '" was already taken by another user.');
        return false;
    }

    /**
     * payment_must_suffice
     */
    private $available_points = null;
    private $discount_percentage = null;
    private $discount_amount = null;
    private $purchases = null;
    private $cash_payment = null;
    private $points_payment = null;

    public function _validation_payment_must_suffice($val)
    {
        $val = (double) $val;

        if (is_null($this->available_points))
        {
            $this->available_points = $val;
            return true;
        }

        if (is_null($this->discount_percentage))
        {
            $this->discount_percentage = $val;
            return true;
        }

        if (is_null($this->discount_amount))
        {
            $this->discount_amount = $val;
            return true;
        }

        if (is_null($this->purchases))
        {
            $this->purchases = $val;
            return true;
        }

        if (is_null($this->cash_payment))
        {
            $this->cash_payment = $val;
            return true;
        }

        if (is_null($this->points_payment))
        {
            $this->points_payment = $val;
        }

        /**
         * Check that we do not accept duplicate discounts
         */
        if ($this->discount_percentage != 0 && $this->discount_amount != 0)
        {
            Validation::active()->set_message('payment_must_suffice', 'Cannot accept discount percentage (' . $this->discount_percentage . '%) and discount amount (' . $this->discount_amount . ' USD) at the same time.');
            return false;
        }

        /**
         * Check that Customer is not using more points more than his available points
         */
        if ($this->points_payment > $this->available_points)
        {
            Validation::active()->set_message('payment_must_suffice', 'Points payment is short by ' . ($this->points_payment - $this->available_points) . '.');
            return false;
        }

        /**
         * Let's do some computation before proceeding to validation
         */
        $total_payment = $this->cash_payment + $this->points_payment;

        /**
         * Now, let's compute how much is the Customer discount in USD
         */
        if ($this->discount_percentage > 0 && $this->discount_percentage <= 100)
        {
            $member_discount_usd = $this->purchases * ($this->discount_percentage / 100);
        }
        else if ($this->discount_amount > 0)
        {
            if ($this->discount_amount > $this->purchases)
                $member_discount_usd = $this->purchases;
            else
                $member_discount_usd = $this->discount_amount;
        }
        else
        {
            $member_discount_usd = 0;
        }

        $discounted_purchases = $this->purchases - $member_discount_usd;

        /**
         * Now, back to validation
         * Check that Payment is enough
         */
        if ($total_payment < $discounted_purchases)
        {
            Validation::active()->set_message('payment_must_suffice', 'Payment is not enough, short by ' . ($discounted_purchases - $total_payment) 
                . ($member_discount_usd > 0 ? " (considering discount of $member_discount_usd USD.)" : "."));
            return false;
        }

        /**
         * Check that Payment is not too much
         */
        if ($total_payment > $discounted_purchases)
        {
            Validation::active()->set_message('payment_must_suffice', 'The member paid too much, over by ' . ($total_payment - $discounted_purchases)
                . ($member_discount_usd > 0 ? " (considering discount of $member_discount_usd USD.)" : "."));
            return false;
        }

        return true;
    }
}