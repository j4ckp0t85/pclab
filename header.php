<div class="header-container">
            <header class="wrapper clearfix">               
                <h1 class="title">-- <?php echo $_SESSION['nome_op']; ?> --</h1>               
                <nav>
                    <ul id="jMenu">
                        <li>
                            <a class="fNiv">CLIENTI</a>
                            <ul>
                                <li>
                                    <a class="voce" href="pagine/search_clienti.php">Archivio</a>
                                </li>
                                <li>
                                    <a class="voce" href="pagine/newcliente.php">Nuovo</a>
                                </li>                                
                            </ul>
                        </li>                        
                        <li>
                            <a class="fNiv">SCHEDE</a>
                            <ul>
                                <li>
                                    <a class="voce" href="pagine/schede.php?year=<?php echo date("Y"); ?>">Archivio - BETA1</a>                                 
                                </li>
                                <li>
                                    <a class="voce" href="pagine/newscheda.php">Inserimento</a>
                                </li>                                
                            </ul>
                        </li>                       
                        <li>
                            <a class="fNiv">ALTRO</a>
                            <ul>
                                <?php if($_SESSION['is_admin']) {?>
                                <li>
                                    <a class="voce">admin</a>
                                </li>  
                                <?php } ?>
                                <li>
                                    <a href="logout.php">logout</a>
                                </li>                                                        
                            </ul>
                        </li>                        
                    </ul>
                </nav>
            </header>
</div>