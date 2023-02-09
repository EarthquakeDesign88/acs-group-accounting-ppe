	$(document).ready(function(){
		// Serach Payable
		$("#searchCustomer").keyup(function () {
			var serachTextCust = $(this).val();
			if (serachTextCust != "") {
				$.ajax({
					url: "action_script_customer.php",
					method: "post",
					data: {
						queryCust: serachTextCust,
					},
					success: function (data) {
						$("#show-listCust").fadeIn();
						$("#show-listCust").html(data);
					},
				});
			} else {
				$("#show-listCust").fadeOut();
				$("#show-listCust").html("");
			}
		});

		// Set searched text in input field on click of search button
		$(document).on("click", "li.customer", function () {
			$("#searchCustomer").val($(this).text());
			$("#show-listCust").html("");
			$("#show-listCust").fadeOut();
			$("#list-group").hide();
		});

		document.getElementById("searchCustomer").addEventListener("search", function(event) {
			$(".list-unstyled").empty();  
		});

	});