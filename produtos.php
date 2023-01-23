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
			$where .= "WHERE (descricao LIKE '%{$pesquisa}%' OR valor LIKE '%{$pesquisa}%' OR obs LIKE '%{$pesquisa}%' OR codbar LIKE '%{$pesquisa}%')";
		}

		$res = $db->select("SELECT * FROM produtos {$where}");

		if (count($res) > 0) {
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-hover table-md" style="font-size: 10pt">';
			echo '<thead>';
			echo '<tr>';
			echo '<th style="text-align: left">Descrição</th>';
			echo '<th style="text-align: center">Valor</th>';
			echo '<th style="text-align: center">Quantidade</th>';
			echo '<th style="text-align: center">Observação</th>';
			echo '<th style="text-align: center">Codigo de Barras</th>';
			echo '<th style="text-align: center">Editar</th>';
			echo '<th style="text-align: center">Deletar</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach ($res as $r) {

				echo '<tr>';
				echo '<td style="text-align: left">' . $r["descricao"] . '</td>';
				echo '<td style="text-align: center">' . $r["valor"] . '</td>';
				echo '<td style="text-align: center">' . $r["quantidade"] . '</td>';
				echo '<td style="text-align: center">' . $r["obs"] . '</td>';
				echo '<td style="text-align: center">' . $r["codbar"] . '</td>';
				echo '<td style="text-align: center">';
				echo '<i title="Editar" onclick="get_item(\'' . $r["idProduto"] . '\')" class="mdi mdi-table-edit" style="cursor: pointer"></i>';
				echo '</td>';
				echo '<td style="text-align: center">';
				echo '<i title="Deletar" onclick="del_item(\'' . $r["idProduto"] . '\')" class="mdi mdi-delete" style="cursor: pointer"></i>';
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

		$descricao = $_POST["descricao"];
		$valor = $_POST["valor"];
		$quantidade = $_POST["quantidade"];
		$codbar = $_POST["codbar"];
		$obs = $_POST["obs"];
		
		$res = $db->_exec("INSERT INTO produtos (descricao,valor,obs,codbar,quantidade) VALUES ('$descricao','$valor','$obs', '$codbar', '$quantidade')");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Edita conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "edit_user") {

		$id = $_POST["id"];
		$descricao = $_POST["descricao"];
		$valor = $_POST["valor"];
		$quantidade = $_POST["quantidade"];
		$codbar = $_POST["codbar"];
		$obs = $_POST["obs"];

		$res = $db->_exec("UPDATE produtos 
			SET idProduto = '{$id}', descricao = '{$descricao}', valor = '{$valor}', codbar = '{$codbar}', obs = '{$obs}', quantidade = '{$quantidade}'
			WHERE idProduto = '{$id}'");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "del_user") {

		$id = $_POST["id"];

		$res = $db->_exec("DELETE FROM produtos WHERE idProduto = '{$id}'");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "get_user") {

		$id = $_POST["id"];

		$res = $db->select("SELECT descricao, valor, obs, codbar, quantidade FROM produtos WHERE idProduto = '{$id}'");

		if (count($res) > 0) {
			$res[0]['descricao'] = utf8_encode($res[0]['descricao']);
			$res[0]['valor'] = utf8_encode($res[0]['valor']);
			$res[0]['obs'] = utf8_encode($res[0]['obs']);
			$res[0]['codbar'] = utf8_encode($res[0]['codbar']);
			$res[0]['quantidade'] = utf8_encode($res[0]['quantidade']);

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
<script src="assets/js/plentz-jquery-maskmoney-cdbeeac/src/jquery.maskMoney.js" type="text/javascript"></script>
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
                descricao: $('#descricao').val(),
				valor: $('#valor').val(),
                codbar: $('#codbar').val(),
				obs: $('#obs').val(),
				quantidade: $('#quantidade').val(),
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

				$("#frm_descricao_edit").val(obj_ret[0].descricao);
				$("#frm_valor_edit").val(obj_ret[0].valor);
				$("#frm_codbar_edit").val(obj_ret[0].codbar);
				$("#frm_obs_edit").val(obj_ret[0].obs);
				$("#frm_quantidade_edit").val(obj_ret[0].quantidade);
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
			url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=edit_user',
			type: 'post',
			data: { 
                id: $("#frm_id").val(),
                descricao: $("#frm_descricao_edit").val(),
				valor: $("#frm_valor_edit").val(),
                codbar: $("#frm_codbar_edit").val(),
				obs: $("#frm_obs_edit").val(),
				quantidade: $("#frm_quantidade_edit").val(),
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
  		$('#valor').maskMoney({prefix:'R$ ', thousands:'.', decimal:','});
		$('#frm_valor_edit').maskMoney({prefix:'R$ ', thousands:'.', decimal:','});
		//$('#cpf').mask('000.000.000-00');
		//$('#frm_cpf_edit').mask('000.000.000-00');
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
							<h5 id="tit_frm_formul" class="modal-title">Incluir Produtos</h5>
						</div>
					</div>
					<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul').modal('hide');">X</button>
				</div>
				<div class="modal-body modal-dialog-scrollable container-fluid" style="max-width: 300 px">
					<form id="frm_general" name="frm_general" class= "col">
						<div class="row">
							<div class="col">
								<label for="descricao" class="form-label">Descrição:</label>
								<input type="text" style="text-align: left" aria-describedby="descricao" class="form-control form-control-lg" name="descricao" id="descricao" placeholder="" style="max-width: 300 px">
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="valor" class="form-label">Valor:</label>
								<input type="text" style="text-align: left" aria-describedby="valor" class="form-control form-control-lg" name="valor" id="valor" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="quantidade" class="form-label">Quantidade:</label>
								<input type="number" style="text-align: left" aria-describedby="quantidade" class="form-control form-control-lg" name="quantidade" id="quantidade" placeholder="">
							</div>
						</div>

                        <div class="row">
							<div class="col">
								<label for="obs" class="form-label">Observação:</label>
								<input type="text" style="text-align: left" aria-describedby="obs" class="form-control form-control-lg" name="obs" id="obs" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="codbar" class="form-label">Código de Barras:</label>
								<input type="text" style="text-align: left" aria-describedby="codbar" maxlength="30" class="form-control form-control-lg" name="codbar" id="codbar" placeholder="">
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
							<h5 id="tit_frm_formul_edit" class="modal-title">Editar Produtos</h5>
						</div>
					</div>
					<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul_edit').modal('hide');">X</button>
				</div>
				<div class="modal-body modal-dialog-scrollable">
					<form id="frm_general_edit" name="frm_general" class="col">
						<div class="row mb-3">
							<div class="col">
								<input type="text" style="text-align: left" aria-describedby="frm_id" class="form-control form-control-lg" name="frm_id" id="frm_id" hidden>
								<label for="frm_descricao_edit" class="form-label">Descrição:</label>
								<input type="text" style="text-align: left" aria-describedby="frm_descricao_edit" class="form-control form-control-lg" name="frm_descricao_edit" id="frm_descricao_edit" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="frm_valor_edit" class="form-label">Valor:</label>
								<input type="text" style="text-align: left" aria-describedby="frm_valor_edit" class="form-control form-control-lg" name="frm_valor_edit" id="frm_valor_edit" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="frm_quantidade_edit" class="form-label">Quantidade:</label>
								<input type="number" style="text-align: left" aria-describedby="frm_quantidade_edit" class="form-control form-control-lg" name="frm_quantidade_edit" id="frm_quantidade_edit" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col">
								<label for="frm_obs_edit" class="form-label">Observação:</label>
								<input type="text" style="text-align: left" aria-describedby="frm_obs_edit" class="form-control form-control-lg" name="frm_obs_edit" id="frm_obs_edit" placeholder="">
							</div>
						</div>

						<div class="row mb-3">
							<div class="col">
								<label for="frm_codbar_edit" class="form-label">Número de codbar:</label>
								<input type="text" style="text-align: left" aria-describedby="frm_codbar_edit" maxlength="30" class="form-control form-control-lg" name="frm_codbar_edit" id="frm_codbar_edit" placeholder="">
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
			<h3 class="page-title"> Produtos </h3>

		</div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title ">Produtos</h4>
						<p class="card-description">Visualize a lista de registros de Produtos </p>

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
		</div>
			<?php
			include('bottom.php');
			?>