<?php
namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleLevel;
use App\Models\ModuleStep;
use App\Models\ModuleBlock;
use App\Models\Language;
use App\Models\Bsc;
use App\Models\Bsw;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic;
use DB;
use Session;
use App;
use Storage;


class ACoreControllerStep0 extends AController {
    public function get($moduleAlias) {
		$module = Module::where('alias', $moduleAlias)->first();
		$moduleLevel = ModuleLevel::where('top_level', $module->id)->orderBy('rang', 'desc')->first();
		$moduleStep = ModuleStep::where('top_level', $moduleLevel->id)->orderBy('rang', 'desc')->get();


		// dd($moduleStep);
		$collection = collect([]);
		$collectionForTags = collect([]);

		foreach($moduleStep as $moduleStepData) {
			$use_for_tags = 'id';
			
			$moduleBlock = ModuleBlock::where('top_level', $moduleStepData->id)->where('a_use_for_tags', 1)->first();

			if($moduleBlock) {
				$use_for_tags = $moduleBlock->db_column;
				
				if($moduleBlock->type === 'alias' || $moduleBlock->type === 'input_with_languages' || $moduleBlock->type === 'editor_with_languages') {
					$use_for_tags .= '_'.App::getLocale();
				}
			}
			
			
			$moduleBlockForSort = ModuleBlock::where('top_level', $moduleStepData->id)->where('a_use_for_sort', 1)->first();
			
			$orderBy = 'id';
			$sortBy = 'asc';

			if($moduleBlockForSort) {
				$orderBy = $moduleBlockForSort->db_column;

				if($moduleBlockForSort->type === 'alias' || $moduleBlockForSort->type === 'input_with_languages' || $moduleBlockForSort->type === 'editor_with_languages') {
					$orderBy .= '_'.App::getLocale();
				}

				if($moduleBlockForSort->sort_by_desc) {
					$sortBy = 'desc';
				}
			}


			$imageFormat = 'jpg';

			$moduleBlockForImage = ModuleBlock::where('top_level', $moduleStepData->id)->where('type', 'image')->first();

			if($moduleBlockForImage) {
				$imageFormat = $moduleBlockForImage->file_format;
			}


			$collection->add(DB::table($moduleStepData->db_table)->orderBy($orderBy, $sortBy)->get());
			$collectionForTags->add($use_for_tags);
		}




		$data = array_merge(self::getDefaultData(), ['module' => $module,
													'moduleStep' => $moduleStep,
													'collection' => $collection,
													'collectionForTags' => $collectionForTags]);


		// $data = array_merge(self::getDefaultData(), ['module' => $module,
		// 											'moduleStep' => $moduleStep,
		// 											'moduleSteps' => ModuleStep::where('top_level', $module->id)->orderBy('rang', 'desc')->get(),
		// 											'moduleStepData' => DB::table($moduleStep->db_table)->orderBy($orderBy, $sortBy)->get(),
		// 											'imageFormat' => $imageFormat,
		// 											'sortBy' => $orderBy,
		// 											'use_for_tags' => $use_for_tags]);

		return view('modules.core.step0', $data);
	}


	public function add($moduleAlias, $moduleStepId) {
		$moduleStep = ModuleStep::find($moduleStepId);

		$data = array_merge(self::getDefaultData(), [
														'moduleStep' => $moduleStep
													]);


		Session::keep('file_id');
														
		return view('modules.core.add_step_0', $data);
	}


