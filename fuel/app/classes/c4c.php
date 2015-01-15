<?php
/**
 * c4c.php
 */

class C4c
{
    const THEME = 'abaca';
    
    const RECAPTCHA_PRIVATE_KEY = "6LeV0NoSAAAAABHkIng-biUPhOJ0jKh-o77xDl_5";
    const RECAPTCHA_PUBLIC_KEY = "6LeV0NoSAAAAANdqZY7Ayqf5_7Co2YbBz3VrZSEP";

    const COMMONLY_SELECTED_COUNTRIES = "Canada, United States, United Kingdom";

    const NO_IMAGE = "/assets/img/no_image.jpg";

    public static function javascript()
    {
        return array(
            'constants.js',

            'cufon-yui.js',
            'calibri.font.js',
            'cufon_elements.js',
            'modernizr.custom.28468.js',
            'jquery.cslider.js?a=15feb2013',
            'easytabs.js',
            'jquery.colorbox.js',
            'spin.min.js',
            'jquery.spin.js',
        );
    }

    public static function admin_menu()
    {
        $menu = array();

        $menu[] = array(array("admin", "home", "Dashboard"));

        $menu[] = array(
            array("", "window", "Manage Celebrities"),
            array("admin/celebrities", "Celebrities"),
            array("admin/celebrities/create", "Add A Celebrity"),
        );

        $menu[] = array(
            array("", "window", "Manage Members"),
            array("admin/members", "Members"),
        );

        $menu[] = array(
            array("", "window", "Daily Mail"),
            array("admin/iemail/create", "Add Mail"),
            array("admin/iemail", "Set Active Mail"),
        );

        $menu[] = array(
            array("", "window", "Donors"),
            array("admin/donor", "View Donors"),
            array("admin/donor/create", "Add a Donor"),
        );

        $menu[] = array(
            array("", "window", "Benefactors"),
            array("admin/benefactor", "View Benefactors"),
            array("admin/benefactor/create", "Add a Benefactor"),
        );

        $menu[] = array(array("admin/comment", "window", "Comments"));

        $set_selected = false;
        for ($x = count($menu) - 1; $x >= 0; $x--)
        {
            foreach ($menu[$x] as $item)
            {
                if ('/' . $item[0] == $_SERVER['REQUEST_URI'])
                    $set_selected = true;
            }

            if ($set_selected)
            {
                $menu[$x][0][] = true;
                break;
            }
        }

        if ( ! $set_selected)
            $menu[0][0][] = true;

        return $menu;
    }
}

// eof
