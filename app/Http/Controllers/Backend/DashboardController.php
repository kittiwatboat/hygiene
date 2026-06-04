<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $prefix = 'backend';
    protected $segment = 'webpanel';
    protected $controller = 'dashboard';
    protected $folder = 'dashboard';

    public function index(Request $request)
    {
        // dd('aaa');
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "Dashboard", "last" => 0],
        ];
        return view("$this->prefix.page.$this->folder.index", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'navs' => $navs,
        ]);
    }
}
