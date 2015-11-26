<h3>Notificación automática Sistema de reservas fcom</h3>

<h3>Formulario de contacto</h3>

	<ul style ="list-style:none;padding:5px">
		<li><strong>Enviado por:</strong> {{$autor or ''}}</li>
		<li><strong>Título:</strong> <span>{{$titulo or  ''}}</span></li>
		<li><strong>Texto:</strong> <span>{{$texto or ''}}</span></li>

	</ul>
	<a href="mailto:{{$mail or ''}}">Responder a {{$autor or ''}} ({{$mail or ''}})</a>