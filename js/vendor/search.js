$(document).ready(function(){
	$("#suggest").keyup(function(){
		if (($(this).val().length)>2) {
		$.get("pagine/suggest.php", {company: $(this).val()}, function(data){
			$("datalist").empty();
			$("ol").html(data);
		});
		}
		else 
			$("ol").empty();
	});
});