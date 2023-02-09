function onLoadWindow() {
	var bodyset = document.body;
	var classes = bodyset.getAttribute('class').split(' ');
	var contains = classes.indexOf('loadwindow') > -1;
	var bf =  window.getComputedStyle(bodyset, ':before');                           
	
	var divElement = document.createElement("div");
	divElement.className = "loadwindow-fade";
	//bodyset.appendChild(divElement);
	
	var fade = document.getElementsByClassName('loadwindow-fade');

	if(contains) {
		var timer = 500;
		var d = 1;
		var cal = 10/timer;
		for(var i = 0;i<=timer;i++){
			setTimeout(function(){
				//divElement.style.opacity = cal;

				if(d>=timer){
					bodyset.classList.remove("loadwindow");
				}
				
				cal+=0.1;
				d++;
			},timer);
		}
		
	}
}

function onLoadWindowNoclass() {
	var bodyset = document.body;                        
	
	var divElement = document.createElement("div");
	divElement.className = "loadwindow-fade";
	var fade = document.getElementsByClassName('loadwindow-fade');

	var timer = 500;
	var d = 1;
	var cal = 10/timer;
	for(var i = 0;i<=timer;i++){
		setTimeout(function(){
			//divElement.style.opacity = cal;

			if(d>=timer){
				bodyset.classList.add("loadwindow-remove");
			}
			
			cal+=0.1;
			d++;
		},timer);
	}
}

document.addEventListener('DOMContentLoaded', function () {
 	onLoadWindowNoclass();
});