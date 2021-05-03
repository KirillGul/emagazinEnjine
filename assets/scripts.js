function SetCookie(cityNum){	
	var ws=new Date(); ws.setDate(1+ws.getDate());
	document.cookie="city=" + cityNum + ";"   
	location.href=window.location;  
}

function middleButton(e) {
	if (e.which == 2) {
		open(this.getAttribute("data-href"), "_blank");
		return false;
    }
    return true;
}

function goodLoadImg(a){ 
	a.parentNode.parentElement.classList.remove('camera'); 
}
function errLoadImg(a){
	goodLoadImg(a);		
	a.src='/assets/camera.png';		
}

$(document).ready(function(){	
	document.querySelectorAll(".outli").forEach(function(item){item.addEventListener("mousedown", middleButton);});
	$('.citybutton').click(function(){ 
		$('.citywindow,.citywindow_overlay').fadeIn(500);
	});
	$('.citywindow_closer,.citywindow_overlay').click(function(){
		$('.citywindow,.citywindow_overlay').fadeOut(500);
	});
});
