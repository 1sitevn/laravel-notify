<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 22:32
 */

namespace OneSite\Notify\Http\Controllers;


/**
 * Class Test
 * @package OneSite\Notify\Http\Controllers
 */
class Test extends Base
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('test');
    }
}
