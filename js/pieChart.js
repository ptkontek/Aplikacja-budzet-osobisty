function pieChart () 
{
	var chart = new CanvasJS.Chart("chartContainer", {
				exportEnabled: true,
				animationEnabled: true,
				theme: "light2",
				title:{
					text: "A oto wykres przedstawiający Twoje wydatki w okresie..:",
					fontColor: "#ffc34d",
					fontSize: 20,
				},
				data: [{
					type: "pie",
					radius: 130,
					startAngle: 270,
					indexLabelFontSize: 15,
					yValueFormatString: "##0.00\"%\"",
					toolTipContent: "{name}: <strong>{y}</strong>",
					indexLabel: "{name}",
					dataPoints: [
						{ y: 650/3265*100, name: "Jedzenie", exploded: true  },
						{ y: 1200/3265*100, name: "Mieszkanie", exploded: true  },
						{ y: 250/3265*100, name: "Transport" },
						{ y: 50/3265*100, name: "Telekomunikacja" },
						
						{ y: 80/3265*100, name: "Opieka zdrowotna" },
						{ y: 150/3265*100, name: "Ubrania" },
						{ y: 35/3265*100, name: "Higiena" },
						{ y: 150/3265*100, name: "Rozrywka" },
						
						{ y: 140/3265*100, name: "Wycieczka" },
						{ y: 50/3265*100, name: "Szkolenia" },
						{ y: 60/3265*100, name: "Książki" },
						{ y: 200/3265*100, name: "Oszczędności" },
						{ y: 300/3265*100, name: "Na złotą jesień", exploded: true  },
					]
				}]
			});
			
	chart.render();

}

function explodePie (e) 
{
			if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
				e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
			} else {
				e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
			}
			e.chart.render();

}