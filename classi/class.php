<?php

// database
class pclabDB {
	const SERVER = "localhost";
	const USER = "root";
	const PWD = "tramaweb";
	const DB = "pclab";
	
	protected static function connect(){
		$conn = mysqli_connect(self::SERVER, self::USER, self::PWD, self::DB) or die("Errore nella connessione al DB");
		return $conn;
	}
        
        protected static function close($conn){
		mysqli_close($conn);
	}
}

//modellazione classe clienti
class Cliente extends pclabDB{

        public $cliente;
        public $mail;
	public $ragsoc;
        public $tel;
        public $fax;
        public $riftel;	

	// costruttore
	public function __construct($cliente, $ragsoc, $tel, $fax, $riftel,$mail) {
		$this->cliente=$cliente;
                $this->ragsoc=$ragsoc;
                $this->tel=$tel;
                $this->fax=$fax;
                $this->riftel=$riftel;
                $this->mail=$mail;
	}
        
        //restituisce il numero totale di clienti
        public static function countClienti() {
		$conn=parent::connect(); 
                $query="select count(*) as tot from clienti";
                $result=mysqli_query($conn,$query) or die('Errore interrogazione DB');
		$count=mysqli_fetch_assoc($result);		
		return $count['tot'];
		parent::close($conn);
	}
        
        //restituisce l'indirizzo mail di un cliente
        public static function getMail($idcliente) {
		$conn=parent::connect(); 
                $query="select email from clienti where cliente=$idcliente";
                $result=mysqli_query($conn,$query) or die('Errore interrogazione DB');
		$mail=mysqli_fetch_assoc($result);		
		return $mail['email'];
		parent::close($conn);
	}
        
        //recupera i dati dei clienti in base alla query in input
        public static function readClienti($inputQuery) {
		$conn=parent::connect();
		$rows=mysqli_query($conn,$inputQuery) or die('Errore interrogazione DB');
		$clienti=array();
		while($row=mysqli_fetch_array($rows)){
			$cliente = new self($row['cliente'], $row['ragionesociale'],$row['telefono'],$row['Fax'],$row['riftel'],$row['email']); 
			$clienti[] = $cliente;
		}
		return $clienti;
		parent::close($conn);
	}
        
        //inserisce un nuovo cliente
        public static function newCliente($ragsoc,$tel,$fax,$riftel,$mail) { 
                $conn=parent::connect();                
		$query="insert into clienti (ragionesociale, telefono, Fax, riftel,email) values ('".$ragsoc."','".$tel."','".$fax."','".$riftel."','".$mail."')";
                mysqli_query($conn,$query) or die ("Errore inserimento dati nel database");
                parent::close($conn);               
        }
        
        //aggiorna dati di un cliente
        public static function updateCliente($idcliente,$ragsoc,$tel,$fax,$riftel,$mail) { 
                $conn=parent::connect(); 
                $query="update clienti set ragionesociale='".$ragsoc."',telefono='".$tel."',Fax='".$fax."',riftel='".$riftel."',email='".$mail."' where cliente=$idcliente";
                mysqli_query($conn,$query) or die ("Errore aggiornamento dati nel database");
                parent::close($conn);  
        }
}

//modellazione classe operatori
class Operatore extends pclabDB{

	public $amministratore; //flag 0-1 (1= è amministratore)
        public $idfunzione; //id dell'operatore
        public $persona;  //nome dell'operatore	 
        public $tecnico; //flag 0-1 (1= è un tecnico di laboratorio)   

	// costruttore
	public function __construct($amministratore, $idfunzione, $persona, $tecnico) {
		$this->amministratore=$amministratore;
                $this->idfunzione=$idfunzione;
                $this->persona=$persona;
                $this->tecnico=$tecnico;
	}
        
