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
			var checkbox = document.getElementById('totalVat');
			if (checkbox.checked != true) {

				document.getElementById('totalChkVat').value = "0";
				$('#showGrandtotal').prop('readonly', true);
				$('#showVatPercent').prop('readonly', false);
				// $('#showVatPercent').prop('value', '0.00');
				// $('#calVatPercent').prop('value', '0.00');

				$('#amount1').prop('readonly', false);
				$('#amount2').prop('readonly', false);
				$('#amount3').prop('readonly', false);
				$('#amount4').prop('readonly', false);
				$('#amount5').prop('readonly', false);
				$('#amount6').prop('readonly', false);
				$('#amount7').prop('readonly', false);
				$('#amount8').prop('readonly', false);
				$('#showDiffVat').prop('readonly', false);
				$('#showDiffGrand').prop('readonly', false);

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

			} else {

				document.getElementById('totalChkVat').value = "1";
				$('#showGrandtotal').prop('readonly', false);
				$('#showVatPercent').prop('value', '7.00');
				$('#calVatPercent').prop('value', '7.00');
				$('#showVatPercent').prop('readonly', true);

				$('#amount1').prop('readonly', true);
				$('#amount2').prop('readonly', true);
				$('#amount3').prop('readonly', true);
				$('#amount4').prop('readonly', true);
				$('#amount5').prop('readonly', true);
				$('#amount6').prop('readonly', true);
				$('#amount7').prop('readonly', true);
				$('#amount8').prop('readonly', true);
				$('#showDiffVat').prop('readonly', true);
				$('#showDiffGrand').prop('readonly', true);

				var numGrand = parseFloat($('#calGrandtotal').val());
				var numPercent = parseFloat($('#calVatPercent').val());
				var totalsSubT = parseFloat((numGrand * 100) / (100 + numPercent)) || 0;
				var totalVat = parseFloat((numGrand * numPercent) / (100 + numPercent)) || 0;
				$('#amount1').val(formatMoney(totalsSubT));
				$('#showVat').val(formatMoney(totalVat));
				$('#showSubtotal').val(formatMoney(totalsSubT));

				$('#amountHidden1').val(totalsSubT.toFixed(2));
				$('#calVat').val(totalVat.toFixed(2));
				$('#calSubtotal').val(totalsSubT.toFixed(2));

				$('#amount2').val('0.00');
				$('#amount3').val('0.00');
				$('#amount4').val('0.00');
				$('#amount5').val('0.00');
				$('#amount6').val('0.00');
				$('#amount7').val('0.00');
				$('#amount8').val('0.00');
				$('#amountHidden2').val('0.00');
				$('#amountHidden3').val('0.00');
				$('#amountHidden4').val('0.00');
				$('#amountHidden5').val('0.00');
				$('#amountHidden6').val('0.00');
				$('#amountHidden7').val('0.00');
				$('#amountHidden8').val('0.00');

			}
		}