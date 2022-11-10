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
			$where .= "WHERE (Nome LIKE '%{$pesquisa}%' OR CPF LIKE '%{$pesquisa}%' OR Comissão LIKE '%{$pesquisa}%')";
		}

		$res = $db->select("SELECT * FROM usuarios {$where}");

		if (count($res) > 0) {
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-striped table-hover table-md" style="font-size: 10pt">';
			echo '<thead>';
			echo '<tr>';
			echo '<th style="text-align: left">Nome</th>';
			echo '<th style="text-align: center">CPF</th>';
			echo '<th style="text-align: center">Comissão</th>';
			echo '<th style="text-align: center">Editar</th>';
			echo '<th style="text-align: center">Deletar</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach ($res as $r) {
				echo '<tr>';
				echo '<td style="text-align: left">' . $r["Nome"] . '</td>';
				echo '<td style="text-align: center">' . $r["CPF"] . '</td>';
				echo '<td style="text-align: center">' . $r["Comissão"] . '</td>';
				echo '<td style="text-align: center">';
				echo '<i title="Editar" onclick="get_item(\'' . $r["idVendedor"] . '\')" class="fas fa-edit" style="cursor: pointer"></i>';
				echo '</td>';
				echo '<td style="text-align: center">';
				echo '<i title="Deletar" onclick="del_item(\'' . $r["idVendedor"] . '\')" class="fas fa-trash" style="cursor: pointer"></i>';
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
		$cpf = $_POST["cpf"];
		$comissao = $_POST["comissao"];

		$s = $db->select("SELECT idVendedor FROM vendedor ORDER BY idVendedor DESC LIMIT 1");
		foreach ($s as $s1) {
			$codVendedor = intval($s1["idVendedor"]) + 1;
		}
		$res = $db->_exec("INSERT INTO vendedor (idVendedor,Nome,CPF,Comissão) VALUES ('$codVendedor','$nome','$cpf','$comissao')");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Edita conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "edit_user") {


		$id = $_POST["id"];
		$nome = $_POST["nome"];
		$cpf = $_POST["cpf"];
		$comissao = $_POST["comissao"];

		$res = $db->_exec("UPDATE vendedor 
			SET idVendedor = '{$id}', Nome = '{$nome}', CPF = '{$cpf}', Comissão = '{$comissao}'
			WHERE idVendedor = '{$id}'");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "del_user") {


		$id = $_POST["id"];

		$res = $db->_exec("DELETE FROM vendedor WHERE idVendedor = '{$id}'");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "get_user") {


		$id = $_POST["id"];

		$res = $db->select("SELECT Nome, CPF, Comissão FROM vendedor WHERE idVendedor = '{$id}'");

		if (count($res) > 0) {
			$res[0]['Nome'] = utf8_encode($res[0]['Nome']);
			$res[0]['CPF'] = utf8_encode($res[0]['CPF']);
			$res[0]['Comissão'] = utf8_encode($res[0]['Comissão']);

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
                cpf: $('#CPF').val(),
                comissao: $('#Comissão').val(),
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

					$("#frm_nome_edit").val(obj_ret[0].Nome);
					$("#frm_cpf_edit").val(obj_ret[0].CPF);
					$("#frm_comissao_edit").val(obj_ret[0].Comissão);	
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
                cpf: $("#frm_cpf_edit").val(),
                comissao: $("#frm_comissao_edit").val(),
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
</script>

<style>
	.table{
  color: #ffffff; }
 
	
	</style>
	<button class="btn btn-inverse-light btn-lg align-items-center grid-margin">
		<h3 style="font-size: 28px; text-align: center; vertical-align: baseline;"> Anuncie aqui! </h3>
	</button>
	<div class="content-wrapper"   style="background-image: url('assets/coronafree/template/assets/images/galaxy3.png'); background-repeat: no-repeat; background-size: cover;">
		<div class="page-header">
			<h3 class="page-title"> User </h3>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="clientes.php">Clients</a></li>
					<li class="breadcrumb-item active" aria-current="page">Employees</li>
				</ol>
			</nav>
		</div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title ">Employees</h4>
						<p class="card-description">Visualize your employees' register sheet</p>

						<div class="form-group row">
							<div class="col-10" class="size-md">
								<div class="input-group">
								<input type="text" class="form-control" onkeyup="lista_itens()" id="input_pesquisa" placeholder="Browse...">
								</div>
							</div>
							<div class="col-2">
								<div class="input-group">
									<button type="button" onclick="$('#mod_formul').modal('show');" class="btn btn-inverse-light btn-fw btn-md" style="height: 38px"><i class="mdi mdi-library-plus" style="margin-right: 5px"></i>Include</button>
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