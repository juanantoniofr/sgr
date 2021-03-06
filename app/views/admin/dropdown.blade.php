<li class="dropdown ">
  <a href="{{$sgrUser->home()}}" title="Menú" class="dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-list fa-fw"></i> Menú  <span class="caret "></span></a>
  
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    
    <li>
      <a  href="{{$sgrUser->home()}}" title="Escritorio"><i class="fa fa-dashboard fa-fw"></i> Escritorio</a>
    </li>

    <li>
      <a href="{{route('calendarios.html')}}"><i class="fa fa-calendar fa-fw"></i> Calendarios</a>
    </li>

    @if ($sgrUser->esValidadorSgr())
      <li>
        <a  href="{{route('validadorHome.html',array('verpendientes' => 1))}}"><i class="fa fa-check"></i> Validaciones</a>
      </li>
    @endif   
    <li>
      <a  href="{{route('recursos.html')}}"><i class="fa fa-institution fa-fw"></i> Espacios y equipos<span class="fa arrow"></span></a>
    </li>

    @if ($sgrUser->esAdminSgr())
      <li>
        <a href="{{route('pod.html')}}"><i class="fa fa-upload fa-fw"></i>P.O.D</a>
      </li>
      <li>
        <a  href="{{route('users')}}"><i class="fa fa-users fa-fw"></i> Usuarios<span class="fa arrow"></span></a>
      </li>
      <li>
        <a href="config.html"><i class="fa fa-wrench fa-fw"></i> Configuración</a>
      </li>
      <li>
        <a  href="logs.html"><i class="fa fa-files-o fa-fw"></i> Logs</a>
      </li>
    @endif
  </ul>
</li>