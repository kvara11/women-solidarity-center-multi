<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\MenuButtonStep0;
use App\Models\MenuButtonStep1;
use App\Models\Language;
use App\Models\Module;
use App\Models\Bsc;
use App\Models\Bsw;
use App\Models\News;
use App\Models\PhotoGalleryCategory;
use App\Models\Partner;
use App\Widget;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


class PageController extends Controller {
	public static function getDefaultData($lang, $page, $alias0Model = false, $alias1Model = false, $alias2Model = false, $alias3Model = false) {
		Language :: setLang($lang);
		Language :: setPage($page);

		App :: setLocale($lang -> title);

		if($alias0Model) {
			Language :: setAlias0Model($alias0Model);
		}

		if($alias1Model) {
			Language :: setAlias1Model($alias1Model);
		}

		if($alias2Model) {
			Language :: setAlias2Model($alias2Model);
		}

		if($alias3Model) {
			Language :: setAlias3Model($alias3Model);
		}


		Page :: setLang($lang -> title);

		MenuButtonStep0 :: setLang($lang -> title);
		MenuButtonStep0 :: setPage($page -> alias);

		MenuButtonStep1 :: setLang($lang -> title);
		MenuButtonStep1 :: setPage($page -> alias);

		Partner :: setLang($lang -> title);

		$widgetGetVisibility = Widget :: getVisibility($page);

		$bsc = Bsc :: getFullData();
		$copyrightDate = $bsc -> year_of_site_creation;

		if($bsc -> year_of_site_creation < date('Y')) {
			$copyrightDate .= ' - '.date('Y');
		}

		$data = ['page' => $page,
				'language' => $lang,
				'languages' => Language :: where('published', '1') -> orderByDesc('rang') -> get(),
				'menuButtons' => MenuButtonStep0 :: where('published', '1') -> orderByDesc('rang') -> get(),
				'bsc' => Bsc :: getFullData(),
				'bsw' => Bsw :: getFullData($lang -> title),
				'registrationUrl' => '/'.$lang -> title.'/'.Page :: where('slug', 'registration') -> first() -> alias,
				'authorizationUrl' => '/'.$lang -> title.'/'.Page :: where('slug', 'authorization') -> first() -> alias,
				'partners' => Partner :: orderByDesc('rang') -> get(),
				'widgetGetVisibility' => $widgetGetVisibility,
				'copyrightDate' => $copyrightDate];
		
		return $data;
	}



	public static function test() {
		// dd(32);

		$page = Page :: where('slug', 'registration') -> first();
        $language = Language :: where('title', 'ge') -> first();

        Page :: setLang($language -> title);

		return view('auth.m-register', PageController :: getDefaultData($language, $page));
	}




	
	public function getDefaultPageWithDefaultLanguage() {
		$language = Language :: where('like_default', 1) -> first();

		$page = Page :: where('like_default', 1) -> first();

		if($language && $page) {
			$page_template = 'static';
			
			$active_module = Module :: where('page', $page -> id) -> first();
			
			if($active_module) {
				$page_template = 'modules.'.$active_module -> alias.'.step0';
			}

			return view($page_template, self :: getDefaultData($language, $page));
		} else {
			abort(404);
		}
	}

	
	public function getDefaultPage($lang) {
		$language = Language :: where('title', $lang) -> first();

		if($language) {
			$page = Page :: where('like_default', 1) -> first();

			if($page) {
				$page_template = 'static';

				$active_module = Module :: where('page', $page -> id) -> first();
			
				if($active_module) {
					$page_template = 'modules.'.$active_module -> alias.'.step0';
				}
				
				return view($page_template, self :: getDefaultData($language, $page));
			} else {
				abort(404);
			}
		} else {
			abort(404);
		}
	}


	public static function getPage($lang, $pageAlias) {
		$language = Language :: where('title', $lang) -> first();

		if($language) {
			$page = Page :: where('alias_'.$language -> title, $pageAlias) -> first();

			if($page) {
				return view('static', self :: getDefaultData($language, $page));
			} else {
				abort(404);
			}
		} else {
			abort(404);
		}
	}
}