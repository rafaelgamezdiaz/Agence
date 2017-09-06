/* Codigo JQuery para "Controle de Atividades" */
$(document).ready(function(){
	
	$('#btnRelatorio').bind('click', function(e){
		e.preventDefault();
		var form = $(this).parents('form');
		var url = form.attr('action');
		var data = form.serializeArray();
		data = objectifyForm(data);
		console.log(data);
		var token = $('#token').val();
		$.ajax({
			url: url,
			headers: {'X-CSRF-TOKEN': token},
			type: 'POST',
			dataType: 'json',
		   	data : data,
		   	beforeSend: function(){
		   		$(this).text("Cargando");
		   		$(this).attr('disabled',true);
		   		$('#cargando').fadeIn();
		   	},
		   	complete: function(){
		   		$(this).text("Relat&oacute;rio");
		   		$(this).attr('disabled',false);
		   		$('#cargando').fadeOut();
		   	},
		   	error: function(result){
		        var errors = '';
	            for(datos in result.responseJSON){
	                errors += result.responseJSON[datos] + '<br>';
	            }
	            $('#resultRelatorio').show().html(errors); //this is my div with messages
	      	},
	      	success: function(result) {
				console.log('consultores');
				var consultores = result.consultores;
				var meses = result.meses;
				var receita_liquida = result.receita_liquida;
				var custofixo = result.custofixo;
				var comissao = result.comissao;
				var lucro = result.lucro;
				console.log(lucro);
				
				$('#resultGrafico').fadeOut();
				$('#graficos').empty();
				$('#resultRelatorio').empty();
				
				for (var i = 0; i < consultores.length; i++) {

					//var rl_ref = receita_liquida_ref[consultores[i][0]['co_usuario']];
					var rl = receita_liquida[consultores[i][0]['co_usuario']];
					var cf = custofixo[consultores[i][0]['co_usuario']];
					var cs = comissao[consultores[i][0]['co_usuario']];
					var lc = lucro[consultores[i][0]['co_usuario']];
					
					if (lc < 0) { color_l= "red"; cadena_l = "-R$ "; }
					else{ color_l= "blue"; cadena_l = "R$ ";};
					
					// Totales de cada tabla
					var rlt = 0; 
					var cft = 0;
					var cst = 0; 
					var lct = 0;
					var valor_rl = 0;
					var valor_cf = 0;
					var valor_cs = 0;
					var valor_lc = 0;

					// Filas de cada mes para el consultor
					var fila = $('<tbody>');
					for (var j = 0; j < meses.length; j++) {
						valor_rl = Math.round(rl[j]*100)/100;
						valor_cf = Math.round(cf[j]*100)/100;
						valor_cs = Math.round(cs[j]*100)/100;
						valor_lc = Math.round(Math.abs(lc[j])*100)/100;

						fila.append($('<tr>')
							.append($('<td>',{'text': meses[j]}),
									$('<td>',{'text': 'R$ '+ parseFloat(valor_rl).toFixed(2) }),
									$('<td>',{'text': '-R$ '+ parseFloat(valor_cf).toFixed(2) }),
									$('<td>',{'text': '-R$ '+ parseFloat(valor_cs).toFixed(2)}),
									$('<td>').append($('<span>',{
										'style': 'color:'+ color_l,
										'text': cadena_l + parseFloat(valor_lc).toFixed(2)
									}))
									));
						// calculo de totales
						rlt += valor_rl;
						cft += valor_cf;
						cst += valor_cs;
						lct += valor_lc;
					};

					fila.append($('<tr>', {'style': 'background-color: #DEDEDE;'}).append($('<td>').append($('<strong>',{'text': 'SALDO'})),
								$('<td>',{'text': 'R$ ' + parseFloat(rlt).toFixed(2) }),
								$('<td>',{'text': '-R$ ' + parseFloat(cft).toFixed(2) }),
								$('<td>',{'text': '-R$ ' + parseFloat(cst).toFixed(2) }),
								$('<td>').append($('<span>',{
									'style': 'color:'+color_l,
									'text': cadena_l+ parseFloat(lct).toFixed(2)
								}))
							));
					
					$('<section>',{
					'class': "panel panel-featured panel-featured-warning"
					})
					.append(
						$('<header>',{
							'class': "panel-heading"
						})
						.append(
							$('<div>',{
								'class': "panel-actions"
							}),
							$('<h2>',{
								'class': "panel-title",
								'text' : consultores[i][0]['no_usuario']
							})
							),
						$('<div>',{
							'class': "panel-body"
						})
							.append($('<div>',{
								'class': "table-responsive"
								})
								.append($('<table>',{
									'class': "table table-hover mb-none"
									})
									.append($('<thead>')
										.append($('<tr>')
											.append(
												$('<th>',{'text':"Período"}),
												$('<th>',{'text':"Receita Líquida"}),
												$('<th>',{'text':"Custo Fixo"}),
												$('<th>',{'text':"Comissão"}),
												$('<th>',{'text':"Lucro"})
												)),
													fila
										)
								)
							)
					).appendTo('#resultRelatorio');
				};
				$('#resultRelatorio').fadeIn();
		    }
		});
	});

	/* Grafico Performance Comercial */
	$('#btnGrafico').bind('click', function(e){
		e.preventDefault();
		var form = $(this).parents('form');
		var url = form.attr('action2');
		var data = form.serializeArray();
		data = objectifyForm(data);
		var token = $('#token').val();
		$.ajax({
			url: url,
			headers: {'X-CSRF-TOKEN': token},
			type: 'POST',
			dataType: 'json',
		   	data : data,
		   	beforeSend: function(){
		   		$(this).text("Cargando");
		   		$(this).attr('disabled',true);
		   		$('#cargando').fadeIn();
		   	},
		   	complete: function(){
		   		$(this).text("Gr&aacute;fico");
		   		$(this).attr('disabled',false);
		   		$('#cargando').fadeOut();
		   	},
		   	error: function(result){
		        var errors = '';
	            for(datos in data.responseJSON){
	                errors += data.responseJSON[datos] + '<br>';
	            }
	            $('#resultRelatorio').show().html(errors); //this is my div with messages
	      	},
	      	success: function(result) {
				var consultores = result.consultores;
				var receita_liquida = result.receita_liquida;
				var meses = result.meses;
				var costo_medio = result.costo_medio;
				var maxy = result.maxy;
				console.log(consultores);
				console.log(receita_liquida);
				console.log(meses);
				var cm = new Array();
				for (var i = meses.length - 1; i >= 0; i--) {
					cm[i] = costo_medio;
				};
				$('#resultRelatorio').fadeOut();
				$('#resultPizza').fadeOut();
				$('#graficos').empty();
				$('#resultGrafico').empty();
				$('#resultPizza').empty();

				/* SE INSERTA LA SECCION DEL GRAFICO EN E DOM */
				$('<section>',{
					'class': 'panel panel-info',
					'style' : 'border-style: ridge; border-color: #C4B2F8'
				})
					.append(
						$('<header>',{
						'class' : 'panel-heading'
						})
							.append($('<h2>',{
								'class': 'panel-title text-center',
								'text' : 'Performance Comercial'
							})),
						$('<div>',{
							'class' : 'panel-body',
							'style' : 'min-height: 450px;padding: 25px;'
						}).append(
							$('<div>',{
							'class' : 'row',
							'style' : 'margin-left: 20px; position:relative'
							}).append(
								$('<div>',{
										'id' : 'resultGrafico',
										'class': 'ct-chart ct-perfect-fourth ct-golden-section',
										'style': 'position: absolute;'
										}),
								$('<div>',{
									'id': 'linegraph',
									'class': 'ct-chart ct-perfect-fourth ct-golden-section',
									'style': 'position: absolute;'
								})
							)
						)
						
						).appendTo('#graficos');
				
				// Se modifica el formato del array para los valores de X
				var rl = $.map(receita_liquida, function(value, index){
					return [value];
				});

				/* GRAFICO DE BARRAS */
				new Chartist.Bar('#resultGrafico', {
						labels: meses,
						series: rl
					}, {
						// Default mobile configuration
						stackBars: true,
						axisX: {
							labelInterpolationFnc: function(value) {
								return value.split(/\s+/).map(function(word) {
									return word[0];
								}).join('');
							}
						},
						high: maxy+200,
						low: 0,
						legend: {
									show: true
								},
						axisY: {
							offset: 20,
							labelInterpolationFnc: function(value) {
								return 'R$'+ parseFloat( Math.round(value*100)/100 ).toFixed(2);
							}
						}
					}, [
						// Options override for media > 400px
						['screen and (min-width: 400px)', {
							axisX: {
								labelInterpolationFnc: Chartist.noop
							},
							axisY: {
								offset: 60
							}
						}],
						// Options override for media > 800px
						['screen and (min-width: 800px)', {
							stackBars: false,
							seriesBarDistance: 10
						}],
						// Options override for media > 1000px
						['screen and (min-width: 1000px)', {
							horizontalBars: false,
							seriesBarDistance: 15
						}]
					]);
				/* FIN GRAFICO DE BARRAS */

				/* GRAFICO LINEA DE COSTO MEDIO */
				// Datos
				var data = {
					labels: meses,
					series: [ cm ]
				};
				// Opciones
				var responsiveOptions = [
					[
						'only screen', {
							axisX: {
								labelInterpolationFnc: function(value, index) {
									// Interpolation function causes only every 2nd label to be displayed
									if (index % 2 !== 0) {
										return false;
									} else {
										return value;
									}
								}
							}
						}
					]
				];
				// Se inserta el grafico
				new Chartist.Line('#linegraph', 
					data, 
					{
						high: maxy+200,
						low: 0,
						chartPadding: {
							left: 40
						},
						axisX: {
							showLabel: false,
							showGrid: false
						},
						axisY: {
							showLabel: false,
							showGrid: false
						}
					}, 
					responsiveOptions);
				/* FIN DEL GRAFICO LINEA COSTO MEDIO */

				$('#graficos').fadeIn();
				$('#resultGrafico').fadeIn(); 
		    }
		});
	});
	
	/* Grafico Performance Comercial */ 
	$('#btnPizza').bind('click', function(e){
		e.preventDefault();
		var form = $(this).parents('form');
		var url = form.attr('action3');
		var data = form.serializeArray();
		data = objectifyForm(data);
		var token = $('#token').val();
		$.ajax({
			url: url,
			headers: {'X-CSRF-TOKEN': token},
			type: 'POST',
			dataType: 'json',
		   	data : data,
		   	beforeSend: function(){
		   		$(this).text("Cargando");
		   		$(this).attr('disabled',true);
		   		$('#cargando').fadeIn();
		   	},
		   	complete: function(){
		   		$(this).text("Pizza");
		   		$(this).attr('disabled',false);
		   		$('#cargando').fadeOut();
		   	},
		   	error: function(result){
		        var errors = '';
	            for(datos in data.responseJSON){
	                errors += data.responseJSON[datos] + '<br>';
	            }
	            $('#resultRelatorio').show().html(errors); //this is my div with messages
	      	},
	      	success: function(result) {
				var consultores = result.consultores;
				var rl = result.rl;
				var rln = result.rln; // Nombres de los consultores
				$('#resultGrafico').fadeOut();
				$('#resultRelatorio').fadeOut();
				$('#graficos').empty();
				$('#resultRelatorio').fadeOut();

				/* SE INSERTA LA SECCION DEL GRAFICO EN E DOM */
				$('<section>',{
					'class': 'panel panel-danger',
					'style' : 'border-style: ridge; border-color: #F8B2B2'
				}).append(
					$('<header>',{
						'class' : 'panel-heading'
						})
							.append($('<h2>',{
								'class': 'panel-title text-center',
								'text' : 'Participacao na Receita'
							})),
					$('<div>',{
							'class': 'panel-body',
							'style' : 'min-height: 400px'
						}).append(
							$('<div>',{
									'id' : 'resultPizza',
									'class': 'chart chart-md',
									'style': 'position: absolute'
									})
						))
					.appendTo('#graficos');
				
				/* GRAFICO PIE (Chartist: Pie Chart) */
	            // Inicializamos la variable que contendra los datos para el Grafico Pie
	            var flotPieData = []; 
	            var label = ""; 
	            var data = [];
	            for (var i = rl.length - 1; i >= 0; i--) {
	            	flotPieData[i] = {
	            		label: rln[i],
						data: [
							[1, rl[i]]
						]
	            	};
	            };
				
				// Se inserta el grafico
				var plot = $.plot('#resultPizza', flotPieData, {
					series: {
						pie: {
							show: true,
							combine: {
								color: '#999',
								threshold: 0.1
							}
						}
					},
					legend: {
						show: false
					},
					grid: {
						hoverable: true,
						clickable: true
					}
				}); 
				
				$('#resultPizza').fadeIn();
		    }
		});
	});
	
});

	function objectifyForm(formArray) {//serialize data function
		  var returnArray = {};
		  var flag = 0; // Si 0, aun no se ha agregado la lista de los nombres, Si 1 ya se agrego.
		  var lista = new Array();
		  for (var i = 0; i < formArray.length; i++){
		  	if (formArray[i]['name'] == 'lista') {
		  		lista.push(formArray[i]['value']);
		  	};
		  }
		  for (var i = 0; i < formArray.length; i++){
		  	if (formArray[i]['name'] != 'lista') {
		    	returnArray[formArray[i]['name']] = formArray[i]['value'];
		  	};
		  	if (formArray[i]['name'] == 'lista' && flag == 0) {
		  		returnArray[formArray[i]['name']] = lista;
		  		flag = 1;
		  	};
		  }
		  return returnArray;
		};

