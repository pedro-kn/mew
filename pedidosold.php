<?php

include ("db.php");
$db = new Database();

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if(isset($_GET["a"])){

    function remove_acento($string){
		$caracteres_sem_acento = array(
			'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Â'=>'Z', 'Â'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
			'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
			'Ï'=>'I', 'Ñ'=>'N', 'Å'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
			'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
			'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
			'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'Å'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
			'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
			'Ä'=>'a', 'î'=>'i', 'â'=>'a', 'È'=>'s', 'È'=>'t', 'Ä'=>'A', 'Î'=>'I', 'Â'=>'A', 'È'=>'S', 'È'=>'T',
		);
		$nova_string = strtr($string, $caracteres_sem_acento);
		return ($nova_string);
	}
	
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Buscar conteúdo na div conteudo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_user"){
		
		$pesquisa = $_POST['pesq'];
        $where = "";

        if($pesquisa != ""){
            $where .= "WHERE idPedido LIKE '%{$pesquisa}%' OR c.Nome LIKE '%{$pesquisa}%' OR v.Nome LIKE '%{$pesquisa}%' OR quantidade LIKE '%{$pesquisa}%' OR preco LIKE '%{$pesquisa}%' OR nf LIKE '%{$pesquisa}%' OR p.statusped LIKE '%{$pesquisa}%'";
        }    
    
		$res = $db->select("SELECT p.idPedido, c.Nome as nomec, v.Nome as nomev, p.quantidade, p.preco, p.nf, p.statusped
                            FROM pedidos p
                            INNER JOIN cliente c ON c.idCliente = p.idCliente
                            INNER JOIN vendedor v ON v.idVendedor = p.idVendedor
                            {$where} ORDER BY p.idPedido");
		
		if(count($res) > 0){
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">';
				echo '<thead>';
					echo '<tr>';
						echo '<th style="text-align: left">idPedido</th>';
						echo '<th style="text-align: center">Nome do Cliente</th>';
						echo '<th style="text-align: center">Nome do Vendedor</th>';
                        echo '<th style="text-align: center">Quantidade</th>';
                        echo '<th style="text-align: center">Preço</th>';
                        echo '<th style="text-align: center">Nota Fiscal</th>';
                        echo '<th style="text-align: center">Status</th>';                                  
                        echo '<th style="text-align: center">Editar</th>';
                        echo '<th style="text-align: center">Deletar</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
                foreach($res as $r){
					echo '<tr  onclick="get_item_ped('.$r["idPedido"].')" >';
						echo '<td style="text-align: left">'.$r["idPedido"].'</td>';
						echo '<td style="text-align: center">'.$r["nomec"].'</td>';
						echo '<td style="text-align: center">'.$r["nomev"].'</td>';
                        echo '<td style="text-align: center">'.$r["quantidade"].'</td>';
                        echo '<td style="text-align: center">'.$r["preco"].'</td>';
                        echo '<td style="text-align: center">'.$r["nf"].'</td>';
                        echo '<td style="text-align: center">'.$r["statusped"].'</td>';

                        echo '<td style="text-align: center">';
							echo '<i title="Editar" onclick="get_item(\''.$r["idPedido"].'\')" class="fas fa-edit" style="cursor: pointer"></i>';
						echo '</td>';
                        echo '<td style="text-align: center">';
							echo '<i title="Deletar" onclick="del_item(\''.$r["idPedido"].'\')" class="fas fa-trash" style="cursor: pointer"></i>';
						echo '</td>';
					echo '</tr>';
				}
				echo '</tbody>';
			echo '</table>';
			echo '</div>';
		}else{
			echo '<div class="alert alert-warning" role="alert">';
				echo 'Nenhum registro localizado!';
			echo '</div>';
		}
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Ocultamente cria o pedido, e após Exibe lista de itens na div modInsert:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_mod_insert"){    
    
		$vendedor = $_POST["vendedor"];
        $cliente = $_POST["cliente"];

		$ped = $db->_exec("INSERT INTO pedidos (idCliente,idVendedor,statusped) VALUES ($cliente,'$vendedor','1')");
		
		$s = $db->select("SELECT idPedido FROM pedidos WHERE idCliente = $cliente AND idVendedor = '$vendedor' ORDER BY idPedido DESC LIMIT 1");

		foreach($s as $s1){
			$numped = $s1["idPedido"];
		}

		$res = $db->select("SELECT idProdutos, descricao, Preço FROM produtos ORDER BY descricao");
		
		if(count($res) > 0){
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">';
				echo '<thead>';
					echo '<tr>';
						echo '<th style="text-align: left">Descrição</th>';
						echo '<th style="text-align: center">Preço</th>';
                        echo '<th style="text-align: center">Quantidade</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
                foreach($res as $r){
					echo '<tr >';
						echo '<td  style="text-align: left">'.$r["descricao"].'</td>';
						echo '<td style="text-align: center">'.$r["Preço"].'</td>';
						echo '<td style="text-align: center">';
							echo '<input type="number" onchange="incluiPed(this.value,\''.$r["idProdutos"].'\',\''.$numped.'\')" min="0" max="100"></input>';
						echo '</td>';
					echo '</tr>';
				}
				echo '</tbody>';
			echo '</table>';
			echo '</div>';
		}else{
			echo '<div class="alert alert-warning" role="alert">';
				echo 'Nenhum registro localizado!';
			echo '</div>';
		}
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Inserir conteúdo dentro da lista de pedidos criada em lista_mod_insert:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "inclui_pedido"){
		


        $quantidade = $_POST["quantidade"];
        $produto = $_POST["produto"];
        $pedido = $_POST["pedido"];
	
		$sel = $db->select("SELECT Preço FROM produtos WHERE idProdutos = $produto");
		
			if(count($sel)>0){
				$preco = floatval($sel[0]["Preço"])*$quantidade;
			}

		$res = $db->_exec("INSERT INTO itens_pedido (idPedido,idProdutos,quantidade,valor_final) VALUES ($pedido,'$produto',$quantidade,$preco)");
		
		
        echo $res;
	}



    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Confirmar a inserção de conteúdo dentro da lista de pedidos criada em lista_mod_insert:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "inclui_client"){
		
		//obtem o numerdo do pedido da tabela itens pedido a ser incluso
        $numpedido = $_POST["numpedido"];
		
		$somaquantidade = 0;
		$somavalor = 0;
        
		$sel = $db->select("SELECT idPedido, idProdutos, quantidade, valor_final FROM itens_pedido WHERE idPedido = $numpedido");

		//logica para a soma dos valores quantidade e valor para fazer o update na tabela de pedidos
		if(count($sel)>0){
			foreach($sel as $s){
				$somaquantidade = $somaquantidade + $s["quantidade"];
				$somavalor = $somavalor + $s["valor_final"];
			}	
		}

		//logicas para gerar os valores de nota fiscal
		$numero = intval(rand(1,pow(10,6)));
		
		$sum = 0;
		$chave = "";
		$chave1 = "";
			while($sum <= 11){
				$chave1 = rand(1000,(pow(10,4))); 
				$chave .= $chave1;
			$sum++;
			}
		
		$nfe = $db->_exec("INSERT INTO nf (idPedido,numero,serie,chave,data_hora) VALUES ($numpedido,$numero,1,'$chave',LOCALTIME())");		
		
		//update nos valores da tabela pedidos
		$res = $db->_exec("UPDATE pedidos SET quantidade = $somaquantidade, preco = $somavalor, nf = '$numero', statusped = 2 WHERE idPedido = $numpedido");
		
		//baixa nos estoques pós emissao da nf
		$sel1 = $db->select("SELECT p.idProdutos, e.idProdutos as eidprod, e.quantidade as equant, p.quantidade as pquant FROM itens_pedido p 
							INNER JOIN itens_estoque e ON e.idProdutos = p.idProdutos
							WHERE p.idPedido = $numpedido");

			foreach($sel1 as $s){

					$idp = $s["eidprod"];
					$subtracao = floatval($s["equant"]) - floatval($s["pquant"]);
					$baixa = $db->_exec("UPDATE itens_estoque SET quantidade = $subtracao WHERE idProdutos = $idp");
			}
		echo $res;
	}

	

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Edita conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "edit_client"){
        

        $id = $_POST["id"];
        $descricao = $_POST["descricao"];
        $endereco = $_POST["endereco"];
        $quantidade = $_POST["quantidade"];
        

        $res = $db->_exec("UPDATE itens_estoque 
			SET iditens_estoque = {$id},  quantidade = '{$quantidade}'
			WHERE iditens_estoque = {$id}");

        echo $res;
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "del_user"){
      

        $id = $_POST["id"];

		$del = $db->_exec("DELETE FROM itens_pedido WHERE idPedido = '{$id}'");	
        $res = $db->_exec("DELETE FROM pedidos WHERE idPedido = '{$id}'");
		
        echo $res;
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "get_client"){
      

        $id = $_POST["id"];

        $res = $db->select("SELECT iditens_estoque, p.descricao, e.endereco, quantidade
			FROM itens_estoque i 
			inner join end_estoque e on e.idend_estoque = i.idend_estoque
			inner join produtos p on p.idProdutos = i.idProdutos
			WHERE iditens_estoque = {$id}");
		
        if(count($res) > 0){
            $res[0]['descricao'] = utf8_encode($res[0]['descricao']);
            $res[0]['endereco'] = utf8_encode($res[0]['endereco']);
			$res[0]['quantidade'] = utf8_encode($res[0]['quantidade']);
			
            $a_retorno["res"] = $res;
            $c_retorno = json_encode($a_retorno["res"]);
            print_r($c_retorno);
        }
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca conteúdo para a exibição dos detalhes do pedido:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "get_det_ped"){
      

        $id = $_POST["id"];

		$res = $db->select("SELECT p.idPedido, p.idCliente, p.idVendedor, c.Nome as nomec, v.Nome as nomev, p.quantidade, p.preco, p.nf, p.statusped
							FROM pedidos p
							INNER JOIN cliente c ON c.idCliente = p.idCliente
							INNER JOIN vendedor v ON v.idVendedor = p.idVendedor
							WHERE p.idPedido = {$id}");
		
        if(count($res) > 0){
            $res[0]['nomev'] = remove_acento($res[0]['nomev']);
            $res[0]['nomec'] = remove_acento($res[0]['nomec']);
			$res[0]['nf'] = remove_acento($res[0]['nf']);
			$res[0]['statusped'] = remove_acento($res[0]['statusped']);
			$res[0]['quantidade'] = remove_acento($res[0]['quantidade']);
			$res[0]['preco'] = remove_acento($res[0]['preco']);
			
			$c_retorno = array();
			$body = "";

			$lista = $db->select("SELECT p.descricao, i.idProdutos, p.idProdutos, p.Preço as preco, i.quantidade, i.valor_final FROM itens_pedido i
								INNER JOIN produtos p ON p.idProdutos = i.idProdutos 
								WHERE i.idPedido = {$id}");
				foreach($lista as $s){
					$body .= '<tr>';
						$body .= '<td style="text-align: left">'.$s["descricao"].'</td>';
						$body .= '<td style="text-align: center">'.$s["quantidade"].'</td>';
						$body .= '<td style="text-align: center">'.$s["preco"].'</td>';
						$body .= '<td style="text-align: center">'.$s["valor_final"].'</td>';
				}						
			
			$title = '<h5 id="div_exibe_title" class="modal-title">Informações do Pedido '.$id.'</h5>';
			
			$c_retorno["title"] = $title;	
			$c_retorno["header"] = $res;	
            //$a_retorno["res"] = $res;
            //$c_retorno["header"] = json_encode($a_retorno["res"]);
			$c_retorno["body"] = $body;
			echo json_encode($c_retorno);
            //print_r(json_encode($c_retorno));
			//print_r($a_retorno["res"]);

        }
	}

    die();
}

// Includes para o script:
include("header.php");
include("dashboard.php");

?>


<script type="text/javascript" src="./assets/js/jquery-3.6.1.min.js"></script>
<script type="text/javascript">


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
			data: {pesq: $('#input_pesquisa').val() 			},
			beforeSend: function(){
				$('#div_conteudo').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				$('#div_conteudo').html(retorno); 
			}
		});
	}
    
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* inclui no modal os itens para inclusão:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const incluiPed = (quantidade,produto,pedido) => {
		$('#numpedido').val(pedido);
		if(ajax_div){ ajax_div.abort(); }
			ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=inclui_pedido',
			type: 'post',
			data: {quantidade: quantidade,
				produto: produto,
                pedido: pedido},
			
			success: function retorno_ajax(retorno) {
				//$('#numpedido').val(pedido);
				if(!retorno){
					alert("ERRO AO INLUIR ITEM NO PEDIDO!");
				} 
			}
		});
	}

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
				vendedor: $('#frm_val1_insert').val(),
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
	* Incluir itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const incluiClient = () => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=inclui_client',
			type: 'post',
			data: { 
                numpedido: $('#numpedido').val(),
              
            },
			beforeSend: function(){

				$('#modal_formul').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				alert(retorno);
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
			url: '?a=get_client',
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

					$("#frm_val1_edit").val(obj_ret[0].descricao);
					$("#frm_val2_edit").val(obj_ret[0].endereco);
					$("#frm_val3_edit").val(obj_ret[0].quantidade);	
				}
			}
		});
	}

	  /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Pesquisar itens dos detalhes do pedido:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const get_item_ped = (id) => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=get_det_ped',
			type: 'post',
			data: { 
                id: id,
            },
			beforeSend: function(){
                $('#mod_formul_exibe').modal("show");
			},
			success: function retorno_ajax(retorno) {
				alert(retorno);
				var obj = JSON.parse(retorno);
				
				//alert(teste.header);
				//if(retorno){
                    $("#frm_id_exibe").val(id);
                    
					var obj_ret = obj.header;

					$("#frm_val1_exibe").val(obj_ret[0].nomev);
					$("#frm_val2_exibe").val(obj_ret[0].nomec);
					$("#frm_val3_exibe").val(obj_ret[0].nf);
					$("#frm_val4_exibe").val(obj_ret[0].statusped);	
					$("#frm_val5_exibe").val(obj_ret[0].quantidade);	
					$("#frm_val6_exibe").val(obj_ret[0].preco);	

					$('#div_exibe_title').html(obj.title); 

					$('#div_exibe_ped').html(obj.body); 
				//}
			}
		});
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Editar itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const editClient = () => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=edit_client',
			type: 'post',
			data: { 
                id: $("#frm_id").val(),
                descricao: $("#frm_val1_edit").val(),
				endereco: $("#frm_val2_edit").val(),
                quantidade: $("#frm_val3_edit").val(),
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
        if( confirm( "Deseja excluir o pedido?")){
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
                    	alert("ERRO AO DELETAR ITENS! " + retorno);
                	}
		    	}
		    });
        }else{
            lista_itens();
        }
	}
