<div class = "day {{ $view }} @if($isDayAviable) formlaunch @else disable @endif" id = {{date('jnYGi',mktime($hour,$min,0,$mon,$day,$year))}} data-fecha="{{date('j-n-Y',mktime($hour,$min,0,$mon,$day,$year))}}" data-hora="{{date('G:i',mktime($hour,$min,0,$mon,$day,$year))}}">

    <div class="titleEvents"> @if($view == 'month') <small>{{ $day }}</small>@endif </div>
    
    <div class="divEvents" data-numero-de-eventos="{{count($currentday->events())}}">
        @foreach($currentday->events($id_recurso,$id_grupo) as $event)
            <div class="divEvent" data-fecha="{{date('j-n-Y',mktime($hour,$min,0,$mon,$day,$year))}}" data-hora="{{substr($event->horaInicio,0,2)}}">
            
            @if (count($currentday->events($id_recurso,$id_grupo)) > 4) <a style="display:none" class="cerrar" href="">Cerrar</a>@endif
           
                <a class = " 
                        @if ($event->solape(mktime(0,0,0,(int) $mon,(int) $day,(int) $year)) && $event->estado != 'aprobada')   text-danger
                        @elseif($event->estado == 'aprobada')   text-success
                        @elseif ($event->estado == 'pendiente') text-info
                        @elseif ($event->estado == 'denegada')  text-warning
                        @endif
                        linkpopover linkEvento {{$event->evento_id}}  {{$event->id}}"
                        id="{{$event->id}}" 
                        data-id-serie="{{$event->evento_id}}" data-id="{{$event->id}}"  href="" rel="popover" data-html="true" 
                        
                        data-title="
                            @if($view != 'week') 
                                {{ Date::parsedatetime($event->horaInicio,'H:i:s','G:i')}}-{{Date::parsedatetime($event->horaFin,'H:i:s','G:i')}}
                            @endif
                            
                            {{ $event->titulo }}
                            {{ htmlentities('<a href="" class="closePopover"> X </a>') }}
                            " 
                        data-content="{{htmlentities(sgrEvento::tooltip($event,$day,$mon,$year,$hour,$min))}}">
                        @if ($event->solape(mktime(0,0,0,(int) $mon,(int) $day,(int) $year)) && $event->estado != 'aprobada')
                            <span data-toggle="tooltip" title="Solicitud con solapamiento" class="fa fa-exclamation fa-fw text-danger" aria-hidden="true"></span>
                        @else
                        <!-- Icono -->
                        <span   data-toggle="tooltip" title="Solicitud {{$event->estado}}" 
                                class=" fa fa-fw
                                    @if($event->estado == 'aprobada') fa-check text-success
                                    @elseif ($event->estado == 'pendiente') fa-question text-info
                                    @elseif ($event->estado == 'denegada') fa-ban text-warning
                                @endif" 
                                aria-hidden="true"></span>
                        <!-- ./Icono -->
                         @endif
                         <!-- Title -->
                         @if($view != 'week') 
                            {{ Date::parsedatetime($event->horaInicio,'H:i:s','G:i')}}-{{Date::parsedatetime($event->horaFin,'H:i:s','G:i')}}
                        @endif
                        {{ $event->titulo }}
                        <!-- ./Title -->
                </a>
               
            
            </div> <!-- ./divEvent -->  
        @endforeach
    </div> <!-- ./divEvents -->
        
    @if (count($currentday->events($id_recurso,$id_grupo)) > 4) <a class="linkMasEvents" href=""> + {{ (count($currentday->events($id_recurso,$id_grupo))-4) }}  más </a>@endif
          
</div>
