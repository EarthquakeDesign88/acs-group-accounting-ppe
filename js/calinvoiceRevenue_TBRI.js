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
					checkInv();
				});
			});

			$("input").each(function() {
				$(this).click(function(){
					checkInv();
				});
			});

			$("body").each(function() {
				$(this).click(function(){
					checkInv();
				});
			});

		});


		function checkInv() {

			var numSubAmount3 = parseFloat($('#invresubdescHidden3').val());
			var numSubAmount4 = parseFloat($('#invresubdescHidden4').val());

			var totalSubAmount = parseFloat(numSubAmount4 - numSubAmount3);
			$('#invresubdesc5').val(totalSubAmount.toFixed(4));

			var numSubAmount6 = parseFloat($('#invresubdesc6').val());
			var numSubAmount7 = parseFloat((numSubAmount4 - numSubAmount3) * numSubAmount6);
			$('#invresubdesc7').val(numSubAmount7.toFixed(4));

			var numSubAmount8 = parseFloat($('#invresubdescHidden8').val());
			var numSubAmount = parseFloat((numSubAmount4 - numSubAmount3) * numSubAmount6 * numSubAmount8);

			$('#invresubdesc9').val(numSubAmount.toFixed(4));
			$('#invresubdescHidden9').val(numSubAmount.toFixed(4));
			// $('#amount2').val(numSubAmount.toFixed(2));
			// $('#amountHidden2').val(numSubAmount.toFixed(2));

			if($('#invresubdescHidden9').val() == '0.0000') {

			} else {
				$('#amount2').val(numSubAmount.toFixed(2));
				$('#amountHidden2').val(numSubAmount.toFixed(2));
			}

			var numAmount1 = parseFloat($('#amountHidden1').val());
			var numAmount2 = parseFloat($('#amountHidden2').val());
			var numAmount3 = parseFloat($('#amountHidden3').val());
			var numAmount4 = parseFloat($('#amountHidden4').val());
			var numAmount5 = parseFloat($('#amountHidden5').val());
			var numAmount6 = parseFloat($('#amountHidden6').val());
			var numAmount7 = parseFloat($('#amountHidden7').val());
			var numAmount8 = parseFloat($('#amountHidden8').val());
			var numPercent = parseFloat($('#calVatPercent').val());
			var numDiffVat = Number($('#calDiffVat').val());
			var numDiffGrand = Number($('#calDiffGrand').val());

			var totalSubT = parseFloat(numAmount1 + numAmount2 + numAmount3 + numAmount4 + numAmount5 + numAmount6 + numAmount7 + numAmount8) || 0;
			var totalVat = parseFloat(totalSubT * (numPercent / 100) + numDiffVat) || 0;

			var calVat = parseFloat($('#calVat').val());
			var totalGrandT = parseFloat(totalSubT + calVat + numDiffGrand) || 0;
			$('#showSubtotal').val(formatMoney(totalSubT));
			$('#showVat').val(formatMoney(totalVat));
			$('#showGrandtotal').val(formatMoney(totalGrandT));

			$('#calSubtotal').val(totalSubT.toFixed(2));
			$('#calVat').val(totalVat.toFixed(2));
			$('#calGrandtotal').val(totalGrandT.toFixed(2));

		}