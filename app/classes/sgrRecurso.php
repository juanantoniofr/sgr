<?php 

	class sgrRecurso{
		/* :) 1-5-2017 */
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

		

	}
?>