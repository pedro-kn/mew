<style>
  .sidebar .nav .nav-item.profile .profile-desc .profile-name h5{
    color: white;
  }
</style>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <a  href="main.php"><img src="assets/coronafree/template/assets/images/estetica.png" alt="logo" width="133" height="70" /></a>
    <a class="sidebar-brand brand-logo-mini" href="main.php"><img src="assets/coronafree/template/assets/images/logo-mini.svg" alt="logo" /></a>
  </div>
  <ul class="nav">
    <li class="nav-item profile">
      <div class="profile-desc">
        <div class="profile-pic">
          <div class="count-indicator">
            <img class="img-xs rounded-circle " src="assets/coronafree/template/assets/images/faces/pedro.jpeg" alt="">
            <span class="count bg-success"></span>
          </div>
          <div class="profile-name">
            <h5 class="mb-0 font-weight-normal">Pedro Kneubuehler</h5>
            <span>Key Member</span>
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
    <li class="nav-item nav-category">
      <span class="nav-link">Navegação</span>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="fullcalendar.php">
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
          <li class="nav-item"> <a class="nav-link" href="vendedor.php">Empregados</a></li>
          <li class="nav-item"> <a class="nav-link" href="clientes.php">Clientes</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="produtos.php">
        <span class="menu-icon">
          <i class="mdi mdi-pill"></i>
        </span>
        <span class="menu-title">Produtos</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="avaliacoes.php">
        <span class="menu-icon">
          <i class="mdi mdi-nutrition"></i>
        </span>
        <span class="menu-title">Avaliações</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="pedidos.php">
        <span class="menu-icon">
          <i class="mdi mdi-note-text"></i>
        </span>
        <span class="menu-title">Pedidos</span>
      </a>
    </li>
    
  </ul>
</nav>
