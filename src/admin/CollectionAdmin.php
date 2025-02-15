<?php

namespace Silverstripe\Quantum\Admin;

use SilverStripe\Admin\ModelAdmin;
use Silverstripe\Quantum\Model\Collection;

class CollectionAdmin extends ModelAdmin
{

    private static $menu_title = "Collections";

	private static $url_segment = "collections";

	private static $menu_priority = 100;

	private static $url_priority = 30;

    private static $managed_models = [
        Collection::class
    ];

    public function init() {
		parent::init();
	}

}
