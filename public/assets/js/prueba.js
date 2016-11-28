$(function(e){

	$('#SCReader').on('change',function(e){
		
		json = lector.getJsonObject().toString();//get("numeroSerie");
		numSerie = lector.getJsonObject().get("numeroSerie");
		//console.log($('#lector'));
		//console.log(json);
		//console.log(numSerie);
		console.log(lector);
		});


});