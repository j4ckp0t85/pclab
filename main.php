<?php         
    ob_start();  
    if((!$_POST['user'])&&(!$_SESSION['nome_op'])){
        header('location: index.php');
        ob_end_flush();
    }
    else
    {
        session_start();
        require_once("classi/class.php"); 
        $test = Operatore::readOperatoreById($_POST['user']);   
        $_SESSION['id_op']=$test->idfunzione;
        $_SESSION['nome_op']=$test->persona; 
        $_SESSION['is_admin']=$test->amministratore;
        $_SESSION['is_tecnico']=$test->tecnico;
        unset($test);
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>PcLab - Home</title>
        <meta name="description" content="PcLab login archivio interventi">
        <meta name="keywords" content="pclab,menu,archivio">
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="shortcut icon" href="favicon.ico" />
                
        <link rel="stylesheet" href="css/normalize.min.css" type="text/css" />
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="css/jmenu.css" type="text/css" />
        <link rel="stylesheet" href="css/jquery-ui.css" type="text/css" />
              
        <script type="text/javascript" src="js/vendor/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="js/vendor/jquery-ui-min.js"></script>
       
        <script type="text/javascript" src="js/vendor/jMenu.jquery.js"></script>
        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div id="alert_js">Attenzione: Javascript non presente nel browser. Abilitarlo</div>
        <?php include('header.php'); ?>      
        
        <div id="content"><?php include('pagine/content.php'); ?>  </div>
       
        <?php include('footer.php'); ?>
        <script type="text/javascript" src="js/vendor/closelistener.js"></script>
        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. 
            <script>
                (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
                function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
                e=o.createElement(i);r=o.getElementsByTagName(i)[0];
                e.src='//www.google-analytics.com/analytics.js';
                r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
                ga('create','UA-XXXXX-X');ga('send','pageview');
            </script>
        -->
    </body>
</html>
<?php
    }
?>