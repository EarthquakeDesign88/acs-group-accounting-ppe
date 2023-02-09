	$(document).ready(function(){
		// Serach Payable
		$("#searchPayable").keyup(function () {
			var serachTextPaya = $(this).val();
			if (serachTextPaya != "") {
				$.ajax({
					url: "action_script_invoice.php",
					method: "post",
					data: {
						queryPaya: serachTextPaya,
					},
					success: function (data) {
						$("#show-listPaya").fadeIn();
						$("#show-listPaya").html(data);
					},
				});
			} else {
				$("#show-listPaya").fadeOut();
				$("#show-listPaya").html("");
			}
		});

		// Set searched text in input field on click of search button
		$(document).on("click", "li.payable", function () {
			$("#searchPayable").val($(this).text());
			$("#show-listPaya").html("");
			$("#show-listPaya").fadeOut();
			$("#list-group").hide();
		});

		document.getElementById("searchPayable").addEventListener("search", function(event) {
			$(".list-unstyled").empty();  
		});

	});