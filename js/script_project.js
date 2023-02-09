	$(document).ready(function(){
		// Serach Payable
		$("#searchProject").keyup(function () {
			var serachTextProj = $(this).val();
			var compid = $('#compid').val();
			if (serachTextProj != "") {
				$.ajax({
					url: "action_script_project.php",
					method: "post",
					data: {
						queryProj: serachTextProj,
						queryComp: compid,
					},
					success: function (data) {
						$("#show-listProj").fadeIn();
						$("#show-listProj").html(data);
					},
				});
			} else {
				$("#show-listProj").fadeOut();
				$("#show-listProj").html("");
			}
		});

		// Set searched text in input field on click of search button
		$(document).on("click", "li.project", function () {
			$("#searchProject").val($(this).text());
			$("#show-listProj").html("");
			$("#show-listProj").fadeOut();
			$("#list-group").hide();
		});

		document.getElementById("searchProject").addEventListener("search", function(event) {
			$(".list-unstyled").empty();  
		});

	});