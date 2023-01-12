<?php

include("db.php");
$db = new Database();

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (isset($_GET["a"])) {

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Edita conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "monta_cookie") {

		$nome = $_POST["nome"];
		$senha = md5($_POST["senha"]);

		if($nome == "" || $senha == ""){
			echo '<div class="alert alert-warning" role="alert">';
				echo 'O campo nome ou senha se encontra vazio!';
			echo '</div>';
		}else{

			$sel = $db->select("SELECT idUsuario, nome, statuscli, senha FROM usuarios WHERE nome = '$nome'");
			//print_r(empty($sel));
			//die();
			if(!empty($sel)){					
				if ($sel[0]["senha"]==$senha) {

					setcookie("idUsuario", md5($sel[0]["idUsuario"].time()), 0);
					setcookie("nome", $nome, 0);
					setcookie("senha", $senha, 0);
					setcookie("permissao", $sel[0]["statuscli"], 0);

					echo 1;

				}elseif($senha !== $sel[0]["senha"]){
					echo '<div class="alert alert-warning" role="alert">';
						echo 'Senha Incorreta!';
					echo '</div>';
				}else{
					echo '<div class="alert alert-warning" role="alert">';
						echo 'Nome de Usuario Incorreto!';
					echo '</div>';
				}
			}else{
				echo '<div class="alert alert-warning" role="alert">';
					echo 'Não há registro desse nome de Usuario!';
				echo '</div>';
			}

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
	 * Monta Cookie:
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const cookie_onclick = () => {
		if (ajax_div) {
			ajax_div.abort();
		}
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=monta_cookie',
			type: 'post',
			data: {
				nome: $("#user_login").val(),
				senha: $("#senha_login").val(),
			},
			beforeSend: function() {
				$('#div_retorno').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				console.log(retorno);
				if(retorno==1){
					document.location.href="./main.php";
				}else{
					if (retorno) {
						$('#div_retorno').html(retorno);
					} else {
						alert("ERRO! " + retorno);
					}
				}
			}
		});
	}
</script>

<div class="container-fluid page-body-wrapper full-page-wrapper">
	<div class="row w-100 m-0">
		<div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
			<div class="card col-lg-4 mx-auto">
				<div class="card-body px-5 py-5">
					<h3 class="card-title text-left mb-3">Login</h3>
						<form>
					<!-- <form name="login" method="POST" enctype="multipart/form-data" class="form" action="./loginprocess.php">-->
						<div class="form-group">
							<label>Usuário ou e-mail:</label>
							<input type="text" id="user_login" name="user_login" class="form-control p_input">
						</div>
						<div class="form-group">
							<label>Senha:</label>
							<input type="text" id="senha_login" name="senha_login" class="form-control p_input">
						</div>
						<div class="form-group d-flex align-items-center justify-content-between">
							<div class="form-check">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input">Lembrar</label>
							</div>
							<a href="#" class="forgot-pass">Esqueci a Senha</a>
						</div>
						</form>
						<div class="text-center">
							<button type="submit" onclick="cookie_onclick()" class="btn btn-primary btn-block enter-btn">Login</button>
						</div>
						
						<div id="div_retorno" class="d-flex"></div>
						<p class="sign-up">Não tem uma Conta?<a href="#"> Registre-se</a></p>
					
				</div>
			</div>
		</div>
		<!-- content-wrapper ends -->
	</div>
	<!-- row ends -->
</div>
<!-- page-body-wrapper ends -->




<?php
include('bottom.php');
?>