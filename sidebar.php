<style>
	.sidebar .nav .nav-item.profile .profile-desc .profile-name h5 {
		color: white;
	}
</style>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
		<a href="main.php"><img src="assets/coronafree/template/assets/images/estetica.png" alt="logo" width="133" height="70" /></a>
		<a class="sidebar-brand brand-logo-mini" href="main.php"><img src="assets/coronafree/template/assets/images/logo-mini.svg" alt="logo" /></a>
	</div>
	<ul class="nav">
		<li class="nav-item profile">
			<div class="profile-desc">
				<div class="profile-pic">
					<div class="count-indicator">
						<img class="img-xs rounded-circle " src="assets/coronafree/template/assets/images/faces/user.png" alt="">
						<span class="count bg-success"></span>
					</div>
					<div class="profile-name">

						<?php
						if (!empty($_COOKIE['nome'])) {
							echo '<h5 class="mb-0 font-weight-normal">' . $_COOKIE['nome'] . '</h5>';
							echo '<span>';
							if ($_COOKIE['permissao'] == 1) {
								echo 'Usuário';
							} elseif ($_COOKIE['permissao'] == 2) {
								echo 'Gerente';
							}
							echo '</span>';
						} else {
							echo '<a href="./login.php">';
							echo '<h5 class="mb-0 font-weight-normal">Faça o Login!</h5>';
							echo '<span>Clique aqui para o Login</span>';
							echo '</a>';
						}
						?>
					</div>
				</div>
				<a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
				<div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
					<a href="#" class="dropdown-item preview-item">
						<div class="preview-thumbnail">
							<div class="preview-icon bg-dark rounded-circle">
								<i class="mdi mdi-settings text-primary"></i>
							</div>
						</div>
						<div class="preview-item-content">
							<p class="preview-subject ellipsis mb-1 text-small">Configurações</p>
						</div>
					</a>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item preview-item">
						<div class="preview-thumbnail">
							<div class="preview-icon bg-dark rounded-circle">
								<i class="mdi mdi-onepassword  text-info"></i>
							</div>
						</div>
						<div class="preview-item-content">
							<p class="preview-subject ellipsis mb-1 text-small">Trocar Senha</p>
						</div>
					</a>
					<div class="dropdown-divider"></div>
					<a href="fullcalendar.php" class="dropdown-item preview-item">
						<div class="preview-thumbnail">
							<div class="preview-icon bg-dark rounded-circle">
								<i class="mdi mdi-calendar-today text-success"></i>
							</div>
						</div>
						<div class="preview-item-content">
							<p class="preview-subject ellipsis mb-1 text-small">Minha Agenda</p>
						</div>
					</a>
				</div>
			</div>
		</li>
		<div <?php if (empty($_COOKIE['nome'])) {
					echo "hidden";
				} ?>>
			<li class="nav-item nav-category">
				<span class="nav-link">Navegação</span>
			</li>

			<li class="nav-item menu-items">
				<?php
				$link = "fullcalendar.php";
				if (strpos($_SERVER['REQUEST_URI'], $link) !== false) {
					echo '<a class="nav-link active" href="./' . $link . '?uid=' . $_COOKIE["idUsuario"] . '">';
				} else {
					echo '<a class="nav-link" href="./' . $link . '?uid=' . $_COOKIE["idUsuario"] . '">';
				}
				?>
				<span class="menu-icon">
					<i class="mdi mdi-calendar"></i>
				</span>
				<span class="menu-title">Agenda</span>
				</a>
			</li>

			<li class="nav-item menu-items">
				<a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
					<span class="menu-icon">
						<i class="mdi mdi-account-card-details"></i>
					</span>
					<span class="menu-title">Usuários</span>
					<i class="menu-arrow"></i>
				</a>
				<div class="collapse" id="ui-basic">
					<ul class="nav flex-column sub-menu">
						<div <?php if ($_COOKIE['permissao'] == 1) {
									echo "hidden";
								} ?>>
							<li class="nav-item">
								<?php
								$link = "vendedor.php";
								if (strpos($_SERVER['REQUEST_URI'], $link) !== false) {
									echo '<a class="nav-link active" href="./' . $link . '?uid=' . $_COOKIE["idUsuario"] . '">Funcionários';
								} else {
									echo '<a class="nav-link" href="./' . $link . '?uid=' . $_COOKIE["idUsuario"] . '">Funcionários';
								}
								?>
								</a>
							</li>
						</div>
						<li class="nav-item">
							<?php
							$link = "clientes.php";
							if (strpos($_SERVER['REQUEST_URI'], $link) !== false) {
								echo '<a class="nav-link active" href="./' . $link . '?uid=' . $_COOKIE["idUsuario"] . '">Clientes';
							} else {
								echo '<a class="nav-link" href="./' . $link . '?uid=' . $_COOKIE["idUsuario"] . '">Clientes';
							}
							?>
							</a>
						</li>
					</ul>
				</div>
			</li>
			<li class="nav-item menu-items">
				<?php
				$link = "produtos.php";
				if (strpos($_SERVER['REQUEST_URI'], $link) !== false) {
					echo '<a class="nav-link active" href="./' . $link . '?uid=' . $_COOKIE["idUsuario"] . '">';
				} else {
					echo '<a class="nav-link" href="./' . $link . '?uid=' . $_COOKIE["idUsuario"] . '">';
				}
				?>
				<span class="menu-icon">
					<i class="mdi mdi-pill"></i>
				</span>
				<span class="menu-title">Produtos</span>
				</a>
			</li>
			<li class="nav-item menu-items">
				<?php
				$link = "avaliacoes.php";
				if (strpos($_SERVER['REQUEST_URI'], $link) !== false) {
					echo '<a class="nav-link active" href="./' . $link . '?uid=' . $_COOKIE["idUsuario"] . '">';
				} else {
					echo '<a class="nav-link" href="./' . $link . '?uid=' . $_COOKIE["idUsuario"] . '">';
				}
				?>
				<span class="menu-icon">
					<i class="mdi mdi-nutrition"></i>
				</span>
				<span class="menu-title">Avaliações</span>
				</a>
			</li>
			<li class="nav-item menu-items">
				<?php
				$link = "pedidos.php";
				if (strpos($_SERVER['REQUEST_URI'], $link) !== false) {
					echo '<a class="nav-link active" href="./' . $link . '?uid=' . $_COOKIE["idUsuario"] . '">';
				} else {
					echo '<a class="nav-link" href="./' . $link . '?uid=' . $_COOKIE["idUsuario"] . '">';
				}
				?>
				<span class="menu-icon">
					<i class="mdi mdi-note-text"></i>
				</span>
				<span class="menu-title">Pedidos</span>
				</a>
			</li>
		</div>
	</ul>
</nav>