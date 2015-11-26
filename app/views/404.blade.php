@extends('layout')

@section('head')
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

		body {
			margin:0;
			font-family:'Lato', sans-serif;
			text-align:center;
			color: #999;
		}

		.welcome {
			width: 300px;
			height: 200px;
			position: absolute;
			left: 50%;
			top: 50%;
			margin-left: -150px;
			margin-top: -100px;
		}

		a, a:visited {
			text-decoration:none;
		}

		h1 {
			font-size: 32px;
			margin: 16px 0 0 0;
		}
	</style>
@stop
@section('title')
    SGR: 404
@stop
@section('content')
<div class="container">
  <div class="row">

		<div class="welcome">
			<h2>La ruta no existe</h2>
			<p>Vaya a la página de <a href="{{route('loginsso')}}">Inicio</a></p>
			
		</div>
	
	</div>
</div>
@stop