	public function insert(Request $request, $moduleAlias, $moduleStepId) {
		$module = Module::firstWhere('alias', $moduleAlias);
		$moduleStep = ModuleStep::find($moduleStepId);


		if(!Session::has('file_id')) {
			Session::flash('file_id', rand(1000000,9999999));
		} else {
			Session::reflash();
		}

		$id = Session::get('file_id');


		// Image - uploading image without checking it passed validation or not
			foreach($moduleStep->moduleBlock as $data) {
				if($data->type === 'image') {
					if($request->hasFile($data->db_column)) {
						$prefix = '';

						if($data->prefix) {
							$prefix = $data->prefix.'_';
						}
						
						$validator = Validator::make($request->all(), array(
							$data->db_column => 'mimes:jpeg,jpg,png,gif|required|max:10000'
						));
						
						if($validator->fails()) {
							return redirect()->route('coreAddStep0', [$module->alias, $moduleStep->id])->withErrors($validator)->withInput();
						}

						$request->file($data->db_column)->storeAs('public/images/modules/'.$module->alias.'/step_0', $prefix.$id.'.'.$data->file_format);	
						
						$imagePath = 'app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'.'.$data->file_format;

						$image = ImageManagerStatic::make(storage_path($imagePath));
						$width = $image->width();
						$height = $image->height();

						if($data->fit_type === 'fit') {
							$image->fit($data->image_width,
											$data->image_height,
											function() {},
											$data->fit_position);
						}
						
						if($data->fit_type === 'resize') {
							$image->resize($data->image_width,
												$data->image_height,
												function ($constraint) {
												$constraint->aspectRatio();
												});
							
							if($width < $data->image_width && $height < $data->image_height) {
								$image = ImageManagerStatic::make(storage_path($imagePath));																																			
							}
						}
						
						if($data->fit_type === 'resize_with_bg') {
							if($width > $data->image_width || $height > $data->image_height) {
								$image->resize($data->image_width,
													$data->image_height,
													function ($constraint) {
													$constraint->aspectRatio();
													});																																	
							}

							$image->resizeCanvas($data->image_width, $data->image_height, 'center', false, '#FFFFFF');
						}

						if($data->fit_type === 'default') {
							$image = ImageManagerStatic::make(storage_path($imagePath));
						}

						$image->save();

						for($i = 1; $i < 4; $i++) {
							if($data->{'prefix_'.$i}) {
								$request->file($data->db_column)->storeAs('public/images/modules/'.$module->alias.'/step_0', $prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format );

								if($data->{'fit_type_'.$i} === 'fit') {
									$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format))->fit($data->{'image_width_'.$i},
																																															$data->{'image_height_'.$i},
																																															function() {},
																																															$data->fit_position);
								}
								
								if($data->{'fit_type_'.$i} === 'resize') {
									$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format))->resize($data->{'image_width_'.$i},
																																															$data->{'image_height_'.$i},
																																															function ($constraint) {
																																																$constraint->aspectRatio();
																																															});

									
									$width = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format))->width();
									$height = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format))->height();

									if($width < $data->{'image_width_'.$i} && $height < $data->{'image_height_'.$i}) {
										$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format));																																			
									}
								}

								if($data->fit_type === 'resize_with_bg') {
									if($width > $data->{'image_width_'.$i} || $height > $data->{'image_height_'.$i}) {
										$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format))->resize($data->{'image_width_'.$i},
																																																									$data->{'image_height_'.$i},
																																																									function ($constraint) {
																																																									$constraint->aspectRatio();
																																																									});																																	
									}
									
									$image-> resizeCanvas($data->{'image_width_'.$i}, $data->{'image_height_'.$i}, 'center', false, '#FFFFFF');
								}

								if($data->{'fit_type_'.$i} === 'default') {
									$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format));
								}

								$image->save();
							}
						}
					}
				}
			}
		//


		// File - uploading file without checking it passed validation or not
			foreach($moduleStep->moduleBlock as $data) {
				if($data->type === 'file') {
					if($request->hasFile($data->db_column)) {
						$prefix = '';

						if($data->prefix) {
							$prefix = $data->prefix.'_';
						}

						$validator = Validator::make($request->all(), array(
							$data->db_column => "required|mimes:".$data->file_format."|max:10000"
						));

						if($validator->fails()) {

							return redirect()->route('coreEditStep0', array($module->alias, $id))->withErrors($validator)->withInput();
						}
						
						$request->file($data->db_column)->storeAs('public/images/modules/'.$module->alias.'/step_0', $prefix.$id.'.'.$data->file_format);
					}
				}
			}
		// 


		// Validation
			$validationArray = [];

			foreach($moduleStep->moduleBlock as $data) {
				$prefix = '';

				if($data->prefix) {
					$prefix = $data->prefix.'_';
				}
				
				$imagePath = storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'.'.$data->file_format);
				
				if($data->type === 'image' || $data->type === 'file') {
					if(!file_exists($imagePath)){
						$validationArray[$data->db_column] = $data->validation;
					}
				} else {
					if($data->type !== 'alias' && $data->type !== 'input_with_languages' && $data->type !== 'editor_with_languages') {
						$validationArray[$data->db_column] = $data->validation;
					} else {
						$validationData = [];

						foreach(Language::where('disable', 0)->get() as $langData) {
							$validationArray[$data->db_column.'_'.$langData->title] = $data->validation;
						}
					}
				}
				
			}

			$validator = Validator::make($request->all(), $validationArray);

			if($validator->fails()) {
				return redirect()->route('coreAddStep0', array($module->alias, $moduleStep->id))->withErrors($validator)->withInput();
			}
		//


		$insertQuery = [];
		
		foreach($moduleStep->moduleBlock as $data) {
			if($data->type !== 'image'
				&& $data->type !== 'file'
				&& $data->type !== 'rang'
				&& $data->type !== 'alias'
				&& $data->type !== 'input_with_languages'
				&& $data->type !== 'editor_with_languages'
				&& $data->type !== 'checkbox'
				&& $data->type !== 'multiply_checkboxes_with_category') {
				$insertQuery[$data->db_column] = (!is_null($request->input($data->db_column)) ? $request->input($data->db_column) : '');
			}
			
			if($data->type === 'alias') {
				foreach(Language::where('disable', 0)->get() as $langData) {
					$value = $request->input($data->db_column.'_'.$langData->title);
					$value = preg_replace("/[^A-ZА-Яა-ჰ0-9 -]+/ui",
											'',
											$value);

					$value = mb_strtolower(trim($value));

					$symbols = array('     ', '    ', '   ', '  ', ' ', '.', ',', '!', '?', '=', '#', '%', '+', '*', '/', '_', '\'', '"');

					$replace_symbols = array('-', '-', '-', '-', '-', '-', '-', '', '-', '-', '', '', '-', '', '-', '-', '', '');

					$value = str_replace($symbols,
											$replace_symbols,
											$value);
											
					$insertQuery[$data->db_column.'_'.$langData->title] = $value;
				}
			}

			if($data->type === 'input_with_languages') {
				foreach(Language::where('disable', 0)->get() as $langData) {
					$insertQuery[$data->db_column.'_'.$langData->title] = $request->input($data->db_column.'_'.$langData->title);
				}
			}

			if($data->type === 'editor_with_languages') {
				foreach(Language::where('disable', 0)->get() as $langData) {
					$insertQuery[$data->db_column.'_'.$langData->title] = $request->input($data->db_column.'_'.$langData->title);
				}
			}

			if($data->type === 'checkbox') {
				$insertQuery[$data->db_column] = (!is_null($request->input($data->db_column)) ? $request->input($data->db_column) : 0);
			}


			$checkboxString = '';

			if($data->type === 'multiply_checkboxes_with_category') {
				if($request->input($data->db_column)) {
					for($i = 0; $i < count($request->input($data->db_column)); $i++) {
						$checkboxString .= $request->input($data->db_column)[$i].',';
					}
				}
				
				$insertQuery[$data->db_column] = $checkboxString;
			}
			

			$multiplyCheckboxString = '';

			if($data->type === 'multiply_checkboxes') {
				if($request->input($data->db_column)) {
					for($i = 0; $i < count($request->input($data->db_column)); $i++) {
						$multiplyCheckboxString .= $request->input($data->db_column)[$i].',';
					}
				}

				$insertQuery[$data->db_column] = $multiplyCheckboxString;
			}
		}

		$id = DB::table($moduleStep->db_table)->insertGetId($insertQuery);


		$request->session()->flash('successStatus', __('bsw.successStatus'));

		return redirect()->route('coreEditStep0', array($module->alias, $moduleStep->id, $id));
	}


	public function addMultImagesStep0(Request $request, $moduleAlias, $id) {
		$module = Module::where('alias', $moduleAlias)->first();
		$moduleStep = ModuleStep::where('top_level', $module->id)->orderBy('rang', 'desc')->first();
		$moduleBlocks = ModuleBlock::where([['top_level', $moduleStep->id], ['type', 'image']])->orderBy('rang', 'desc')->first();

		$validated = $request->validate([
            'images' => 'required',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,gif|max:10000',
        ]);

		foreach($request->file('images') as $data) {
			$highestRang = DB::table($moduleStep->db_table)->max('rang');

			$newRowId = DB::table($moduleStep->db_table)->insertGetId([
				'rang' => $highestRang + 5 
			]);

			$prefix = '';

			if($moduleBlocks->prefix) {
				$prefix = $moduleBlocks->prefix.'_';
			}

			$data->storeAs('public/images/modules/'.$module->alias.'/step_0', $prefix.$newRowId.'.'.$moduleBlocks->file_format);	
			$imagePath = 'app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$newRowId.'.'.$moduleBlocks->file_format;

			$image = ImageManagerStatic::make(storage_path($imagePath));
			$width = $image->width();
			$height = $image->height();

			if($moduleBlocks->fit_type === 'fit') {
				$image->fit($moduleBlocks->image_width,
								$moduleBlocks->image_height,
								function() {},
								$moduleBlocks->fit_position);
			}
			
			if($moduleBlocks->fit_type === 'resize') {
				$image->resize($moduleBlocks->image_width,
									$moduleBlocks->image_height,
									function ($constraint) {
									$constraint->aspectRatio();
									});
					
				if($width < $moduleBlocks->image_width && $height < $moduleBlocks->image_height) {
					$image = ImageManagerStatic::make(storage_path($imagePath));																																			
				}
			}
			
			if($moduleBlocks->fit_type === 'resize_with_bg') {
				if($width > $moduleBlocks->image_width || $height > $moduleBlocks->image_height) {
					$image->resize($moduleBlocks->image_width,
										$moduleBlocks->image_height,
										function ($constraint) {
										$constraint->aspectRatio();
										});																																	
				}

				$image->resizeCanvas($moduleBlocks->image_width, $moduleBlocks->image_height, 'center', false, '#FFFFFF');
			}

			if($moduleBlocks->fit_type === 'default') {
				$image = ImageManagerStatic::make(storage_path($imagePath));
			}

			$image->save();

			for($i = 1; $i < 4; $i++) {
				if($moduleBlocks->{'prefix_'.$i}) {
					$data->storeAs('public/images/modules/'.$module->alias.'/step_1', $prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format );

					if($moduleBlocks->{'fit_type_'.$i} === 'fit') {
						$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format))->fit($moduleBlocks->{'image_width_'.$i},
																																												$moduleBlocks->{'image_height_'.$i},
																																												function() {},
																																												$moduleBlocks->fit_position);
					}
					
					if($moduleBlocks->{'fit_type_'.$i} === 'resize') {
						$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format))->resize($moduleBlocks->{'image_width_'.$i},
																																												$moduleBlocks->{'image_height_'.$i},
																																												function ($constraint) {
																																													$constraint->aspectRatio();
																																												});

						
						$width = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format))->width();
						$height = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format))->height();

						if($width < $moduleBlocks->{'image_width_'.$i} && $height < $moduleBlocks->{'image_height_'.$i}) {
							$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format));																																			
						}
					}

					if($moduleBlocks->{'fit_type_'.$i} === 'resize_with_bg') {
						if($width > $moduleBlocks->{'image_width_'.$i} || $height > $moduleBlocks->{'image_height_'.$i}) {
							$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format))->resize($moduleBlocks->{'image_width_'.$i},
																																																						$moduleBlocks->{'image_height_'.$i},
																																																						function ($constraint) {
																																																						$constraint->aspectRatio();
																																																						});																																	
						}
						
						$image-> resizeCanvas($moduleBlocks->{'image_width_'.$i}, $moduleBlocks->{'image_height_'.$i}, 'center', false, '#FFFFFF');
					}

					if($moduleBlocks->{'fit_type_'.$i} === 'default') {
						$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format));
					}

					$image->save();
				}
			}
		}

		$request->session()->flash('successStatus', __('bsw.successStatus'));


		return redirect()->route('coreGetStep0', array($moduleAlias));
	}
	

	public function addMultImages(Request $request, $moduleAlias, $id) {
		$module = Module::where('alias', $moduleAlias)->first();
		$moduleStep = ModuleStep::where('top_level', $module->id)->orderBy('rang', 'desc')->skip(1)->take(1)->first();
		$moduleBlocks = ModuleBlock::where([['top_level', $moduleStep->id], ['type', 'image']])->orderBy('rang', 'desc')->first();

		$validated = $request->validate([
            'images' => 'required',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,gif|max:10000',
        ]);

		foreach($request->file('images') as $data) {
			$highestRang = DB::table($moduleStep->db_table)->max('rang');

			$newRowId = DB::table($moduleStep->db_table)->insertGetId([
				'parent' => $id, 'rang' => $highestRang + 5 
			]);

			$prefix = '';

			if($moduleBlocks->prefix) {
				$prefix = $moduleBlocks->prefix.'_';
			}

			$data->storeAs('public/images/modules/'.$module->alias.'/step_1', $prefix.$newRowId.'.'.$moduleBlocks->file_format);	
			$imagePath = 'app/public/images/modules/'.$module->alias.'/step_1/'.$prefix.$newRowId.'.'.$moduleBlocks->file_format;

			$image = ImageManagerStatic::make(storage_path($imagePath));
			$width = $image->width();
			$height = $image->height();

			if($moduleBlocks->fit_type === 'fit') {
				$image->fit($moduleBlocks->image_width,
								$moduleBlocks->image_height,
								function() {},
								$moduleBlocks->fit_position);
			}
			
			if($moduleBlocks->fit_type === 'resize') {
				$image->resize($moduleBlocks->image_width,
									$moduleBlocks->image_height,
									function ($constraint) {
									$constraint->aspectRatio();
									});
					
				if($width < $moduleBlocks->image_width && $height < $moduleBlocks->image_height) {
					$image = ImageManagerStatic::make(storage_path($imagePath));																																			
				}
			}
			
			if($moduleBlocks->fit_type === 'resize_with_bg') {
				if($width > $moduleBlocks->image_width || $height > $moduleBlocks->image_height) {
					$image->resize($moduleBlocks->image_width,
										$moduleBlocks->image_height,
										function ($constraint) {
										$constraint->aspectRatio();
										});																																	
				}

				$image->resizeCanvas($moduleBlocks->image_width, $moduleBlocks->image_height, 'center', false, '#FFFFFF');
			}

			if($moduleBlocks->fit_type === 'default') {
				$image = ImageManagerStatic::make(storage_path($imagePath));
			}

			$image->save();

			for($i = 1; $i < 4; $i++) {
				if($moduleBlocks->{'prefix_'.$i}) {
					$data->storeAs('public/images/modules/'.$module->alias.'/step_1', $prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format );

					if($moduleBlocks->{'fit_type_'.$i} === 'fit') {
						$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_1/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format))->fit($moduleBlocks->{'image_width_'.$i},
																																												$moduleBlocks->{'image_height_'.$i},
																																												function() {},
																																												$moduleBlocks->fit_position);
					}
					
					if($moduleBlocks->{'fit_type_'.$i} === 'resize') {
						$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_1/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format))->resize($moduleBlocks->{'image_width_'.$i},
																																												$moduleBlocks->{'image_height_'.$i},
																																												function ($constraint) {
																																													$constraint->aspectRatio();
																																												});

						
						$width = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_1/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format))->width();
						$height = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_1/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format))->height();

						if($width < $moduleBlocks->{'image_width_'.$i} && $height < $moduleBlocks->{'image_height_'.$i}) {
							$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_1/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format));																																			
						}
					}

					if($moduleBlocks->{'fit_type_'.$i} === 'resize_with_bg') {
						if($width > $moduleBlocks->{'image_width_'.$i} || $height > $moduleBlocks->{'image_height_'.$i}) {
							$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_1/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format))->resize($moduleBlocks->{'image_width_'.$i},
																																																						$moduleBlocks->{'image_height_'.$i},
																																																						function ($constraint) {
																																																						$constraint->aspectRatio();
																																																						});																																	
						}
						
						$image-> resizeCanvas($moduleBlocks->{'image_width_'.$i}, $moduleBlocks->{'image_height_'.$i}, 'center', false, '#FFFFFF');
					}

					if($moduleBlocks->{'fit_type_'.$i} === 'default') {
						$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_1/'.$prefix.$newRowId.'_'.$moduleBlocks->{'prefix_'.$i}.'.'.$moduleBlocks->file_format));
					}

					$image->save();
				}
			}
		}

		$request->session()->flash('successStatus', __('bsw.successStatus'));
		return redirect()->route('coreEditStep0', array($moduleAlias, $id));
	}
	

	private function rename_temp_files($moduleStepId, $oldId, $id) {
		$moduleStep = ModuleStep::find($moduleStepId);

		// Image - uploading image without checking it passed validation or not
			foreach($moduleStep->moduleBlock as $data) {
				if($data->type === 'image') {
					$prefix = '';

					if($data->prefix) {
						$prefix = $data->prefix.'_';
					}

					Storage::move('public/images/modules/'.$moduleStep->moduleLevel->module->alias.'/step_0/'.$prefix.$oldId.'.'.$data->file_format,
									'public/images/modules/'.$moduleStep->moduleLevel->module->alias.'/step_0/'.$prefix.$id.'.'.$data->file_format);


					for($i = 1; $i < 4; $i++) {
						if($data->{'prefix_'.$i}) {
							Storage::move('public/images/modules/'.$moduleStep->moduleLevel->module->alias.'/step_0/'.$prefix.$oldId.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format,
											'public/images/modules/'.$moduleStep->moduleLevel->module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format);
						}
					}
				}
			}
		// 


		// File - uploading file without checking it passed validation or not
			foreach($moduleStep->moduleBlock as $data) {
				if($data->type === 'file') {
					$prefix = '';

					if($data->prefix) {
						$prefix = $data->prefix.'_';
					}

					Storage::move('public/images/modules/'.$moduleStep->moduleLevel->module->alias.'/step_0/'.$prefix.$oldId.'.'.$data->file_format,
									'public/images/modules/'.$moduleStep->moduleLevel->module->alias.'/step_0/'.$prefix.$id.'.'.$data->file_format);
				}
			}
		// 
	}


	public function edit($moduleAlias, $moduleStepId, $id) {
		if(Session::has('file_id')) {
			self::rename_temp_files($moduleStepId, Session::get('file_id'), $id);
		
			// dd(Session::get('file_id'));
		}
		

		$module = Module::where('alias', $moduleAlias)->first();
		$moduleStep = ModuleStep::find($moduleStepId);
		$moduleBlocks = ModuleBlock::where('top_level', $moduleStep->id)->orderBy('rang', 'desc')->get();
		$pageData = DB::table($moduleStep->db_table)->find($id);

		$moduleBlock = ModuleBlock::where('top_level', $moduleStep->id)->firstWhere('a_use_for_tags', 1);

		$use_for_tags = 'id';

		$activeLangForFront = Language::where('like_default', 1)->first();

		if($moduleBlock) {
			$use_for_tags = $moduleBlock->db_column;

			if($moduleBlock->type === 'alias' || $moduleBlock->type === 'input_with_languages' || $moduleBlock->type === 'editor_with_languages') {
				$use_for_tags .= '_'.$activeLangForFront->title;
			}
		}

		$activeLang = Language::where('like_default_for_admin', 1)->first();


		$prevId = 0;
		$nextId = 0;

		$prevIdIsSaved = false;
		$nextIdIsSaved = false;


		// Data for bar arrows.
			$orderBy = 'id';
			$sortBy = 'asc';
			
			$moduleBlocksForSort = ModuleBlock::where('top_level', $moduleStep->id)->where('a_use_for_sort', 1)->first();

			if($moduleBlocksForSort) {
				$orderBy = $moduleBlocksForSort->db_column;

				if($moduleBlocksForSort->type === 'alias' || $moduleBlocksForSort->type === 'input_with_languages' || $moduleBlocksForSort->type === 'editor_with_languages') {
					$orderBy .= '_'.$activeLang->title;
				}

				if($moduleBlocksForSort->sort_by_desc) {
					$sortBy = 'desc';
				}
			}


			foreach(DB::table($moduleStep->db_table)->orderBy($orderBy, $sortBy)->get() as $data) {
				if($nextIdIsSaved && !$nextId) {
					$nextId = $data->id;
				}
				
				if($pageData->id === $data->id) {
					$prevIdIsSaved = true;
					$nextIdIsSaved = true;
				}
				
				if(!$prevIdIsSaved) {
					$prevId = $data->id;
				}
			}
		//

		
		$selectData = [];
		$selectOptgroudData = [];
		$multCheckboxCatTable = '';

		foreach(ModuleBlock::where('top_level', $moduleStep->id)->orderBy('rang', 'desc')->get() as $data) {
			// Select
				if($data->type === 'select') {
					$selectData[$data->db_column][0] = '-- '.__('bsw.select').' --';

					foreach(DB::table($data->select_table)->orderBy($data->select_sort_by, $data->select_sort_by_text)->get() as $dataInside) {
						$selectData[$data->db_column][$dataInside->{$data->select_search_column}] = $dataInside->{$data->select_option_text};
					}
				}
			// 
			
			// Select with optgroups
				if($data->type === 'select_with_optgroup') {
					$selectOptgroudData[$data->db_column][0] = '-- '.__('bsw.select').' --';
					$alex = array('-- '.__('bsw.select').' --');

					foreach(DB::table($data->select_optgroup_table)->orderBy($data->select_optgroup_sort_by, 'desc')->get() as $dataInside) {
						foreach(DB::table($data->select_optgroup_2_table)->where('parent', $dataInside->id)->orderBy($data->select_optgroup_2_sort_by, 'desc')->get() as $dataInsideTwice) {
							$alex[$dataInside->{$data->select_optgroup_text}][$dataInsideTwice->id] = $dataInsideTwice->{$data->select_optgroup_2_text};
						}
					}

					$selectOptgroudData[$data->db_column] = $alex;
				}
			// 
			

			// Multiply Checkbox With Category
				$multiplyCheckboxCategory = [];

				if($data->type === 'multiply_checkboxes_with_category') {
					$checkboxTableText = $data->sql_select_with_checkboxes_option_text;														
					$checkboxArray = array();

					foreach(DB::table($data->sql_select_with_checkboxes_table)->orderBy($data->sql_select_with_checkboxes_sort_by, 'desc')->get() as $dataInside) {
						$checkboxTableTextInside = $data->sql_select_with_checkboxes_option_text_inside;
						$tempDbColumn = $data->db_column;
						$tempArray = explode(',', $pageData->$tempDbColumn);

						foreach(DB::table($data->sql_select_with_checkboxes_table_inside)->where('parent', $dataInside->id)->orderBy($data->sql_select_with_checkboxes_sort_by_inside, 'desc')->get() as $dataInsideTwice) {
							$active = 0;
							foreach($tempArray as $tempData) {
								if($tempData == $dataInsideTwice->id) {
									$active = 1;
								}
							}

							$checkboxArray[$dataInside->$checkboxTableText][$dataInsideTwice->id] = array('title' => $dataInsideTwice->$checkboxTableTextInside, 'active' => $active);
						}
					}

					$multiplyCheckboxCategory[$data->db_column] = $checkboxArray;
				}
			//


			// Multiply Checkbox
				$multiplyCheckbox = [];
				
				if($data->type === 'multiply_checkboxes') {
					$checkboxText = $data->sql_select_with_checkboxes_option_text;														
					$checkboxArray = array();
					$active = 0;

					foreach(DB::table($data->sql_select_with_checkboxes_table)->orderBy($data->sql_select_with_checkboxes_sort_by, 'desc')->get() as $dataInside) {
						$tempDbColumn = $data->db_column;
						$tempArray = explode(',', $pageData->$tempDbColumn);

						foreach($tempArray as $tempData) {
							if($tempData == $dataInside->id) {
								$active = 1;
							}
						}

						$checkboxArray[$dataInside->id] = array('title' => $dataInside->$checkboxText, 'active' => $active);
					}
					
					$multiplyCheckbox[$data->db_column] = $checkboxArray;
				}
			//
		}

		
		$use_for_sort = 'rang';


		$imageFormat = 'jpg';

		$nextModuleStepData = false;

		$nextModuleStep = ModuleStep::where('top_level', $module->id)->orderBy('rang', 'desc')->skip(1)->take(1)->first();
		
		if($nextModuleStep) {
			$nextModuleStepData = DB::table($nextModuleStep->db_table)->where('parent', $id)->orderBy($use_for_sort, 'desc')->get();

			$moduleBlockForImage = ModuleBlock::where('top_level', $nextModuleStep->id)->where('type', 'image')->first();

			if($moduleBlockForImage) {
				$imageFormat = $moduleBlockForImage->file_format;
			}
		}

		$moduleBlockForSort = ModuleBlock::where('top_level', $module->id)->where('a_use_for_sort', 1)->first();

		$use_for_sort = 'id';
		$sort_by = 'DESC';

		if($moduleBlockForSort) {
			$use_for_sort = $moduleBlockForSort->db_column;

			if(!$moduleBlockForSort->sort_by_desc) {
				$sort_by = 'ASC';
			}
		}


		// $parentModuleStep = 


		$data = array_merge(self::getDefaultData(), ['module' => $module,
														'moduleStep' => $moduleStep,
														'moduleStepData' => DB::table($moduleStep->db_table)->orderBy($use_for_sort, $sort_by)->get(),
														'moduleBlocks' => $moduleBlocks,
														'selectData' => $selectData,
														'selectOptgroudData' => $selectOptgroudData,
														'languages' => Language::where('disable', 0)->orderBy('rang', 'desc')->get(),
														'sortBy' => $orderBy,
														'id' => $id,
														'imageFormat' => $imageFormat,
														'nextModuleStepData' => $nextModuleStepData,
														'nextModuleStep' => $nextModuleStep,
														'moduleStep1Data' => $nextModuleStep,
														'data' => $pageData,
														'prevId' => $prevId,
														'nextId' => $nextId,
														'use_for_tags' => $use_for_tags,
														'multiplyCheckbox' => $multiplyCheckbox,
														'multiplyCheckbox' => $multiplyCheckbox,
														'multiplyCheckboxCategory' => $multiplyCheckboxCategory]);

		return view('modules.core.step1', $data);
	}


	public function update(Request $request, $moduleAlias, $moduleStepId, $id) {
		// dd($moduleStepId);

		$moduleStep = ModuleStep::find($moduleStepId);

		$module = Module::where('alias', $moduleAlias)->first();
		// $moduleStep->moduleBlock = ModuleBlock::where('top_level', $moduleStep->id)->orderBy('rang', 'desc')->get();

		// Image - uploading image without checking it passed validation or not
			foreach($moduleStep->moduleBlock as $data) {
				if($data->type === 'image') {
					if($request->hasFile($data->db_column)) {
						$prefix = '';

						if($data->prefix) {
							$prefix = $data->prefix.'_';
						}
						
						$validator = Validator::make($request->all(), array(
							$data->db_column => 'mimes:jpeg,jpg,png,gif|required|max:10000'
						));
						
						if($validator->fails()) {
							return redirect()->route('coreEditStep0', array($module->alias, $moduleStep->id, $id))->withErrors($validator)->withInput();
						}

						$request->file($data->db_column)->storeAs('public/images/modules/'.$module->alias.'/step_0', $prefix.$id.'.'.$data->file_format);	
						
						$imagePath = 'app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'.'.$data->file_format;

						$image = ImageManagerStatic::make(storage_path($imagePath));
						$width = $image->width();
						$height = $image->height();

						if($data->fit_type === 'fit') {
							$image->fit($data->image_width,
											$data->image_height,
											function() {},
											$data->fit_position);
						}
						
						if($data->fit_type === 'resize') {
							$image->resize($data->image_width,
												$data->image_height,
												function ($constraint) {
												$constraint->aspectRatio();
												});
								
							if($width < $data->image_width && $height < $data->image_height) {
								$image = ImageManagerStatic::make(storage_path($imagePath));																																			
							}
						}
						
						if($data->fit_type === 'resize_with_bg') {
							if($width > $data->image_width || $height > $data->image_height) {
								$image->resize($data->image_width,
													$data->image_height,
													function ($constraint) {
													$constraint->aspectRatio();
													});																																	
							}

							$image->resizeCanvas($data->image_width, $data->image_height, 'center', false, '#FFFFFF');
						}

						if($data->fit_type === 'default') {
							$image = ImageManagerStatic::make(storage_path($imagePath));
						}

						$image->save();

						for($i = 1; $i < 4; $i++) {
							if($data->{'prefix_'.$i}) {
								$request->file($data->db_column)->storeAs('public/images/modules/'.$module->alias.'/step_0', $prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format );

								if($data->{'fit_type_'.$i} === 'fit') {
									$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format))->fit($data->{'image_width_'.$i},
																																															$data->{'image_height_'.$i},
																																															function() {},
																																															$data->fit_position);
								}
								
								if($data->{'fit_type_'.$i} === 'resize') {
									$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format))->resize($data->{'image_width_'.$i},
																																															$data->{'image_height_'.$i},
																																															function ($constraint) {
																																																$constraint->aspectRatio();
																																															});

									
									$width = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format))->width();
									$height = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format))->height();

									if($width < $data->{'image_width_'.$i} && $height < $data->{'image_height_'.$i}) {
										$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format));																																			
									}
								}

								if($data->fit_type === 'resize_with_bg') {
									if($width > $data->{'image_width_'.$i} || $height > $data->{'image_height_'.$i}) {
										$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format))->resize($data->{'image_width_'.$i},
																																																									$data->{'image_height_'.$i},
																																																									function ($constraint) {
																																																									$constraint->aspectRatio();
																																																									});																																	
									}
									
									$image-> resizeCanvas($data->{'image_width_'.$i}, $data->{'image_height_'.$i}, 'center', false, '#FFFFFF');
								}

								if($data->{'fit_type_'.$i} === 'default') {
									$image = ImageManagerStatic::make(storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'_'.$data->{'prefix_'.$i}.'.'.$data->file_format));
								}

								$image->save();
							}
						}
					}
				}
			}
		// 


		// File - uploading file without checking it passed validation or not
			foreach($moduleStep->moduleBlock as $data) {
				if($data->type === 'file') {
					if($request->hasFile($data->db_column)) {
						$prefix = '';

						if($data->prefix) {
							$prefix = $data->prefix.'_';
						}

						$validator = Validator::make($request->all(), array(
							$data->db_column => "required|mimes:".$data->file_format."|max:10000"
						));

						if($validator->fails()) {

							return redirect()->route('coreEditStep0', array($module->alias, $moduleStep->id, $id))->withErrors($validator)->withInput();
						}
						
						$request->file($data->db_column)->storeAs('public/images/modules/'.$module->alias.'/step_0', $prefix.$id.'.'.$data->file_format);
					}
				}
			}
		// 
		
		
		// Validation
			$validationArray = [];

			foreach($moduleStep->moduleBlock as $data) {
				$prefix = '';

				if($data->prefix) {
					$prefix = $data->prefix.'_';
				}
				
				$imagePath = storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'.'.$data->file_format);
				
				if($data->type === 'image' || $data->type === 'file') {
					if(!file_exists($imagePath)){
						$validationArray[$data->db_column] = $data->validation;
					}
				} else {
					if($data->type !== 'alias' && $data->type !== 'input_with_languages' && $data->type !== 'editor_with_languages') {
						$validationArray[$data->db_column] = $data->validation;
					} else {
						$validationData = [];
	
						foreach(Language::where('disable', 0)->get() as $langData) {
							$validationArray[$data->db_column.'_'.$langData->title] = $data->validation;
						}
					}
				}
				
			}

			$validator = Validator::make($request->all(), $validationArray);

			if($validator->fails()) {
				return redirect()->route('coreEditStep0', array($module->alias, $moduleStep->id, $id))->withErrors($validator)->withInput();
			}
		//
		
		$updateQuery = [];
		
		foreach($moduleStep->moduleBlock as $data) {
			if($data->type !== 'image'
				&& $data->type !== 'file'
				&& $data->type !== 'rang'
				&& $data->type !== 'alias'
				&& $data->type !== 'input_with_languages'
				&& $data->type !== 'editor_with_languages'
				&& $data->type !== 'checkbox'
				&& $data->type !== 'multiply_checkboxes_with_category') {
				$updateQuery[$data->db_column] = (!is_null($request->input($data->db_column)) ? $request->input($data->db_column) : '');
			}
			
			if($data->type === 'alias') {
				foreach(Language::where('disable', 0)->get() as $langData) {
					$value = $request->input($data->db_column.'_'.$langData->title);
					$value = preg_replace("/[^A-ZА-Яა-ჰ0-9 -]+/ui",
											'',
											$value);

					$value = mb_strtolower(trim($value));

					$symbols = array('     ', '    ', '   ', '  ', ' ', '.', ',', '!', '?', '=', '#', '%', '+', '*', '/', '_', '\'', '"');

					$replace_symbols = array('-', '-', '-', '-', '-', '-', '-', '', '-', '-', '', '', '-', '', '-', '-', '', '');

					$value = str_replace($symbols,
											$replace_symbols,
											$value);
											
					$updateQuery[$data->db_column.'_'.$langData->title] = $value;
				}
			}

			if($data->type === 'input_with_languages') {
				foreach(Language::where('disable', 0)->get() as $langData) {
					$updateQuery[$data->db_column.'_'.$langData->title] = $request->input($data->db_column.'_'.$langData->title);
				}
			}

			if($data->type === 'editor_with_languages') {
				foreach(Language::where('disable', 0)->get() as $langData) {
					$updateQuery[$data->db_column.'_'.$langData->title] = $request->input($data->db_column.'_'.$langData->title);
				}
			}

			if($data->type === 'checkbox') {
				$updateQuery[$data->db_column] = (!is_null($request->input($data->db_column)) ? $request->input($data->db_column) : 0);
			}


			$checkboxString = '';

			if($data->type === 'multiply_checkboxes_with_category') {
				if($request->input($data->db_column)) {
					for($i = 0; $i < count($request->input($data->db_column)); $i++) {
						$checkboxString .= $request->input($data->db_column)[$i].',';
					}
				}
				
				$updateQuery[$data->db_column] = $checkboxString;
			}
			

			$multiplyCheckboxString = '';

			if($data->type === 'multiply_checkboxes') {
				if($request->input($data->db_column)) {
					for($i = 0; $i < count($request->input($data->db_column)); $i++) {
						$multiplyCheckboxString .= $request->input($data->db_column)[$i].',';
					}
				}

				$updateQuery[$data->db_column] = $multiplyCheckboxString;
			}
		}

		DB::table($moduleStep->db_table)->where('id', $id)->update($updateQuery);


		$request->session()->flash('successStatus', __('bsw.successStatus'));

		return redirect()->route('coreEditStep0', array($module->alias, $moduleStep->id, $id));
	}


	public function delete($moduleAlias, $moduleStepId, $id) {
		$module = Module::where('alias', $moduleAlias)->first();
		$moduleStep = ModuleStep::find($moduleStepId);
		
		foreach($moduleStep->moduleBlock as $data) {
			$prefix = '';

			if($data->prefix) {
				$prefix = $data->prefix.'_';
			}
			
			$filePath = storage_path('app/public/images/modules/'.$module->alias.'/step_0/'.$prefix.$id.'.'.$data->file_format);
			
			if(file_exists($filePath)) {
				unlink($filePath);
			}
		}
		
		DB::table($moduleStep->db_table)->delete($id);

		Session::flash('successDeleteStatus', __('bsw.deleteSuccessStatus'));

		return redirect()->route('coreGetStep0', $module->alias);
	}
}