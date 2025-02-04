<?php

namespace SilverStripe\Quantum\Controller;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Quantum\Auth\Security\PermissionCode;
use Silverstripe\Quantum\Model\Collection;
use SilverStripe\Security\Security;

class APIController extends Controller
{
    private static $url_segment = "api/v1/";

    private static $url_handlers = [
        '$@' => 'index',
    ];

    public function index()
    {
        $member = Security::getCurrentUser();

        if (!$member) {
            return HTTPResponse::create('{\'message\':\'unauthorized action\'}');
        }

        $url = $this->getRequest()->getURL();
        $requestPath = str_replace( self::$url_segment, '', $url);

        if ($requestPath && $requestPath !== '') {
            $collection = Collection::get()->filter('Route', $requestPath)->First();

            if (!$collection) {
                return HTTPResponse::create('{\'message\':\'not found\'}');
            }

            if (class_exists(PermissionCode::class) && !$collection->checkPermission()) {
                return HTTPResponse::create('{\'message\':\'unauthorized action\'}');
            }

            $response = $collection->getPreparedData();
        
            if ($response) {
                return HTTPResponse::create(json_encode($response));
            }
        }

        return HTTPResponse::create('{\'message\':\'not found\'}');
    }
}