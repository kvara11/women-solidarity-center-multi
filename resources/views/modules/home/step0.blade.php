@extends('master')

@section('pageMetaTitle'){{ $page -> metaTitle }}@endsection
@section('pageMetaDescription'){{ $page -> metaDescription }}@endsection
@section('pageMetaUrl'){{ $page -> metaUrl }}@endsection

@section('content')
	<div class="container main_section_padding">
		<!-- Vue -->
			<div id="app">
				<example title="Vue - {{ $page -> title }}"></example>
			</div>
		<!--  -->

		{{-- <!-- React -->
			<div id="examplereact" temp="eeee"></div>
		<!--  --> --}}

		@if(Session :: has('orderSuccessStatus'))
			<!-- Order status. -->
				<div class="p-2">
					<div class="alert alert-success m-0" role="alert">
						{{ Session :: get('orderSuccessStatus') }}
					</div>
				</div>
			<!--  -->
		@endif

		@if(Session::has('subscribe-success'))
			<div class="p-2">
				<div class="alert alert-success m-0" role="alert">
					{{ Session::get('subscribe-success') }}
				</div>
			</div>
		@endif

        @if(Session::has('subscribe-error'))
			<div class="p-2">
				<div class="alert alert-success m-0" role="alert">
					{{ Session::get('subscribe-error') }}
				</div>
			</div>
		@endif
		
		<div class="container home__main">
			@include('modules.animated_header.step0', $animatedHeader)
		</div>

		<h1 class="p-2">
			
		</h1>
		
		<div class="container">
			<div class="news row mb-5">
				<div class="news__header_wrapper 
							col-12 
							text-center 
							p-0 
							my-3 
							pt-2">
					@include('includes.pages_headers', [
											'title' => __('bsw.news') ,
										])
				</div>

				<div  class="row p-2 pt-0">
					<div class="col-lg-6 col-12 p-0">
						<div class="row gx-2 gy-2 h-100">
							<div class="col-sm-6 
										news__block_wrapper 
										p-2 
										position-relative">
								<a href="{{ $eventPage->fullUrl }}">
									<div class="news__block 
												p-lg-0 
												p-5
												h-100 
												d-flex 
												justify-content-center 
												align-items-center">
										<div class="news__block_inner 
													d-flex 
													justify-content-center 
													align-items-center 
													p-5 
													h-auto">
											<img src="/storage/images/events.svg" alt=""/>
										</div>
									</div>

									<div class="position-absolute news__block__title text-center">
										<h5 class="fw-bold">ღონისძიებები</h5>
									</div>
								</a>
							</div>

							<div class="col-sm-6 news__block_wrapper p-2 position-relative">
								<a href="{{ $publicationsPage->fullUrl }}">
									<div class="news__block 
												p-lg-0 
												p-5
												h-100 
												d-flex 
												justify-content-center 
												align-items-center">
										<div class="news__block_inner 
													d-flex 
													justify-content-center 
													align-items-center 
													p-5 
													h-auto">
											<img src="/storage/images/blog.svg" alt=""/>
										</div>
									</div>

									<div class="position-absolute news__block__title text-center">
										<h5 class="fw-bold">პუბლიკაციები</h5>
									</div>
								</a> 
							</div>
						</div>
					</div>

					<div class="col-lg-6 col-12">
						<a href="{{ $eventPage->fullUrl }}">
							<div class="news__image">
                    			<img class="" src="{{ asset('/storage/images/modules/about_us/93/'.$aboutPage->id.'_preview.jpg') }}" alt="{{ $aboutPage->title }}"/>
                            	 
								
								<div class="news__image_description p-2">
									<h4 class="news__image_description_title p-2">
										Talks of modern professions
									</h4>

									<div class="news__image_description_text p-2">
										It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="about_us
                    mb-5 
                    row  
                    p-md-0 
                    p-3">
            <div class="about_us__image 
                        col-xl-8 
                        col-12 
                        p-0">
                <div class="row about_us__stripe">
                    <div class="col-7 
                                about_us__stripe_white 
                                p-xl-4 
                                p-0"></div>
                    <div class="col-5 
                                about_us__stripe_blue 
                                p-xl-4 
                                p-0"></div>
                </div>

                <div class="row 
                            about_us__image_wrapper  
                            ps-0 
                            pt-0 
                            pe-md-5 
                            pb-md-5
                            pe-4
                            pb-4">
                    <img class="about_us__big_image" src="{{ asset('/storage/images/modules/about_us/93/'.$aboutPage->id.'_preview.jpg') }}" alt="{{ $aboutPage->title }}"/>
                </div>
            </div>

            <div class="about_us__description
                        col-xl-4
                        col-12
                        items-end
                        justify-content-center
                        p-sm-4
                        p-2
                        position-relative">
                <div class="row 
                            about_us__stripe_blue 
                            p-lg-3 
                            p-0">
                </div>

                <div class="about_us__text 
                            px-md-3 
                            px-2 
                            h-100 
                            w-100">
                    <h2 class="about_us__description_text p-2" >
                        {{ $aboutPage->title }}
                    </h2>

                    <div class="about_us__description_text p-2">
                        {!! $aboutPage->text !!}
                    </div>

                    <div class="p-2">
                        <div class="col-xxl-8
                                    col-xl-10
                                    col-lg-4
                                    col-sm-6
                                    col-10
                                    p-0
                                    yellow_button">
                            @include('includes.button_yellow', [
                                                'title' => 'შემოუერთდი ჩვენს ჯგუფს',
                                                'link' => $aboutPage->fullUrl,
                                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
		<div class="pertners pt-4">
			@include('modules.partners.step0')
		</div>

		<div class="container mt-5 bg-white p-md-0 p-3 pt-0">
			@include('modules.join_our_network.step0')
		</div>
	</div>
@endsection