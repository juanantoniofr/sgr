<?php

class PdfController extends BaseController {

	/**
		* //Devuelve pdf con datos de reserva
		* @param Input::get('idEventos') string cadena 
		*
		* @return  Response::make($result)->header('Content-Type', 'application/pdf')
	*/
	public function build(){
		
		if (Evento::withTrashed()->where('evento_id','=',Input::get('idEventos'))->count() == 0) return View::make('pdf.msg'); 

		$event = Evento::withTrashed()->where('evento_id','=',Input::get('idEventos'))->first();
		$events = Evento::withTrashed()->where('evento_id','=',Input::get('idEventos'))->get();
			

		$recursos = Evento::where('evento_id','=',Input::get('idEventos'))->groupby('recurso_id')->get();
		
		setlocale(LC_TIME,'es_ES@euro','es_ES.UTF-8','esp');
		
	   	$strDayWeek = sgrDate::getStrDayWeek($event->fechaEvento);
		$strDayWeekInicio = sgrDate::getStrDayWeek($event->fechaInicio);
		$strDayWeekFin = sgrDate::getStrDayWeek($event->fechaFin);
		$created_at = ucfirst(strftime('%A %d de %B  a las %H:%M:%S',strtotime($event->created_at)));
		$impreso_at = ucfirst(strftime('%A %d de %B  a las %H:%M:%S',strtotime('now')));
	   
	    $html = View::make('pdf.justificante')->with(compact('event','events','strDayWeek','strDayWeekInicio','strDayWeekFin','recursos','created_at','impreso_at'));
	   	

	   	$result = myPDF::getPDF($html,'comprobante');

	   	return Response::make($result)->header('Content-Type', 'application/pdf');
	}


}