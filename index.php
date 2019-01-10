<?php
    require_once("classi/class.php");
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>PcLab - Login</title>
        <meta name="description" content="PcLab login archivio interventi">
        <meta name="keywords" content="pclab,index,login, archivio">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="stylesheet" href="css/index.css">          
        <link rel="shortcut icon" href="favicon.ico" />      
        <script src="js/vendor/jquery-1.11.0.min.js"></script>
        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <!--[if (gte IE 6)&(lte IE 8)]>
            <link rel="stylesheet" href="css/index-ie.css" type="text/css" />
        <![endif]-->
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div id="alert_js">Attenzione: Javascript non presente nel browser. Abilitarlo</div>
                 <div id="main">
                    <h1>PcLab - Login</h1>                    
                    <form method="post" action="main.php" autocomplete="off">
                         <div id="tbl">
                            <div class="row">                                
                                <div class="col"><label for="user">Username:</label> </div>
                                <div class="col">
                                    <select name="user" id="user">
                                         <?php
                                             $users=Operatore::readOperatori();   
                                             foreach ($users as $key=>$value)
                                             {    
                                         ?>
                                        <option value="<?php echo $users[$key]->idfunzione; ?>"><?php echo $users[$key]->persona; ?></option>
                                        <?php
                                             }
                                        ?>                                   
                                    </select>
                               </div>                               
                            </div>
                            <div class="row"> 
                                <div class="col"><label for="passwd">Password:</label> </div><div class="col"><input type="password" name="passwd" id="passwd"></div>                              
                            </div>
                            <div class="row"> 
                                <div class="col">
                                    <input type="submit" value="Entra">
                                </div> 
                            </div>  
                         </div>
                         
                    </form>
                    </div>     
                 <script type="text/javascript">
                    $(window).resize(function(){

                    $('#main').css({
        		position:'absolute',
        		left: ($(window).width() - $('#main').outerWidth())/2,
        		top: ($(window).height() - $('#main').outerHeight())/2
                    });

		    });

			// To initially run the function:
		    $(window).resize();
                 </script>
                       
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