<?php

include("db.php");
$db = new Database();

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (isset($_GET["a"])) {

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Encontra os agendamentos no banco de dados e inclui os agendamentos na tela
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_agenda"){

		$res = $db->select("SELECT idAgendamento, idUsuario, idCliente, idPedido, hora_ini, hora_fim, data_agend, descricao FROM agendamentos");

		$count = count($res);
		$res["count"]=$count;
		
		echo json_encode($res);
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Inclui um evento novo via click no calendario:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


	if ($_GET["a"] == "add_event") {

		$title = $_POST['title'];
		$allDay = $_POST['allDay'];
		$start = $_POST['start'];
		$end = $_POST['end'];


		//tratamento para o formato de data
		
			//$mes = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "0300 - (Horário Padrão de Brasília)");
			//$mesn = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "");
			/*
			$mes = array("(Horário Padrão de Brasília)");
			$mesn = array("");
			$start2 = str_replace($mes, $mesn, $_POST['start']);
			$start1 = strtotime($start2);
			$start = date("d-m-Y H:i:s",$start1);

			$end2 = str_replace($mes, $mesn, $_POST['end']);
			$end1 = strtotime($end2);
			$end = date("d-m-Y H:i:s",$end1);
			*/

		$res = $db->_exec("INSERT INTO agendamentos (idUsuario, idCliente, idPedido, hora_ini, hora_fim, data_agend, descricao )
                    		VALUES (20,1,45,'$start','$end',LOCALTIME(),'$title' );");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Inclui um evento novo via botao de inclusão:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


	if ($_GET["a"] == "add_event_button") {

		$idAgend = $_POST['idagend'];
		$idPed = $_POST['idpedido'];
		$descricao = $_POST['descricao'];
		$start = $_POST['dataini'];
		$end = $_POST['datafim'];

		$start1 = floatval(preg_replace('/[^0-9]/', '', $start));
		$end1 = floatval(preg_replace('/[^0-9]/', '', $end));
		
		if($descricao == NULL){$descricao = "";}

		if($end1 < $start1){
			echo 2;
		}else if($idPed == NULL){
			$res = $db->_exec("UPDATE agendamentos SET hora_ini = '$start', hora_fim = '$end', descricao = '$descricao', data_agend = LOCALTIME() WHERE idAgendamento = $idAgend");
			echo $res;
		}else{
			$res = $db->_exec("UPDATE agendamentos SET idPedido = $idPed, hora_ini = '$start', hora_fim = '$end', descricao = '$descricao', data_agend = LOCALTIME() WHERE idAgendamento = $idAgend");
			echo $res;
		}
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Ocultamente cria o agendamento, e após Exibe lista de itens na div modInsert:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_mod_insert"){    
	
		$usuario = $_POST["usuario"];
		$cliente = $_POST["cliente"];
		
		$ped = $db->_exec("INSERT INTO agendamentos (idAgendamento,idCliente,idUsuario) VALUES ('',$cliente,$usuario)");
		
		$age = $db->select("SELECT idAgendamento FROM agendamentos ORDER BY idAgendamento DESC LIMIT 1");
		
		$s = $db->select("SELECT idPedido FROM pedidos WHERE idCliente = $cliente ORDER BY idPedido");
		
		if(count($s) > 0){
			
			echo '<div class="row mb-3">';
				echo '<div class="col">';
					echo '<label for="frm_numped_insert" class="form-label">Pedido:</label>';
						echo '<div class="scrollable">';
							echo '<select id="frm_numped_insert"  class="select form-control form-control-lg" name="frm_numped_insert" type="text" style="color: #ffffff" >';
								echo '<option value="" selected></option>';
								
									foreach($s as $s1){
										echo  '<option value="'.$s1["idPedido"].'">'.$s1["idPedido"].'</option>';
									}
								
							echo '</select>';
						echo '</div>';
				echo '</div>';
			echo '</div>';
		}else{
			echo '<div class="alert alert-warning" role="alert">';
				echo 'Nenhum pedido associado a este cliente foi localizado!';
			echo '</div>';
		}
			
		echo '<div class="table-responsive">';
		echo '<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">';
			echo '<thead>';
				echo '<tr>';
					echo '<th style="text-align: center">Dia e Horario de Início</th>';
					echo '<th style="text-align: center">Dia e Horario de Fim</th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
				echo '<tr >';
					echo '<td style="text-align: left"><input type="datetime-local" id="data_ini" name="data_ini"></input></td>';
					echo '<td style="text-align: center"><input type="datetime-local" id="data_fim" name="data_fim"></input></td>';
				echo '</tr>';
				echo '<tr >';
					echo '<td style="text-align: left"><input type="text" id="descricao_include" name="descricao_include"></input></td>';
					echo '<td><input id="idagend" value="'.$age[0]["idAgendamento"].'" hidden></input></td>';
					echo '</tr>';
			echo '</tbody>';
		echo '</table>';
		echo '</div>';
		
	}
	
	die();
}	

include('header.php');
include('sidebar.php');
include('navbar.php');
?>

<head>

<script>

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Exibir no modal os itens para inclusão:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const listaModinsert = () => {
		if(ajax_div){ ajax_div.abort(); }
			ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=lista_mod_insert',
			type: 'post',
			data: {pesq: $('#input_pesquisa').val(),
				usuario: $('#frm_val1_insert').val(),
				cliente: $('#frm_val2_insert').val()},
			beforeSend: function(){
				$('#mod_insert').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				$('#mod_insert').html(retorno); 
			}
		});
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Salva no banco de dados as informações de agendamentos feitos via botão de inclusão
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const incluiClient = () => {
		if(ajax_div){ ajax_div.abort(); }
			ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=add_event_button',
			type: 'post',
			data: { 
				idagend: $('#idagend').val(),
				idpedido: $('#frm_numped_insert').val(),
				dataini: $('#data_ini').val(),
				datafim: $('#data_fim').val(),
				descricao: $('#descricao_include').val(),
			},
			success: function retorno_ajax(retorno) {
				if(retorno==2){
					alert("A hora de fim precisa ser depois da hora de início!");
					location.reload();	
				}else if(retorno==1){
					alert("Agendamento realizado com sucesso!");
					location.reload();  
				}else{
					alert("ERRO AO CRIAR O EVENTO! " + retorno);
				}
			}
		});
	}


	$(document).ready(function() {
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();

		/*  className colors

		className: default(transparent), important(red), chill(pink), success(green), info(blue)

		*/


		/* initialize the external events
		-----------------------------------------------------------------*/

		$('#external-events div.external-event').each(function() {

			// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
			// it doesn't need to have a start or end
			var eventObject = {
				title: $.trim($(this).text()) // use the element's text as the event title
			};

			// store the Event Object in the DOM element so we can get to it later
			$(this).data('eventObject', eventObject);

			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true, // will cause the event to go back to its
				revertDuration: 0 //  original position after the drag
			});

		});

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		* Listar itens:
		* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
		var ajax_div = $.ajax(null);
		const lista_itens_agenda = () => {
			if(ajax_div){ ajax_div.abort(); }
				ajax_div = $.ajax({
				cache: false,
				async: true,
				url: '?a=lista_agenda',
				type: 'post',
				data: {},
				beforeSend: function(){
					//$('#calendar').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
				},
				success: function retorno_ajax(retorno) {
										
					var objlista = JSON.parse(retorno);  
				    var objagend = new Object();
					const eventsvar = [];
					
					for(var i=0; i<objlista.count; i++){

						objagend = { id: objlista[i].idAgendamento, 
									 title: objlista[i].descricao, 
								 	 start: objlista[i].hora_ini, 
								 	 end: objlista[i].hora_fim, 
									 className: 'info'};
	
						eventsvar.push(objagend);
						
					}

					
					
					/* initialize the calendar
					-----------------------------------------------------------------*/
					
					var calendar = $('#calendar').fullCalendar({
						
						header: {
							left: 'title',
							center: 'agendaDay,agendaWeek,month',
							right: 'prev,next today'
						},
						editable: true,
						firstDay: 0, //  1(Monday) this can be changed to 0(Sunday) for the USA system
						selectable: true,
						defaultView: 'month',
						editable: true,
						eventLimit: true,
						allDayDefault: false,
						timeFormat: 'H:mm',
						axisFormat: 'h:mm',
						
						columnFormat: {
							month: 'ddd', // Mon
							week: 'ddd d', // Mon 7
							day: 'dddd M/d', // Monday 9/7
							agendaDay: 'dddd d'
						},
						titleFormat: {
							month: 'MMMM yyyy', // September 2009
							week: "MMMM yyyy", // September 2009
							day: 'MMMM yyyy' // Tuesday, Sep 8, 2009
						},
						
						allDaySlot: false,
						selectHelper: true,
						select: function(start, end, allDay) {
							var title = prompt('Event Title:');
							if (title) {
								
								calendar.fullCalendar('renderEvent', {
										title: title,
										start: start,
										end: end,
										allDay: allDay
									},
									true // make the event "stick"
								);

								/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
								* Salva no banco de dados as informações de agendamentos feitos via calendário
								* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
								var ajax_div = $.ajax(null);
								
									if(ajax_div){ ajax_div.abort(); }
										ajax_div = $.ajax({
										cache: false,
										async: true,
										url: '?a=add_event',
										type: 'post',
										data: { 
											title: title,
											start: start,
											end: end,
											allDay: allDay
										},
										success: function retorno_ajax(retorno) {
											if(retorno){
												alert("Agendamento realizado com sucesso!");  
											}else{
												alert("ERRO AO CRIAR O EVENTO! " + retorno);
											}
										}
									});
							
							}
							calendar.fullCalendar('unselect');
						},
						droppable: true, // this allows things to be dropped onto the calendar !!!
						drop: function(date, allDay) { // this function is called when something is dropped

							// retrieve the dropped element's stored Event Object
							var originalEventObject = $(this).data('eventObject');

							// we need to copy it, so that multiple events don't have a reference to the same object
							var copiedEventObject = $.extend({}, originalEventObject);

							// assign it the date that was reported
							copiedEventObject.start = date;
							copiedEventObject.allDay = allDay;

							// render the event on the calendar
							// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
							$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

							// is the "remove after drop" checkbox checked?
							if ($('#drop-remove').is(':checked')) {
								// if so, remove the element from the "Draggable Events" list
								$(this).remove();
							}

						},
						viewRender: function (view) {

						$('#calendar').fullCalendar('removeEvents');
						$('#calendar').fullCalendar('addEventSource', eventsvar);
						$('#calendar').fullCalendar('refetchEvents');
						},
						
						eventClick: function(eventsvar, jsEvent, view) {

						alert('Event: ' + eventsvar.title);
						alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
						alert('View: ' + view.name);

						},

						events: [
						
						],

						eventColor: '#422FD6'
						
					});
				} // fim da success do lista itens agenda
			});
				
			
		} // fim do ajax lista itens agenda

		// Evento inicial:
        $(document).ready(function() {
            lista_itens_agenda();
        });

	});
