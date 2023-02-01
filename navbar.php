<div class="container-fluid page-body-wrapper">
    <nav class="navbar p-0 fixed-top d-flex flex-row">
        <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="main.php"><img src="assets/coronafree/template/assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item dropdown">
                    <a class="nav-link" id="profileDropdown" href="" data-toggle="dropdown">
                        <div class="navbar-profile">
                            <?php
                            if (!empty($_COOKIE['nome'])) {
                                echo '<img class="img-xs rounded-circle" src="assets/coronafree/template/assets/images/faces/user.png" alt="">';
                                echo '<p class="mb-0 d-none d-sm-block navbar-profile-name">' . $_COOKIE['nome'] . '</p>';
                                echo '<i class="mdi mdi-menu-down d-none d-sm-block"></i>';
                            } else {
                                echo '<img class="img-xs rounded-circle" src="assets/coronafree/template/assets/images/faces/user.png" alt="">';
                                echo '<p class="mb-0 d-none d-sm-block navbar-profile-name">Faça o Login!</p>';
                                echo '<i class="mdi mdi-menu-down d-none d-sm-block"></i>';
                            }
                            ?>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
                        <h6 class="p-3 mb-0">Perfil</h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-settings text-success"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject mb-1">Configurações</p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                            <?php
                            if (!empty($_COOKIE['nome'])) {
                                echo '<form action="' . ($_SERVER['PHP_SELF']) . '" method="POST">';
                                    echo '<div class="preview-thumbnail col">';
                                        echo '<div class="preview-icon bg-dark  rounded-circle">';
                                            echo '<i class="mdi mdi-logout text-danger"></i>';
                                        echo '</div>';
                                    echo '</div>';
                                    echo '<div class="preview-item-content col" >';
                                        echo '<input id="sub1" type="submit" value="Logout" class="dropdown-item preview-item"></input>';
                                    echo '</div>';
                                echo '</form>';
                                echo '</a>';
                            } else {
                                echo '<a class="dropdown-item preview-item" href="login.php">';
                                    echo '<div class="preview-thumbnail">';
                                        echo '<div class="preview-icon bg-dark  rounded-circle">';
                                        echo '<i class="mdi mdi-logout text-danger"></i>';
                                        echo '</div>';
                                    echo '</div>';
                                    echo '<div class="preview-item-content" >';
                                        echo '<p class="preview-subject mb-1">Log in</p>';
                                    echo '</div>';
                                echo '</a>';
                            }
                            ?>

                        <div class="dropdown-divider"></div>
                        <p class="p-3 mb-0 text-center">Configurações Avançadas</p>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="mdi mdi-format-line-spacing"></span>
            </button>
        </div>
    </nav>
    <div class="main-panel">