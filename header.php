<?php
     //FUNÇOES PARA LOGOUT DE USUARIOS (COOKIES ESTAO SENDO SETADOS EM login.php)
     function logOut() {
        //setcookie('userDetails[username]',"", time()-1200);

        setcookie("idUsuario","", time()-1200);
        setcookie("nome","", time()-1200);
        setcookie("senha","", time()-1200);
        setcookie("permissao","", time()-1200);
        //unset($_COOKIE);
    }

    if ('POST' === $_SERVER['REQUEST_METHOD'])
    {
        logOut();
        header('Location: ./login.php');
        exit;    
    }

?>

<!DOCTYPE html>
<html lang="en">
  
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Espaço Estética</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/coronafree/template/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/coronafree/template/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="assets/coronafree/template/assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="assets/coronafree/template/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/coronafree/template/assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/coronafree/template/assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/coronafree/template/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/coronafree/template/assets/images/E.webp"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
     <!-- carrega jQuery e funções do calendário -->
    <link href='assets/fullcalendarmaster/css/fullcalendar.css' rel='stylesheet' />
    <link href='assets/fullcalendarmaster/css/fullcalendar.print.css' rel='stylesheet' media='print' />
    <script src="assets/coronafree/template/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src='assets/fullcalendarmaster/js/jquery-3.6.1.min.js' type="text/javascript"></script>
    <script type="text/javascript" src="assets/bootstrap/js/bootstrap.js"></script>
    <script src='assets/fullcalendarmaster/js/jquery-ui.custom.min.js' type="text/javascript"></script>
    
    <script src='assets/fullcalendarmaster/js/fullcalendar.js' type="text/javascript"></script>
    <script src='assets/js/jQuery-Mask-Plugin-master/src/jquery.mask.js' type="text/javascript"></script>
  

    <!-- Bootstrap core CSS -->
		
		<link href="./assets/bootstrap/js/bootstrap.min.js" rel="stylesheet">
  
    
  </head>
  	<style>
	.form-control{
		color: #ffffff;
		--bs-text-opacity: 1;
		color: rgba(var(--bs-white-rgb), var(--bs-text-opacity)) !important;
	}
	</style>		
 
    <div class="container-scroller">
      