@extends('layout')
 
@section('title')
    Admin:: carga P.O.D 
@stop
 
@section('content')
<div class="container">

  <div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-cogs fa-fw"></i> Gestión P.O.D</h3>
    </div>
  </div> <!-- /.row -->

  <div class="row">
      <div class="panel panel-info">
        
        <div class="panel-heading">
        <h4><i class="fa fa-upload fa-fw"></i> Cargar P.O.D</h4>
        </div>
        
        <div class="panel-body">
        
     
      

   



        <div class="col-lg-12">
          {{Form::open( array( 'url' => route('uploadPOD'), 'files' => true ) )}}
            
          
            

          <div class="form-group">
            {{Form::label('csvfile', 'Archivo csv:')}} 
            {{Form::file('csvfile', $attributes = array());}}
          </div>
            
              
          <button type="submit" class="btn btn-primary">Importar POD</button>
         <!--  <p class="text-info"><i class=" fa fa-file-code-o fa-fw"></i> Exportar a csv separando los campos por punto y coma.</p>
          -->
          
        {{Form::close()}}
      
  </div><!-- /.panel-body -->
   </div><!-- /.panel-default -->
   </div><!-- /.row -->
      @if (!empty($msgEmpty))
      
          <div class='alert alert-danger' rol='alert'>{{ $msgEmpty }}</div>
      
      @endif

      
       <!-- result export -->
      <br /> 
      @if (!empty($events))
      <div class="panel panel-success">
        
        <div class="panel-heading">
          <i class="fa fa-check fa-fw"></i> Eventos importados con éxito
        </div>
        
        <div class="panel-body" style="height:350px;overflow:scroll">
         
          
          
            <table class="table table-striped">
            <tr>
              <th>Fila</th>
              <th>Id. Lugar</th>
              <th>F. Inicio</th>
              <th>F. Fin</th>
              <th>Día</th>
              <th>H. Inicio</th>
              <th>H. Fin</th>
              <th>Lugar</th>
              <th>Asignatura</th>
              <th>Profesor</th>
              <th>Cod. día Semana</th>
            </tr>
            @foreach($events as $numFila => $contentFila)
              <tr>
                <td>{{ $numFila }}</td>
          
                @foreach($contentFila as $valueColumn)  
                  <td>{{ $valueColumn }}</td>
                @endforeach
              
              </tr>
            @endforeach
            
            </table>
          
          
         </div><!-- .//panel-body -->
      </div><!-- .//panel-success -->   
      @endif


      @if (!empty($noexistelugar))
        <div class="panel panel-danger">
        
          <div class="panel-heading">
            <i class="fa fa-ban fa-fw"></i> <strong>Error al guardar:</strong>: No existe Espacio o Lugar <small>(los eventos no se guardaron)</small>.
          </div>
          
          <div class="panel-body" style="height:350px;overflow:scroll">  
          
            
            <table class="table table-striped">
              <tr>
                  <th>Fila</th>
                  <th>Id. Lugar</th>
                  <th>F. Inicio</th>
                  <th>F. Fin</th>
                  <th>Día</th>
                  <th>H. Inicio</th>
                  <th>H. Fin</th>
                  <th>Lugar</th>
                  <th>Asignatura</th>
                  <th>Profesor</th>
                  <th>Cod. día Semana</th>
              </tr>

              @foreach($noexistelugar as $numerofila => $aFila)
              <tr>
                  <td>{{ $numerofila }}</td>
                  
                  @foreach($aFila as $valor)  
                    <td>{{ $valor }}</td>
                  @endforeach
                
                </tr>
              @endforeach
         
            </table>
          </div><!-- .//panel-body -->
      </div><!-- .//panel-danger -->
     @endif    
    
    @if (!empty($solapescsv))
      <div class="panel panel-danger" >
        
        <div class="panel-heading">
          <i class="fa fa-ban fa-fw"></i> <strong>Error al guardar:</strong> Solapamientos en archivo csv <small>(los eventos no se guardaron)</small>.  
        </div>
        
        <div class="panel-body" style="height:350px;overflow:scroll">  
        
          
          <table class="table table-striped">
            <tr>
              <th>Fila</th>
              <th>Id. Lugar</th>
              <th>F. Inicio</th>
              <th>F. Fin</th>
              <th>Día</th>
              <th>H. Inicio</th>
              <th>H. Fin</th>
              <th>Lugar</th>
              <th>Asignatura</th>
              <th>Profesor</th>
              <th>Cod. día Semana</th>
            </tr>

          @foreach($solapescsv as $numerofila => $aFila)
            <tr>
                <td>{{ $numerofila }}</td>
                
                @foreach($aFila as $valor)  
                  <td>{{ $valor }}</td>
                @endforeach
              
              </tr>
            
          
          @endforeach
       
        </table>
      </div><!-- .//panel-body -->
    </div><!-- .//panel-danger -->
    @endif

     @if (!empty($solapesdb))
      <div class="panel panel-danger" style="height:350px;overflow:scroll">
        
        <div class="panel-heading">
          <i class="fa fa-ban fa-fw"></i> <strong>Error al guardar:</strong> Solapamientos con eventos existentes <small>(los eventos no se guardaron)</small>.  
        </div>
        
        <div class="panel-body">  
        
          
          <table class="table table-striped">
            <tr>
              <th>Fila</th>
              <th>Id. Lugar</th>
              <th>F. Inicio</th>
              <th>F. Fin</th>
              <th>Día</th>
              <th>H. Inicio</th>
              <th>H. Fin</th>
              <th>Lugar</th>
              <th>Asignatura</th>
              <th>Profesor</th>
              <th>Cod. día Semana</th>
            </tr>

          @foreach($solapesdb as $numerofila => $aFila)
            <tr>
                <td>{{ $numerofila }}</td>
                
                @foreach($aFila as $valor)  
                  <td>{{ $valor }}</td>
                @endforeach
              
              </tr>
            
          
          @endforeach
       
        </table>
      </div><!-- .//panel-body -->
    </div><!-- .//panel-danger -->
    @endif
</div><!-- ./container -->
@stop