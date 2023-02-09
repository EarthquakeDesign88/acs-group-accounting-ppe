	$(document).ready(function () {
		// Serach Payable
		$("#searchTaxwithheld").keyup(function () {
			var serachTextTax = $(this).val();
			if (serachTextTax != "") {
				$.ajax({
					url: "action_script_taxwithheld.php",
					method: "post",
					data: {
						queryTax: serachTextTax,
					},
					success: function (data) {
						$("#show-listTaxwithheld").fadeIn();
						$("#show-listTaxwithheld").html(data);
					},
				});
			} else {
				$("#show-listTaxwithheld").fadeOut();
				$("#show-listTaxwithheld").html("");
			}
		});

		// Set searched text in input field on click of search button
		$(document).on("click", "li.tax", function () {
			$("#searchTaxwithheld").val($(this).text());
			$("#show-listTaxwithheld").html("");
			$("#show-listTaxwithheld").fadeOut();
			$("#list-group").hide();
		});

		document.getElementById("searchTaxwithheld").addEventListener("search", function(event) {
			$(".list-unstyled").empty();
		});

	});