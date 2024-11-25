<?php

namespace Silverstripe\Quantum\Admin;

use SilverStripe\Admin\ModelAdmin;
use Silverstripe\Quantum\Model\DataSource;

class DataSourceAdmin extends ModelAdmin
{

    private static $menu_title = "Data Sources";

	private static $url_segment = "data-sources";

	private static $menu_priority = 100;

	private static $url_priority = 30;

    private static $managed_models = [
        DataSource::class
    ];

    public function init() {
		parent::init();
	}

}
