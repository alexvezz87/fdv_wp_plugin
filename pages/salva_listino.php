<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    //istanzio la variabile globale del database
        global $wpdb;        
        $wpdb->prefix = "wp_fdv_";
       
        $lista_ordine = new ListaOrdine($wpdb);
        $v_lista_ordine = new ViewListaOrdine($lista_ordine);
        
        if(isset($_POST['chiudi-ordine'])){
                //questo listener si preoccupa di cambiare lo stato di apertura di ordine nel database
                $id = $_GET['id'];
                $l_ordine = new ListaOrdine($wpdb);
                $c_l_ordine = new ControllerListaOrdine($l_ordine);
                $c_l_ordine->chiudiOrdine($id);
        }
        if(isset($_POST['carica-ordine'])){
              //apro l'ordine
                   $l_ordine = new ListaOrdine($wpdb);
                   $c_l_ordine = new ControllerListaOrdine($l_ordine);
                   
                   $c_l_ordine->apriOrdine();
        }
        
        
            
            //ora devo controllare se c'è un ordine già aperto
            //Se c'è, per immettere l'ordine devo chiuderlo prima
            //Se non c'è posso immetterlo e poi aprire l'ordine
        _e('<h1>Gestione Ordine</h1>');
        _e('<div id="admin-gestione-ordine" >');
             $id_ultimo_ordine = $v_lista_ordine->getIDUltimoOrdine();
             
             if($id_ultimo_ordine == false){
                 _e('Ordini non ancora presenti');
             }
             else{
                 if($v_lista_ordine->getOrdineStatus($v_lista_ordine->getIDUltimoOrdine()) == 0){
                     //ordine presente e chiuso
                     //gestisco la data
                     $data_apertura_raw = explode(' ', $v_lista_ordine->getApertura_Ordine($id_ultimo_ordine));
                     $giorni_apertura_raw = $data_apertura_raw[0];
                     $giorni_apertura = explode('-', $giorni_apertura_raw);
                     
                     $data_chiusura_raw = explode(' ', $v_lista_ordine->getChiusura_Ordine($id_ultimo_ordine));
                     $giorni_chiusura_raw = $data_chiusura_raw[0];
                     $giorni_chiusura = explode('-', $giorni_chiusura_raw);
                    
                      _e('<span style="font-size:1.2em">Periodo Ordine: <span style="font-weight:bold; color:red">CHIUSO</span></span><br><br><span style="font-weight:bold">Data Apertura:</span> '.$giorni_apertura[2].' '.  writeMese($giorni_apertura[1]).' '.$giorni_apertura[0].' -
                         <span style="font-weight:bold">Data Chiusura:</span> '.$giorni_chiusura[2].' '.  writeMese($giorni_chiusura[1]).' '.$giorni_chiusura[0]);
                 }
                 else if($v_lista_ordine->getOrdineStatus($v_lista_ordine->getIDUltimoOrdine()) == 1){
                     //ordine presente e aperto
                     //gestisco la data
                     $data_apertura_raw = explode(' ', $v_lista_ordine->getApertura_Ordine($id_ultimo_ordine));
                     $giorni_apertura_raw = $data_apertura_raw[0];
                     $giorni_apertura = explode('-', $giorni_apertura_raw);
                     _e('<span style="font-size:1.2em">Periodo Ordine: <span style="font-weight:bold; color:green">APERTO</span></span><br><br><span style="font-weight:bold">Data Apertura:</span> '.$giorni_apertura[2].' '.  writeMese($giorni_apertura[1]).' '.$giorni_apertura[0]);
                 }
             }
             
             
              _e('<form action="admin.php?page=gestione_listino&id='.$id_ultimo_ordine.'" method="post" enctype="multipart/form-data">
                   <table>
                       <tr>
                           <td >Carica il file degli Ordini (Ordini.xls): </td>
                           <td><input onClick="abilita_bottone()"  type="file" name="carica-file-ordine"></td>
                       <tr>');
                       //controllo se non c'è un ordine aperto
                        
                      
                       if($id_ultimo_ordine == false){
                           //ordine non esiste ancora
                           _e('<td colspan="2"><input id="carica-file" type="submit" name="carica-ordine" value="Carica il file" disabled></td>');   
                       }
                       else if($v_lista_ordine->getOrdineStatus($v_lista_ordine->getIDUltimoOrdine()) == 0){
                           //ordine chiuso
                           _e('<td colspan="2"><input id="carica-file" type="submit" name="carica-ordine" value="Carica il file" disabled></td>');                           
                       }
                       else if($v_lista_ordine->getOrdineStatus($v_lista_ordine->getIDUltimoOrdine()) == 1){
                           //ordine aperto
                           _e('<td colspan="2">
                                    <input type="submit" name="carica-ordine" value="Carica il file" disabled>
                                    <input type="submit" name="chiudi-ordine" value="Chiudi Ordine" >
                               </td>');
                       }
                            
                           
                     _e('  
                     </tr>
                   </tr>
                   </table>
               </form>
               <p style="font-size:0.9em; margin-top:20px"><strong>ATTENZIONE</strong><br>Prima di caricare un nuovo file excel di ordini, verificare che il periodo sia CHIUSO.</p>
                <script type="text/javascript" language="javascript">
                
                        function abilita_bottone(){
                        
                                document.getElementById("carica-file").removeAttribute(\'disabled\');
                        }
                    </script>
               ');
           _e('</div>');
        
       
            if(isset($_POST['carica-ordine'])){
                $path = '../files_caricati/';
                if ($_FILES['carica-file-ordine']['error'] == 0){
                    $info_path = pathinfo($_FILES['carica-file-ordine']['name']);	
                    if ($info_path['extension'] != 'xls'){
				_e('script language="javascript">
					alert("Il file selezionato non è supportato! Utilizzare solo i file .xls");
					</script>');
                                die(); //se il file non è .xls non carico nulla
                    }
                    else{
                        if(!copy($_FILES['carica-file-ordine']['tmp_name'], $path.$_FILES['carica-file-ordine']['name'])){
                            _e('<script language="javascript">
                                alert("Impossibile caricare il file!!");
				</script>');
                            die();
                        }
                        else{
                            //se il file è stato caricato in modo corretto allora devo parsarlo e caricarlo nel database
                            $nome_file_ordine = $_FILES['carica-file-ordine']['name'];
                            if(file_exists($path.$nome_file_ordine)){                                        
                                salva_listino($path.$nome_file_ordine, $wpdb);
                                //echo '<br><br>';
                                //visualizza_listino($wpdb);
                            }
                        }
                    }
                }
            }
?>
