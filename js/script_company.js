	$(document).ready(function(){
		// Serach Payable
		$("#searchCompany").keyup(function () {
			var serachTextComp = $(this).val();
			if (serachTextComp != "") {
				$.ajax({
					url: "action_script_invoice.php",
					method: "post",
					data: {
						queryComp: serachTextComp,
					},
					success: function (data) {
						$("#show-listComp").fadeIn();
						$("#show-listComp").html(data);
					},
				});
			} else {
				$("#show-listComp").fadeOut();
				$("#show-listComp").html("");
			}
		});

		// Set searched text in input field on click of search button
		$(document).on("click", "li.company", function () {
			$("#searchCompany").val($(this).text());
			$("#show-listComp").html("");
			$("#show-listComp").fadeOut();
			$("#list-group").hide();
		});

		document.getElementById("searchCompany").addEventListener("search", function(event) {
			$(".list-unstyled").empty();
		});

	});