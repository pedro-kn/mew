<?php


include("db.php");
$db = new Database();

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (isset($_GET["a"])) {

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Buscar conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	
	if ($_GET["a"] == "lista_user") {

		$pesquisa = $_POST['pesq'];
		$where = "";

		if ($pesquisa != "") {
			$where .= "WHERE (nome LIKE '%{$pesquisa}%' OR cpf LIKE '%{$pesquisa}%' OR telefone LIKE '%{$pesquisa}%' OR email LIKE '%{$pesquisa}%' OR obs LIKE '%{$pesquisa}%' OR statuscli LIKE '%{$pesquisa}%')";
		}

		$res = $db->select("SELECT * FROM clientes {$where}");

		if (count($res) > 0) {
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-md" style="font-size: 10pt">';
			echo '<thead>';
			echo '<tr>';
			echo '<th style="text-align: left">Nome</th>';
			echo '<th style="text-align: center">CPF</th>';
			echo '<th style="text-align: center">Telefone</th>';
			echo '<th style="text-align: center">E-mail</th>';
			echo '<th style="text-align: center">Observação</th>';
			echo '<th style="text-align: center">Relatórios</th>';
			if($_COOKIE['permissao']==2){
				echo '<th style="text-align: center">Status</th>';
				echo '<th style="text-align: center">Editar</th>';
				echo '<th style="text-align: center">Deletar</th>';
			}
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach ($res as $r) {

				echo '<td style="text-align: left">' . $r["nome"] . '</td>';
				echo '<td style="text-align: center">' . $r["cpf"] . '</td>';
				echo '<td style="text-align: center">' . $r["telefone"] . '</td>';
				echo '<td style="text-align: center">' . $r["email"] . '</td>';
				echo '<td style="text-align: center">' . $r["obs"] . '</td>';
				echo '<td style="text-align: center" class="mdi mdi-information-outline" onclick="get_item_rel('. $r["idCliente"] .')"></td>';
				
				if($_COOKIE['permissao']==2){
					echo '<td style="text-align: center">' . $r["statuscli"] . '</td>';
					echo '<td style="text-align: center">';
					echo '<i title="Editar" onclick="get_item(\'' . $r["idCliente"] . '\')" class="mdi mdi-table-edit" style="cursor: pointer"></i>';
					echo '</td>';
					echo '<td style="text-align: center">';
					echo '<i title="Deletar" onclick="del_item(\'' . $r["idCliente"] . '\')" class="mdi mdi-delete" style="cursor: pointer"></i>';
					echo '</td>';
				}
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
		} else {
			echo '<div class="alert alert-warning" role="alert">';
			echo 'Nenhum registro localizado!';
			echo '</div>';
		}
	}
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Inserir conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "inclui_user") {

	
		$nome = $_POST["nome"];
		$cpf = $_POST["cpf"];
		$telefone = $_POST["telefone"];
		$email = $_POST["email"];
		$obs = $_POST["obs"];
		$status = 1;
		

		$res = $db->_exec("INSERT INTO clientes (nome,cpf,telefone,email,obs,statuscli) VALUES ('$nome','$cpf','$telefone','$email','$obs','$status')");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Edita conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "edit_user") {


		$id = $_POST["id"];
		$nome = $_POST["nome"];
		$cpf = $_POST["cpf"];
		$telefone = $_POST["telefone"];
		$email = $_POST["email"];
		$obs = $_POST["obs"];

		$res = $db->_exec("UPDATE clientes 
			SET idCliente = '{$id}', nome = '{$nome}', cpf = '{$cpf}', telefone = '{$telefone}', email = '{$email}', obs = '{$obs}'
			WHERE idCliente = '{$id}'");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "del_user") {

		$id = $_POST["id"];

		$res = $db->_exec("DELETE FROM clientes WHERE idCliente = '{$id}'");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "get_user") {

		$id = $_POST["id"];

		$res = $db->select("SELECT nome, cpf, telefone, email, obs FROM clientes WHERE idCliente = '{$id}'");

		if (count($res) > 0) {
			$res[0]['nome'] = utf8_encode($res[0]['nome']);
			$res[0]['cpf'] = utf8_encode($res[0]['cpf']);
			$res[0]['telefone'] = utf8_encode($res[0]['telefone']);
			$res[0]['email'] = utf8_encode($res[0]['email']);
			$res[0]['obs'] = utf8_encode($res[0]['obs']);

			$a_retorno["res"] = $res;
			$c_retorno = json_encode($a_retorno["res"]);
			print_r($c_retorno);
		}
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca conteúdo para exibição no modal de relatorio:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "get_relatorio") {

		$id = $_POST["id"];

		$sel = $db->select("SELECT a.idAvaliacoes, a.descricao, c.nome as nome, a.idCliente, c.idCliente FROM avaliacoes a
                            INNER JOIN clientes c ON c.idCliente = a.idCliente
							WHERE a.idCliente = '{$id}'");
		
			echo '<div class="col-md-12 grid-margin stretch-card">';
				echo '<div class="card">';
					echo '<div class="card-body">';
						if(count($sel)>0){
							echo '<div class="row justify-content-between">';
								echo '<div class="col-10">';
									echo '<h4 class="card-title">Relatorios de '. $sel[0]['nome'] .'</h4>';
								echo '</div>';
								echo '<div class="col-2">';
									echo '<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$("#mod_formul_relatorio").modal("hide");">X</button>';
								echo '</div>';
							echo '</div>';
							foreach($sel as $s){
							echo '<div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">';
								echo '<div onclick="get_rel(\'' . $s["idAvaliacoes"] . '\')" class="text-md-center text-xl-left">';
									echo '<h6 class="mb-1">'.$s['descricao'].'</h6>';
									echo '<p class="text-muted mb-0">'.$s['nome'].'</p>';
								echo '</div>';
								echo '<div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">';
									echo '<i title="Deletar" onclick="del_ava(\'' . $s["idAvaliacoes"] . '\')" class="mdi mdi-delete" style="cursor: pointer"></i>';
									echo '</div>';
							echo '</div>';
							}
						}else{
							echo '<div class="alert alert-warning" role="alert">';
								echo 'Nenhum registro localizado!';
							echo '</div>';
							}

					echo '</div>';
				echo '</div>';
			echo '</div>';
		
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca detalhes do relatorio selecionado:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "get_rel_det") {

		$id = $_POST["id"];

        $res = $db->select("SELECT c.nome as nomec, v.nome as nomev, a.descricao as descricao, a.stats as stats
                        FROM avaliacoes a
                        INNER JOIN clientes c ON c.idCliente = a.idCliente
                        INNER JOIN usuarios v ON v.idUsuario = a.idUsuario
                        WHERE a.idAvaliacoes = '{$id}'");
        
        $sel = $db->select("SELECT l.idLPA, l.idPergunta, l.idAvaliacoes, p.pergunta
                        FROM list_perg_ava l 
                        INNER JOIN perguntas p ON p.idPergunta = l.idPergunta
                        WHERE l.idAvaliacoes = '{$id}'");

        $sel1 = $db->select("SELECT r.resposta
                        FROM list_perg_ava l 
                        INNER JOIN resp_ava r ON r.idLPA = l.idLPA
                        WHERE l.idAvaliacoes = '{$id}'");
        
        //monta o corpo de exibição de cada avaliação

        $body = "";

        //$body .= '<div class="row">';
            $body .= '<div class=" col-md-12 grid-margin stretch-card">';
                $body .= '<div class="card">';
                    $body .= '<div class="card-body">';
                        $body .= '<div class="d-flex flex-row justify-content-between">';
                        $body .= '<h4 class="card-title mb-1">'.$res[0]['descricao'].'</h4>';
						if($res[0]['stats']!==2){
							$body .= '<h6 class="card-title mb-1">Respostas Pendentes!</h6>';
						}   
                            $body .= '</div>';
                            $body .= '<div class="row">';
                                $body .= '<div class="col-6">';
                                $body .= '<p class="text-muted mb-0">Usuário:</p>';
                                $body .= '<h6 class="mb-1">'.$res[0]['nomev'].'</h6>';
                            $body .= '</div>'; 
                            $body .= '<div class="col-6">';
                                $body .= '<p class="text-muted mb-0">Cliente:</p>';
                                $body .= '<h6 class="mb-1">'.$res[0]['nomec'].'</h6>';
                            $body .= '</div>'; 
                        $body .= '</div>';
                            $body .= '<div class="row">';
                            $body .= '<div class="col-12">';

                                if($sel>0){
                                $countr = 0;
                                $array_res = array();
                                foreach($sel as $s){
                                        $countr++;
                                        $body .= '<div class="preview-list">';
                                            $body .= '<div class="preview-item border-bottom">';
                                                $body .= '<div class="preview-thumbnail">';
                                                $body .= '<i class="mdi mdi-chevron-double-right"></i>';
                                            $body .= '</div>';
                                            $body .= '<div class="preview-item-content d-sm-flex flex-grow">';
                                                $body .= '<div class="flex-grow">';
                                                $body .= '<h6 class="preview-subject">'.$s['pergunta'].'</h6>';
                                                $body .= '<input id="resp_input'.$countr.'" "type="text" size="60"';
                                                    //if($res[0]['stats']==2){
                                                        $body .= 'disabled';
                                                    //}
                                                $body .= '>';
                                            $body .= '</div>';
                                            $body .= '<td style="text-align: center">';
                                                $body .= '<i title="Deletar" onclick="del_perg_ava(\'' . $s["idLPA"] . '\')" class="mdi mdi-delete" style="cursor: pointer"></i>';
                                                    
                                            $body .= '</td>';
                                            $body .= '<div class="mr-auto text-sm-center pt-2 pt-sm-0">';
                                                $body .= '<p class="text-muted">'.$countr.'</p>';
                                            $body .= '</div>';
                                        $body .= '</div>';
                                    $body .= '</div>';
                                $body .= '</div>';
                                }  
                                }else{
                                $body .= '<div class="alert alert-warning" role="alert">';
                                    $body .= 'Nenhum registro localizado!';
                                $body .= '</div>';
                                }
                            $body .= '</div>';
                        $body .= '</div>';
                    $body .= '<div class="modal-footer">';

                    $body .= '</div>';
                $body .= '</div>';
            $body .= '</div>';
        $body .= '</div>';
    //$body .= '</div>';

    //exibe as respostas nos campos de input

    $retorno["body"]=$body;
    $retorno["resp"]=$sel1;
    $retorno["count"]=$countr;
    
    echo json_encode($retorno);

	}

	die();
}

include('header.php');
include('sidebar.php');
include('navbar.php');
?>

<script>
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Listar itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const lista_itens = () => {
		if(ajax_div){ ajax_div.abort(); }
			ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=lista_user',
			type: 'post',
			data: {pesq: $('#input_pesquisa').val()},
			beforeSend: function(){
				$('#div_conteudo').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				$('#div_conteudo').html(retorno); 
			}
		});
	}
    
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Incluir itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const incluiUser = () => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=inclui_user',
			type: 'post',
			data: { 
                nome: $('#Nome').val(),
				cpf: $('#cpf').val(),
                telefone: $('#phone').val(),
                email: $('#email').val(),
				obs: $('#obs').val(),
            },
			beforeSend: function(){

				$('#mod_formul').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				if(retorno){
                    $('#mod_formul').modal('hide');
					location.reload();
                    lista_itens();  
                }else{
                    alert("ERRO AO CADASTRAR USUÁRIO! " + retorno);
                }
			}
		});
	}

	// Evento inicial:
	$(document).ready(function() {
		lista_itens();
	});

	// ifnalizacao das divs em aberto no modal de relatorio
	$(document).ready(function(){

		$("#mod_formul_relatorio").on('hide.bs.modal', function(){
			location.reload();
		});

	});

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Pesquisar itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const get_item = (id) => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=get_user',
			type: 'post',
			data: { 
                id: id,
            },
			beforeSend: function(){
                $('#mod_formul_edit').modal("show");
			},
			success: function retorno_ajax(retorno) {
				
				if(retorno){
                    $("#frm_id").val(id);
                    
					var obj_ret = JSON.parse(retorno);

					$("#frm_nome_edit").val(obj_ret[0].nome);
					$("#frm_cpf_edit").val(obj_ret[0].cpf);
					$("#frm_phone_edit").val(obj_ret[0].telefone);
					$("#frm_email_edit").val(obj_ret[0].email);	
					$("#frm_obs_edit").val(obj_ret[0].obs);
				}
			}
		});
	}

	 /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Pesquisar itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const get_rel = (id) => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=get_rel_det',
			type: 'post',
			data: { 
                id: id,
            },
			beforeSend: function(){
                $('#div_relatorio2').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
            },
            success: function retorno_ajax(retorno) {
                
                var obj = JSON.parse(retorno);
                var objbody = obj.body;
                var objresp = obj.resp;

                $('#div_relatorio2').html(objbody);

                for(var i=1;i<=obj.count;i++){
                    if(objresp[i-1].resposta!==undefined){
                        $('#resp_input'+i+'').val(objresp[i-1].resposta);
                    }
                }
			}
		});
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Pesquisar itens para o modal de relatorios:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const get_item_rel = (id) => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=get_relatorio',
			type: 'post',
			data: { 
                id: id,
            },
			beforeSend: function(){
                $('#mod_formul_relatorio').modal("show");
			},
			success: function retorno_ajax(retorno) {
				$('#div_relatorio').html(retorno); 
			}
		});
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Editar itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const editUser = () => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=edit_user',
			type: 'post',
			data: { 
                id: $("#frm_id").val(),
                nome: $("#frm_nome_edit").val(),
				cpf: $("#frm_cpf_edit").val(),
                telefone: $("#frm_phone_edit").val(),
                email: $("#frm_email_edit").val(),
				obs: $("#frm_obs_edit").val(),
            },
			beforeSend: function(){
                $('#mod_formul_edit').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				if(retorno){
                    $('#mod_formul_edit').modal('hide');
                    location.reload();
                    lista_itens();  
                }else{
                    alert("ERRO AO EDITAR USUÁRIO! " + retorno);
                }
			}
		});
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Excluir usuário:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function del_item(id){
        if( confirm( "Deseja excluir o usuário?")){
            if(ajax_div){ ajax_div.abort(); }
		        ajax_div = $.ajax({
		    	cache: false,
		    	async: true,
		    	url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=del_user',
		    	type: 'post',
		    	data: { 
                    id: id,
                },
		    	success: function retorno_ajax(retorno) {
                    if(retorno){
						location.reload();
                    	lista_itens();  
                	}else{
                    	alert("ERRO AO DELETAR USUÁRIO! " + retorno);
                	}
		    	}
		    });
        }else{
            lista_itens();
        }	
	}
	
	//Mascaras para inputs e exibição com JSMASK
	$(document).ready(function(){
  		$('#phone').mask('(00) 0 0000-0000');
		$('#frm_phone_edit').mask('(00) 0 0000-0000');
		$('#cpf').mask('000.000.000-00');
		$('#frm_cpf_edit').mask('000.000.000-00');
	});

