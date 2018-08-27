function potvrdiIPosalji(pitanje){
	return confirm(pitanje);
}

function zapamtiSelektovano(forma, element, vrednost){
	var selektovanaLista = forma.elements[element]; 
	for(var i=0; i<selektovanaLista.length; i++){
		
		if(selektovanaLista.options[i].value == vrednost)
			selektovanaLista.options[i].selected = true;
	}
}

function createRequest(){											// ajax 			
	if(window.XMLHttpRequest){
		var req = new XMLHttpRequest();
	}
	else{
		var req = new ActiveXObject(Microsoft.XMLHTTP);
	}
	return req;
}

$(document).ready(function(){										// funkcija za time picker jquery plugin

	$('#time').timepicker();
	
});

