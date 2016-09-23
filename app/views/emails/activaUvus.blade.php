<?php $u = unserialize($user); ?>
<h3>Notificación automática SGR (Sistema de reservas fcom)</h3>

<h3>Actualización cuenta en SGR con UVUS {{$u->username}} </h3>

<ul>
	<li>Estado: @if($u->estado) Activada @else Desactivada @endif</li>
	<li>Caducidad: {{date('d-m-Y',strtotime($u->caducidad))}}</li>
</ul>

<p>Puede acceder a SGR con su Usuario Virtual en la url: <a target="_blank" href = "https://servidorfcom.us.es/sgr/wellcome">servidorfcom.us.es/sgr</a></p>