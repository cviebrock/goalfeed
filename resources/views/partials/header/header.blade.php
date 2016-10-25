<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">
				Goalfeed</a>
		</div>

		<div id="navbar" class="navbar-collapse collapse">
			@if (Auth::guest())
				<span class="navbar-form navbar-right">

					<a class="btn btn-success" href="{{ url('/login') }}">Login</a>
					<a class="btn " href="{{ url('/register') }}">Register</a>
				</span>
			@else
				<a href="{{ url('/logout') }}"
						onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
					Logout
				</a>

				<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
					{{ csrf_field() }}
				</form>
			@endif

		</div><!--/.navbar-collapse -->
	</div>
</nav>

