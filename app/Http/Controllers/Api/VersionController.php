<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class VersionController extends Controller
{
	public function show()
	{
		return response()->json([
			'version' => Setting::get('app_version', '1'),
		]);
	}
}
