<html>
    <head>
        <title>App Name - @yield('pageMetaTitle')</title>

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

		<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/html_tags.css') }}">
    </head>
    <body>
		<header class="p-2 row">
			<div class="col-2 p-2">
				Logo
			</div>

			<div class="col-8 p-2">
				@include('modules.menu_buttons.basic')
			</div>

			<div class="col-2 p-2">
				@include('modules.languages.basic')
			</div>
		</header>


        @section('sidebar')
            This is the master sidebar.
        @show


        <div class="container">
            @yield('content')
        </div>


		<footer class="p-2 row">
			<div class="col-2 p-2">
				Copyright
			</div>

			<div class="col-8 p-2">
				Footer Menu
			</div>

			<div class="col-2 p-2">
				Created by HobbyStudio
			</div>

			<a href="/admin" target="_blank">
				Admin Panel
			</a>
		</footer>
    </body>
</html>