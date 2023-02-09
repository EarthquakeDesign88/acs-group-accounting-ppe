	$(document).ready(function () {
		// Serach Payable
		$("#searchaddTax").keyup(function () {
			var serachTextTax = $(this).val();
			if (serachTextTax != "") {
				$.ajax({
					url: "action_script_taxwithheld.php",
					method: "post",
					data: {
						queryTax: serachTextTax,
					},
					success: function (data) {
						$("#show-listTax").fadeIn();
						$("#show-listTax").html(data);
					},
				});
			} else {
				$("#show-listTax").fadeOut();
				$("#show-listTax").html("");
			}
		});

		// Set searched text in input field on click of search button
		$(document).on("click", "li.tax", function () {
			$("#searchaddTax").val($(this).text());
			$("#show-listTax").html("");
			$("#show-listTax").fadeOut();
			$("#list-group").hide();
		});

		document.getElementById("searchaddTax").addEventListener("search", function(event) {
			$(".list-unstyled").empty();
		});

	});