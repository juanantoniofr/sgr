@extends('layout')

@section('head')
<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
<style>
  
    #font-lato {
        text-align: center;
        vertical-align: middle;
        font-weight: 100;
        font-family: 'Lato';
        font-size:56px;
        font-style: italic;
        color: #B0BEC5;
    }

</style>
@stop

@section('title')
    SGR: inicio
@stop



@section('content')
    <div class = "container">
        <p id = "font-lato">Sistema de Gestión de Reservas<br /> Facultad de Comunicación</p>
        <div class="col-md-12 col-sx-12 text-center" style = "">
            <a href="{{route('loginsso')}}" class="btn btn-primary" style="margin-top:10px;width:15%;">Login</a>
        </div>
        <div class="col-md-12 col-sx-12 text-center" style = "margin-top:10px;margin-bottom:50px">
            <a href="https://servidorfcom.us.es/" class="text-warning" style="">Ver ocupación curso 2014/2015</a>
        </div>
    </div>
@stop 