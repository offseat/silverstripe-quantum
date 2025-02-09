<?php

namespace Silverstripe\Quantum\Admin;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\View\Requirements;

class DashboardAdmin extends LeftAndMain
{

    private static $menu_title = "Dashboard";

	private static $url_segment = "dashboard";

	private static $menu_priority = 1000;

	private static $url_priority = 30;

    private static $menu_icon_class = 'font-icon-dashboard';

 

    public function init() {
		parent::init();
        Requirements::css("_resources/stripe-quantum/client/dist/assets/index.css");
        Requirements::javascript("_resources/stripe-quantum/client/dist/assets/index.js");
        $this->extend('updateInit');
	}

    public function Content()
    {
        return $this->renderWith('Silverstripe/Quantum/Admin/DashboardAdmin_Content');
    }
}
