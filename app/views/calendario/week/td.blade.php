<!-- marca branch master2 -->@if (!$sgrDia->festivo())
	
@endif
<div 
  class = "day week @if(  $sgrDia->reservable(Auth::user()->id) && !$sgrDia->festivo()) formlaunch @else disable @endif @if($sgrDia->festivo()) festivo @endif" 
  id = "{{date('jnYGi',$sgrDia->timestamp($hora,'30'))}}" 
  data-fecha="{{date('j-n-Y',$sgrDia->timestamp())}}" 
  data-hora="{{date('G:i',$sgrDia->timestamp($hora,'30'))}}"
  style="width:100%;">
	<div style="position:absolute;border-bottom:1px dotted #345;top:{{$j*82;}}px;height:41px;width:100%;"></div>

</div>
