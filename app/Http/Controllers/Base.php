<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 22:31
 */

namespace OneSite\Notify\Http\Controllers;


use Illuminate\Routing\Controller;


/**
 * Class Base
 * @package OneSite\Notify\Http\Controllers
 */
class Base extends Controller
{
    /**
     * Base constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            return $next($request);
        });

        view()->addLocation($this->getViewPath());
    }

    /**
     * @return string
     */
    private function getViewPath()
    {
        return base_path('vendor/onesite/notify/resources/views');
    }
}
