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

			// $(".pcashSub1").each(function() {
			// 	$(this).keyup(function(){
			// 		calculateSum();
			// 	});
			// });

			// $(".pcashVatP1").each(function() {
			// 	$(this).keyup(function(){
			// 		calculateSum();
			// 	});
			// });

			// $(".pcashTax1").each(function() {
			// 	$(this).keyup(function(){
			// 		calculateSum();
			// 	});
			// });

			// $(".pcashTaxP1").each(function() {
			// 	$(this).keyup(function(){
			// 		calculateSum();
			// 	});
			// });

			// $(".pcashVat1").each(function() {
			// 	$(this).keyup(function(){
			// 		calculateSum();
			// 	});
			// });

			// $(".pcashTaxT1").each(function() {
			// 	$(this).keyup(function(){
			// 		calculateSum();
			// 	});
			// });

			// $(".pcashGrand1").each(function() {
			// 	$(this).keyup(function(){
			// 		calculateSum();
			// 	});
			// });

			// $(".pcashDiff1").each(function() {
			// 	$(this).keyup(function(){
			// 		calculateSum();
			// 	});
			// });

			// $(".pcashNet1").each(function() {
			// 	$(this).keyup(function(){
			// 		calculateSum();
			// 	});
			// });

		});

		function calculateSum() {
			
			var pcashSub1 = parseFloat($('.pcashSub1').val());
			var pcashVatP1 = parseFloat($('.pcashVatP1').val());
			var totalsVat1 = parseFloat((pcashSub1 * pcashVatP1) / 100) || 0;
			$('#pcashVat1').val(formatMoney(totalsVat1));
			$('#pcashVatHidden1').val(totalsVat1.toFixed(2));

			var pcashTax1 = parseFloat($('.pcashTax1').val());
			var pcashTaxP1 = parseFloat($('.pcashTaxP1').val());
			var totalsTaxT1 = parseFloat((pcashTax1 * pcashTaxP1) / 100) || 0;
			$('#pcashTaxT1').val(formatMoney(totalsTaxT1));
			$('#pcashTaxTHidden1').val(totalsTaxT1.toFixed(2));

			var pcashVat1 = parseFloat($('.pcashVat1').val());
			var pcashTaxT1 = parseFloat($('.pcashTaxT1').val());
			var totalsGrand1 = parseFloat((pcashSub1 + pcashVat1) - pcashTaxT1) || 0;
			$('#pcashGrand1').val(formatMoney(totalsGrand1));
			$('#pcashGrandHidden1').val(totalsGrand1.toFixed(2));

			var pcashGrand1 = parseFloat($('.pcashGrand1').val());
			var pcashDiff1 = parseFloat($('.pcashDiff1').val());
			var totalsNet1 = parseFloat(pcashGrand1 + pcashDiff1) || 0;
			$('#pcashNet1').val(formatMoney(totalsNet1));
			$('#pcashNetHidden1').val(totalsNet1.toFixed(2));



			//----- -----//
			var pcashSub2 = parseFloat($('.pcashSub2').val());
			var pcashVatP2 = parseFloat($('.pcashVatP2').val());
			var totalsVat2 = parseFloat((pcashSub2 * pcashVatP2) / 100) || 0;
			$('#pcashVat2').val(formatMoney(totalsVat2));
			$('#pcashVatHidden2').val(totalsVat2.toFixed(2));

			var pcashTax2 = parseFloat($('.pcashTax2').val());
			var pcashTaxP2 = parseFloat($('.pcashTaxP2').val());
			var totalsTaxT2 = parseFloat((pcashTax2 * pcashTaxP2) / 100) || 0;
			$('#pcashTaxT2').val(formatMoney(totalsTaxT2));
			$('#pcashTaxTHidden2').val(totalsTaxT2.toFixed(2));

			var pcashVat2 = parseFloat($('.pcashVat2').val());
			var pcashTaxT2 = parseFloat($('.pcashTaxT2').val());
			var totalsGrand2 = parseFloat((pcashSub2 + pcashVat2) - pcashTaxT2) || 0;
			$('#pcashGrand2').val(formatMoney(totalsGrand2));
			$('#pcashGrandHidden2').val(totalsGrand2.toFixed(2));

			var pcashGrand2 = parseFloat($('.pcashGrand2').val());
			var pcashDiff2 = parseFloat($('.pcashDiff2').val());
			var totalsNet2 = parseFloat(pcashGrand2 + pcashDiff2) || 0;
			$('#pcashNet2').val(formatMoney(totalsNet2));
			$('#pcashNetHidden2').val(totalsNet2.toFixed(2));


			//----- -----//
			var pcashSub3 = parseFloat($('.pcashSub3').val());
			var pcashVatP3 = parseFloat($('.pcashVatP3').val());
			var totalsVat3 = parseFloat((pcashSub3 * pcashVatP3) / 100) || 0;
			$('#pcashVat3').val(formatMoney(totalsVat3));
			$('#pcashVatHidden3').val(totalsVat3.toFixed(2));

			var pcashTax3 = parseFloat($('.pcashTax3').val());
			var pcashTaxP3 = parseFloat($('.pcashTaxP3').val());
			var totalsTaxT3 = parseFloat((pcashTax3 * pcashTaxP3) / 100) || 0;
			$('#pcashTaxT3').val(formatMoney(totalsTaxT3));
			$('#pcashTaxTHidden3').val(totalsTaxT3.toFixed(2));

			var pcashVat3 = parseFloat($('.pcashVat3').val());
			var pcashTaxT3 = parseFloat($('.pcashTaxT3').val());
			var totalsGrand3 = parseFloat((pcashSub3 + pcashVat3) - pcashTaxT3) || 0;
			$('#pcashGrand3').val(formatMoney(totalsGrand3));
			$('#pcashGrandHidden3').val(totalsGrand3.toFixed(2));

			var pcashGrand3 = parseFloat($('.pcashGrand3').val());
			var pcashDiff3 = parseFloat($('.pcashDiff3').val());
			var totalsNet3 = parseFloat(pcashGrand3 + pcashDiff3) || 0;
			$('#pcashNet3').val(formatMoney(totalsNet3));
			$('#pcashNetHidden3').val(totalsNet3.toFixed(2));

		}