</script>
<style>
	.topright {
	position: absolute;
	top: 38px;
	right: 60px;
	font-size: 15px;
	}

	.fc .fc-event{
		color: black;
	}
	.fc-widget-header{
		color: black;
	}
	.fc-header-title-2{
		color: black;
	}
	.fc-day-number{
		color: black;
	}

	
/* Cell Styles
------------------------------------------------------------------------*/

    /* <th>, usually */
.fc-widget-content {  /* <td>, usually */
	border: 1px solid #FFF;
	}
.fc-widget-header{
    border-bottom: 1px solid #EEE; 
}	


.fc-state-highlight > div > div.fc-day-number{
    background-color: #424178;
    color: #FFFFFF;
    border-radius: 50%;
    margin: 4px;
}
	
.fc-cell-overlay { /* semi-transparent rectangle while dragging */
	background: #bce8f1;
	opacity: .3;
	filter: alpha(opacity=30); /* for IE */
	}

	.fc-state-default {
	border-color: #000;
	color: #000;	
	}
	.fc-state-hover,
	.fc-state-down,
	.fc-state-active,
	.fc-state-disabled {
	color: #000;
	background-color: #e5e5e5;
	}


	body {
		margin-top: 40px;
		text-align: center;
		font-size: 14px;
		font-family: "Helvetica Nueue", Arial, Verdana, sans-serif;
		background-color: #000;
	}

	#wrap {
		width: 1100px;
		margin: auto auto;
	}

	#external-events {
		float: left;
		width: 150px;
		padding: 0 10px;
		text-align: left;
	}

	#external-events h4 {
		font-size: 16px;
		margin-top: 0;
		padding-top: 1em;
	}

	.external-event {
		/* try to mimick the look of a real event */
		margin: 10px 0;
		padding: 2px 4px;
		background: #3366CC;
		color: #fff;
		font-size: .85em;
		cursor: pointer;
	}

	#external-events p {
		margin: 1.5em 0;
		font-size: 11px;
		color: #666;
	}

	#external-events p input {
		margin: 0;
		vertical-align: middle;
	}

	#calendar {
		/* 		float: right; */
		margin: auto auto;
		width: 1000px;
		background-color: #EEEEEE;
		border-radius: 6px;
		box-shadow: 0 #C3C3C3;
	}
