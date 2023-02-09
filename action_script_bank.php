	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	
	<script type="text/javascript">
		$('#SelBank').change(function() {

			var id_bank = $(this).val();
			$.ajax({
				type: "POST",
				url: "ajax_bank.php",
				data: {id:id_bank,SelBank:'SelBank'},
				success: function(data){
					$('#SelBranch').html(data);
				}
			});

		});
	</script>