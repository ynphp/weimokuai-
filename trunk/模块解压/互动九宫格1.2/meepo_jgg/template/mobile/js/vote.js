var getvote;
$(function(){
	
$(".btnVote").click(function(){
		if(oopen != 'vote_layer'){
			getdate();
		}
		if(oopen == 'vote_layer'){
			clearTimeout(getvote);
			}
			oopen=switchto(oopen,'vote_layer');
});
$(document).on('click','#closevote', function(){
			$(".btnVote").click();
	});

});
	$(document).keydown(function (event)
    {    
           if (event.keyCode == 84) {
				$('.btnVote').click();
            }
    });  
  function getdate(){
		$.getJSON('vote_plug/vote_data.php',function(json){
			if(json){
				var len = json.length;
					var vote_date = json;
	  				$('#sumvote').text(vote_date[0].sumperson);
					
					if(vote_date[0].sumvotes){
						$('.vote-null').hide();
						$("#pie-chart").show();	
							if(voteshowway==1){
								pie_chat(vote_date);
							}else if(voteshowway==2){
								bar_chat(vote_date);
							}else if(voteshowway==3){
								sbar_chat(vote_date);
							}
						}else{
						$("#pie-chart").hide();
						$('.vote-null').show();	
							}
						if(oopen == 'vote_layer'){

							getvote=window.setTimeout("getdate();", votefresht*1000);
						}
			}
		});	

	  }
function sbar_chat(vote_date){
	var len = vote_date.length;
	var xfontsize;
	if(len<9){
		xfontsize = '2em';
	}else if(len>=9 && len<13){
		xfontsize = '1.3em';
	}
    $('#pie-chart').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {

            categories: vote_date[1],
			labels : {
				style : {
					'fontSize' :xfontsize
				}
			},
            title: {
                text:''
            }
        },
        yAxis: {
		
            min: 0,
            title: {
                text: '',
            },
            labels: {
                overflow: 'justify'
            }
        },
		
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        tooltip: {
                    pointFormat: '获得票数: <b>{point.y}票</b>'
        },
       legend: {
                    enabled: false
                },
        series: [{
            name: 'Year 1800',
                    colorByPoint: true,
            data: vote_date
        }]
    });
}
function bar_chat(vote_date){
	var len = vote_date.length;
	var xfontsize;
	if(len<9){
		xfontsize = '2em';
	}else if(len>=9 && len<13){
		xfontsize = '1.3em';
	}
    $('#pie-chart').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'bar'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: vote_date[1],
			labels : {
				style : {
					'fontSize' :xfontsize
				}
			},
            title: {
                text:''
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: '',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
		
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        tooltip: {
                    pointFormat: '获得票数: <b>{point.y}票</b>）'
        },
       legend: {
                    enabled: false
                },
        series: [{
            name: 'Year 1800',
                    colorByPoint: true,
            data: vote_date
        }]
    });
}
  function pie_chat(vote_date){
 chart = $("#pie-chart").highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: ''
        },
        tooltip: {
            pointFormat: '获得票数: <b>{point.y}票</b>（{point.percentage:.1f}%）'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}:</b><b style="color:red">{point.y}票</b>({point.percentage:.1f}%)',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: '投票名单',
            data: vote_date
        }]
    });  
	
	}
