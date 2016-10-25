@extends('layouts.main', ['jumbotron' => true])

@section('jumbotron')
	<div class="row text-center-xs text-left-md">
		<div class="col-xs-12 col-md-3">
			<div class="col-xs-4 col-xs-offset-4 col-md-offset-0 col-md-12">
				<img src="./light-logo.png" class="logo">
			</div>
		</div>
		<div class="col-xs-12 col-md-9">
			<h1>Goalfeed.ca</h1>
			<p>Goalfeed provides nearly instant notifications every time a goal is scored in the NHL.</p>
			<p>Currently, we offer an extension for Google Chrome to trigger a goal-light light sequence for your Philips Hue lights.</p>
			<p>
				<a href="https://chrome.google.com/webstore/detail/chrome-goalfeed-for-phili/fgcbighghabceookojahkojodedbgfoc?hl=en-US&gl=CA&authuser=0">Check us out in the Chrome Store!</a>
			</p>
		</div>
	</div>
@endsection

@section('content')
	<!-- Example row of columns -->
	<div class="row">
		<div class="col-md-6">
			<h2>Fast!</h2>
			<p>Goalfeed is the fastest NHL trigger source for your Philips Hue lights.</p>
		</div>
		<div class="col-md-6">
			<h2>Growing!</h2>
			<p>We're working to add support for additional sports and leagues as well as clients for other platforms!</p>
		</div>
	</div>
@endsection
