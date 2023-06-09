<?php

namespace App\Http\Controllers;

use App\Models\FooterLinksStep0;
use App\Models\JoinOurNetworkStep0;
use App\Models\Page;
use App\Models\MenuButtonStep0;
use App\Models\Language;
use App\Models\Module;
use App\Models\Bsc;
use App\Models\Bsw;
use App\Models\Partner;
use App\Widget;

use Illuminate\Http\Request;


class FrontController extends Controller {
	public function __construct() {
		$requesPath = urldecode(\Request::path());

		$activePageArray = explode('/', $requesPath);

		$activePage = $activePageArray[1];

		config(['activePageAlias' => $activePage]);


		Bsc::initConfigs();
		Bsw::initConfigs();
    }
	

    public static function getDefaultData($lang, $page, $activeInfoBlockModel = false) {
		\App::setLocale($lang->title);


		if($activeInfoBlockModel) {
			Language::setActiveInfoBlock($activeInfoBlockModel);
		} else {
			Language::setActiveInfoBlock($page);
		}
		

		$widgetGetVisibility = Widget::getVisibility($page);

		if(config('constants.year_of_site_creation') < date('Y')) {
			config(['constants.year_of_site_creation' => config('constants.year_of_site_creation').' - '.date('Y')]);
		}

		$data = ['page' => $page,
				'homePage' => Page::firstWhere('slug', 'home'),
				'language' => $lang,
				'languages' => Language::where('disable', '0')->orderByDesc('rang')->get(),
				'menuButtons' => MenuButtonStep0::with(['page', 'menuButtonStep1', 'menuButtonStep1.page'])->orderByDesc('rang')->get(),
				'widgetGetVisibility' => $widgetGetVisibility,
				'joinOurNewtork' => JoinOurNetworkStep0::first(),
				'partners' => Partner::orderByDesc('rang')->get(),
				'footerLinks' => FooterLinksStep0::with(['page', 'footerLinksStep1', 'footerLinksStep1.page'])->orderByDesc('rang')->get(),
				'applicationPage' => Page::firstWhere('slug', 'application'),
				'termsPage' => Page::firstWhere('slug', 'terms'),
				'privacyPage' => Page::firstWhere('slug', 'privacy'),
			];
		
		return $data;
	}


    public static function getPage($lang, $pageAlias) {
		$language = Language::firstWhere('title', $lang);

		if($language) {
			$page = Page::firstWhere('alias_'.$language->title, $pageAlias);

			if($page) {
				return view('static', self::getDefaultData($language, $page, $page));
			} else {
				abort(404);
			}
		} else {
			abort(404);
		}
	}
}