        //recupera la lista completa e in ordine alfabetico di tutti gli operatori attivi
        public static function readOperatori() {
		$conn=parent::connect();                
		$query="select * from destinatari where attivo=1 order by persona";
		$rows=mysqli_query($conn,$query) or die('Errore interrogazione DB');
		$operatori=array();
		while($row=mysqli_fetch_array($rows)){
			$operatore = new self($row['Amministratore'],$row['idfunzione'],$row['persona'],$row['Tecnico']); 
			$operatori[] = $operatore;
		}
		return $operatori;
		parent::close($conn);
	}
        
                
        //recupera i dati di un singolo operatore (per id)
        public static function readOperatoreById($idTecnico) {
		$conn=parent::connect();                
		$query="select * from destinatari where idfunzione=$idTecnico";
		$rows=mysqli_query($conn,$query) or die('Errore interrogazione DB');		
		$row=mysqli_fetch_array($rows);
		$operatore = new self($row['Amministratore'],$row['idfunzione'],$row['persona'],$row['Tecnico']); 		
		return $operatore;
		parent::close($conn);
	}
}      

//modellazione classe schede
class Scheda extends pclabDB {
        public $idScheda;  //identificativo scheda, è composto sostanzialmente dalla 'concatenazione di due stringhe-numeriche': la prima è un numero incrementale di scheda da 1 in su, la seconda è l'anno    
        public $idCliente;  //identificativo cliente
        public $idTecnico;  //identificativo tecnico che ha eseguito la riparazione
        public $cliente;   //usato per salvare il nome di un cliente nella funzione readSchede (archivio storico generico)
        public $dataAccettazione;   //data in cui è stata registrata la scheda cliente    
        public $dataConsegna; //data in cui è stata chiusa la scheda dal tecnico
        public $dataStimata; //data prevista di consegna in fase di registrazione scheda
        public $inGaranzia; //riferimento al campo SNC della tabella reclamicliente, specifica se il materiale consegnato è o meno in garanzia ('G' = garanzia, 'NG' = non in garanzia)
        public $materialeCons; //materiale consegnato al momento della registrazione della scheda
        public $noteTecnico; //note aggiuntive scritte dal tecnico che ha eseguito il lavoro
        public $problematica; //descrizione della problematica evidenziata dal cliente
        public $registrataDa; //chi ha registrato la scheda nel db
        public $richiestaCliente; //richieste del cliente in fase di registrazione scheda
        public $risoluzione; //note del tecnico che ha chiuso la scheda
        public $schedaChiusa; //0 aperta/1 chiusa
        public $totale; //costo lavoro complessivo
        
        /*  **********NOTE SULLA TABELLA DEL DB*************
        *   DATARC = data accettazione cliente
        *   DataDisposizione = data chiusura scheda tecnico
        *   dataritiro = data stimata per la consegna
        ****************************************************/
        
        /* 
         * costruttore con "workaround per overloading": si passa come parametro un array
         * le chiavi dell'array sono in sostanza un sottoinsieme degli attributi definiti nella classe, i relativi valori sono assegnati dai risultati delle query
         * CHIAVI ARRAY DEVONO ESSERE UGUALI A NOME ATTRIBUTI DEFINITI
         */
        public function __construct($args) {
            while ($val=current($args)) {
                $this->{key($args)}=$val;
                next($args);
            }
        }       
        
        //recupera l'ultima scheda, per l'inserimento 
        public static function getLast($year) {
                $conn=parent::connect();                
		$query="SELECT NUMERORC as idscheda FROM `reclamicliente` where NUMERORC like '%$year' ORDER BY NUMERORC desc LIMIT 1";
		$result=mysqli_query($conn,$query) or die('Errore interrogazione DB');
		$count=mysqli_fetch_assoc($result);		
		return $count['idscheda'];
		parent::close($conn);
        }
        
        //inserisce una nuova scheda
        public static function newScheda($idscheda,$dataInserimento,$nomeOperatore,$idcliente,$problematica,$year,$materialeConsegnato,$richiesteCliente,$garanzia,$dataConsegnaPrevista) { 
                $conn=parent::connect();                
		$query="INSERT INTO reclamicliente (NUMERORC,DATARC,REGISTRATADA,CLIENTERC,RCCHIUSO,DESCRIZIONERC,TIPONC,ANNO,CODICEPRODOTTOFINITO,Colata,RICHIESTACLIENTE,Dispositore,DescrizioneRichiestaCliente,ImportoLavoriEseguiti,SNC,DATAritiro) values ('".$idscheda."','".$dataInserimento."','".$nomeOperatore."','".$idcliente."','0','".$problematica."','12','$year','7','".$materialeConsegnato."','29','0','".$richiesteCliente."','0','".$garanzia."','".$dataConsegnaPrevista."')";
                mysqli_query($conn,$query) or die ("Errore inserimento scheda");
                parent::close($conn);               
        }
        
