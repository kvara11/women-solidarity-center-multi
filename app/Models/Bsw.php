<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App;

class Bsw extends Model {
	public static function initConfigs() {
		foreach(Bsw::all() as $data) {
			config(['bsw.'.$data->system_word => $data->{ 'word_'.App::getLocale() }]);
		}
	}


	public static function getFullData() {
		$bsws = (object)[];

		foreach(Bsw::all() as $data) {
			$bsws->{ $data->system_word } = $data->{ 'word_'.App::getLocale() };
		}
		
		return $bsws;
	}
}
