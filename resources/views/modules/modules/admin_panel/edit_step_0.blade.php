@extends('admin.layouts.master')


@section('pageMetaTitle')
    Modules
@endsection


@section('content')
	@include('admin.includes.tags', [
		'tag0Text' => 'Modules',
		'tag0Url' => route('moduleStartPoint'),
		'tag1Text' => $module -> alias
	])


	@include('admin.includes.bar', [
		'addUrl' => route('moduleAdd'),
		'deleteUrl' => route('moduleDelete', $module -> id),
		'nextId' => $nextModuleId,
		'prevId' => $prevModuleId,
		'nextRoute' => route('moduleEdit', $nextModuleId),
		'prevRoute' => route('moduleEdit', $prevModuleId),
		'backRoute' => route('moduleStartPoint')
	])


	{{ Form :: model($module, array('route' => array('moduleUpdate', $module -> id))) }}
		<div class="p-2">
			{{ Form :: text('alias') }}
		</div>

		<div class="p-2">
			@foreach($languages as $data)
				{{ Form :: text('title_'.$data -> title) }}
			@endforeach

			<div class="p-2">
				<label>
					{{ Form :: radio('hide_for_admin', '0') }}

					Show for admin?
				</label>

				<label>
					{{ Form :: radio('hide_for_admin', '1') }}

					Hide for admin?
				</label>
			</div>

			<div class="p-2">
				<div>
					<label>
						{{ Form :: radio('include_type', '0') }}

						მივამაგროთ გვერდს
					</label>
				</div>

				<div>
					<label>
						{{ Form :: radio('include_type', '1') }}

						გამოვაჩინოთ ყველა გვერდზე
					</label>
				</div>

				<div>
					<label>
						{{ Form :: radio('include_type', '2') }}

						გამოვაჩინოთ გვერდებზე
					</label>
				</div>

				<div>
					<label>
						{{ Form :: radio('include_type', '3') }}

						გამოვაჩინოთ ყველა გვერდზე, გარდა
					</label>
				</div>

				<div>
					<label>
						{{ Form :: radio('include_type', '4') }}

						არ მივაბათ მოდული არცერთ გვერდს
					</label>
				</div>
			</div>
			

			<div class="p-2">
				გვერდი
				<br>
				{{ Form :: select('page', $pagesForSelect, $module -> page) }}
			</div>


			<div class="p-2">
				@foreach($pagesForIncludeInPages as $key => $data)
					<div>
						<label>
							<input type="checkbox"
									value="{{ $key }}"
									name="page_include_{{ $key }}">
							
							{{ $data['alias'] }} 
						</label>
					</div>
				@endforeach
			</div>


			<div class="p-2">
				ფერი:
				<br>
				{{ Form :: text('icon_bg_color') }}
			</div>
		</div>

	
		{{ Form :: submit('Submit') }}
	{{ Form :: close() }}


	@include('admin.includes.addButton', ['text' => 'Step', 'url' => route('moduleStepAdd', $module -> id)])


    <div>
		@foreach($moduleSteps as $data)
			@include('admin.includes.horizontalEditDeleteBlock', [
				'title' => $data -> title,
				'editLink' => route('moduleStepEdit', array($module -> id, $data -> id)),
				'deleteLink' => route('moduleStepDelete', array($module -> id, $data -> id))
			])
		@endforeach
    </div>
@endsection
