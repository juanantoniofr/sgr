<?php 

	class sgrRecurso{

		protected $recurso; //obj Recurso
		protected $items = array(); //array de obj sgrRecurso 
		protected $contendor; //Obj Recurso || null
		
		public function __construct($recurso = ''){
			if (empty($recurso)) 	$this->recurso = new Recurso;
			else 									$this->recurso = $recurso;
			
			foreach ($recurso->items as $item) {
				$this->items[] = new recursoItem($item);
			}
			$this->contenedor = $this->recurso->contenedor;
			//if ($this->recurso->contenedor != null)		$this->contenedor = $this->recurso->contenedor;
			//else $this->contenedor = null;

			return $this;
		}

		public function recurso(){
			
			return $this->recurso;
		}
		public function id(){
			
			return $this->recurso->id;
		}

		public function tipo(){
			
			return $this->recurso->tipo;
		}

		public function nombre(){
			
			return $this->recurso->nombre;
		}

		public function isAdministrador($id){

			return $this->recurso->administradores->contains($id);
		}
		
		public function isDisabled(){

			return $this->recurso->disabled; 
		}

		public function items(){

			return $this->items;
		}

		public function contenedor(){
			
			return $this->contenedor;
		}

		public function save(){
		
			return $this->recurso->save();
		}

		//Eventos
		/**
			* //Devuelve los eventos entre $fechas con estado en $estado
			* @param $fini int timestamp fecha inicial
			*	@param $estado array estado de los eventos a obtener (aprobada | denegada | pendiente)
			* @param $ffin int timestamp fecha final
			*	@return Collection Objets type Evento 
			*
		*/
		public function getEventos($fini = '',$estados = array(),$ffin = ''){//
			/*
				//sino se especifica ningún estado para los eventos a obtener se obtienen todos independientemente de su estado
				'estadoEvento' => array('denegada',
																'aprobada',
																'pendiente',
																'finalizada',
																'anulada',
																'liberada',)
			*/
			if (empty($estados)) $estados[] = Config::get('options.estadosEvento');  
			
			$aFechas = array($fini,$ffin);
			if (empty($fini)) $aFechas[0] = strtotime('1970-1-1');
			if (empty($ffin))	$aFechas[1] = Config::get('options.maxtimestamp');
			

			$datos = array('aFechas' => $aFechas, 'estados' => $estados);
			
			
			$eventos = $this->recurso->eventos->filter(function($evento) use ($datos){
				return $evento->fechaEvento >= date('Y-m-d',$datos['aFechas'][0]) && $evento->fechaEvento <= date('Y-m-d',$datos['aFechas'][1]) && in_array($evento->estado,$datos['estados']);
			});
			
			return $eventos;
		}

		


	}
?>