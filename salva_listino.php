<?php

//AUTORE: Alex Vezzelli - alexsoluzioniweb.it
   
    require_once 'install_DB.php';
    require_once 'excel_reader2.php';
     

    function salva_listino($file_excel, $db){        
             
           
            //devo pulire le tabelle
            dropTableCassetta($db);
            createCassetta($db);
            
            dropTableFrutta_Verdura($db);
            createFrutta_Verdura($db);
            
            dropTableAggiuntaCassetta($db);
            createAggiunta_Cassetta($db);
            
            $data=new Spreadsheet_Excel_Reader($file_excel);
            $sheets=0;
            
            $foglio_cassetta = array();
            $item_cassetta = array();
            
            $foglio_frutta_verdura = array();
            $item_frutta_verdura = array();
            
            $foglio_prodotti_aggiunti = array();
            $item_prodotti_aggiunti = array();
            
             // Genero una tabella con i dati del file
            
             $conta=count($data->sheets);
                $shts=0;for($a=0;$a<$conta;$a++){
                  $shts++;
                  $sheets=$shts-1;
                  // Genero una tabella con i dati del file
                 
                  // Eseguo il ciclo delle righe
                  for($i=0;$i<=$data->sheets[$sheets]['numRows'];$i++) {
                    $riga_vuota_cassetta = false;
                    $riga_vuota_f_v = false;
                    $riga_vuota_aggiunta = false;
                    // Eseguo il ciclo delle colonne
                    for($j=1;$j<=$data->sheets[$sheets]['numCols'];$j++){
                     // if($data->sheets[$sheets]['cells'][$i][$j]!=""){echo "  <td>".$data->sheets[$sheets]['cells'][$i][$j]."</td> ";}
                        
                      if($sheets == 0){
                          //Foglio Cassette
                          if($i > 1){
                              if($j==1 && $riga_vuota_cassetta == false){
                                  if($data->sheets[$sheets]['cells'][$i][$j] == ''){
                                      $riga_vuota_cassetta = true;
                                  }
                                  else{
                                    //Tipologia cassetta
                                    $item_cassetta['Tipologia'] = $data->sheets[$sheets]['cells'][$i][$j];
                                  }
                              }
                              if($j==2 && $riga_vuota_cassetta == false){
                                  //Num Prodotti
                                  $item_cassetta['Num_Prodotti'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              if($j==3 && $riga_vuota_cassetta == false){
                                  //Peso
                                  $item_cassetta['Peso'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              if($j==4 && $riga_vuota_cassetta == false){
                                  //Tipologia Cliente
                                  $item_cassetta['Tipologia_Cliente'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              if($j==5 && $riga_vuota_cassetta == false){
                                  //Prezzo
                                  $item_cassetta['Prezzo'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              if($j==6 && $riga_vuota_cassetta == false){
                                  //foto
                                  $item_cassetta['Foto'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                          }
                          
                      }
                      if($sheets == 1){
                          //Foglio Frutta e Verdura
                          if($i > 1){
                              if($j==1 && $riga_vuota_f_v == false){
                                  if($data->sheets[$sheets]['cells'][$i][$j] == ''){
                                      $riga_vuota_f_v = true;
                                  }
                                  else{
                                    //Tipologia 
                                    $item_frutta_verdura['Tipo'] = $data->sheets[$sheets]['cells'][$i][$j];
                                  }
                              }
                              if($j==2 && $riga_vuota_f_v == false){
                                  //Prodotto
                                  $item_frutta_verdura['Prodotto'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              if($j==3 && $riga_vuota_f_v == false){
                                  //Prezzo
                                  $item_frutta_verdura['Prezzo'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              if($j==4 && $riga_vuota_f_v == false){
                                  //Unità
                                  $item_frutta_verdura['Unita'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              if($j==5 && $riga_vuota_f_v == false){
                                  //Unità
                                  $item_frutta_verdura['Foto'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              
                          }
                      }
                      if($sheets == 2){
                          //Foglio prodotti da aggiungere a cassetta
                          if($i>1){
                              if($j==1 && $riga_vuota_aggiunta == false){
                                  if($data->sheets[$sheets]['cells'][$i][$j] == ''){
                                      $riga_vuota_aggiunta = true;
                                  }
                                  else{
                                      //Tipologia
                                  $item_prodotti_aggiunti['Tipo'] = $data->sheets[$sheets]['cells'][$i][$j];
                                  }                                  
                              }
                              if($j==2 && $riga_vuota_aggiunta == false){
                                  //Prodotto
                                  $item_prodotti_aggiunti['Prodotto'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              if($j==3 && $riga_vuota_aggiunta == false){
                                  //Prezzo
                                  $item_prodotti_aggiunti['Prezzo'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              if($j==4 && $riga_vuota_aggiunta == false){
                                  //Unità
                                  $item_prodotti_aggiunti['Unita'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              if($j==5 && $riga_vuota_aggiunta == false){
                                  //Errore peso in kg
                                  $item_prodotti_aggiunti['Peso'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              if($j==6 && $riga_vuota_aggiunta == false){
                                  //Note
                                  $item_prodotti_aggiunti['Note'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                              if($j==7 && $riga_vuota_aggiunta == false){
                                  //Note
                                  $item_prodotti_aggiunti['Foto'] = $data->sheets[$sheets]['cells'][$i][$j];
                              }
                          }
                      }
                    }
                    if($sheets == 0 && $i>1 && $riga_vuota_cassetta == false)
                        array_push($foglio_cassetta, $item_cassetta);
                    if($sheets == 1 && $i>1 && $riga_vuota_f_v == false)
                        array_push($foglio_frutta_verdura, $item_frutta_verdura);
                    if($sheets == 2 && $i>1 && $riga_vuota_aggiunta == false)
                        array_push($foglio_prodotti_aggiunti, $item_prodotti_aggiunti);
                  }
                 
                }
               
               $cassetta = new Cassetta($db);
               $c_cassetta = new ControllerCassetta($cassetta);
               
               $c1 = 0;
               $errors = 0;
               //print_r($foglio_cassetta);
               while($c1 < count($foglio_cassetta)){
                   $cassetta->setCassetta($foglio_cassetta[$c1]['Tipologia'], $foglio_cassetta[$c1]['Num_Prodotti'], $foglio_cassetta[$c1]['Peso'], $foglio_cassetta[$c1]['Tipologia_Cliente'], $foglio_cassetta[$c1]['Prezzo'], $foglio_cassetta[$c1]['Foto']);
                   if(!$c_cassetta->saveCassetta()){
                       $errors++;
                   }                   
                   $c1++;
               }
               
               if($errors>0){
                   echo '<br><br>Damn! you got some errors!!';
               }
               else{
                   echo '<br><br>Database Table Cassetta, correctly updated!, ';
               }
               
               //echo '<br><br>';
               //print_r($foglio_frutta_verdura);
               
               $frutta_verdura = new Frutta_Verdura($db);
               $c_frutta_verdura = new ControllerFrutta_Verdura($frutta_verdura);
               $errors=0;
               $c2 = 0;
               
               while($c2 < count($foglio_frutta_verdura) ){
                    $frutta_verdura->setFruttaVerdura($foglio_frutta_verdura[$c2]['Tipo'], $foglio_frutta_verdura[$c2]['Prodotto'], $foglio_frutta_verdura[$c2]['Prezzo'], $foglio_frutta_verdura[$c2]['Unita'], $foglio_frutta_verdura[$c2]['Foto']);
                    if(!$c_frutta_verdura->saveFrutta_Verdura()){
                        $errors++;
                    }
                    $c2++;
               }
                if($errors>0){
                   echo '<br><br>Damn! you got some errors!!';
               }
               else{
                   echo 'Database Table Frutta_Verdura, correctly updated!, ';
               }
               
               $aggiunta_cassetta = new Aggiunta_Cassetta($db);
               $c_aggiunta_cassetta = new ControllerAggiunta_Cassetta($aggiunta_cassetta);
               $errors = 0;
               $c3 = 0;
              
               while($c3 < count($foglio_prodotti_aggiunti)){
                   
                   $aggiunta_cassetta->setAggiunta_Cassetta($foglio_prodotti_aggiunti[$c3]['Tipo'], $foglio_prodotti_aggiunti[$c3]['Prodotto'], $foglio_prodotti_aggiunti[$c3]['Prezzo'], $foglio_prodotti_aggiunti[$c3]['Unita'], $foglio_prodotti_aggiunti[$c3]['Peso'], $foglio_prodotti_aggiunti[$c3]['Note'], $foglio_prodotti_aggiunti[$c3]['Foto']);
                   
                   if(!$c_aggiunta_cassetta->saveAggiunta_Cassetta()){
                       $errors++;
                   }
                   
                   $c3++;
               }
                if($errors>0){
                   echo '<br><br>Damn! you got some errors!!';
               }
               else{
                   echo 'Database Table Aggiunta_Cassetta, correctly updated!<br><br>';
                  
                       
                  
               }
    }
    
    
    
    
             
       ?>     
        
 