	$(document).ready(function(){
		// Serach Payable
		$("#searchPayment").keyup(function () {
			var serachTextPcash = $(this).val();
			var dep = $('#dep').val();
			if ($('#dep').val() != "") {
				if (serachTextPcash != "") {
					$.ajax({
						url: "action_script_pettycash.php",
						method: "post",
						data: {
							queryPcash: serachTextPcash,
							queryDep: dep,
						},
						success: function (data) {
							$("#show-listPettycash").fadeIn();
							$("#show-listPettycash").html(data);
						},
					});
				} else {
					$("#show-listPettycash").fadeOut();
					$("#show-listPettycash").html("");
				}
			} else {

			}
		});

		// Set searched text in input field on click of search button
		$(document).on("click", "li.pettycash", function () {
			$("#searchPayment").val($(this).text());
			$("#show-listPettycash").html("");
			$("#show-listPettycash").fadeOut();
			$("#list-group").hide();
		});

		document.getElementById("searchPayment").addEventListener("search", function(event) {
			$(".list-unstyled").empty(); 
		});

	});