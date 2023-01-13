<?php

include("db.php");
$db = new Database();

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (isset($_GET['a'])) {

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Encontra os agendamentos no banco de dados e inclui os agendamentos na tela
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_agenda"){

		$res = $db->select("SELECT idAgendamento, idUsuario, a.idCliente, c.idCliente, c.nome, idPedido, hora_ini, hora_fim, data_agend, descricao 
							FROM agendamentos a
							INNER JOIN clientes c WHERE c.idCliente = a.idCliente");

		$count = count($res);
		$res["count"]=$count;
		
		echo json_encode($res);
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Inclui um evento novo via click no calendario:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


	if ($_GET["a"] == "add_event") {

		$start = $_POST['start'];
		$end = $_POST['end'];

		//tratamento para o formato de data
		
			//$mes = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "0300 - (Horário Padrão de Brasília)");
			//$mesn = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "");
			
			$mes = array("GMT-0300 (Horário Padrão de Brasília)");
			$mesn = array("");

			$start2 = str_replace($mes, $mesn, $_POST['start']);
			$start1 = strtotime($start2);
			$start = date("Y-m-d\TH:i",$start1);

			$end2 = str_replace($mes, $mesn, $_POST['end']);
			$end1 = strtotime($end2);
			$end = date("Y-m-d\TH:i",$end1);
			
		$res = $db->_exec("INSERT INTO agendamentos (hora_ini, hora_fim)
                    		VALUES ('$start','$end');");

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
		}else if($idPed == NULL || $idPed == 'Nenhum pedido associado a este cliente foi localizado!'){
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
		
		//checa se o ultimo insert tem cliente ou não (isso interpreta se o agendamento veio via botão inset ou click na agenda)

		$nullcheck = $db->select("SELECT idAgendamento, idCliente FROM agendamentos ORDER BY idAgendamento DESC LIMIT 1");
		$id = $nullcheck[0]['idAgendamento'];

		if($nullcheck[0]['idCliente']==NULL){
			$ped= $db->_exec("UPDATE agendamentos 
				SET idCliente = $cliente, idUsuario = $usuario
				WHERE idAgendamento = {$id}");
		}else{
			$ped = $db->_exec("INSERT INTO agendamentos (idAgendamento,idCliente,idUsuario) VALUES ('',$cliente,$usuario)");
		}

		$age = $db->select("SELECT idAgendamento, hora_ini, hora_fim FROM agendamentos ORDER BY idAgendamento DESC LIMIT 1");

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
			echo '<div id="frm_numped_insert" class="alert alert-warning" role="alert">';
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
					echo '<td style="text-align: left"><input type="datetime-local" id="data_ini" name="data_ini" value="'.$age[0]["hora_ini"].'"></input></td>';
					echo '<td style="text-align: center"><input type="datetime-local" id="data_fim" name="data_fim" value="'.$age[0]["hora_fim"].'"></input></td>';
				echo '</tr>';
				echo '<tr >';
					echo '<th style="text-align: center">Descrição:</th>';
				echo '</tr>';
				echo '<tr >';
					echo '<td style="text-align: left"><input type="text" id="descricao_include" name="descricao_include"></input></td>';
					echo '<td><input id="idagend" value="'.$age[0]["idAgendamento"].'" hidden></input></td>';
				echo '</tr>';
			echo '</tbody>';
		echo '</table>';
		echo '</div>';
		
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca conteúdo para exibir na div de edição do pedido:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "get_client"){
	

		$id = $_POST["id"];
		$title = $_POST["title"];

		$sel = $db->select("SELECT a.idUsuario, u.nome as nomeu, a.idCliente, c.nome as nomec, a.idPedido, a.hora_ini as horaini, a.hora_fim as horafim FROM agendamentos a  
							INNER JOIN clientes c ON c.idCliente = a.idCliente
                            INNER JOIN usuarios u ON u.idUsuario = a.idUsuario
							WHERE idAgendamento = $id");

		$c_retorno["id"] = $id;
		$c_retorno["title"] = $title;	
		$c_retorno["start"] = $sel[0]["horaini"];;	
		$c_retorno["end"] = $sel[0]["horafim"];;
		if($sel != NULL){
		$c_retorno["idUsuario"] = $sel[0]["idUsuario"];
		$c_retorno["idCliente"] = $sel[0]["idCliente"];
		$c_retorno["nomeu"] = $sel[0]["nomeu"];
		$c_retorno["nomec"] = $sel[0]["nomec"];
		$c_retorno["idPedido"] = $sel[0]["idPedido"];
		}
		echo json_encode($c_retorno);
		
		
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Edita o evento onclick:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "edit_client"){
		

		$id = $_POST["id"];

		$usuario = $_POST["usuario"];
		$cliente = $_POST["cliente"];
		$start = $_POST["start"];
		$end = $_POST["end"];
		$idPed = $_POST["idPed"];
		$descricao = $_POST["descricao"];
		
		$start1 = floatval(preg_replace('/[^0-9]/', '', $start));
		$end1 = floatval(preg_replace('/[^0-9]/', '', $end));
		
		if($usuario == "" || $cliente == "" || $start == "" || $end == "" || $descricao == ""){
			$res = 2;
		}elseif($end1 < $start1){
			$res = 3;
		}elseif($idPed == "" || $idPed == NULL){
			$res = $db->_exec("UPDATE agendamentos 
				SET idCliente = {$cliente},  idUsuario = {$usuario}, idPedido = '', hora_ini = '$start', hora_fim = '$end', descricao = '$descricao'
				WHERE idAgendamento = {$id}");
		}else{
			$res = $db->_exec("UPDATE agendamentos 
				SET idCliente = {$cliente},  idUsuario = {$usuario}, idPedido = {$idPed},  hora_ini = '$start', hora_fim = '$end',  descricao = '$descricao'
				WHERE idAgendamento = {$id}");
		}
		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Edita o evento on drag:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "edit_client_auto"){
		
		$id = $_POST["id"];

		$mes = array("GMT-0300 (Horário Padrão de Brasília)");
			$mesn = array("");

			$start2 = str_replace($mes, $mesn, $_POST['start']);
			$start1 = strtotime($start2);
			$start = date("Y-m-d\TH:i",$start1);

			$end2 = str_replace($mes, $mesn, $_POST['end']);
			$end1 = strtotime($end2);
			$end = date("Y-m-d\TH:i",$end1);

		$res = $db->_exec("UPDATE agendamentos 
			SET hora_ini = '$start', hora_fim = '$end'
			WHERE idAgendamento = {$id}");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "del_user"){
	

		$id = $_POST["id"];

		$res = $db->_exec("DELETE FROM agendamentos WHERE idAgendamento = '{$id}'");
		
		echo $res;
		
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Exclui o ultimo registro quando o insert de agendamento não foi completo corretamente
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "cancel_user"){

		$s = $db->select("SELECT idAgendamento FROM agendamentos ORDER BY idAgendamento DESC LIMIT 1");
		
		$res = $db->_exec("DELETE FROM agendamentos WHERE idAgendamento = '{$s[0]['idAgendamento']}'");
		
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

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Exibir no modal os itens para inclusão:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const listaModinsert = () => {
		if(ajax_div){ ajax_div.abort(); }
			ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=lista_mod_insert',
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
			url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=add_event_button',
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

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Excluir usuário:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function delete_Event(){
		if( confirm( "Deseja excluir o pedido?")){
			if(ajax_div){ ajax_div.abort(); }
				ajax_div = $.ajax({
				cache: false,
				async: true,
				url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=del_user',
				type: 'post',
				data: { 
					id: $("#frm_id_edit").val()
				},
				success: function retorno_ajax(retorno) {
					
					if(retorno==1){
						location.reload();

					}else{
						alert("ERRO AO DELETAR ITENS! " + retorno);
					}
				}
			});
		}else{
			lista_itens_agenda();
		}
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Exclui o ultimo registro quando o insert de agendamento não foi completo corretamente
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function cancela_insert(){
		if(ajax_div){ ajax_div.abort(); }
			ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=cancel_user',
			type: 'post',
			data: { 
				
			},
			success: function retorno_ajax(retorno) {
				if(retorno==1){
					location.reload();
				}else{
					alert("ERRO AO DELETAR ITENS! " + retorno);
				}
			}
		});
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* responssavel por dar o update de valores no modal de edição:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const editClient = () => {
		
			if(ajax_div){ ajax_div.abort(); }
			ajax_div = $.ajax({
				cache: false,
				async: true,
				url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=edit_client',
				type: 'post',
				data: { 
					id: $("#frm_id_edit").val(),
					usuario: $("#frm_val1_edit").val(),
					cliente: $("#frm_val2_edit").val(),
					start: $("#frm_val3_edit").val(),
					end: $("#frm_val4_edit").val(),
					idPed: $("#frm_val5_edit").val(),
					descricao: $("#frm_val6_edit").val(),
				},
				beforeSend: function(){
					$('#mod_formul_edit').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
				},
				success: function retorno_ajax(retorno) {

					if(retorno==3){
						alert("ERRO AO EDITAR AGENDAMENTO, A HORA DE FIM PRECISA SER DEPOIS DA HORA DE INÍCIO! " + retorno); 
					}
					if(retorno==2){
						alert("ERRO AO EDITAR AGENDAMENTO, FAVOR INSERIR NOME DO CLIENTE DO USUÁRIO, HORÁRIOS DE AGENDAMENTO E DESCRIÇÃO! " + retorno); 
					}
					if(retorno){
						$('#mod_formul_edit').modal('hide');
						location.reload();
						lista_itens_agenda();  
					}else{
						alert("ERRO AO EDITAR USUÁRIO! " + retorno);
					}
				}
			});
		
	}

	$(document).ready(function(){

		$("#mod_formul").on('hide.bs.modal', function(){
			alert('O agendamento não foi concluído e portanto não foi salvo na agenda.');
			cancela_insert();
		});

	});


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
				url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=lista_agenda',
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
									 title: objlista[i].nome + '\n' + objlista[i].descricao, 
								 	 start: objlista[i].hora_ini, 
								 	 end: objlista[i].hora_fim, 
									 className: 'info',
									 textColor: '#FFFFFF'};
	
						eventsvar.push(objagend);
						
					}

					
					
					/* initialize the calendar
					-----------------------------------------------------------------*/
					
					var calendar = $('#calendar').fullCalendar({
						locale: 'pt-br',
						header: {
							left: 'title',
							center: 'agendaDay,agendaWeek,month',
							right: 'prev,next today'
						},
						editable: true,
						firstDay: 0, //  1(Monday) this can be changed to 0(Sunday) for the USA system
						selectable: true,
						defaultView: 'agendaWeek',
						editable: true,
						eventLimit: true,
						allDayDefault: false,
						timeFormat: 'H:mm',
						axisFormat: 'h:mm',

						ignoreTimezone: false,
						monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
						monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
						dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado'],
						dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
						
						buttonText: {
							prev: "&nbsp;&#9668;&nbsp;",
							next: "&nbsp;&#9658;&nbsp;",
							prevYear: "&nbsp;&lt;&lt;&nbsp;",
							nextYear: "&nbsp;&gt;&gt;&nbsp;",
							today: "Hoje",
							month: "Mês",
							week: "Semana",
							day: "Dia"
						},

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

								/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
								* Salva no banco de dados as informações de agendamentos feitos via calendário
								* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
								var ajax_div = $.ajax(null);
								
									if(ajax_div){ ajax_div.abort(); }
										ajax_div = $.ajax({
										cache: false,
										async: true,
										url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=add_event',
										type: 'post',
										data: { 

											start: start,
											end: end,

										},
										beforeSend: function(){
											$('#mod_formul').modal('show');
										},
										success: function retorno_ajax(retorno) {
											alert(retorno)
											if(retorno){
												
											}else{
												alert("ERRO AO CRIAR O EVENTO! " + retorno);
											}
										}
									});
							
							
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

							location.reload();

							// render the event on the calendar
							// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
							$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
							
							// is the "remove after drop" checkbox checked?
							if ($('#drop-remove').is(':checked')) {
								// if so, remove the element from the "Draggable Events" list
								$(this).remove();
							}
							
						},

						eventDrop: function(copiedEventObject){
							
							/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
							* responssavel por dar o update de valores no modal de edição:
							* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
							var ajax_div = $.ajax(null);
							
								if(ajax_div){ ajax_div.abort(); }
								ajax_div = $.ajax({
									cache: false,
									async: true,
									url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=edit_client_auto',
									type: 'post',
									data: { 
										id: copiedEventObject.id,
										start: copiedEventObject.start,
										end: copiedEventObject.end,

									},
									beforeSend: function(){
										//$('#mod_formul_edit').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
									},
									success: function retorno_ajax(retorno) {
										
										if(retorno){
											//$('#mod_formul_edit').modal('hide');
											location.reload();
											lista_itens_agenda();  
										}else{
											alert("ERRO AO EDITAR USUÁRIO! " + retorno);
										}
									}
								});
								
							
						},

						eventResize: function(copiedEventObject){
							
							/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
							* responssavel por dar o update de valores no modal de edição:
							* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
							var ajax_div = $.ajax(null);
							
								if(ajax_div){ ajax_div.abort(); }
								ajax_div = $.ajax({
									cache: false,
									async: true,
									url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=edit_client_auto',
									type: 'post',
									data: { 
										id: copiedEventObject.id,
										start: copiedEventObject.start,
										end: copiedEventObject.end,

									},
									beforeSend: function(){
										//$('#mod_formul_edit').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
									},
									success: function retorno_ajax(retorno) {
										
										if(retorno){
											
										}else{
											alert("ERRO AO EDITAR USUÁRIO! " + retorno);
										}
									}
								});
								
							
						},
						
						viewRender: function (view) {

						$('#calendar').fullCalendar('removeEvents');
						$('#calendar').fullCalendar('addEventSource', eventsvar);
						$('#calendar').fullCalendar('refetchEvents');
						},
						
						eventClick: function(eventsvar, jsEvent, view) {

							/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
							* Pesquisar itens do campo de edição:
							* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
							var ajax_div = $.ajax(null);
							
								if(ajax_div){ ajax_div.abort(); }
								ajax_div = $.ajax({
									cache: false,
									async: true,
									url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=get_client',
									type: 'post',
									data: { 
										
										id: eventsvar.id,
										title: eventsvar.title,

									},
									beforeSend: function(){
										$('#mod_formul_edit').modal("show");
										
									},
									success: function retorno_ajax(retorno) {
										
								
										var obj = JSON.parse(retorno);

										$("#frm_id_edit").val(obj.id);
										
										if(obj.nomec != undefined){
										
											$("#frm_val1_edit_option").html(obj.nomeu);
											$("#frm_val2_edit_option").html(obj.nomec);
											$("#frm_val1_edit_option").val(obj.idUsuario);
											$("#frm_val2_edit_option").val(obj.idCliente);
										}
										
										$("#frm_val3_edit").val(obj.start)
										$("#frm_val4_edit").val(obj.end);

										$("#frm_val5_edit").val(obj.idPedido);	
										$("#frm_val6_edit").val(obj.title);	
 
										
									}
								});
							
							

						},
						
						events: [
						
						],
						
						eventColor: '#422FD6',
						
						
						
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
			<!--
			<div>
				<button type="button" class="btn btn-primary topright" id="incluireventos" onclick="$('#mod_formul').modal('show');"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">Adicionar Novo Evento</button>
			</div>
-->
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

						<div class="row">
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
						<div class="row">
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

	<!-- Modal formulário Edição-->

	<div class="modal" id="mod_formul_edit">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 70%;">
            <div class="modal-content">
                <div class="modal-header" style="align-items: center">
                    <div style="display: flex; align-items: center">
                        <div style="margin-right: 5px">
                            <h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
                        </div>
                        <div>
                            <h5 id="div_edit_title" style="text-align: center">Agendamento</h5>
                        </div>
                    </div>
                    <button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="location.reload();">X</button>
                </div>
                <div class="modal-body modal-dialog-scrollable">
                    <form id="frm_general_exib" name="frm_general" class="container-fluid" style="display: grid; align-items: center">
                        <div class="row">

                            <div class="col">
                                <input type="text" style="text-align: left" aria-describedby="frm_id_edit" class="form-control form-control-lg" name="frm_id_edit" id="frm_id_edit" hidden>
                                <div class="col" style="text-align: left">
                                	<label for="frm_val1_edit" class="form-label">Usuário:</label>
                                </div>    
									<div class="scrollable">
                                    <select id="frm_val1_edit" value=""  class="select form-control form-control-lg" aria-describedby="frm_val1_edit" name="frm_val1_edit" type="text" style="color: #ffffff">
                                        <option id="frm_val1_edit_option" value="" selected></option>
                                        <?php
                                            $desc = $db->select('SELECT idUsuario, nome FROM usuarios');
                                            foreach($desc as $s){
                                                echo  '<option value="'.$s["idUsuario"].'">'.$s["nome"].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        
                            <div class="col">
								<div class="col" style="text-align: left">
									<label for="frm_val2_edit" class="form-label">Cliente:</label>
								</div>
                                    <div class="scrollable">
                                    <select id="frm_val2_edit"  class="select form-control form-control-lg" aria-describedby="frm_val2_edit" name="frm_val2_edit" type="text" placeholder="" style="color: #ffffff">
                                        <option id="frm_val2_edit_option" value="" selected></option>
                                        <?php
                                            $desc = $db->select('SELECT idCliente, nome FROM clientes');
                                            foreach($desc as $s){
                                                echo  '<option value="'.$s["idCliente"].'">'.$s["nome"].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
						</div>

                        <div class="row ">
                            <div class="col">
								<div class="col" style="text-align: left">
									<label for="frm_val3_edit" class="form-label">Hora de Início:</label>
								</div>
								<input type="datetime-local" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val3_edit" class="form-control form-control-lg" name="frm_val3_edit" id="frm_val3_edit">
                            </div>

                            <div class="col">
								<div class="col" style="text-align: left">
									<label for="frm_val4_edit" class="form-label">Hora de Fim:</label>
								</div>
								<input type="datetime-local" value="" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val4_edit" class="form-control form-control-lg" name="frm_val4_edit" id="frm_val4_edit">
                            </div>
                        </div>	

                        <div class="row">					
                            <div class="col">
								<div class="col" style="text-align: left">
									<label for="frm_val5_edit" class="form-label">Número do Pedido Associado:</label>
								</div>
								<input type="text" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val5_edit" class="form-control form-control-lg" name="frm_val5_edit" id="frm_val5_edit" placeholder="">
                            </div>

                            <div class="col mb-6">
								<div class="col" style="text-align: left">
									<label for="frm_val6_edit" class="form-label">Observações:</label>
								</div>
								<input type="text" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val6_edit" class="form-control form-control-lg" name="frm_val6_edit" id="frm_val6_edit" placeholder="">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="location.reload();">Cancelar</button>
					<button type="button" class="btn btn-danger btn-fw" id="frm_DELETE" onclick="delete_Event();"><img id="img_btn_DELETE" style="width: 15px; display: none; margin-right: 10px">Deletar</button>
					<button type="button" class="btn btn-primary" id="frm_OK" onclick="editClient();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
				</div>
            </div>
        </div>
    </div>	
</body>

<?php

include('bottom.php');
?>