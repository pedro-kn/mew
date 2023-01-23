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

        $sel = $db->select("SELECT a.idAvaliacoes, a.descricao, c.nome, a.idCliente, c.idCliente FROM avaliacoes a
                            INNER JOIN clientes c WHERE c.idCliente = a.idCliente");

		$res = $db->select("SELECT * FROM perguntas");

		echo '<div class="row">';
            echo '<div class="col-md-4 grid-margin stretch-card">';
                echo '<div class="card">';
                    echo '<div class="card-body">';
                        echo '<h4 class="card-title">Cadastro de Avaliações</h4>';
                        echo '<button type="button" onclick="modal_cad_ava();" class="btn btn-inverse-light btn-fw btn-md" style="height: 32px"><i class="mdi mdi-library-plus" style="margin-right: 5px"></i>Incluir Nova Avaliação</button>';
                        if($sel>0){
                            foreach($sel as $s){
                            echo '<div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">';
                                echo '<div onclick="get_item(\'' . $s["idAvaliacoes"] . '\')" class="text-md-center text-xl-left">';
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
                                            echo '<i class="mdi mdi-chevron-double-right"></i>';
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

        $descricao = $_POST["descricao"];
        $usuario = $_POST["usuario"];
        $cliente = $_POST["cliente"];
        $checklist1 = $_POST["checklist1"];


        $res = $db->_exec("INSERT INTO avaliacoes (idCliente,idUsuario,data_hora,descricao,stats) VALUES ($cliente,$usuario,LOCALTIME(),'{$descricao}',1)");
        
        $sel = $db->select("SELECT idAvaliacoes FROM avaliacoes ORDER BY idAvaliacoes DESC LIMIT 1");

        foreach($checklist1 as $c){
            $res = $db->_exec("INSERT INTO list_perg_ava (idPergunta,idAvaliacoes) VALUES ($c,{$sel[0]['idAvaliacoes']})");
        }

		echo $res;
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Inclui as repostas da avalia~ção:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "inclui_resposta") {

        $id = $_POST["id"];
        $resposta = $_POST["resposta"];
        $count = $_POST["count"];

        $array_resp = array();

        $array_resp = explode("$@$", $resposta, -1);
        
        $sel = $db->select("SELECT idLPA FROM list_perg_ava  WHERE idAvaliacoes = {$id} ORDER BY idLPA");
        $sele = $db->select("SELECT stats FROM avaliacoes WHERE idAvaliacoes = {$id}");

        if($sele[0]['stats']==1){
            $i=0;
            foreach($array_resp as $a){
                $res = $db->_exec("INSERT INTO resp_ava (idLPA,idAvaliacoes,resposta) VALUES ({$sel[$i]['idLPA']},$id,'$a')");
                $i++;
            }

            $updt = $db->_exec("UPDATE avaliacoes SET stats = 2 WHERE idAvaliacoes = '{$id}'");

            echo $res;
        }else{
            echo 2;
        }
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
            $countr++; 
            echo '<div class="preview-list">';
                echo '<div class="preview-item border-bottom">';
                    echo '<div class="preview-thumbnail">';
                    echo '<input type="checkbox" id="pergts'.$countr.'" name="pergts" value="'.$r['idPergunta'].'">';
                    echo '</div>';
                        echo '<div class="preview-item-content d-sm-flex flex-grow">';
                            echo '<div class="flex-grow">';
                                //echo '<h6 class="preview-subject">'.$r['pergunta'].'</h6>';
                                
                                echo '<label for="pergts'.$countr.'">'.$r['pergunta'].'</label><br>';
                            echo '</div>';
                        echo '<div class="mr-auto text-sm-center pt-2 pt-sm-0">';
                        
                            echo '<p class="text-muted">'.$countr.'</p>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
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
	* Deleta pergunta do cadastro de perguntas:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "del_user") {

		$id = $_POST["id"];

		$res = $db->_exec("DELETE FROM perguntas WHERE idPergunta = '{$id}'");

		echo $res;
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta avaliação do cadastro de avaliações:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "del_aval") {

		$id = $_POST["id"];

		$res = $db->_exec("DELETE FROM avaliacoes WHERE idAvaliacoes = '{$id}'");

		echo $res;
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta pergunta da avaliação selecionada:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "del_perg_aval") {

		$id = $_POST["id"];

		$res = $db->_exec("DELETE FROM list_perg_ava WHERE idLPA = '{$id}'");

		echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "get_user") {

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

        $body .= '<div class="row">';
            $body .= '<div class="offset-md-2 col-md-8 grid-margin stretch-card">';
                $body .= '<div class="card">';
                    $body .= '<div class="card-body">';
                        $body .= '<div class="d-flex flex-row ">';
                        $body .= '<h4 class="card-title mb-1">'.$res[0]['descricao'].'</h4>';   
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
                                                $body .= '<input id="resp_input'.$countr.'" "type="text" size="70"';
                                                    if($res[0]['stats']==2){
                                                        $body .= 'disabled';
                                                    }
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
                        if($res[0]['stats']==2){
                           
                        }else{
                            $body .= '<button type="button" class="btn btn-primary" id="OK" onclick="incluiResposta(\'' . $countr+1 . '\',\'' . $id . '\');"><img id="img_btn_ok" style="width: 15px; color: black; display: none; margin-right: 10px">OK</button>';
                        }
                    $body .= '</div>';
                $body .= '</div>';
            $body .= '</div>';
        $body .= '</div>';
    $body .= '</div>';

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

        //obter o valor das checkboxes 
        var checkedBoxes = document.querySelectorAll('input[type=checkbox]:checked');

        var array_perg = [];
        for(var i=1;i<=checkedBoxes.length;i++){
            array_perg[i-1]=checkedBoxes[i-1].value;
        }

        if(ajax_div){ ajax_div.abort(); }
        ajax_div = $.ajax({
          cache: false,
          async: true,
          url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=inclui_avaliacao',
          type: 'post',
          data: { 
                descricao: $('#frm_val0_insert').val(),
                usuario: $('#frm_val1_insert').val(),
                cliente: $('#frm_val2_insert').val(),
                checklist1: array_perg,
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

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Incluir as respostas a partir do modal de inclusao de respostas:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const incluiResposta = (count,id) => {
        if(count>0){
            var resposta = "";
            
            for(var i=1;i<count;i++){
               resposta += $('#resp_input'+i+'').val() + '$@$';
            }

        }
        if(ajax_div){ ajax_div.abort(); }
         
        ajax_div = $.ajax({
          cache: false,
          async: true,
          url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=inclui_resposta',
          type: 'post',
          data: { 
                id: id,
                resposta: resposta,
                count: count
              },
          beforeSend: function(){
              },
          success: function retorno_ajax(retorno) {
            if(retorno==1){     
                location.reload();
                lista_itens(); 
            }else{
                alert("A RESPOSTA JA FOI REGISTRADA, MONTE UMA NOVA AVALIAÇÃO OU APAGUE AS PERGUNTAS JA FEITAS" + retorno);
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
                url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=inclui_user',
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
            url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=lista_mod_insert',
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
			url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=get_user',
			type: 'post',
			data: { 
                id: id,
            },
			beforeSend: function(){
                $('#div_conteudo2').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
            },
            success: function retorno_ajax(retorno) {
                
                var obj = JSON.parse(retorno);
                var objbody = obj.body;
                var objresp = obj.resp;

                $('#div_conteudo2').html(objbody);

                for(var i=1;i<=obj.count;i++){
                    if(objresp[i-1].resposta!==undefined){
                        $('#resp_input'+i+'').val(objresp[i-1].resposta);
                    }
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
	* Excluir pergunta do cadastro de perguntas:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function del_item(id){
        if( confirm( "Deseja excluir a pergunta?")){
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

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Excluir avaliação do cadastro de avaliações:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function del_ava(id){
        if( confirm( "Deseja excluir a avaliação?")){
            if(ajax_div){ ajax_div.abort(); }
		        ajax_div = $.ajax({
		    	cache: false,
		    	async: true,
		    	url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=del_aval',
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

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Excluir pergunta da avaliação selecionada:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function del_perg_ava(id){
        if( confirm( "Deseja excluir a pergunta dessa avaliação?")){
            if(ajax_div){ ajax_div.abort(); }
		        ajax_div = $.ajax({
		    	cache: false,
		    	async: true,
		    	url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=del_perg_aval',
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
                                <label for="frm_val0_insert" class="form-label">Nome da Avaliação:</label>
                                <div class="scrollable">
                                <input id="frm_val0_insert"  class="select form-control form-control-lg" name="frm_val0_insert" type="text" style="color: #ffffff"></input>
                                </div>
                            </div>
                        </div>
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
        <div id="div_conteudo2" class="template-demo"></div>     
    </div>
</body> 

<?php
include('bottom.php');
?>