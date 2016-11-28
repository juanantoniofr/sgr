@extends('layout')

@section('head')
<style>
  .container{margin-top:10px;}
</style>
@stop
@section('title')
    SGR: Ayuda a usuarios
@stop

@section('content')
<div class="container">
  <div class="row">
    
    <h2>SGR: Ayuda a usuarios</h2>
    

    <div class="col-sm-6 col-md-4">
      <h3>Acceso</h3>
      <div class="thumbnail">
        <div class="embed-responsive embed-responsive-4by3">
          <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/tuyk33tNVmw?rel=0&HD=1" frameborder="0" allowfullscreen HD></iframe>
        </div>

        <div class="caption">
          
          <p>Integramos el acceso a la aplicación <abbr title="Sistema de Gestión de Reservas">SGR</abbr> con el servicio <abbr title="Single Sign-On">sso</abbr> de la Universidad de Sevilla, así, los alumnos, <abbr title="Personal Docente e Investigador">PDI</abbr> y <abbr title="Personal de Administración y Servicios">PAS</abbr> de la Facultad de Comunicación pueden acceder a la aplicación  con su <abbr title="Usuario Virtual de la Universidad de sevilla.">UVUS</abbr>.</p>
          
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-md-4">
      <h3>Reserva puntual</h3>
      <div class="thumbnail">
        <div class="embed-responsive embed-responsive-4by3">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/v2SNAtQipHo?rel=0&HD=1" frameborder="0" allowfullscreen HD></iframe>
        </div>
        
        <div class="caption">
          
          <p>Las reservas puntuales son aquellas que se limitan a un día concreto y no se repiten de forma periódica. Por ejemplo: reservar el martes 12 de noviembre de 8:30 a 12:30. </p>
          
        </div>
      </div>
    </div>
    
    <div class="col-sm-6 col-md-4">
      <h3>Reserva periódica</h3>
      <div class="thumbnail">
        <div class="embed-responsive embed-responsive-4by3">
           
            
            <iframe width="560" height="315" src="https://www.youtube.com/embed/Zbh-0kbqba4?rel=0&HD=1" frameborder="0" allowfullscreen HD></iframe>
        </div>
        
        <div class="caption">
          
          <p><abbr title="Sistema de Gestión de Reservas">SGR</abbr> permite realizar reservas periódicas con una frecuencia de repetición de una semana. Por ejemplo: reservar todos los martes y miércoles desde el 15 de octubre hasta el 21 de enero de 10:30 a 12:30. </p>
          
        </div>
      </div>
    </div>    
    

    <div class="col-sm-6 col-md-4">
      <h3>Guardar o imprimir comprobante</h3>
      <div class="thumbnail">
        <div class="embed-responsive embed-responsive-4by3">
           
            
            <iframe width="560" height="315" src="https://www.youtube.com/embed/yMK0qwtjMno?rel=0&HD=1" frameborder="0" allowfullscreen HD></iframe>
        </div>
        
        <div class="caption">
          
          <p>Podemos guardar o imprimir un comprobante de nuestra reserva en <abbr title="Sistema de Gestión de Reservas">SGR</abbr> justo después de salvarla o en cualquier momento accediendo a la ventana  contextual como se muestra en el video.</p>
          
        </div>
      </div>
    </div> 

    <div class="col-sm-6 col-md-4">
      <h3>Editar/eliminar reserva</h3>
      <div class="thumbnail">
        <div class="embed-responsive embed-responsive-4by3">
          <iframe width="560" height="315" src="https://www.youtube.com/embed/Iy55ZQ4TfdU?rel=0&HD=1" frameborder="0" allowfullscreen HD></iframe>
        </div>
        <div class="caption">
          
          <p>Al editar podemos modificar todos los valores que definen una reserva: título, horario, fecha, periocidad, actividad... Si la reserva es periódica, tanto al eliminar como al editar, los cambios afectan a toda la serie. Desde la ventana contextual de detalle podemos acceder a las opciones de edición y eliminación.</p>
          
        </div>
      </div>
    </div> 


     <div class="col-sm-6 col-md-4">
      <h3>Imprimir ocupación</h3>
      <div class="thumbnail">
        <div class="embed-responsive embed-responsive-4by3">
          <iframe width="560" height="315" src="https://www.youtube.com/embed/fQxW_aI0auk?rel=0&HD=1" frameborder="0" allowfullscreen HD></iframe>
        </div>
        <div class="caption">
          <p> En <abbr title="Sistema de Gestión de Reservas">SGR</abbr> podemos imprimir o descargar calendarios con la ocupación desde las dos vistas disponibles: mensual y semanal.</p>
          
        </div>
      </div>
    </div> 

  </div>
</div>


@stop