$(document).ready(function(){
    $(document).on('click', '.systembets_button_div', function(){
        var systemid = $(this).attr("id");
        
	//if close --> open systembets
        if (!$(this).hasClass('systembets_button_div_open')){
            //set button to close
            $(this).addClass('systembets_button_div_open');
            
            //if div has no data the load data
            if ($( "#systembets_container_"+systemid).hasClass("hidden")) {
            
                $( "#systembets_container_"+systemid).removeClass('hidden');
            //if div has aleady data just show div
            } else {
                $( "#systembets_container_"+systemid).slideDown(500);
            }
        //close systembets
	} else {
            $( "#systembets_container_"+systemid).slideUp(500);
            $(this).removeClass('systembets_button_div_open');
        }	
    });
});