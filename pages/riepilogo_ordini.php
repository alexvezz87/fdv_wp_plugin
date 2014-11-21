<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

    //LISTENER SCONTO
    if(isset($_POST['calcola-sconto'])){
        $id_ordine = $_GET['ordine'];
        $sconto = isset($_POST['sconto-ordine']) ? number_format((float) $_POST['sconto-ordine'], 2) : 0;
                
        $oc = new OrdineCliente(null, null);
        $c_oc = new ControllerOrdineCliente($oc);
        $v_oc = new ViewOrdineCliente($oc);
        
        //salvo lo sconto
        $c_oc->setSconto($id_ordine, $sconto);
        //calcolo il nuovo costo finale
        $cf = number_format((float)$v_oc->getCostoFinale($id_ordine),2);
        $cf = $cf - $sconto;
        //setto il nuovo costo finale
        $c_oc->setCostoFinale($id_ordine, $cf);
    }

    
    _e('<h1>Riepilogo ordini ricevuti</h1>');
    
    global $wpdb;
    $wpdb->prefix = "wp_fdv_";
    $lista_ordine = new ListaOrdine($wpdb);
    $v_lista_ordine = new ViewListaOrdine($lista_ordine);
    
    $periodo = $v_lista_ordine->getIDOrdini();
    if(count($periodo) > 0){
        //ho degli ordini
        $i=0;
        while($i < count($periodo)){
            $apertura = $v_lista_ordine->getApertura_Ordine($periodo[$i]);
            $chiusura = $v_lista_ordine->getChiusura_Ordine($periodo[$i]);
            $giorno_apertura = "";
            $giorno_chiusura = "";
            
            if($apertura != null || $apertura != false){
                $data_apertura = explode(' ', $apertura);
                $temp_apertura = explode('-', $data_apertura[0]);
                $giorno_apertura = $temp_apertura[2].' '.writeMese($temp_apertura[1]).' '.$temp_apertura[0];
            }
            else{
                $giorno_apertura = "non identificato.";
            }
            if($chiusura != null || $chiusura != false ){
                $data_chiusura = explode(' ', $chiusura);
                $temp_chiusura = explode('-', $data_chiusura[0]);
                
                $giorno_chiusura = $temp_chiusura[2].' '.writeMese($temp_chiusura[1]).' '.$temp_chiusura[0];
            }
            else{
                $giorno_chiusura = "in corso";
            }
            
            _e('<div style="width:100%">');
            if($i==0){
                //primo elemento deve rimanere sempre visibile
                _e('<div>');
            }
            else{
                //il secondo elemento deve rimanere nascosto
                _e('<div class="c-'.$i.'">');
            }
            _e('
                        <h2 style="">Periodo: '.$giorno_apertura.' - '.$giorno_chiusura.' </h2>
                            
                     </div>');
           if($i==0){
                //primo elemento deve rimanere sempre visibile
                _e('<div style="margin-left:20px">');
            }
            else{
                //il secondo elemento deve rimanere nascosto
                _e('<div class="d-'.$i.'" style="margin-left:20px">');
            }
            
            //prendo gli ordini di questo periodo
            
            $ordine_cliente = new OrdineCliente(null, $periodo[$i]);
            $v_ordine_cliente = new ViewOrdineCliente($ordine_cliente);
            $ordini_periodo = $v_ordine_cliente->get_IDs_From_Ordine($periodo[$i]);
            
           /* 
           
            */
            if(count($ordini_periodo)>0){
                _e('      

                            <table class="mia_tabella" id="myTable">
                                <tr style="font-weight:bold">
                                    <th>ID Ordine</th>
                                    <th>Data</th>
                                    <th>Cliente</th>                                    
                                    <th>Costo Totale</th>
                                    <th style="min-width:120px">Sconto</th>
                                    <th>Costo Finale</th>
                                    <th>Note</th>
                                    <th>PDF</th>
                                </tr>
                            
                ');
                
                $k=0;
                //inizio a stampare le righe della tabella
                while($k < count($ordini_periodo)){
                    $ordine_cliente = $v_ordine_cliente->getOrdineCliente($ordini_periodo[$k]);
                    $cliente = new Cliente(null, null);
                    $v_cliente = new ViewCliente($cliente);
                    
                    $id_ordine = $v_ordine_cliente->getIdFromData($ordine_cliente->getDataOrdine());
                    
                    $data_raw = $ordine_cliente->getDataOrdine();
                    $data_split = explode(' ', $data_raw);
                    $data_giorni_raw = $data_split[0];
                    $data_ora = $data_split[1];
                    $data_giorni = explode('-', $data_giorni_raw);
                    
                    $sconto = 0.00;
                    //var_dump($v_ordine_cliente->getSconto($id_ordine));
                    if($v_ordine_cliente->getSconto($id_ordine) != null){
                        
                        $sconto = (float)$v_ordine_cliente->getSconto($id_ordine);
                    }
                    
                                     
                    _e('
                        <tr class="c-sub-'.$id_ordine.'" >
                            <td  style="cursor:pointer"  onClick="show('.$id_ordine.')">'.$id_ordine.'</td>
                            <td>'.$data_giorni[2].' '.writeMese($data_giorni[1]).' '.$data_giorni[0].' - '.$data_ora.'</td>
                            <td>'.$v_cliente->getNominativo($v_cliente->getEmailFromID($ordine_cliente->getID_Cliente())).'</td>                            
                            <td>€ '.number_format($ordine_cliente->getCostoTotale(),2).'</td>
                            <td ><form action="'.admin_url().'/admin.php?page=riepilogo_ordini&ordine='.$id_ordine.'" method="post">€ <input style="width:50px" type="text" name="sconto-ordine" value="'.number_format($sconto,2).'"><input class="aggiungi-sconto" type="submit" name="calcola-sconto" value="OK"></form>');
                            
                            $sconto_fisso = $v_cliente->getSconto($v_cliente->getEmailFromID($ordine_cliente->getID_Cliente()));
                            if($sconto_fisso != null && $sconto_fisso != '' && $sconto_fisso != 0){
                                _e('<br>Sconto fisso: '.$sconto_fisso.'%');
                            }                    
                        _e('</td>
                            <td>€ '.number_format($v_ordine_cliente->getCostoFinale($id_ordine),2).'</td>
                            <td style="width:400px; text-align:justify">'.stripslashes($ordine_cliente->getNote()).'</td>
                            <td><form action="'.curPageURL().'" method="post"><input class="aggiungi" type="submit" name="crea-pdf-ordine-'.$id_ordine.'" value="Crea PDF"></form>');
                             if(isset($_POST['crea-pdf-ordine-'.$id_ordine])){
                                //CODICE PER CREARE IL PDF DELL'ORDINE SINGOLO
                               
                                 include 'pdf/create_ordine_singolo.php';
                                 
                             }
                       _e(' </td>
                        </tr>
                        <tr  >
                            <td colspan="7" id="ds-sub-'.$id_ordine.'" class="ds-sub'.$id_ordine.'" onClick="hide('.$id_ordine.')" style="display:none">');
                                
                                include 'tabella_articoli_ordine.php';
                                
                     _e('   </td>
                        </tr>
                    ');
                     
                     _e('<script type="text/javascript" language="javascript">
                         
                            function show(count){                                
                                    document.getElementById("ds-sub-"+count).style.display = \'inline\';
                                                                       
                            }                            
                            function hide(count){
                                document.getElementById("ds-sub-"+count).style.display = \'none\';

                            }
                            
                    
                        
                    </script>');
                    
                    $k++;
                }
                
                _e('</table>
                         </div>
                    </div>');
                $location_pdf = plugins_url().'/gestione_ordine/pdf/create_pdf.php';
                $location_plugin = plugins_url().'/gestione_ordine/';
                
                if($i==0){
                    //posso stampare l'ordine settimanale solo per l'ultimo periodo
                    _e('<br><form style="padding:0; margin:0; border:0" action="'.curPageURL().'" method="post"><input class="aggiungi" type="submit" name="crea-pdf-settimanale-'.$periodo[$i].'" value="Crea PDF ordine settimanale"></form>');

                    if(isset($_POST['crea-pdf-settimanale-'.$periodo[$i]])){
                       //CODICE PER CREARE IL PDF DELL'ORDINE SETTIMANALE
                        include 'pdf/create_ordine_settimanale.php';

                    }
                }
                 
            }
            else{
                _e('Nessun ordine presente.');
                
            }
           
            _e('</div>');
            $i++;
        }
        
    }
    else{
        //non ho ordini
        _e('Periodo ordini non presente.');
    }
    
    //_e('</div>');
   
    
    _e('<hr>');
    _e('<h1 id="ricerca">Ricerca ordini</h1>');
    
    //La ricerca di ordini prevede di cercare principalmente due parametri di ingresso:
    //1. Il cliente
    //2. Il periodo di ordine
    //entrabe i valori possono essere ricercati singolarmente o concatenati
    
    _e('<div style="width:80%">');
    _e('   <form style="width:100%" action="'.curPageURL().'#ricerca" method="post">');
    _e('        <div style="float:left"><label for="cliente">Ricerca per Cliente</label>');
    //ottengo i clienti
    $cliente = new Cliente(null, null);
    $v_cliente = new ViewCliente($cliente);
    //ottengo tutti i clienti
    $clienti = $v_cliente->getClienti();
    _e('        <select name="ricerca-cliente" style="margin-left:40px">');
    _e('            <option value=""> </option>');
    $count_clienti=0;
    while($count_clienti < count($clienti)){
        $cliente = $clienti[$count_clienti];
        _e('        <option value="'.$cliente->getID().'">'.$cliente->getNominativo().'</option>');
        
        $count_clienti++;
    }
    
    _e('        </select></div>');
    _e('        <div style="float:left; margin-left:40px"><input class="aggiungi" type="submit" name="ricerca-ordini" value="RICERCA"></div>');
    _e('   </form>');
    _e('</div>');
    
    _e('<div style="display:block; clear:both; padding-top:30px">');
    //Ascoltatore della ricerca
    
    
    
    if(isset($_POST['ricerca-ordini']) || isset($_POST['crea-pdf-ricerca-ordine'])){
        $num_ordine = 0;
        if( isset($_POST['crea-pdf-ricerca-ordine'])){
          $num_ordine = $_GET['id-ordine'];
        }
        
        if(!isset($_POST['ricerca-ordini'])){
            $id_cliente = $_SESSION['id_cliente'];
            //_e('id_cliente: '.$id_cliente );
        }
        else if(!isset($_SESSION['id_cliente'])){
            $id_cliente = $_POST['ricerca-cliente'];
            $_SESSION['id_cliente'] = $id_cliente;
        }else if(isset($_POST['ricerca-ordini'])){
             $id_cliente = $_POST['ricerca-cliente'];
            $_SESSION['id_cliente'] = $id_cliente;
        }
              
        
        _e('<span style="font-size:1.4em">Cliente: <span style="font-weight:bold">'.$v_cliente->getNominativoByID($id_cliente).'</span></span>');                
        //Ottengo gli ordini di questo cliente
        $ordine_cliente = new OrdineCliente(null, $periodo[$i]);
        $v_ordine_cliente = new ViewOrdineCliente($ordine_cliente);
        
        $ordini_del_cliente = $v_ordine_cliente->get_IDs_From_Cliente($id_cliente);
        if(count($ordini_del_cliente)>0){
            //ci sono degli ordini
             _e('
                 <table id="myTable" class="mia_tabella" >
                    <tr>
                        <th>ID Ordine</th>
                        <th>Data</th>
                        <th>Cliente</th>                                    
                        <th>Costo Totale</th>
                        <th>Costo Finale</th>
                        <th>Note</th>
                        <th>PDF</th>
                    </tr>                            
            ');
            
            
            $count = 0;
            while($count < count($ordini_del_cliente)){
                $ordine_cliente = new OrdineCliente($id_cliente, null);
                $v_ordine_cliente = new ViewOrdineCliente($ordine_cliente);
                
                $id_ordine = $ordini_del_cliente[$count];
                $ordine_cliente = $v_ordine_cliente->getOrdineCliente($id_ordine);
                $data_raw = $ordine_cliente->getDataOrdine();
                $data_split = explode(' ', $data_raw);
                $data_giorni_raw = $data_split[0];
                $data_ora = $data_split[1];
                $data_giorni = explode('-', $data_giorni_raw);
                _e('
                    <tr>
                        <td>'.$id_ordine.'</td>
                        <td>'.$data_giorni[2].' '.writeMese($data_giorni[1]).' '.$data_giorni[0].' - '.$data_ora.'</td>
                        <td>'.$v_cliente->getNominativoByID($id_cliente).'</td>                            
                        <td>€ '.number_format($ordine_cliente->getCostoTotale(),2).'</td>
                        <td>€ '.number_format($v_ordine_cliente->getCostoFinale($id_ordine),2).'</td>
                        <td>'.$ordine_cliente->getNote().'</td>
                        <td><form action="'.curPageURL().'&id-ordine='.$id_ordine.'#ricerca" method="post"><input class="aggiungi" type="submit" name="crea-pdf-ricerca-ordine" value="Crea PDF"></form>');
                        
                        if(isset($_POST['crea-pdf-ricerca-ordine'])&& $num_ordine == $id_ordine){
                        //CODICE PER CREARE IL PDF DELL'ORDINE SINGOLO
                           
                            include 'pdf/create_ordine_singolo.php';
                        }
                       _e(' </td>');
                _e(' </tr>');       
                
                
                $count++;
            }
            
            _e('</table><br><br>');
                    
        }
        else{
            //non ci sono degli ordini
            _e('La ricerca sul cliente non ha restituito risultati.');
        }
        
    }
     _e('</div>');
    
    
?>