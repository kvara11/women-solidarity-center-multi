@extends('admin.master')


@section('pageMetaTitle')
    Modules > {{ $moduleStep ->  module -> alias }} > {{ $moduleStep -> title }} > {{ __('bsw.adding') }}
@endsection


@section('content')
	@include('admin.includes.tags', [
		'tag0Text' => 'Modules',
		'tag0Url' => route('moduleStartPoint'),
		'tag1Text' => $moduleStep -> module -> alias,
		'tag1Url' => route('moduleEdit', $moduleStep -> module -> id),
		'tag3Text' => $moduleStep -> title,
		'tag3Url' => route('moduleStepEdit', [$moduleStep -> module -> id, $moduleStep -> id]),
		'tag4Text' => __('bsw.adding')
	])
	

	<div class="step2 p-2">
		@if($errors -> any())
			<div class="p-2">
				<div class="alert alert-danger m-0">
					{{ __('bsw.warningStatus') }}
				</div>
			</div>
		@endif
		
		
		@if(Session :: has('successStatus'))
			<div class="p-2">
				<div class="alert alert-success m-0" role="alert">
					{{ Session :: get('successStatus') }}
				</div>
			</div>
		@endif


		{{ Form :: open(array('route' => ['moduleBlockInsert', $moduleStep -> module -> id, $moduleStep -> id])) }}
			<div class="p-2">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>type</span>
					</div>
					
					<div class="p-2">
						{{ Form :: select('type', $blockTypes, null, ['id' => 'type_select']) }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forInput forInputWithLanguages forEditor forEditorWithLanguages forColorPicker forRang forFile forCalendar forImage forSelect forCheckbox">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>label</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('label') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forInput forImage forFile forInputWithLanguages forAlias forColorPicker forMultiplyCheckboxesWithCategory forRang forCalendar forEditor forEditorWithLanguages forSelect forCheckbox">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>db_column:</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('db_column') }}
					</div>
				</div>
			</div>

			

			<div class="p-2 dataBlock forImage forFile">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>prefix:</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('prefix') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forFile forImage">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>file_format</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('file_format') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forInput forInputWithLanguages forEditor forEditorWithLanguages">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>example</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('example') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forImage">
				<div class="standard-block row p-2">
					<div class="col-4 p-2">
						image_width {{ Form :: number('image_width', 0) }}
					</div>
					
					<div class="col-4 p-2">
						image_height {{ Form :: number('image_height', 0) }}
					</div>
					
					<div class="col-2 p-2">
						{{ Form :: radio('fit_type', 'fit') }} fit
					</div>
					
					<div class="col-2 p-2">
						{{ Form :: radio('fit_type', 'resize') }} resize
					</div>

					<div class="col-2 p-2">
						{{ Form :: radio('fit_type', 'resize_with_bg') }} resize_with_bg
					</div>

					<div class="col-2 p-2">
						{{ Form :: radio('fit_type', 'default') }} default
					</div>

					<div class="col-4 p-2">
						<div>
							fit_position
						</div>

						<div>
							{{ Form :: select('fit_position',
												[
													'top-left' => 'top-left',
													'top' => 'top',
													'top-right' => 'top-right',
													'left' => 'left',
													'center' => 'center',
													'right' => 'right',
													'bottom-left' => 'bottom-left',
													'bottom' => 'bottom',
													'bottom-right' => 'bottom-right'
												]) }}
						</div>
					</div>
				</div>
			</div>


			<div class="p-2 dataBlock forImage">
				<div class="standard-block row p-2">
					<div class="col-4 p-2">
						image_width_1 {{ Form :: number('image_width_1', 0) }}
					</div>
					
					<div class="col-4 p-2">
						image_height_1 {{ Form :: number('image_height_1', 0) }}
					</div>

					<div class="">
						{{ Form :: text('prefix_1') }}
					</div>

					<div class="col-2 p-2">
						{{ Form :: radio('fit_type_1', 'fit') }} fit_1
					</div>
					
					<div class="col-2 p-2">
						{{ Form :: radio('fit_type_1', 'resize') }} resize_1
					</div>

					<div class="col-2 p-2">
						{{ Form :: radio('fit_type_1', 'resize_with_bg') }} resize_with_bg_1
					</div>

					<div class="col-2 p-2">
						{{ Form :: radio('fit_type_1', 'default') }} default_1
					</div>

					<div class="col-4 p-2">
						<div>
							fit_position_1
						</div>

						<div>
							{{ Form :: select('fit_position_1',
												[
													'top-left' => 'top-left',
													'top' => 'top',
													'top-right' => 'top-right',
													'left' => 'left',
													'center' => 'center',
													'right' => 'right',
													'bottom-left' => 'bottom-left',
													'bottom' => 'bottom',
													'bottom-right' => 'bottom-right'
												]) }}
						</div>
					</div>
				</div>
			</div>


			<div class="p-2 dataBlock forImage">
				<div class="standard-block row p-2">
					<div class="col-4 p-2">
						image_width_2 {{ Form :: number('image_width_2', 0) }}
					</div>
					
					<div class="col-4 p-2">
						image_height_2 {{ Form :: number('image_height_2', 0) }}
					</div>
					
					<div class="">
						{{ Form :: text('prefix_2') }}
					</div>

					<div class="col-2 p-2">
						{{ Form :: radio('fit_type_2', 'fit') }} fit_2
					</div>
					
					<div class="col-2 p-2">
						{{ Form :: radio('fit_type_2', 'resize') }} resize_2
					</div>

					<div class="col-2 p-2">
						{{ Form :: radio('fit_type_2', 'resize_with_bg') }} resize_with_bg_2
					</div>

					<div class="col-2 p-2">
						{{ Form :: radio('fit_type_2', 'default') }} default_2
					</div>

					<div class="col-4 p-2">
						<div>
							fit_position_2
						</div>

						<div>
							{{ Form :: select('fit_position_2',
												[
													'top-left' => 'top-left',
													'top' => 'top',
													'top-right' => 'top-right',
													'left' => 'left',
													'center' => 'center',
													'right' => 'right',
													'bottom-left' => 'bottom-left',
													'bottom' => 'bottom',
													'bottom-right' => 'bottom-right'
												]) }}
						</div>
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forImage">
				<div class="standard-block row p-2">
					<div class="col-4 p-2">
						image_width_3 {{ Form :: number('image_width_3', 0) }}
					</div>
					
					<div class="col-4 p-2">
						image_height_3 {{ Form :: number('image_height_3', 0) }}
					</div>

					<div class="">
						{{ Form :: text('prefix_3') }}
					</div>

					<div class="col-2 p-2">
						{{ Form :: radio('fit_type_3', 'fit') }} fit_3
					</div>
					
					<div class="col-2 p-2">
						{{ Form :: radio('fit_type_3', 'resize') }} resize_3
					</div>
					
					<div class="col-2 p-2">
						{{ Form :: radio('fit_type_3', 'resize_with_bg') }} resize_with_bg_3
					</div>

					<div class="col-2 p-2">
						{{ Form :: radio('fit_type_3', 'default') }} default_3
					</div>

					<div class="col-4 p-2">
						<div>
							fit_position_3
						</div>

						<div>
							{{ Form :: select('fit_position_3',
												[
													'top-left' => 'top-left',
													'top' => 'top',
													'top-right' => 'top-right',
													'left' => 'left',
													'center' => 'center',
													'right' => 'right',
													'bottom-left' => 'bottom-left',
													'bottom' => 'bottom',
													'bottom-right' => 'bottom-right'
												]) }}
						</div>
					</div>
				</div>
			</div>

						
			<div class="p-2 dataBlock forFile forImage">
				<div class="standard-block p-2">
					<label>
						{{ Form :: checkbox('file_possibility_to_delete', '1') }}

						file_possibility_to_delete?
					</label>
				</div>
			</div>
			
			<div class="p-2 dataBlock forFile ">
				<div class="standard-block p-2">
					<label>
						{{ Form :: checkbox('show_file_url', '1') }}

						show_file_url?
					</label>
				</div>
			</div>

			<div class="p-2 dataBlock">
				<div class="standard-block p-2">						
					<div class="p-2">
						<label>
							{{ Form :: checkbox('hide', '1') }}

							hide?
						</label>
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>min_range</span>
					</div>
					
					<div class="p-2">
						{{ Form :: number('min_range', 0) }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>max_range</span>
					</div>
					
					<div class="p-2">
						{{ Form :: number('max_range', 0) }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>range_step</span>
					</div>
					
					<div class="p-2">
						{{ Form :: number('range_step', 0) }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>range_value</span>
					</div>
					
					<div class="p-2">
						{{ Form :: number('range_value', 0) }}
					</div>
				</div>
			</div>


			<div class="p-2 dataBlock forSelect">
				<div class="standard-block p-2 row">
					<div class="p-2 col-4">
						select_table {{ Form :: text('select_table', null, ['class' => 'w-50']) }}
					</div>

					<div class="p-2 col-4">
						select_condition {{ Form :: text('select_condition', null, ['class' => 'w-50']) }}
					</div>

					<div class="p-2 col-4">
						select_sort_by {{ Form :: text('select_sort_by', null, ['class' => 'w-50']) }}
					</div>

					<div class="p-2 col-4">
						select_search_column {{ Form :: text('select_search_column', null, ['class' => 'w-50']) }}
					</div>

					<div class="p-2 col-4">
						select_option_text {{ Form :: text('select_option_text', null, ['class' => 'w-50']) }}
					</div>

					<div class="p-2 col-4">
						select_sort_by_text {{ Form :: text('select_sort_by_text', null, ['class' => 'w-50']) }}
					</div>
				</div>
			</div>

			
			<div class="p-2 dataBlock forSelectWithOptgroup">
				<div class="standard-block p-2">
					<div class="p-2 col-4">
						select_optgroup_table {{ Form :: text('select_optgroup_table', null, ['class' => 'w-50']) }}
					</div>

					<div class="p-2 col-4">
						select_optgroup_sort_by {{ Form :: text('select_optgroup_sort_by', null, ['class' => 'w-50']) }}
					</div>
				</div>


				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>select_optgroup_text</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('select_optgroup_text') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forSelectWithOptgroup">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>select_option_2_text</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('select_option_2_text') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forSelectWithOptgroup">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>select_optgroup_2_table</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('select_optgroup_2_table') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forSelectWithOptgroup">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>select_optgroup_2_sort_by</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('select_optgroup_2_sort_by') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forSelectWithOptgroup">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>select_optgroup_2_text</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('select_optgroup_2_text') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>select_active_option</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('select_active_option') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forSelectWithOptgroup">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>select_first_option_value</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('select_first_option_value') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>select_first_option_text</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('select_first_option_text') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forSelectWithAjax">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>label_for_ajax_select</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('label_for_ajax_select') }}
					</div>
				</div>
			</div>

			<!-- <div class="p-2 dataBlock forFile">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>file_folder</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('file_folder') }}
					</div>
				</div>
			</div> -->

			<!-- <div class="p-2 dataBlock forFile">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>file_title</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('file_title') }}
					</div>
				</div>
			</div> -->

			<!-- <div class="p-2 dataBlock forFile">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>file_name_index_1</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('file_name_index_1') }}
					</div>
				</div>
			</div> -->

			<!-- <div class="p-2 dataBlock forFile">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>file_name_index_2</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('file_name_index_2') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forFile">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>file_name_index_3</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('file_name_index_3') }}
					</div>
				</div>
			</div> -->

			<div class="p-2 dataBlock">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>radio_value</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('radio_value') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>radio_class</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('radio_class') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forMultiplyCheckboxes forMultiplyCheckboxesWithCategory">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>sql_select_with_checkboxes_table</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('sql_select_with_checkboxes_table') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forMultiplyCheckboxes forMultiplyCheckboxesWithCategory">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>sql_select_with_checkboxes_sort_by</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('sql_select_with_checkboxes_sort_by') }}
					</div>
				</div>
			</div>
			
			<div class="p-2 dataBlock forMultiplyCheckboxes forMultiplyCheckboxesWithCategory">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>sql_select_with_checkboxes_option_text</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('sql_select_with_checkboxes_option_text') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forMultiplyCheckboxesWithCategory">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>sql_select_with_checkboxes_table_inside</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('sql_select_with_checkboxes_table_inside') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forMultiplyCheckboxesWithCategory">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>sql_select_with_checkboxes_sort_by_inside</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('sql_select_with_checkboxes_sort_by_inside') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forMultiplyCheckboxesWithCategory">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>sql_select_with_checkboxes_option_text_inside</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('sql_select_with_checkboxes_option_text_inside') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forMultiplyInputParams">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>params_values_table</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('params_values_table') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forInput forInputWithLanguages forMultiplyCheckboxes forFile forMultiplyCheckboxesWithCategory forCalendar forColorPicker forImage forEditor forEditorWithLanguages forSelect forCheckbox">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>div_id</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('div_id') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forInput forInputWithLanguages forMultiplyCheckboxes forFile forMultiplyCheckboxesWithCategory forCalendar forColorPicker forImage forEditor forEditorWithLanguages forSelect forCheckbox">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>parent_div_id</span>
					</div>
					
					<div class="p-2">
						{{ Form :: text('parent_div_id') }}
					</div>
				</div>
			</div>

			<div class="p-2 dataBlock forInput forInputWithLanguages forImage forFile forAlias forColorPicker forMultiplyCheckboxesWithCategory forRang forCalendar forEditor forEditorWithLanguages forSelect forCheckbox">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						validation
					</div>

					<div class="p-2">
						{{ Form :: text('validation') }}
					</div>
				</div>
			</div>

			<div class="p-2 submit-button">
				{{ Form :: submit('Submit') }}
			</div>
		{{ Form :: close() }}
	</div>
@endsection
