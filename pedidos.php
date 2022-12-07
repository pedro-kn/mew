

    <?php

    include ('db.php');
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
                $where .= "WHERE idPedido LIKE '%{$pesquisa}%' OR c.nome LIKE '%{$pesquisa}%' OR v.nome LIKE '%{$pesquisa}%' OR quantidade LIKE '%{$pesquisa}%' OR preco LIKE '%{$pesquisa}%' OR nf LIKE '%{$pesquisa}%' OR p.statusped LIKE '%{$pesquisa}%'";
            }    
        
            $res = $db->select("SELECT p.idPedido, c.nome as nomec, v.nome as nomev, p.quantidade, p.preco, p.nf, p.statusped
                                FROM pedidos p
                                INNER JOIN clientes c ON c.idCliente = p.idCliente
                                INNER JOIN usuarios v ON v.idUsuario = p.idUsuario
                                {$where} ORDER BY p.idPedido");
            
            if(count($res) > 0){
                echo '<div class="table-responsive">';
                echo '<table id="tb_lista" class="table table-hover table-md" style="font-size: 10pt">';
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th style="text-align: left">idPedido</th>';
                            echo '<th style="text-align: center">Nome do Cliente</th>';
                            echo '<th style="text-align: center">Nome do Usuário</th>';
                            echo '<th style="text-align: center">Quantidade</th>';
                            echo '<th style="text-align: center">Preço</th>';
                            echo '<th style="text-align: center">Nota Fiscal</th>';
                            echo '<th style="text-align: center">Status</th>'; 
                            echo '<th style="text-align: center">Mais</th>';                                  

                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach($res as $r){
                        echo '<tr>';
                            echo '<td style="text-align: left">'.$r["idPedido"].'</td>';
                            echo '<td style="text-align: center">'.$r["nomec"].'</td>';
                            echo '<td style="text-align: center">'.$r["nomev"].'</td>';
                            echo '<td style="text-align: center">'.$r["quantidade"].'</td>';
                            echo '<td style="text-align: center">'.$r["preco"].'</td>';
                            echo '<td style="text-align: center">'.$r["nf"].'</td>';
                            echo '<td style="text-align: center">'.$r["statusped"].'</td>';

							echo '<td style="text-align: center">';
                            	echo '<a href="#" id="profile-dropdown" data-toggle="dropdown" class="dropleft"><i class="mdi mdi-playlist-plus"></i></a>';
                            	echo '<div class="dropdown-menu dropleft sidebar-dropdown col-1" style="max-width: 30px" aria-labelledby="profile-dropdown">';
                            		echo '<a href="#" onclick="get_item_ped(\''.$r["idPedido"].'\')" class="dropdown-item preview-item">';
                            			echo '<p class="preview-subject ellipsis mb-1 text-medium" style="text-align: left"><i class="mdi mdi-information-outline text-info"></i> Informações</p>';
									echo '</a>';
                                    echo '<div class="dropdown-divider"></div>';
                            			echo '<a href="#" onclick="faturar_item(\''.$r["idPedido"].'\')" class="dropdown-item preview-item">';
                            				echo '<p class="preview-subject ellipsis mb-1 text-medium" style="text-align: left"><i class="mdi mdi-briefcase-check  text-primary"></i> Faturar Pedido</p>';
                            			echo '</a>';
                            		echo '<div class="dropdown-divider"></div>';
                            			echo '<a href="#" onclick="get_item(\''.$r["idPedido"].'\')" class="dropdown-item preview-item">';
                            				echo '<p class="preview-subject ellipsis mb-1 text-medium" style="text-align: left"><i class="mdi mdi-table-edit  text-success"></i> Editar</p>';
                            			echo '</a>';
                            		echo '<div class="dropdown-divider"></div>';
                            			echo '<a href="#" onclick="del_item(\''.$r["idPedido"].'\')" class="dropdown-item preview-item">';
                            				echo '<p class="preview-subject ellipsis mb-1 text-medium" style="text-align: left"><i class="mdi mdi-delete text-danger"></i> Excluir</p>';
										echo '</a>';
                                echo '</div>';  
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
        
            $usuario = $_POST["usuario"];
            $cliente = $_POST["cliente"];

            $ped = $db->_exec("INSERT INTO pedidos (idPedido,idCliente,idUsuario,statusped) VALUES ('',$cliente,$usuario,'1')");
            
            $s = $db->select("SELECT idPedido FROM pedidos WHERE idCliente = $cliente AND idUsuario = '$usuario' ORDER BY idPedido DESC LIMIT 1");

            foreach($s as $s1){
                $numped = $s1["idPedido"];
            }

            $res = $db->select("SELECT idProduto, descricao, valor FROM produtos ORDER BY descricao");
            
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
                            echo '<td style="text-align: center">'.$r["valor"].'</td>';
                            echo '<td style="text-align: center">';
                                echo '<input type="number" onchange="incluiPed(this.value,\''.$r["idProduto"].'\',\''.$numped.'\')" min="0" max="100"></input>';
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
        * Ocultamente edita o pedido, e após Exibe lista de itens na div modInsert:
        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        if($_GET["a"] == "lista_mod_edit"){    
        
            $usuario = $_POST["usuario"];
            $cliente = $_POST["cliente"];

            $ped = $db->_exec("INSERT INTO pedidos (idPedido,idCliente,idUsuario,statusped) VALUES ('',$cliente,$usuario,'1')");
            
            $s = $db->select("SELECT idPedido FROM pedidos WHERE idCliente = $cliente AND idUsuario = '$usuario' ORDER BY idPedido DESC LIMIT 1");

            foreach($s as $s1){
                $numped = $s1["idPedido"];
            }

            $res = $db->select("SELECT idProduto, descricao, valor FROM produtos ORDER BY descricao");
            
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
                            echo '<td style="text-align: center">'.$r["valor"].'</td>';
                            echo '<td style="text-align: center">';
                                echo '<input type="number" onchange="incluiPed(this.value,\''.$r["idProduto"].'\',\''.$numped.'\')" min="0" max="100"></input>';
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
        
            $sel = $db->select("SELECT valor FROM produtos WHERE idProduto = $produto");
            
                if(count($sel)>0){
                    
                    $float_var = preg_replace('/[^0-9]/', '', $sel[0]["valor"]);
                    $preco = (floatval($float_var)*$quantidade)/100;
                    
                    $reais = "R$ " . number_format($preco, 2, ",", ".");
                }

            $res = $db->_exec("INSERT INTO itens_pedido (idPedido,idProduto,quantidade,preco) VALUES ($pedido,$produto,$quantidade,'$reais')");
            
            
            echo $res;
        }

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * editar conteúdo dentro da lista de pedidos do modal de edição:
        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        if($_GET["a"] == "edita_pedido"){
            


            $id = $_POST["id"];
            $quantidade = $_POST["quantidade"];
            $iditens = $_POST["iditens"];
            
        
            $sel = $db->select("SELECT valor FROM produtos WHERE idProduto = $id");
            
                if(count($sel)>0){
                    
                    $float_var = preg_replace('/[^0-9]/', '', $sel[0]["valor"]);
                    $preco = (floatval($float_var)*$quantidade)/100;
                    
                    $reais = "R$ " . number_format($preco, 2, ",", ".");
                }

            //$res = $db->_exec("INSERT INTO itens_pedido (idPedido,idProduto,quantidade,preco) VALUES ($pedido,$produto,$quantidade,'$reais')");
            $res = $db->_exec("UPDATE itens_pedido SET idProduto = $id, quantidade = $quantidade, preco = '$reais' WHERE idItens_pedido = $iditens");
            
            
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
            
            $sel = $db->select("SELECT idPedido, idProduto, quantidade, preco FROM itens_pedido WHERE idPedido = $numpedido");

            //logica para a soma dos valores quantidade e valor para fazer o update na tabela de pedidos
            if(count($sel)>0){
                foreach($sel as $s){
                    $somaquantidade = $somaquantidade + $s["quantidade"];

                    $float_var = preg_replace('/[^0-9]/', '', $s["preco"]);
                    $preco = (floatval($float_var))/100;

                    $somavalor = $somavalor + $preco;

                    $reais = "R$ " . number_format($somavalor, 2, ",", ".");
                }	
            }		
            
            //update nos valores da tabela pedidos
            $res = $db->_exec("UPDATE pedidos SET quantidade = $somaquantidade, preco = '$reais', nf = '', statusped = 2 WHERE idPedido = $numpedido");
            
           
            echo $res;
        }

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * Gera a nota fiscal:
        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        if($_GET["a"] == "inclui_nf"){
            
            //obtem o numerdo do pedido da tabela itens pedido a ser incluso
            $numpedido = $_POST["id"];

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
            
            //baixa nos estoques pós emissao da nf
            $sel1 = $db->select("SELECT p.idProduto, e.idProduto as eidprod, e.quantidade as equant, p.quantidade as pquant 
            FROM itens_pedido p 
            INNER JOIN produtos e ON e.idProduto = p.idProduto
            WHERE p.idPedido = $numpedido");

            foreach($sel1 as $s){

                $idp = $s["eidprod"];
                $subtracao = floatval($s["equant"]) - floatval($s["pquant"]);
                $baixa = $db->_exec("UPDATE produtos SET quantidade = $subtracao WHERE idProduto = $idp");
            }
            
            $nfe = $db->_exec("INSERT INTO nf (idPedido,numero,serie,chave,data_hora) VALUES ($numpedido,'$numero',1,'$chave',LOCALTIME())");		
            $res = $db->_exec("UPDATE pedidos SET nf = '$numero', statusped = 3 WHERE idPedido = $numpedido");
            
            echo $res;
        }

        

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * Edita o pedido:
        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        if($_GET["a"] == "edit_client"){
            

            $id = $_POST["id"];

            $usuario = $_POST["usuario"];
            $cliente = $_POST["cliente"];
            $nf = $_POST["nf"];
            $statusped = $_POST["statusped"];
            $quantidade = $_POST["quantidade"];
            $valor = $_POST["valor"];		
            
            
            $res = $db->_exec("UPDATE pedidos 
                SET idCliente = {$cliente},  idUsuario = {$usuario}
                WHERE idPedido = {$id}");

            echo $res;
        }

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * Deleta conteúdo:
        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        if($_GET["a"] == "del_user"){
        

            $id = $_POST["id"];

            $sel = $db->select("SELECT statusped FROM pedidos WHERE idPedido = $id");
            
            if($sel[0]["statusped"] == 3){
                $res = 2;
                echo $res;
            }else{
                
                /* Lógica para readicionar itens ao estoque, não está sendo usado no momento
                $sel1 = $db->select("SELECT p.idProduto, e.idProduto as eidprod, e.quantidade as equant, p.quantidade as pquant 
                                    FROM itens_pedido p 
                                    INNER JOIN produtos e ON e.idProduto = p.idProduto
                                    WHERE p.idPedido = $id");

                    foreach($sel1 as $s){

                            $idp = $s["eidprod"];
                            $soma = floatval($s["equant"]) + floatval($s["pquant"]);
                            $baixa = $db->_exec("UPDATE produtos SET quantidade = $soma WHERE idProduto = $idp");
                    }*/

                $del = $db->_exec("DELETE FROM itens_pedido WHERE idPedido = '{$id}'");	
                $res = $db->_exec("DELETE FROM pedidos WHERE idPedido = '{$id}'");
                
                echo $res;
            }
        }

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * Busca conteúdo para exibir na div de edição do pedido:
        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        if($_GET["a"] == "get_client"){
        

            $id = $_POST["id"];

            $sel = $db->select("SELECT statusped FROM pedidos WHERE idPedido = $id");
            
            if($sel[0]["statusped"] == 3){
                $res = 2;
                echo $res;
            }else{    

            $res = $db->select("SELECT p.idPedido, p.idCliente, p.idUsuario, c.nome as nomec, v.nome as nomev, p.quantidade, p.preco, p.nf, p.statusped
                                FROM pedidos p
                                INNER JOIN clientes c ON c.idCliente = p.idCliente
                                INNER JOIN usuarios v ON v.idUsuario = p.idUsuario
                                WHERE p.idPedido = {$id}");
            
            if(count($res) > 0){
                $res[0]['nomev'] = remove_acento($res[0]['nomev']);
                $res[0]['nomec'] = remove_acento($res[0]['nomec']);
                $res[0]['nf'] = remove_acento($res[0]['nf']);
                $res[0]['statusped'] = remove_acento($res[0]['statusped']);
                $res[0]['quantidade'] = remove_acento($res[0]['quantidade']);
                $res[0]['preco'] = remove_acento($res[0]['preco']);
                
                // declaração de variaveis para exibição das selects

                $c_retorno = array();
                $body = "";
                $include = "";
                $desc1 = "";
                $placeholderdesc = "";
                $desc2 = "";
                $desc3 = "";

                $lista = $db->select("SELECT i.idPedido, p.descricao, i.idItens_pedido as idItens, i.idProduto, p.idProduto, p.valor as valor, i.quantidade, i.preco as preco_final
                                    FROM itens_pedido i
                                    INNER JOIN produtos p ON p.idProduto = i.idProduto 
                                    WHERE i.idPedido = {$id}");

                    $include .= '<tr>';
                    $include .= '<td style="text-align: center">Incluir novo Item</td>';  
                    
                    //select de descrição

                    $desc = $db->select('SELECT idProduto, descricao FROM produtos');

                        $desc1 .= '<div class="scrollable">';
                        $desc1 .= '<select id="frm_val1_edit" onchange="setaproduto(this.value)" class="select form-control form-control-lg" aria-describedby="frm_val1_edit" name="frm_val1_edit" type="text" placeholder="" style="color: #ffffff">';
                            

                        foreach($lista as $p){
                            $placeholderdesc .= '<option value="" selected>'.$p["descricao"].'</option>';
                        }

                        foreach($desc as $d){
                            $desc2 .= '<option value="'.$d["idProduto"].'">'.$d["descricao"].'</option>';
                        }
                        $desc3 .= '</select>';
                        $desc3 .= '</div>';

                    //select de quantidade   



                    //montagem do body         

                    foreach($lista as $s){
                        $body .= '<tr>';
                            $body .= '<td id="edit_desc" style="text-align: left">'.$desc1.$placeholderdesc.$desc2.$desc3.'</td>';
                            //$body .= '<td style="text-align: center">'.$s["quantidade"].'</td>';
                            //placeholder="'.$s["quantidade"].'"
                            $body .= '<td id="edit_quant" style="text-align: center"><input type="number" placeholder="'.$s["quantidade"].'" onchange="editPed(this.value,\''.$s["idItens"].'\')" min="0" max="100"></input></td>';
                            $body .= '<td style="text-align: center">'.$s["valor"].'</td>';
                            $body .= '<td style="text-align: center">'.$s["preco_final"].'</td>';
                            $body .= '<td style="text-align: center"><a class="dropdown-item preview-item" ><p><i class="mdi mdi-checkbox-marked-outline text-sucess"></i></p></a></td>';
                    }						
                
                $title = '<h5 id="div_edit_title" class="modal-title">Informações do Pedido '.$id.'</h5>';
                
                $c_retorno["include"] = $include;
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
        }

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * Busca conteúdo para a exibição dos detalhes do pedido:
        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        if($_GET["a"] == "get_det_ped"){
        

            $id = $_POST["id"];

            $res = $db->select("SELECT p.idPedido, p.idCliente, p.idUsuario, c.nome as nomec, v.nome as nomev, p.quantidade, p.preco, p.nf, p.statusped
                                FROM pedidos p
                                INNER JOIN clientes c ON c.idCliente = p.idCliente
                                INNER JOIN usuarios v ON v.idUsuario = p.idUsuario
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

                $lista = $db->select("SELECT i.idPedido, p.descricao, i.idProduto, p.idProduto, p.valor as valor, i.quantidade, i.preco as preco_final
                                    FROM itens_pedido i
                                    INNER JOIN produtos p ON p.idProduto = i.idProduto 
                                    WHERE i.idPedido = {$id}");
                    foreach($lista as $s){
                        $body .= '<tr>';
                            $body .= '<td style="text-align: left">'.$s["descricao"].'</td>';
                            $body .= '<td style="text-align: center">'.$s["quantidade"].'</td>';
                            $body .= '<td style="text-align: center">'.$s["valor"].'</td>';
                            $body .= '<td style="text-align: center">'.$s["preco_final"].'</td>';
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
    include('header.php');
    include('sidebar.php');
    include('navbar.php');

    ?>


    <script type="text/javascript" src="assets/js/plentz-jquery-maskmoney-cdbeeac/src/jquery.maskMoney.js"></script>
    
   
    <script type="text/javascript">
        
        //criação de função para passar o numero do pedido para a parte de edição
        
        var idprodutosetado = $('#frm_val1_edit').val();
        function setaproduto(id){
            idprodutosetado = id;
        }

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
                    alert(retorno);
                    //$('#numpedido').val(pedido);
                    if(!retorno){
                        alert("ERRO AO INLUIR ITEM NO PEDIDO!");
                    } 
                }
            });
        }

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * permite a edição de itens dentro do pedido:
        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        var ajax_div = $.ajax(null);
        const editPed = (quantidade,iditens) => {
            //$('#frm_val1_edit').val(pedido);
            alert(id);
            if(ajax_div){ ajax_div.abort(); }
                ajax_div = $.ajax({
                cache: false,
                async: true,
                url: '?a=edita_pedido',
                type: 'post',
                data: {quantidade: quantidade,
                       id: idprodutosetado,
                       iditens: iditens},
                
                success: function retorno_ajax(retorno) {
                    alert(retorno);
                    //$('#numpedido').val(pedido);
                    if(!retorno){
                        alert("ERRO AO EDITAR ITEM NO PEDIDO!");
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
        * Exibir no modal os itens para edição:
        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        var ajax_div = $.ajax(null);
        const listaModEdit = () => {
            if(ajax_div){ ajax_div.abort(); }
                ajax_div = $.ajax({
                cache: false,
                async: true,
                url: '?a=lista_mod_edit',
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
        * Pesquisar itens do campo de edição:
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
                    alert(retorno);
                    var obj = JSON.parse(retorno);
                    
                    //alert(teste.header);
                    if(retorno==2){
                        alert("Não é possível editar o pedido pois a nota fiscal já foi emitida!");
                        location.reload();
                        lista_itens();  
                    }else{
                        $("#frm_id_edit").val(id);
                        
                        var obj_ret = obj.header;

                        if(obj_ret[0].nf==""){
                            var nf = "NF não emitida";
                        }else{
                            var nf = obj_ret[0].nf;
                        };
                        
                        $("#frm_val1_edit").val(obj_ret[0].nomev);
                        $("#frm_val2_edit").val(obj_ret[0].nomec);
                        $("#frm_val3_edit").val(nf);
                        $("#frm_val4_edit").val(obj_ret[0].statusped);	
                        $("#frm_val5_edit").val(obj_ret[0].quantidade);	
                        $("#frm_val6_edit").val(obj_ret[0].preco);	

                        $('#div_edit_title').html(obj.title); 
                        $('#div_edit_ped_include').html(obj.include); 
                        $('#div_edit_ped').html(obj.body); 
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
                        
                        if(obj_ret[0].nf==""){
                            var nf = "NF não emitida";
                        }else{
                            var nf = obj_ret[0].nf;
                        };

                        $("#frm_val1_exibe").val(obj_ret[0].nomev);
                        $("#frm_val2_exibe").val(obj_ret[0].nomec);
                        $("#frm_val3_exibe").val(nf);
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
        * responssavel por dar o update de valores no modal de edição:
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
                    id: $("#frm_id_edit").val(),
                    usuario: $("#frm_val1_edit").val(),
                    cliente: $("#frm_val2_edit").val(),
                    nf: $("#frm_val3_edit").val(),
                    statusped: $("#frm_val4_edit").val(),
                    quantidade: $("#frm_val5_edit").val(),
                    valor: $("#frm_val6_edit").val(),
                },
                beforeSend: function(){
                    $('#mod_formul_edit').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
                },
                success: function retorno_ajax(retorno) {
                    alert(retorno);
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
        * Gerar a Nota fiscal:
        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        var ajax_div = $.ajax(null);
        function faturar_item(id){
            if( confirm( "Deseja gerar a NF?")){
                if(ajax_div){ ajax_div.abort(); }
                    ajax_div = $.ajax({
                    cache: false,
                    async: true,
                    url: '?a=inclui_nf',
                    type: 'post',
                    data: { 
                        id: id,
                    },
                    success: function retorno_ajax(retorno) {
                        alert(retorno);
                        if(retorno){
                            location.reload();
                            lista_itens();  
                        }else{
                            alert("ERRO AO GERAR NF! " + retorno);
                        }
                    }
                });
            }else{
                lista_itens();
            }
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
                        
                        if(retorno==1){
                            location.reload();
                            lista_itens();
                        }else if(retorno==2){
                            alert("Não foi possível deletar o pedido pois a nota fiscal já foi emitida!");
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

    
<!-- Pagina principal -->
    
    <button class="btn btn-inverse-light btn-lg align-items-center grid-margin">
		<h3 style="font-size: 28px; text-align: center; vertical-align: baseline;"> Anuncie aqui! </h3>
	</button>
	<div class="content-wrapper"   style="background-image: url('assets/coronafree/template/assets/images/galaxy3.png'); background-repeat: no-repeat; background-size: cover;">
		<div class="page-header">
			<h3 class="page-title"> Pedidos </h3>

		</div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title ">Pedidos</h4>
						<p class="card-description">Visualize a lista de registros de Pedidos </p>

                    <div class="form-group row">
                        <div class="col-10">
                            <div class="input-group">
                            <input type="text" class="form-control" onkeyup="lista_itens()" id="input_pesquisa" placeholder="Pesquisar">
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
                            <h5 id="div_edit_title"></h5>
                        </div>
                    </div>
                    <button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul_edit').modal('hide');">X</button>
                </div>
                <div class="modal-body modal-dialog-scrollable">
                    <form id="frm_general_exib" name="frm_general">
                        <div class="row mb-3">

                            <div class="col">
                                <input type="text" style="text-align: left" aria-describedby="frm_id_edit" class="form-control form-control-lg" name="frm_id_edit" id="frm_id_edit" hidden>
                                
                                <label for="frm_val1_edit" class="form-label">Usuário:</label>
                                    <div class="scrollable">
                                    <select id="frm_val1_edit"  class="select form-control form-control-lg" aria-describedby="frm_val1_edit" name="frm_val1_edit" type="text" placeholder="" style="color: #ffffff">
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
                        
                            <div class="col">
                                <label for="frm_val2_edit" class="form-label">Cliente:</label>
                                
                                    <div class="scrollable">
                                    <select id="frm_val2_edit"  class="select form-control form-control-lg" aria-describedby="frm_val2_edit" name="frm_val2_edit" type="text" placeholder="" style="color: #ffffff">
                                        <option value="" selected></option>
                                        <?php
                                            $desc = $db->select('SELECT idCliente, nome FROM clientes');
                                            foreach($desc as $s){
                                                echo  '<option value="'.$s["idCliente"].'">'.$s["nome"].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        
                            <div class="col">
                                <label for="frm_val3_edit" class="form-label">Nota Fiscal:</label>
                                <input type="text" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val3_edit" class="form-control form-control-lg" name="frm_val3_edit" id="frm_val3_edit" placeholder="" disabled>
                            </div>

                            <div class="col">
                                <label for="frm_val4_edit" class="form-label">Status:</label>
                                <input type="text" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val4_edit" class="form-control form-control-lg" name="frm_val4_edit" id="frm_val4_edit" placeholder="" disabled>
                            </div>
                        </div>	
                        
                        <div class="row mb-3">
                            <div class="col">			
                                <label for="frm_vallista_edit" class="form-label"><b>Produtos:</b></label>
                                    <div class="table-responsive">
                                        <table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: left">Descrição do Produto</th>
                                                    <th style="text-align: center">Quantidade</th>
                                                    <th style="text-align: center">Valor Unitário</th>
                                                    <th style="text-align: center">Valor</th>
                                                    <th style="text-align: center">Alterar</th>
                                            </thead>
                                            <tbody id="div_edit_ped_include"> </tbody>
                                            <tbody id="div_edit_ped"> </tbody>
                                        </table>
                                    </div>				
                            </div>			
                        </div>

                        <div class="row mb-3">					
                            <div class="col">
                                <label for="frm_val5_edit" class="form-label">Quantidade Total:</label>
                                <input type="text" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val5_edit" class="form-control form-control-lg" name="frm_val5_edit" id="frm_val5_edit" placeholder="" disabled>
                            </div>

                            <div class="col">
                                <label for="frm_val6_edit" class="form-label">Valor Final:</label>
                                <input type="text" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val6_edit" class="form-control form-control-lg" name="frm_val6_edit" id="frm_val6_edit" placeholder="" disabled>
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
                                <input type="text" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val1_exibe" class="form-control form-control-lg" name="frm_val1_exibe" id="frm_val1_exibe" placeholder="" disabled>
                            </div>
                        
                            <div class="col">
                                <label for="frm_val2_exibe" class="form-label">Cliente:</label>
                                <input type="text" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val2_exibe" class="form-control form-control-lg" name="frm_val2_exibe" id="frm_val2_exibe" placeholder="" disabled>
                            </div>
                        
                            <div class="col">
                                <label for="frm_val3_exibe" class="form-label">Nota Fiscal:</label>
                                <input type="text" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val3_exibe" class="form-control form-control-lg" name="frm_val3_exibe" id="frm_val3_exibe" placeholder="" disabled>
                            </div>

                            <div class="col">
                                <label for="frm_val4_exibe" class="form-label">Status:</label>
                                <input type="text" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val4_exibe" class="form-control form-control-lg" name="frm_val4_exibe" id="frm_val4_exibe" placeholder="" disabled>
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
                                            <tbody id="div_exibe_ped"> </tbody>
                                        </table>
                                    </div>				
                            </div>			
                        </div>

                        <div class="row mb-3">					
                            <div class="col">
                                <label for="frm_val5_exibe" class="form-label">Quantidade Total:</label>
                                <input type="text" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val5_exibe" class="form-control form-control-lg" name="frm_val5_exibe" id="frm_val5_exibe" placeholder="" disabled>
                            </div>

                            <div class="col">
                                <label for="frm_val6_exibe" class="form-label">Valor Final:</label>
                                <input type="text" style="text-align: left; background-color:#2A3038" aria-describedby="frm_val6_exibe" class="form-control form-control-lg" name="frm_val6_exibe" id="frm_val6_exibe" placeholder="" disabled>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="$('#mod_formul_exibe').modal('hide');">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="frm_OK" onclick="$('#mod_formul_exibe').modal('hide');"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
                    <button type="button" class="btn btn-primary" id="frm_faturar" onclick="$('#mod_formul_exibe').modal('hide');"><img id="img_btn_faturar" style="width: 15px; display: none; margin-right: 10px">Faturar Pedido</button>
                </div>
            </div>
        </div>
    </div>

       
        <?php
        include('bottom.php');
        ?>