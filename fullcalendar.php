<?php


include("db.php");
$db = new Database();

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (isset($_GET["a"])) {

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Encontra os novos valores dos produtos na tela de edição
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_agenda"){
	

		//$id = $_POST["id"];
		//$idProd = $_POST["idProd"];
		//$quantidade = $_POST["quantidade"];

		$res = $db->select("SELECT * FROM agendamentos");

		//$c_retorno["valor"] = $res[0]['valor'];	
		//$c_retorno["valorf"] = $reais;
		
		echo json_encode($res);
			
		
		
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Inclui um evento novo via click no calendario:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


	if ($_GET["a"] == "add_event") {

		$title = $_POST['title'];
		$allDay = $_POST['allDay'];


		//tratamento para o formato de data
		
			//$mes = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "0300 - (Horário Padrão de Brasília)");
			//$mesn = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "");
			$mes = array("(Horário Padrão de Brasília)");
			$mesn = array("");
			$start2 = str_replace($mes, $mesn, $_POST['start']);
			$start1 = strtotime($start2);
			$start = date("d-m-Y H:i:s",$start1);

			$end2 = str_replace($mes, $mesn, $_POST['end']);
			$end1 = strtotime($end2);
			$end = date("d-m-Y H:i:s",$end1);
		
		$res = $db->_exec("INSERT INTO agendamentos (idUsuario, idCliente, idPedido, hora_ini, hora_fim, data_agend, descricao )
                    		VALUES (20,1,45,'$start','$end',LOCALTIME(),'$title' );");

		echo $res;
	}
	
	die();
}	

include('header.php');
include('sidebar.php');
include('navbar.php');
?>

<head>


<script>

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

			events: [

				
				
				
				{
					title: 'All Day Event',
					start: new Date(y, m, 1),
					className: 'info'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d - 3, 16, 0),
					allDay: false,
					className: 'info'
				},
				{
					id: 666,
					title: 'Teste do pepe',
					start: new Date(y, m, d - 3, 16, 0),
					allDay: false,
					className: 'info'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d + 4, 16, 0),
					allDay: false,
					className: 'info'
				},
				{
					title: 'Meeting',
					start: new Date(y, m, d, 10, 30),
					allDay: false,
					className: 'important'
				},
				{
					title: 'Lunch',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false,
					className: 'important'
				},
				{
					title: 'Birthday Party',
					start: new Date(y, m, d + 1, 19, 0),
					end: new Date(y, m, d + 1, 22, 30),
					allDay: false,
				},
				{
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'http://google.com/',
					className: 'success'
				}
			],
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
					$('#calendar').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
				},
				success: function retorno_ajax(retorno) {
					
					var obj = JSON.parse(retorno);
                    //var obj_ret = obj.header;
					alert(obj);

					idagendamento = obj[0].idAgendamento;
					idusuario = obj[0].idUsuario;
					idcliente = obj[0].idCliente;
					idpedido = obj[0].idPedido;
					title = obj[0].descricao;
					start = obj[0].hora_ini;
					end = obj[0].hora_fim; 
                    
					$('#calendar').html(retorno);
					
					events: [{
						

					},
					
					]
				}
			});
		}

		// Evento inicial:
        $(document).ready(function() {
            lista_itens_agenda();
        });

	});
</script>
<style>
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
			<div></div>
			<div id='calendar'></div>
			<div>""</div>
			<div style='clear:both'></div>
		</div>
</body>

<?php

include('bottom.php');
?>