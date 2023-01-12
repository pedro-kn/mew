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
			$where .= "WHERE (nome LIKE '%{$pesquisa}%' OR telefone LIKE '%{$pesquisa}%' OR email LIKE '%{$pesquisa}%' OR statuscli LIKE '%{$pesquisa}%')";
		}

		$res = $db->select("SELECT * FROM usuarios {$where}");

		if (count($res) > 0) {
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-hover table-md" style="font-size: 10pt">';
			echo '<thead>';
			echo '<tr>';
			echo '<th style="text-align: left">Nome</th>';
			echo '<th style="text-align: center">Telefone</th>';
			echo '<th style="text-align: center">E-mail</th>';
			echo '<th style="text-align: center">Status</th>';
			echo '<th style="text-align: center">Editar</th>';
			echo '<th style="text-align: center">Deletar</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach ($res as $r) {

				echo '<tr>';
				echo '<td style="text-align: left">' . $r["nome"] . '</td>';
				echo '<td style="text-align: center">' . $r["telefone"] . '</td>';
				echo '<td style="text-align: center">' . $r["email"] . '</td>';
				echo '<td style="text-align: center">' . $r["statuscli"] . '</td>';
				echo '<td style="text-align: center">';
				echo '<i title="Editar" onclick="get_item(\'' . $r["idUsuario"] . '\')" class="mdi mdi-table-edit" style="cursor: pointer"></i>';
				echo '</td>';
				echo '<td style="text-align: center">';
				echo '<i title="Deletar" onclick="del_item(\'' . $r["idUsuario"] . '\')" class="mdi mdi-delete" style="cursor: pointer"></i>';
				echo '</td>';
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
		$telefone = $_POST["telefone"];
		$email = $_POST["email"];
		$senha = md5($_POST["senha"]);
		$status = 1;
		

		$res = $db->_exec("INSERT INTO usuarios (idUsuario,nome,telefone,email,statuscli,senha) VALUES ('','$nome','$telefone','$email','$status','$senha')");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Edita conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "edit_user") {


		$id = $_POST["id"];
		$nome = $_POST["nome"];
		$telefone = $_POST["telefone"];
		$email = $_POST["email"];
		$senha = md5($_POST["senha"]);

		$res = $db->_exec("UPDATE usuarios 
			SET idUsuario = '{$id}', nome = '{$nome}', telefone = '{$telefone}', email = '{$email}', senha = '{$senha}'
			WHERE idUsuario = '{$id}'");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "del_user") {


		$id = $_POST["id"];

		$res = $db->_exec("DELETE FROM usuarios WHERE idUsuario = '{$id}'");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "get_user") {


		$id = $_POST["id"];

		$res = $db->select("SELECT nome, telefone, email FROM usuarios WHERE idUsuario = '{$id}'");

		if (count($res) > 0) {
			$res[0]['nome'] = utf8_encode($res[0]['nome']);
			$res[0]['telefone'] = utf8_encode($res[0]['telefone']);
			$res[0]['email'] = utf8_encode($res[0]['email']);

			$a_retorno["res"] = $res;
			$c_retorno = json_encode($a_retorno["res"]);
			print_r($c_retorno);
		}
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
			url: '?a=lista_user',
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
			url: '?a=inclui_user',
			type: 'post',
			data: { 
                nome: $('#Nome').val(),
                telefone: $('#phone').val(),
                email: $('#email').val(),
				senha: $('#senha').val(),
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

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Pesquisar itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const get_item = (id) => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=get_user',
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
					$("#frm_phone_edit").val(obj_ret[0].telefone);
					$("#frm_email_edit").val(obj_ret[0].email);	
				}
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
			url: '?a=edit_user',
			type: 'post',
			data: { 
                id: $("#frm_id").val(),
                nome: $("#frm_nome_edit").val(),
                telefone: $("#frm_phone_edit").val(),
                email: $("#frm_email_edit").val(),
				senha: $("#frm_senha_edit").val(),
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
		    	url: '?a=del_user',
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
							<h5 id="tit_frm_formul" class="modal-title">Incluir Usuário</h5>
						</div>
					</div>
					<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul').modal('hide');">X</button>
				</div>
				<div class="modal-body modal-dialog-scrollable container-fluid" style="max-width: 300 px">
					<form id="frm_general" name="frm_general" class= "col">
						<div class="row">
							<div class="col">
								<label for="Nome" class="form-label">Nome:</label>
								<input type="text" style="text-align: left" aria-describedby="Nome" class="form-control form-control-lg" name="Nome" id="Nome" placeholder="" style="max-width: 300 px">
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="phone" class="form-label">Número de Telefone:</label>
								<input type="tel" style="text-align: left" aria-describedby="phone" maxlength="11" class="form-control form-control-lg" name="phone" id="phone" placeholder="(XX) X XXXX-XXXX">
								<small>Formato: [XX] X XXXX-XXXX</small>
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="Comissão" class="form-label">E-mail:</label>
								<input type="email" style="text-align: left" aria-describedby="email" class="form-control form-control-lg" name="email" id="email" placeholder="">
								<small>Exemplo: joao12@endereco.com</small><br><br>	
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label for="Comissão" class="form-label">Senha:</label>
								<input type="password" style="text-align: left" aria-describedby="email" class="form-control form-control-lg" name="email" id="email" placeholder="">
								
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
							<h5 id="tit_frm_formul_edit" class="modal-title">Editar Usuário</h5>
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
						<div class="row mb-3">
							<div class="col">
								<label for="frm_senha_edit" class="form-label">Senha:</label>
								<input type="password" style="text-align: left" aria-describedby="frm_senha_edit" class="form-control form-control-lg" name="frm_senha_edit" id="frm_senha_edit" placeholder="">
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

	<!-- Pagina principal -->
	<button class="btn btn-inverse-light btn-lg align-items-center grid-margin">
		<h3 style="font-size: 28px; text-align: center; vertical-align: baseline;"> Anuncie aqui! </h3>
	</button>
	<div class="content-wrapper"   style="background-image: url('assets/coronafree/template/assets/images/galaxy3.png'); background-repeat: no-repeat; background-size: cover;">
		<div class="page-header">
			<h3 class="page-title"> Usuários </h3>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="clientes.php">Clientes</a></li>
					<li class="breadcrumb-item active" aria-current="page">Funcionários</li>
				</ol>
			</nav>
		</div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title ">Funcionários</h4>
						<p class="card-description">Visualize a lista de registros de funcionários</p>

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