        //recupera i dati di una scheda d'ingresso specifica di cui viene passato l'id in input
        public static function readScheda($numScheda) {
		$conn=parent::connect();                
		$query="SELECT DESCRIZIONERC as problematica, CAUSERC as risoluzione, Colata as materialeCons, DescrizioneRichiestaCliente as richiestaCliente, DATARC as dataArrivo, DataDisposizione as dataConsegna, Dispositore as idTecnico, NoteTecnico as noteTecnico,SNC as inGaranzia, DATAritiro as dataStimata, REGISTRATADA as registrataDa , importolavorieseguiti as totale FROM reclamicliente WHERE NUMERORC=$numScheda";
		$rows=mysqli_query($conn,$query) or die('Dati non presenti');
                $row=mysqli_fetch_array($rows);
                $schede=array();
                $args=array(                            
                            "problematica"=>$row['problematica'],
                            "risoluzione"=>($row['risoluzione'])?$row['risoluzione']:"#",
                            "materialeCons"=>$row['materialeCons'],                            
                            "richiestaCliente"=>($row['richiestaCliente'])?$row['richiestaCliente']:"nessuna",
                            "dataAccettazione"=>strftime("%A %d %B %Y alle ore %H:%M", strtotime($row['dataArrivo'])),
                            "dataConsegna"=>($row['dataConsegna'])?$row['dataConsegna']:"###",
                            "idTecnico"=>($row['idTecnico']!="0")?$row['idTecnico']:"41",
                            "noteTecnico"=>($row['noteTecnico'])?$row['noteTecnico']:"nessuna",  
							"registrataDa"=>$row['registrataDa'],
                            "inGaranzia"=>$row['inGaranzia'],
                            "dataStimata"=>strftime("%A %d %B %Y", strtotime($row['dataStimata'])),
							"totale"=>' '.$row['totale'],
                        ); 
		$scheda = new self($args); 
        $schede[] = $scheda;
		return $schede;
		parent::close($conn);
	}
    
	
        //recupera la lista delle schede relative a un anno specifico passato come parametro di input
        public static function readSchede($year) {
		$conn=parent::connect();                
		$query="SELECT reclamicliente.NUMERORC as IDscheda,reclamicliente.CLIENTERC as IDcliente, clienti.ragionesociale as cliente, reclamicliente.DATARC as dataArrivo, reclamicliente.DataDisposizione as dataConsegna, reclamicliente.importolavorieseguiti as totale,reclamicliente.RCCHIUSO as schedaChiusa FROM clienti,reclamicliente WHERE (reclamicliente.clienterc=clienti.cliente) AND (reclamicliente.DATARC LIKE '$year%') ORDER BY reclamicliente.DATARC,reclamicliente.NUMERORC ASC";
		$rows=mysqli_query($conn,$query) or die('Dati non presenti');
		$schede=array();
		while($row=mysqli_fetch_array($rows)){			
                        $args=array(
                            "idScheda"=> $row['IDscheda'],
                            "idCliente"=>$row['IDcliente'],
                            "cliente"=>$row['cliente'],
                            "dataAccettazione"=>strftime("%d %b", strtotime($row['dataArrivo'])),
                            "dataConsegna"=>($row['dataConsegna'])?strftime("%d %b", strtotime($row['dataConsegna'])):"###",
                            "totale"=>' '.$row['totale'],                            
                            "schedaChiusa"=>''.$row['schedaChiusa'],
                        );
                        $scheda = new self($args);
			$schede[] = $scheda;
		}
		return $schede;
		parent::close($conn);
	}        
        
