<li class="dropdown ">
  <a href="{{Auth::user()->getHome()}}" title="Menú" class="dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-list fa-fw"></i> Menú  <span class="caret "></span></a>
  
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    
    <li ><a  href="{{Auth::user()->getHome()}}" title="Escritorio"><i class="fa fa-dashboard fa-fw"></i> Escritorio</a></li>

    <li><a href="{{route('calendarios.html')}}"><i class="fa fa-calendar fa-fw"></i> Calendarios</a></li>
    
    @if (Auth::user()->capacidad == '6')
    <li><a  href="{{route('recursos')}}"><i class="fa fa-institution fa-fw"></i> Espacios y equipos<span class="fa arrow"></span></a></li>
    <!-- <li><a  href="{{route('informes')}}"><i class="fa fa-wrench fa-fw"></i> </a></li> -->
    @endif         

  </ul>
            
</li>