</script>

<style>
	.table{
  color: #ffffff; }

	</style>

	<!-- Modal formulário de inclusao-->
	<div class="modal" id='mod_formul'>
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 50%;">
			<div class="modal-content">
				<div class="modal-header" style="align-items: center">
					<div style="display: flex; align-items: center">
						<div style="margin-right: 5px">
							<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
						</div>
						<div>
							<h5 id="tit_frm_formul" class="modal-title">Incluir Clientes</h5>
						</div>
					</div>
					<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul').modal('hide');">X</button>
				</div>
				<div class="modal-body modal-dialog-scrollable container-fluid" style="max-width: 300 px">
					<form id="frm_general" name="frm_general" class= "col">
						<div class="row">
							<div class="col">
								<label for="Nome" class="form-label">Nome:</label>
								<input type="text" style="text-align: left" aria-describedby="Nome" class="form-control form-control-lg text-white" name="Nome" id="Nome" placeholder="" style="max-width: 300 px">
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="cpf" class="form-label">CPF:</label>
								<input type="text" style="text-align: left" aria-describedby="cpf" maxlength="11" class="form-control form-control-lg" name="cpf" id="cpf" placeholder="XXX.XXX.XXX-XX">
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="phone" class="form-label">Número de Telefone:</label>
								<input type="tel" style="text-align: left" aria-describedby="phone" maxlength="11" class="form-control form-control-lg" name="phone" id="phone" placeholder="(XX) X XXXX-XXXX">
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="email" class="form-label">E-mail:</label>
								<input type="email" style="text-align: left" aria-describedby="email" class="form-control form-control-lg" name="email" id="email" placeholder="">
								<small>Exemplo: joao12@endereco.com</small><br><br>	
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="obs" class="form-label">Observação:</label>
								<input type="text" style="text-align: left" aria-describedby="obs" class="form-control form-control-lg" name="obs" id="obs" placeholder="">
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" onclick="$('#mod_formul').modal('hide');">Cancelar</button>
					<button type="button" class="btn btn-primary" id="OK" onclick="incluiUser();"><img id="img_btn_ok" style="width: 15px; color: black; display: none; margin-right: 10px">OK</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal formulário de edição -->
	<div class="modal" id="mod_formul_edit">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 50%;">
			<div class="modal-content">
				<div class="modal-header" style="align-items: center">
					<div style="display: flex; align-items: center">
						<div style="margin-right: 5px">
							<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
						</div>
						<div>
							<h5 id="tit_frm_formul_edit" class="modal-title">Editar Clientes</h5>
						</div>
					</div>
					<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul_edit').modal('hide');">X</button>
				</div>
				<div class="modal-body modal-dialog-scrollable">
					<form id="frm_general_edit" name="frm_general" class="col">
						<div class="row mb-3">
							<div class="col">
								<input type="text" style="text-align: left" aria-describedby="frm_id" class="form-control form-control-lg" name="frm_id" id="frm_id" hidden>
								<label for="frm_nome_edit" class="form-label">Nome:</label>
								<input type="text" style="text-align: left" aria-describedby="frm_nome_edit" class="form-control form-control-lg" name="frm_nome_edit" id="frm_nome_edit" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="frm_cpf_edit" class="form-label">CPF:</label>
								<input type="text" style="text-align: left" aria-describedby="frm_cpf_edit" maxlength="11" class="form-control form-control-lg" name="frm_cpf_edit" id="frm_cpf_edit" placeholder="">
							</div>
						</div>

						<div class="row mb-3">
							<div class="col">
								<label for="frm_phone_edit" class="form-label">Número de Telefone:</label>
								<input type="text" style="text-align: left" aria-describedby="frm_phone_edit" class="form-control form-control-lg" name="frm_phone_edit" id="frm_phone_edit" placeholder="">
							</div>
						</div>

						<div class="row mb-3">
							<div class="col">
								<label for="frm_email_edit" class="form-label">E-mail:</label>
								<input type="text" style="text-align: left" aria-describedby="frm_email_edit" class="form-control form-control-lg" name="frm_email_edit" id="frm_email_edit" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="frm_obs_edit" class="form-label">Observação:</label>
								<input type="text" style="text-align: left" aria-describedby="frm_obs_edit" class="form-control form-control-lg" name="frm_obs_edit" id="frm_obs_edit" placeholder="">
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" onclick="$('#mod_formul_edit').modal('hide');">Cancelar</button>
					<button type="button" class="btn btn-primary" id="frm_OK" onclick="editUser();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal formulário de relatorioo -->
	<div class="modal" id="mod_formul_relatorio">
		
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 70%;">
			<div class="modal-content">
				<div class="modal-header" style="align-items: center">
					<div style="display: flex; align-items: center">
						<div style="margin-right: 5px">
							<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
						</div>
						<div>
							<h5 id="tit_frm_formul_edit" class="modal-title">Relatorios</h5>
						</div>
					</div>
					<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul_relatorio').modal('hide');">X</button>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div id="div_relatorio"></div>
					</div>
					<div class="col-md-8">
						<div id="div_relatorio2"></div>
					</div>
				</div>
				
				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>

	<!-- Pagina principal -->
	<button class="btn btn-inverse-light btn-lg align-items-center grid-margin">
		<h3 style="font-size: 28px; text-align: center; vertical-align: baseline;"> Anuncie aqui! </h3>
	</button>
	<div class="content-wrapper"   style="background-image: url('assets/coronafree/template/assets/images/galaxy3.png'); background-repeat: no-repeat; background-size: cover;">
		<div class="page-header">
			<h3 class="page-title"> Usuários </h3>
		</div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title ">Clientes</h4>
						<p class="card-description">Visualize a lista de registros de Clientes</p>

						<div class="form-group row">
							<div class="col-10" class="size-md">
								<div class="input-group">
									<input type="text" class="form-control" onkeyup="lista_itens()" id="input_pesquisa" placeholder="Pesquise">
								</div>
							</div>
							<div class="col-2">
								<div class="input-group">
									<button type="button" onclick="$('#mod_formul').modal('show');" class="btn btn-inverse-light btn-fw btn-md" style="height: 38px"><i class="mdi mdi-library-plus" style="margin-right: 5px"></i>Incluir</button>
								</div>
							</div>
						</div>	

						<div id="div_conteudo" class="template-demo"></div>
					</div>
				</div>
			</div>

<?php
include('bottom.php');
?>