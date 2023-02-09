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

		// function addCommas(nStr) {
		// 	nStr += '';
		// 	x = nStr.split('.');
		// 	x1 = x[0];
		// 	x2 = x.length > 1 ? '.' + x[1] : '';
		// 	var rgx = /(\d+)(\d{3})/;
		// 	while (rgx.test(x1)) {
		// 		x1 = x1.replace(rgx, '$1' + ',' + '$2');
		// 	}
		// 	return x1 + x2;
		// }

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

			$(".numSubNoV").each(function() {
				$(this).keyup(function(){
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

			$(".numTax1").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numTaxPercent1").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numTaxTotal1").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numTax2").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numTaxPercent2").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numTaxTotal2").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numTax3").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numTaxPercent3").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".numTaxTotal3").each(function() {
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

			var numSubNoV = parseFloat($('.numSubNoV').val());

			var numSub = parseFloat($('.numSub').val());
			var numPercent = parseFloat($('.numPercent').val());
			var numVatDiff = parseFloat($('.numVatDiff').val());
			var totalsVat = parseFloat(((numSub * numPercent) / 100) + numVatDiff) || 0;
			$('#sumVatShow').val(formatMoney(totalsVat));
			$('#sumVatHidden').val(totalsVat.toFixed(2));

			var numTax1 = parseFloat($('.numTax1').val());
			var numTaxPercent1 = parseFloat($('.numTaxPercent1').val());
			var numTaxDiff1 = parseFloat($('.numTaxDiff1').val());
			var totalsTax1 = parseFloat(((numTax1 * numTaxPercent1) / 100) + numTaxDiff1) || 0;
			$('#sumTaxTotal1').val(formatMoney(totalsTax1));
			$('#sumTaxTotalHidden1').val(totalsTax1.toFixed(2));

			var numTax2 = parseFloat($('.numTax2').val());
			var numTaxPercent2 = parseFloat($('.numTaxPercent2').val());
			var numTaxDiff2 = parseFloat($('.numTaxDiff2').val());
			var totalsTax2 = parseFloat(((numTax2 * numTaxPercent2) / 100) + numTaxDiff2) || 0;
			$('#sumTaxTotal2').val(formatMoney(totalsTax2));
			$('#sumTaxTotalHidden2').val(totalsTax2.toFixed(2));

			var numTax3 = parseFloat($('.numTax3').val());
			var numTaxPercent3 = parseFloat($('.numTaxPercent3').val());
			var numTaxDiff3 = parseFloat($('.numTaxDiff3').val());
			var totalsTax3 = parseFloat(((numTax3 * numTaxPercent3) / 100) + numTaxDiff3) || 0;
			$('#sumTaxTotal3').val(formatMoney(totalsTax3));
			$('#sumTaxTotalHidden3').val(totalsTax3.toFixed(2));

			var numTaxTotal1 = parseFloat($('.numTaxTotal1').val());
			var numTaxTotal2 = parseFloat($('.numTaxTotal2').val());
			var numTaxTotal3 = parseFloat($('.numTaxTotal3').val());
			var numVat = parseFloat($('.numVat').val());
			var totalsGrand = parseFloat(numSubNoV + numSub + numVat - (numTaxTotal1 + numTaxTotal2 + numTaxTotal3)) || 0;
			$('#sumGrand').val(formatMoney(totalsGrand));
			$('#sumGrandHidden').val(totalsGrand.toFixed(2));

			var numGrand = parseFloat($('.numGrand').val());
			var numDiff = parseFloat($('.numDiff').val());
			var totalsNet = parseFloat(numGrand + numDiff) || 0;
			$('#sumNet').val(formatMoney(totalsNet));
			$('#sumNetHidden').val(totalsNet.toFixed(2));

		}