</script>

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
						<h5 id="tit_frm_formul" class="modal-title">Incluir Peidos</h5>
					</div>
				</div>
				<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul').modal('hide');">X</button>
			</div>
			<div class="modal-body modal-dialog-scrollable">
				<form id="frm_general" name="frm_general">

				<div class="row mb-3">
						<div class="col">
							<label for="frm_val1_insert" class="form-label">Vendedor:</label>
								<div class="scrollable">
								<select id="frm_val1_insert"  class="select form-control form-control-lg" name="frm_val1_insert" type="text" >
									<option value="" selected></option>
									<?php
										$desc = $db->select('SELECT idVendedor, Nome FROM vendedor');
										foreach($desc as $s){
											echo  '<option value="'.$s["idVendedor"].'">'.$s["Nome"].'</option>';
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
								<select id="frm_val2_insert"  onchange="listaModinsert()" class="select form-control form-control-lg" name="frm_val2_insert" type="text" >
									<option value="" selected></option>
									<?php
										$desc = $db->select('SELECT idCliente, Nome FROM cliente');
										foreach($desc as $s){
											echo  '<option value="'.$s["idCliente"].'">'.$s["Nome"].'</option>';
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
						<h5 id="tit_frm_formul_edit" class="modal-title">Editar Itens do Estoque</h5>
					</div>
				</div>
				<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul_edit').modal('hide');">X</button>
			</div>
			<div class="modal-body modal-dialog-scrollable">
				<form id="frm_general_edit" name="frm_general">
					<div class="row mb-3">
						<div class="col">
                            <input type="text" style="text-align: left" aria-describedby="frm_id" class="form-control form-control-lg" name="frm_id" id="frm_id" hidden>
							<label for="frm_val1_edit" class="form-label">Descrição:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val1_edit" class="form-control form-control-lg" name="frm_val1_edit" id="frm_val1_edit" placeholder="" disabled>
						</div>
					</div>

					<div class="row mb-3">
						<div class="col">
							<label for="frm_val2_edit" class="form-label">Endereço:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val2_edit" class="form-control form-control-lg" name="frm_val2_edit" id="frm_val2_edit" placeholder="" disabled>
						</div>
					</div>

					<div class="row mb-3">
						<div class="col">
							<label for="frm_val3_edit" class="form-label">Quantidade:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val3_edit" class="form-control form-control-lg" name="frm_val3_edit" id="frm_val3_edit" placeholder="">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="$('#mod_formul_edit').modal('hide');">Cancelar</button>
				<button type="button" class="btn btn-primary" id="frm_OK" onclick="editClient();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal formulário Exibição-->
<div class="modal" id="mod_formul_exibe">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 70%;">
		<div class="modal-content">
			<div class="modal-header" style="align-items: center">
				<div style="display: flex; align-items: center">
					<div style="margin-right: 5px">
						<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
					</div>
					<div>
						<h5 id="div_exibe_title"></h5>
					</div>
				</div>
				<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul_exibe').modal('hide');">X</button>
			</div>
			<div class="modal-body modal-dialog-scrollable">
				<form id="frm_general_exib" name="frm_general">
					<div class="row mb-3">

						<div class="col">
                            <input type="text" style="text-align: left" aria-describedby="frm_id_exibe" class="form-control form-control-lg" name="frm_id_exibe" id="frm_id_exibe" hidden>
							<label for="frm_val1_exibe" class="form-label">Vendedor:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val1_exibe" class="form-control form-control-lg" name="frm_val1_exibe" id="frm_val1_exibe" placeholder="" disabled>
						</div>
					
						<div class="col">
							<label for="frm_val2_exibe" class="form-label">Cliente:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val2_exibe" class="form-control form-control-lg" name="frm_val2_exibe" id="frm_val2_exibe" placeholder="" disabled>
						</div>
					
						<div class="col">
							<label for="frm_val3_exibe" class="form-label">Nota Fiscal:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val3_exibe" class="form-control form-control-lg" name="frm_val3_exibe" id="frm_val3_exibe" placeholder="" disabled>
						</div>

						<div class="col">
							<label for="frm_val4_exibe" class="form-label">Status:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val4_exibe" class="form-control form-control-lg" name="frm_val4_exibe" id="frm_val4_exibe" placeholder="" disabled>
						</div>
					</div>	
					
					<div class="row mb-3">
						<div class="col">			
							<label for="frm_vallista_exibe" class="form-label"><b>Produtos:</b></label>
								<div class="table-responsive">
									<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">
										<thead>
											<tr>
												<th style="text-align: left">Descrição do Produto</th>
												<th style="text-align: center">Quantidade</th>
												<th style="text-align: center">Valor Unitário</th>
												<th style="text-align: center">Valor</th>
										</thead>
										<tbody id="div_exibe_ped">

											
										
										</tbody>
									</table>
								</div>				
						</div>			
					</div>

					<div class="row mb-3">					
						<div class="col">
							<label for="frm_val5_exibe" class="form-label">Quantidade Total:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val5_exibe" class="form-control form-control-lg" name="frm_val5_exibe" id="frm_val5_exibe" placeholder="" disabled>
						</div>

						<div class="col">
							<label for="frm_val6_exibe" class="form-label">Valor Final:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val6_exibe" class="form-control form-control-lg" name="frm_val6_exibe" id="frm_val6_exibe" placeholder="" disabled>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="$('#mod_formul_exibe').modal('hide');">Cancelar</button>
				<button type="button" class="btn btn-primary" id="frm_OK" onclick="$('#mod_formul_exibe').modal('hide');"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal geral -->
<div class="modal" id="mod_general" tabindex="-1" style="z-index: 1400 !important">
	<div id="mod_general" class="modal-dialog modal-dialog-scrollable modal-xl" tabindex="-1">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="tit_frm_general" class="modal-title"></h5>
				<button type="button" id="btn_general_close" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body modal-dialog-scrollable" id="modmenu_content">
			</div>
		</div>
	</div>
</div>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<div style="display: flex; flex: 1">
			<div style="flex: 1">
				<h1 class="h2">Peidos</h1>
			</div>
		</div>
	</div>

	<div class="form-group row">
		<div class="col-10">
			<div class="input-group">
			<input type="text" class="form-control" onkeyup="lista_itens()" id="input_pesquisa" placeholder="Pesquisar">
			</div>
		</div>
		<div class="col-2">
			<div class="input-group">
				<button type="button" onclick="$('#mod_formul').modal('show');" class="btn btn-primary"><i class="fa fa-plus-circle" style="margin-right: 5px"></i>Incluir</button>
			</div>
		</div>
	</div>

	<div id="div_conteudo"></div>
</main>

<?php include("bottom.php"); ?>
