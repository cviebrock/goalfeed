<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


class AdminController extends Controller {

	public function __construct() {

		$this->middleware('admin');
	}

	//
	public function getAdminDash() {

		return view('admin.landing');
	}
}
