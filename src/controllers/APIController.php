<?php

namespace SilverStripe\Quantum\Controller;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPResponse;
use Silverstripe\Quantum\Model\DataSource;
use SilverStripe\Security\Security;

class APIController extends Controller
{
    private static $url_segment = "api/v1/";

    private static $url_handlers = [
        '$@' => 'index',
    ];

    public function index()
    {
        if (!Security::getCurrentUser()) {
            return HTTPResponse::create('{\'message\':\'unauthorized action\'}');
        }

        $url = $this->getRequest()->getURL();
        $request = str_replace( self::$url_segment, '', $url);

        if ($request && $request !== '') {
            $dataSource = DataSource::get()->filter('Route', $request)->First();

            if ($dataSource) {
                $response = $dataSource->getPreparedData();
            
                if ($response) {
                    return HTTPResponse::create(json_encode($response));
                }
            }
        }

        return HTTPResponse::create('{\'message\':\'not found\'}');
    }
}