        //recupera lo storico di tutte le schede di un cliente, in ordine cronologico dalla più recente
        public static function schedeCliente($idCliente) {
		$conn=parent::connect();                
		$query="SELECT reclamicliente.NUMERORC as IDscheda, reclamicliente.DATARC as dataArrivo, reclamicliente.DESCRIZIONERC as problematica,reclamicliente.Colata as materialeCons,reclamicliente.DescrizioneRichiestaCliente as richiestaCliente, reclamicliente.DataDisposizione as dataConsegna, reclamicliente.CAUSERC as risoluzione, reclamicliente.importolavorieseguiti as totale,reclamicliente.Dispositore as idTecnico,reclamicliente.RCCHIUSO as schedaChiusa,reclamicliente.NoteTecnico as noteTecnico,reclamicliente.SNC as inGaranzia FROM reclamicliente WHERE (reclamicliente.clienterc=$idCliente) ORDER BY reclamicliente.DATARC DESC";
		$rows=mysqli_query($conn,$query) or die('Dati non presenti');
		$schede=array();
		while($row=mysqli_fetch_array($rows)){			
                        $args=array(
                            "idScheda"=>$row['IDscheda'],   
                            "dataAccettazione"=>$row['dataArrivo'],
                            "problematica"=>$row['problematica'], 
                            "materialeCons"=>$row['materialeCons'],
                            "richiestaCliente"=>($row['richiestaCliente'])?$row['richiestaCliente']:"nessuna",
                            "dataConsegna"=>($row['dataConsegna'])?$row['dataConsegna']:"###",
                            "risoluzione"=>($row['risoluzione'])?$row['risoluzione']:"#",
                            "totale"=>' '.($row['totale']),
                            "idTecnico"=>($row['idTecnico']!="0")?$row['idTecnico']:"41",
                            "noteTecnico"=>($row['noteTecnico'])?$row['noteTecnico']:"nessuna",
                            "inGaranzia"=>((strcasecmp($row['inGaranzia'],"NG"))==0)?"Non in garanzia":"In garanzia",
                            "schedaChiusa"=>''.$row['schedaChiusa'],
                        );
                        $scheda = new self($args);                        
			$schede[] = $scheda;
		}
		return $schede;
		parent::close($conn);
	}
        
        //aggiornamenti scheda
        public static function updateScheda($query) { 
                $conn=parent::connect();
                mysqli_query($conn,$query) or die ("Errore aggiornamento dati nel database");
                parent::close($conn);  
        }
}

//modellazione classe lavoro- dettagli scheda
class Lavoro extends pclabDB {
        public $idLavoro;  
        public $idScheda;
        public $descrizione; //descrizione singolo intervento eseguito dal tecnico/ del materiale
        public $costoLav; //relativo costo
        public $garanzia; //in garanzia o meno (0=non in garanzia,1=in garanzia)
        
        //costruttore
	public function __construct($idLavoro,$idScheda,$descrizione,$costoLav,$garanzia) {
                $this->idLavoro=$idLavoro;
		$this->idScheda=$idScheda;
                $this->descrizione=$descrizione;
                $this->costoLav=$costoLav;
                $this->garanzia=$garanzia;
	}
               
        //recupera la lista dei lavori eseguiti relativi ad una specifica scheda (parametro numero scheda in input)
        public static function readLavori($numScheda) {
		$conn=parent::connect();                
		$query="SELECT * FROM laboratorio WHERE NUMERORC=$numScheda";
		$rows=mysqli_query($conn,$query) or die('Dati non presenti');
		$lavori=array();
		while($row=mysqli_fetch_array($rows)){
			$lavoro = new self($row['LAVORI'],$row['NUMERORC'],$row['descrizione'],$row['costoLAV'],$row['GARANZIA']); 
			$lavori[] = $lavoro;
		}
		return $lavori;
		parent::close($conn);
	}   
        
        //aggiorna dettaglio
        public static function updateDettaglio($query) { 
                $conn=parent::connect();
                mysqli_query($conn,$query) or die ("Errore aggiornamento dati nel database");
                parent::close($conn);  
        }
}
?>