<!-- marca branch master2 --><!-- :) -->
<li class="dropdown ">
  <a href="{{Auth::user()->getHome()}}" title="Menú" class="dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-list fa-fw"></i> Menú  <span class="caret "></span></a>
  
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    
    <li ><a  href="{{Auth::user()->getHome()}}" title="Escritorio"><i class="fa fa-dashboard fa-fw"></i> Escritorio</a></li>

    <li><a href="{{route('calendarios.html')}}"><i class="fa fa-calendar fa-fw"></i> Calendarios</a></li>
    
    <li><a href="{{route('pod.html')}}"><i class="fa fa-upload fa-fw"></i>P.O.D</a></li>

    <li><a  href="{{route('validadorHome.html',array('verpendientes' => 1))}}"><i class="fa fa-check"></i> Validaciones</a></li>
   
    <li><a  href="{{route('users',array('veractivados' => 1))}}"><i class="fa fa-user fa-fw"></i> Usuarios<span class="fa arrow"></span></a></li>
    
    <li><a  href="{{route('recursos')}}"><i class="fa fa-institution fa-fw"></i> Espacios y equipos<span class="fa arrow"></span></a></li>

    <li><a  href="{{route('tecnicoHome.html')}}"><i class="fa fa-credit-card fa-fw"></i> Atención de reservas<span class="fa arrow"></span></a></li>
                
    <li><a href="config.html"><i class="fa fa-wrench fa-fw"></i> Configuración</a></li>
    
    <li><a  href="logs.html"><i class="fa fa-files-o fa-fw"></i> Logs</a></li>
             

  </ul>
            
</li>