<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function __construct(){
        parent::__construct();
        $this->data['menu'] = 'home';
    }

    public function index(){

        $this->data['title'] = lang('Common.about');
        $this->data['heading'] = lang('Common.about');
        return view('home', $this->data);
    }
}