</style>
</head>
<body   style="background-image: url('assets/coronafree/template/assets/images/pillars.png');  background-repeat: no-repeat; background-size: cover">
		<div id='wrap'>
			
			<div>
				<button type="button" class="btn btn-primary topright" id="incluireventos" onclick="$('#mod_formul').modal('show');"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">Adicionar Novo Evento</button>
			</div>

			<div id='calendar'></div>
			<div style='clear:both'></div>
		</div>

		<!-- Modal formulário Inclusao -->
		<div class="modal" id="mod_formul">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 70%;">
					<div class="modal-content">
						<div class="modal-header" style="align-items: center">
							<div style="display: flex; align-items: center">
								<div style="margin-right: 5px">
									<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
								</div>
								<div>
									<h5 id="tit_frm_formul" class="modal-title">Incluir Agendamento</h5>
								</div>
							</div>
							<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul').modal('hide');">X</button>
						</div>
						<div class="modal-body modal-dialog-scrollable">
							<form id="frm_general" name="frm_general" class="col">

							<div class="row mb-3">
								<div class="col">
									<label for="frm_val1_insert" class="form-label">Usuário:</label>
										<div class="scrollable">
											<select id="frm_val1_insert"  class="select form-control form-control-lg" name="frm_val1_insert" type="text" style="color: #ffffff" >
												<option value="" selected></option>
												<?php
													$desc = $db->select('SELECT idUsuario, nome FROM usuarios');
													foreach($desc as $s){
														echo  '<option value="'.$s["idUsuario"].'">'.$s["nome"].'</option>';
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="row mb-3">
									<div class="col">
										<label for="frm_val2_insert" class="form-label">Cliente:</label>
											<div class="scrollable">
											<select id="frm_val2_insert"  onchange="listaModinsert()" class="select form-control form-control-lg" name="frm_val2_insert" type="text" style="color: #ffffff" >
												<option value="" selected></option>
												<?php
													$desc = $db->select('SELECT idCliente, nome FROM clientes');
													foreach($desc as $s){
														echo  '<option value="'.$s["idCliente"].'">'.$s["nome"].'</option>';
													}
												?>
											</select>
											<input id="numpedido" hidden></input>
										</div>
									</div>
								</div>
								
								<div id="mod_insert"></div>	
			
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" onclick="$('#mod_formul').modal('hide');">Cancelar</button>
							<button type="button" class="btn btn-primary" id="OK" onclick="incluiClient();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
						</div>
					</div>
				</div>
			</div>
</body>

<?php

include('bottom.php');
?>