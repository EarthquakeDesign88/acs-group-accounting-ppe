		function chkNum(ele) {
			var num = parseFloat(ele.value);
			ele.value = num.toFixed(2);
		}

		function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
			try {
				decimalCount = Math.abs(decimalCount);
				decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

				const negativeSign = amount < 0 ? "-" : "";
				let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
				let j = (i.length > 3) ? i.length % 3 : 0;
				return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");

			} catch (e) {
				console.log(e)
			}
		};

		$(document).ready(function(){

			$(".form-control").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$("input").each(function() {
				$(this).click(function(){
					calculateSum();
				});
			});

			$("body").each(function() {
				$(this).click(function(){
					calculateSum();
				});
			});

			$(".numSub").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numPercent").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numVat").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numGrand").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numDiff").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numNet").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

		});

		function calculateSum() {

			//if (document.getElementsByClassName('chkVAT').value == '1'){

				var numSub = parseFloat($('.numSub').val());
				var numPercent = parseFloat($('.numPercent').val());
				var totalsVat = parseFloat(numSub * numPercent / 100) || 0;

				$('#sumVatShow').val(formatMoney(totalsVat));
				$('#sumVatHidden').val(totalsVat.toFixed(2));

				var numVat = parseFloat($('.numVat').val());
				var totalsGrand = parseFloat(numSub + numVat) || 0;

				$('#sumGrand').val(formatMoney(totalsGrand));
				$('#sumGrandHidden').val(totalsGrand.toFixed(2));

				var numGrand = parseFloat($('.numGrand').val());
				var numDiff = parseFloat($('.numDiff').val());
				var totalsNet = parseFloat(numGrand + numDiff) || 0;

				$('#sumNet').val(formatMoney(totalsNet));
				$('#sumNetHidden').val(totalsNet.toFixed(2));

			// } else {

			// 	var numNetVat = parseFloat($('.numNetVat').val());
			// 	var numPercent = parseFloat($('.numPercent').val());
			// 	var totalsVat = parseFloat((numNetVat * numPercent) / (100 + numPercent)) || 0;
			// 	var subtotal = parseFloat(numNetVat - totalsVat) || 0;
			// 	var grandtotal = parseFloat(subtotal + totalsVat) || 0;

			// 	$('#showSub').val(formatMoney(subtotal));

			// 	$('#sumGrand').val(formatMoney(grandtotal));
			// 	$('#sumGrandHidden').val(grandtotal.toFixed(2));

			// 	$('#showSub').val(formatMoney(subtotal));
			// }

		}