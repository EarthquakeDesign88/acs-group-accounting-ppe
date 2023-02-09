	$(document).ready(function(){
		// Serach Payable
		$("#invpurcno").keyup(function () {
			var serachTextPurc = $(this).val();
			if (serachTextPurc != "") {
				$.ajax({
					url: "action_script_invoice.php",
					method: "post",
					data: {
						queryPurc: serachTextPurc,
					},
					success: function (data) {
						$("#show-listPurchase").fadeIn();
						$("#show-listPurchase").html(data);
					},
				});
			} else {
				$("#show-listPurchase").fadeOut();
				$("#show-listPurchase").html("");
			}
		});

		// Set searched text in input field on click of search button
		$(document).on("click", "li.purchase", function () {
			$("#invpurcno").val($(this).text());
			$("#show-listPurchase").html("");
			$("#show-listPurchase").fadeOut();
			$("#list-group").hide();
		});

		document.getElementById("invpurcno").addEventListener("search", function(event) {
			$(".list-unstyled").empty();  
		});

	});