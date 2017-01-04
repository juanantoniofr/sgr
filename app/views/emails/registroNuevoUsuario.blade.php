<!-- marca branch master2 --><?php $u = unserialize($user); ?>
<style>

* {
	font-family:verdana;
	font-size: 12px; 
}

div{
	border-top:none;
	border-bottom: 1px solid #333;
	border-top: 1px solid #333;
	margin-top:20px;
}

#title {
	font-size: 14px;
}

.subtitle{
	font-style: italic;
}

span {
	color:blue;
}

p.label{text-align:right;font-size:12px}

table {
	margin-top:10px;
	padding:20px;
	width: 100%;

}
 td {
 	border:1px solid #aaa;
 }
#first{
	background-color: #aaa;
}
#estado {
	boder:1px solid green;
}
</style>
<h3>Notificación automática SGR</h3>

<h3>Petición de alta de usuario con UVUS: {{$u->username}}</h3>

<div style = "border:1px solid #666;padding:10px;line-height:1.6em"> 
<h4><strong>Datos del Usuario:</strong></h4>

	<ul style ="list-style:none;padding:5px">
		<li class = 'subtitle'><strong>Nombre:</strong> <span>{{$u->nombre}}</span></li>
		<li id = 'title'><strong>Apellidos:</strong> <span>{{$u->apellidos}}</span></li>
		
	</ul>
</div>