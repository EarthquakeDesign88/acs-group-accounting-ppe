

var swal_alert = function(option = new Object()){
	var title = (option.title) ? option.title : "title";
	var text = (option.text) ? option.text : "text";
	var btnclose = (option.btnclose) ? option.btnclose : 1;


	var d = new Object();
	var d = {
		title: title,
		text: text,
		html: true,
		type: 'warning',
		cancelButtonText: "ปิด",
		showCancelButton: btnclose,
		showConfirmButton: false,
		closeOnClickOutside: false
	}

	

	if(!btnclose){
		d.timer = 800;
	}

	swal(d);
}



var swal_success = function(option = new Object()){
	var title = (option.title) ? option.title : "สำเร็จ";
	var text = (option.text) ? option.text : "กรุณารอสักครู่...";
	var fn = (option.fn) ? option.fn : null;
	var btnclose = (option.btnclose) ? option.btnclose : 0;
	var settime = 800;

	
	var d = new Object();
	var d = {
		title: title,
		text: text,
		html: true,
		type: 'success',
		cancelButtonText: "ปิด",
		showCancelButton: btnclose,
		showConfirmButton: false,
		closeOnClickOutside: false
	};

	
	if(!btnclose){
		d.timer = settime;
	}

	
	if(fn!=null){
		var swalAlert = swal;
		swalAlert(d,function(){
			if(!btnclose){
				clearInterval(settime);
				swalAlert.close();
			}

			
		  	if(fn!=null){
		  		fn();
		  	}
		}
	);

	}else{
		swal(d);

	}

}



var swal_fail = function(option = new Object()){
	var title = (option.title) ? option.title : "ไม่สำเร็จ";
	var text = (option.text) ? option.text : "กรุณารอสักครู่...";
	var fn = (option.fn) ? option.fn : null;
	var btnclose = (option.btnclose) ? option.btnclose : 0;


	var settime = 800;
	var d = new Object();
	d = {
		title: title,
		text: text,
		html: true,
		type: 'error',
		cancelButtonText: "ปิด",
		showCancelButton: btnclose,
		showConfirmButton: false,
		closeOnClickOutside: false
	};

	
	if(!btnclose){
		d.timer = settime;
	}

	

	if(fn!=null){
		var swalAlert = swal;
		swalAlert(d,function(){
			if(!btnclose){
				clearInterval(settime);
				swalAlert.close();
			}

			
		  	if(fn!=null){
		  		fn();
		  	}
		}
	);

	}else{

		swal(d);

	}

	

}





var swal_wait = function(option = new Object()){
	var title = (option.title) ? option.title : "กำลังดำเนินการ";
	var text = (option.text) ? option.text : "กรุณารอสักครู่...";
	var fn = (option.fn) ? option.fn : null;
	var settime  = (option.settime) ? option.settime : 1000;
	var swalAlert = swal;

	var

    closeInSeconds = settime,
    timer;


	swalAlert({
	  	title: title,
	  	text: text,
	  	html: true,
	    imageUrl: 'image/icon-spin-lg.gif',
	  	timer: settime,
		showCancelButton: false,
		showConfirmButton: false,
		closeOnClickOutside: false,
		buttons: false,
	},function(){
		clearInterval(settime);
		swalAlert.close();
	  	if(fn!=null){
	  		fn();
	  	}
	});

}



var swal_confirm = function(option = new Object()){

	var title = (option.title) ? option.title :"คุณต้องการดำเนินการใช่หรือไม่ ?";

	var text = (option.text) ? option.text : "";

	var fn  = (option.fn) ? option.fn : null;

	

	var swalAlert = swal;

	

	swalAlert({

	  	title: title,

	  	text: text,

		html: true,

	    imageUrl: 'image/icon-question-lg.png',

		showCancelButton: true,

		confirmButtonText: "ตกลง",

		cancelButtonText: "ยกเลิก",

		confirmButtonColor: '#28a745',

		closeOnConfirm: false,

		closeOnCancel: false,

		closeOnClickOutside: false,

		customClass: "swal-custom"

	},

	function(isConfirm) {

		if (isConfirm) {

			if(fn!=null){

				fn();

			}else{

				swalAlert.close();

			}

		} else {

			swal({

				title: "ยกเลิก",

				text: "กรุณารอสักครู่...",

				html: true,

				type: 'error',

				timer: 800,

				showCancelButton: false,

				showConfirmButton: false,

				closeOnClickOutside: false,

				customClass: "swal-custom"

			});

		}

	});

}



var swal_save = function(option = new Object()){

	var title = (option.title) ? option.title :"คุณต้องการบันทึกข้อมูลใช่หรือไม่ ?";

	var text = (option.text) ? option.text : "";

	var fn  = (option.fn) ? option.fn : null;

	

	var swalAlert = swal;

	

	swalAlert({

	  	title: title,

	  	text: text,

	  	html: true,

	    imageUrl: 'image/icon-save-lg2.png',

		showCancelButton: true,

		confirmButtonText: "บันทึกข้อมูล",

		cancelButtonText: "ไม่บันทึก",

  		html: "Testno  sporocilo za objekt: <b>test</b>",  

		confirmButtonColor: '#28a745',

		closeOnConfirm: false,

		closeOnCancel: false,

		closeOnClickOutside: false,

		customClass: "swal-custom",

		animation: true

	},

	function(isConfirm) {

		if (isConfirm) {

			if(fn!=null){

				fn();

				//swal_success({title:"บันทึกข้อมูลสำเร็จ",text:"กรุณารอสักครู่..."});

			}else{

				swalAlert.close();

			}

		} else {

			swal({

				title: "ยกเลิกการบันทึกข้อมูล",

				text: "กรุณารอสักครู่...",

				html: true,

				type: 'error',

				timer: 800,

				showCancelButton: false,

				showConfirmButton: false,

				closeOnClickOutside: false,

				customClass: "swal-custom"

			});

		}

	});

}



