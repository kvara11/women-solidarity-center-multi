@extends('admin.master')


@section('pageMetaTitle')
    Modules > {{ $module->alias }} > {{ __('bsw.adding') }}
@endsection


@section('content')
	@include('admin.includes.tags', [
		'tag0Text' => 'Modules',
		'tag0Url' => route('moduleStartPoint'),
		'tag1Text' => $module->alias,
		'tag1Url' => route('moduleEdit', $module->id),
		'tag3Text' => __('bsw.adding')
	])

	<div class="p-2">
		@if($errors->any())
			<div class="p-2">
				<div class="alert alert-danger m-0">
					{{ __('bsw.warningStatus') }}
				</div>
			</div>
		@endif
		
		
		@if(Session::has('successStatus'))
			<div class="p-2">
				<div class="alert alert-success m-0" role="alert">
					{{ Session::get('successStatus') }}
				</div>
			</div>
		@endif


		@if(Session::has('successDeleteStatus'))
			<div class="p-2">
				<div class="alert alert-success m-0" role="alert">
					{{ Session::get('successDeleteStatus') }}
				</div>
			</div>
		@endif


		{{ Form::open(array('route' => array('moduleStepInsert', $module->id))) }}
			<div class="p-2">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>parent_step_id:</span>
					</div>
					
					<div class="p-2">
						{{Form::select('parent_step_id', $topLevelSelectValues)}}
					</div>
				</div>
			</div>
			
			<div class="p-2">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>title: *</span>
						<span>(Example: Category)</span>
					</div>
					
					<div class="p-2">
						{{ Form::text('title') }}
					</div>
				</div>

				@error('title')
					<div class="alert alert-danger">
						{{ $message }}
					</div>
				@enderror
			</div>

			<div class="p-2">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>db_table: *</span>
						<span>(Example: news_step_0)</span>
					</div>
					
					<div class="p-2">
						{{ Form::text('db_table') }}
					</div>
				</div>

				@error('db_table')
					<div class="alert alert-danger">
						{{ $message }}
					</div>
				@enderror
			</div>

			<div class="p-2">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>model_name: *</span>
						<span>(Example: NewsStep0)</span>
					</div>
					
					<div class="p-2">
						{{Form :: text('model_name')}}
					</div>
				</div>

				@error('model_name')
					<div class="alert alert-danger">
						{{$message}}
					</div>
				@enderror
			</div>

			<div class="p-2">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>main_column: *</span>
						<span>(Example: title_ge)</span>
					</div>
					
					<div class="p-2">
						{{Form :: text('main_column', 'title_ge')}}
					</div>
				</div>

				@error('main_column')
					<div class="alert alert-danger">
						{{$message}}
					</div>
				@enderror
			</div>

			<div class="p-2">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>order_by_column: *</span>
						<span>(Example: rang)</span>
					</div>
					
					<div class="p-2">
						{{Form :: text('order_by_column')}}
					</div>
				</div>

				@error('order_by_column')
					<div class="alert alert-danger">
						{{$message}}
					</div>
				@enderror
			</div>

			<div class="p-2">
				<div class="standard-block p-2">
					<div class="p-2 d-flex flex-column">
						<span>order_by_sequence: *</span>
						<span>(Example: DESC)</span>
					</div>
					
					<div class="p-2">
						DESC {{Form :: radio('order_by_sequence', 'DESC')}}
						ASC {{Form :: radio('order_by_sequence', 'ASC')}}
					</div>
				</div>
			</div>

			<div class="p-2">
				<div class="standard-block p-2">
					<div class="p-2">
						<label>
							{{ Form::checkbox('images', '1') }}

							images?
						</label>
					</div>

					<div class="p-2">
						<label>
							{{ Form::checkbox('possibility_to_add', '1') }}

							possibility_to_add?
						</label>
					</div>

					<div class="p-2">
						<label>
							{{ Form::checkbox('possibility_to_delete', '1') }}

							possibility_to_delete?
						</label>
					</div>

					<div class="p-2">
						<label>
							{{ Form::checkbox('possibility_to_rang', '1') }}

							possibility_to_rang?
						</label>
					</div>

					<div class="p-2">
						<label>
							{{ Form::checkbox('possibility_to_edit', '1') }}

							possibility_to_edit?
						</label>
					</div>

					<div class="p-2">
						<label>
							{{Form :: checkbox('possibility_to_multy_delete', '1')}}

							possibility_to_multy_delete?
						</label>
					</div>
				</div>
			</div>

			<div class="p-2">
				<div class="standard-block">
					<div class="col-4">
						<label class="w-100">
							<div class="d-flex flex-column p-2">
								<div class="py-1">
									<span>Blocks Max Number:</span>
								</div>

								<div class="py-1">
									<span>(e.g. 5)</span>
								</div>
								
								<div class="pt-1">
									<span class="text-danger">0 means infinite amount</span>
								</div>
							</div>

							<div class="p-2">
								{{ Form::number('blocks_max_number', 0,  ['class' => 'w-100']) }}
							</div>

						</label>
					</div>
				</div>
			</div>

			<div class="p-2 submit-button">
				{{ Form::submit(__('bsw.adding')) }}
			</div>
		{{ Form::close() }}
	</div>
@endsection