@if (Session::has('success'))
	<div class="alert alert-success" role="alert">
		<strong>Success:</strong> {{ Session::get('success') }}
	</div>
	 {{Session::forget('success')}} 
@endif

@if (Session::has('otherErrors'))
	<div class="alert alert-danger" role="alert">
		<strong>Errors:</strong> {{ Session::get('otherErrors') }}
	</div>
	 {{Session::forget('otherErrors')}} 
@endif

@if (isset($ApprvMessage))
	<div class="alert alert-success" role="alert">
		{{ $ApprvMessage }}
	</div>
@endif

@if(count($errors) > 0)

	<div class="alert alert-danger" role="alert">
		<strong>Errors:</strong>
		<ul>
		@foreach ($errors->all() as $error)
			<li> {{ $error }} </li>
		@endforeach
		</ul>
	</div>

@endif