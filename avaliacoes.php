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

		$res = $db->select("SELECT * FROM perguntas");

		echo '<div class="row">';
            echo '<div class="col-md-4 grid-margin stretch-card">';
                echo '<div class="card">';
                echo '<div class="card-body">';
                    echo '<h4 class="card-title">Cadastro de Avaliações</h4>';
                    echo '<button type="button" onclick="modal_cad_ava();" class="btn btn-inverse-light btn-fw btn-md" style="height: 32px"><i class="mdi mdi-library-plus" style="margin-right: 5px"></i>Incluir Nova Avaliação</button>';
                    echo '<div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">';
                    echo '<div class="text-md-center text-xl-left">';
                        echo '<h6 class="mb-1">Transfer to Paypal</h6>';
                        echo '<p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">';
                    echo '<div class="text-md-center text-xl-left">';
                        echo '<h6 class="mb-1">Tranfer to Stripe</h6>';
                        echo '<p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>';
                    echo '</div>';
                    echo '<div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">';
                        echo '<h6 class="font-weight-bold mb-0">$593</h6>';
                    echo '</div>';
                    echo '</div>';
                echo '</div>';
                echo '</div>';
            echo '</div>';
            echo '<div class="col-md-8 grid-margin stretch-card">';
                echo '<div class="card">';
                echo '<div class="card-body">';
                    echo '<div class="d-flex flex-row justify-content-between">';
                    echo '<h4 class="card-title mb-1">Cadastro de Perguntas</h4>';
                    echo '<button type="button" onclick="incluiUser();" class="btn btn-inverse-light btn-fw btn-md" style="height: 32px"><i class="mdi mdi-library-plus" style="margin-right: 5px"></i>Incluir Nova Pergunta</button>';
                    echo '</div>';
                    echo '<div class="row">';
                    echo '<div class="col-12">';

                        if($res>0){
                        $countr = 0;
                        foreach($res as $r){
                            $countr++;
                            echo '<div class="preview-list">';
                            echo '<div class="preview-item border-bottom">';
                                echo '<div class="preview-thumbnail">';
                                    echo '<i class="mdi mdi-checkbox-blank-outline"></i>';
                                echo '</div>';
                                echo '<div class="preview-item-content d-sm-flex flex-grow">';
                                echo '<div class="flex-grow">';
                                    echo '<h6 class="preview-subject">'.$r['pergunta'].'</h6>';
                                    //echo '<p class="text-muted mb-0">Broadcast web app mockup</p>';
                                echo '</div>';
                                echo '<td style="text-align: center">';
                                    echo '<i title="Deletar" onclick="del_item(\'' . $r["idPergunta"] . '\')" class="mdi mdi-delete" style="cursor: pointer"></i>';
                                echo '</td>';
                                echo '<div class="mr-auto text-sm-center pt-2 pt-sm-0">';
                                    echo '<p class="text-muted">'.$countr.'</p>';
                                echo '</div>';
                                echo '</div>';
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
                echo '</div>';
            echo '</div>';
            echo '</div>';
          
    
  
	}
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Inserir conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "inclui_user") {

		$pergunta = $_POST["pergunta"];

		$res = $db->_exec("INSERT INTO perguntas (pergunta) VALUES ('{$pergunta}')");

		echo $res;
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Inclui a nova avaliação:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "inclui_avaliacao") {

        $usuario = $_POST["usuario"];
        $cliente = $_POST["cliente"];
        $checklist = $_POST["checklist"];

        echo 1;
        die();
        $res = $db->_exec("INSERT INTO avaliacoes (idCliente,idUsuario,data_hora) VALUES ($cliente,$usuario,LOCALTIME())");
        

		echo $res;
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * Apresenta no modal de inclusão de avaliações as perguntas a serem associadas
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if($_GET["a"] == "lista_mod_insert"){    

        $res = $db->select("SELECT * FROM perguntas ORDER BY idPergunta");

        if($res!=0){
        $countr = 0;
        $perg = array();
        echo '<form action="#" method="post">';
        foreach($res as $r){
            
            echo '<div class="preview-list">';
                echo '<div class="preview-item border-bottom">';
                    echo '<div class="preview-thumbnail">';
                    echo '<input type="checkbox" id="$perg[]" name="$perg[]" value="'.$r['idPergunta'].'">';
                    echo '</div>';
                        echo '<div class="preview-item-content d-sm-flex flex-grow">';
                            echo '<div class="flex-grow">';
                                //echo '<h6 class="preview-subject">'.$r['pergunta'].'</h6>';
                                
                                echo '<label for="$perg[]">'.$r['pergunta'].'</label><br>';
                            echo '</div>';
                        echo '<div class="mr-auto text-sm-center pt-2 pt-sm-0">';
                        $countr++;
                            echo '<p class="text-muted">'.$countr.'</p>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
            /*
            <form action="/action_page.php">
            <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
            <label for="vehicle1"> I have a bike</label><br>
            <input type="checkbox" id="vehicle2" name="vehicle2" value="Car">
            <label for="vehicle2"> I have a car</label><br>
            <input type="checkbox" id="vehicle3" name="vehicle3" value="Boat">
            <label for="vehicle3"> I have a boat</label><br><br>
            <input type="submit" value="Submit">
            </form>
            */
        }  
        echo '</form>';
        }else{
        echo '<div class="alert alert-warning" role="alert">';
            echo 'Nenhum registro localizado!';
        echo '</div>';
        }
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

		$res = $db->_exec("DELETE FROM perguntas WHERE idPergunta = '{$id}'");

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
        data: {},
        beforeSend: function(){
          $('#div_conteudo').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
        },
        success: function retorno_ajax(retorno) {
          $('#div_conteudo').html(retorno); 
        }
      });
	}
    
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Abre o modal de inclusão de avaliações:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const modal_cad_ava = () => {
        $('#mod_formul').modal("show");
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Incluir a nova avaliação a partir do modal de inclusao de avaliacao:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const incluiAvaliacao = () => {
        if(ajax_div){ ajax_div.abort(); }
        ajax_div = $.ajax({
          cache: false,
          async: true,
          url: '?a=inclui_avaliacao',
          type: 'post',
          data: { 
                usuario: $('#frm_val1_insert').val(),
                cliente: $('#frm_val2_insert').val(),
                checklist: $('#$perg[]').val(),
              },
          beforeSend: function(){
              },
          success: function retorno_ajax(retorno) {
            alert(retorno)
            if(retorno){     
                location.reload();
                lista_itens();  
            }else{
                alert("ERRO AO CADASTRAR A PERGUNTA! " + retorno);
            }
          }
      });
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Incluir nova avaliação:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const incluiUser = () => {
        var pergunta = prompt('Insira a nova pergunta:');
        if(pergunta){
            if(ajax_div){ ajax_div.abort(); }
            ajax_div = $.ajax({
                cache: false,
                async: true,
                url: '?a=inclui_user',
                type: 'post',
                data: { 
                        pergunta: pergunta,
                    },
                beforeSend: function(){
                    },
                success: function retorno_ajax(retorno) {
                if(retorno){     
                    location.reload();
                    lista_itens();  
                }else{
                    alert("ERRO AO CADASTRAR A PERGUNTA! " + retorno);
                }
                }
            });
        }
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * Apresenta no modal de inclusão de avaliações as perguntas a serem associadas
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    var ajax_div = $.ajax(null);
    const listaModinsert = () => {
        if(ajax_div){ ajax_div.abort(); }
            ajax_div = $.ajax({
            cache: false,
            async: true,
            url: '?a=lista_mod_insert',
            type: 'post',
            data: {
                },
            beforeSend: function(){
                $('#mod_insert').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
            },
            success: function retorno_ajax(retorno) {
                $('#mod_insert').html(retorno); 
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
					$("#frm_cpf_edit").val(obj_ret[0].cpf);
					$("#frm_phone_edit").val(obj_ret[0].telefone);
					$("#frm_email_edit").val(obj_ret[0].email);	
					$("#frm_obs_edit").val(obj_ret[0].obs);
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
        if( confirm( "Deseja excluir a pergunta?")){
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
							<h5 id="tit_frm_formul" class="modal-title">Incluir Nova Avaliação</h5>
						</div>
					</div>
					<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul').modal('hide');">X</button>
				</div>
				<div class="modal-body modal-dialog-scrollable container-fluid" style="max-width: 300 px">
					<form id="frm_general" name="frm_general" class= "col">
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
					<button type="button" class="btn btn-primary" id="OK" onclick="incluiAvaliacao();"><img id="img_btn_ok" style="width: 15px; color: black; display: none; margin-right: 10px">OK</button>
				</div>
			</div>
		</div>
	</div>


<body>
    <div class="content-wrapper">
        <div id="div_conteudo" class="template-demo"></div>          
    </div>
</body> 





<?php

include('bottom.php');
?>