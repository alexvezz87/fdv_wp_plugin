<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

 //Devo scrivere nel pdf i valori della cassetta ordinati dal cliente ed anche i contatti (indirizzo e telefono)
                                
                                //creo il pdf
                                $pdf = new MyFPDF();
                                $pdf->Open();
                                $pdf->AddPage();
                                $pdf->SetTextColor(0); // Con queste due funzioni imposto il carattere
                    
                    
                                //compongo il pdf
                                $pdf->SetFont('Arial','',14);
                                $titolo = "Ordine effettuato da ".$v_cliente->getNominativo($v_cliente->getEmailFromID($ordine_cliente->getID_Cliente()));
                                $pdf->MultiCell(500, 10, $titolo, 0, 'center');
                                $pdf->SetFont('Arial','',12);
                                $subtitolo = "Ordine n. ".$id_ordine.' - Data: '.$ordine_cliente->getDataOrdine();
                                $pdf->MultiCell(500, 10, $subtitolo, 0, 'center');
                                                                
                                $pdf->MultiCell(500, 10, 'Dati spedizione:', 0, 'center');
                                
                                //ottengo i dati della spedizione
                                $data = array();
                                
                                $email = $v_cliente->getEmailFromID($ordine_cliente->getID_Cliente());
                                $contatti = new Contatti($email);
                                $v_contatti = new ViewContatti($contatti);
                                $indirizzo = $v_contatti->getIndirizzoSpedizione($email);
                                $data[0] = $indirizzo['Via'].' n. '.$indirizzo['Civico'].' - '.$indirizzo['CAP'].' '.$indirizzo['Citta'].' ('.$indirizzo['Prov'].')';
                                $data[1] = $v_contatti->getTelefono($email);
                                $data[2] = $v_contatti->getCellulare($email);
                                $data[3] = $email;
                                $data[4] = $ordine_cliente->getNote();
                                //print_r($data);
                                 define(LUNGHEZZA, 180);
                                $datas = array();
                                $datas[0] = $data;
                                $header_contatti = array('Indirizzo', 'Telefono', 'Cellulare', 'Email');
                                $pdf->SetFont('Arial','',9);
                                $pdf->MultiCell(LUNGHEZZA, 5, 'Indirizzo: '.utf8_decode($data[0]), 0, 'center');
                                $pdf->MultiCell(LUNGHEZZA, 5, 'Telefono: '.$data[1], 0, 'center');
                                $pdf->MultiCell(LUNGHEZZA, 5, 'Cellulare: '.$data[2], 0, 'center');
                                $pdf->MultiCell(LUNGHEZZA, 5, 'Email: '.$data[3], 0, 'center');
                                
                                
                                
                                
                                $pdf->MultiCell(LUNGHEZZA, 5, 'Note: '. str_replace("\'", "'", utf8_decode($data[4])), 0, 'center');
                                
                                $pdf->SetFont('Arial','',12);                                
                                $pdf->MultiCell(500, 10, 'Prodotti', 0, 'center');
                                
                                //ottengo i prodotti
                                $articolo_ordine = new ArticoloOrdine(null, null, null);
                                $v_articolo_ordine = new ViewArticoloOrdine($articolo_ordine);
                                //ottengo tutti gli id
                                $ids_articoli = $v_articolo_ordine->get_IDs_From_Ordine($id_ordine);
                                $rows = array();
                                if(count($ids_articoli)>0){
                                    //ho degli articoli
                                    $count_articolo = 0;
                                    while($count_articolo < count($ids_articoli)){
                                        $articolo_ordine = $v_articolo_ordine->getArticolo_Ordine($ids_articoli[$count_articolo]);
                                        $row = array();
                                        //suddivisione dei prodotti
                                        
                                        //MODIFICA 03-08-2014
                                        $row[0] = $articolo_ordine->getQuantita().' x '.$articolo_ordine->getUnitaMisura();
                                        $row[1] = $articolo_ordine->getNomeArticolo();
                                        $row[2] = $articolo_ordine->getCassettaPersonalizzata();
                                        
                                        
//                                        if($articolo_ordine->getID_Tabella() == 0){
//                                            //Cassetta
//                                            $cassetta = new Cassetta($wpdb);
//                                            $v_cassetta = new ViewCassetta($cassetta);
//                                           
//                                            $row[0] = $articolo_ordine->getQuantita();
//                                            $row[1] = $v_cassetta->getTipologiaCassetta($articolo_ordine->getID_Prodotto());
//                                            $row[2] = $articolo_ordine->getCassettaPersonalizzata();
//                                        }
//                                        else if($articolo_ordine->getID_Tabella() == 1){
//                                            //Frutta_Verdura
//                                            $fv = new Frutta_Verdura($wpdb);
//                                            $v_fv = new ViewFrutta_Verdura($fv);
//                                            
//                                            $row[0] = $articolo_ordine->getQuantita().' '.$v_fv->getUnita($articolo_ordine->getID_Prodotto());
//                                            $row[1] = $v_fv->getNomeProdotto($articolo_ordine->getID_Prodotto());
//                                            $row[2] = $articolo_ordine->getCassettaPersonalizzata();
//                                        }
//                                        else if($articolo_ordine->getID_Tabella() == 2){
//                                            //Aggiunta_Cassetta
//                                            $ac = new Aggiunta_Cassetta($wpdb);
//                                            $v_ac = new ViewAggiunta_Cassetta($ac);
//                                            
//                                            $row[0] = $articolo_ordine->getQuantita().' '.$v_ac->getUnita($articolo_ordine->getID_Prodotto());
//                                            $row[1] = $v_ac->getNomeProdotto($articolo_ordine->getID_Prodotto());
//                                            $row[2] = $articolo_ordine->getCassettaPersonalizzata();
//                                        }
                                        
                                        if($row[2] == 0){
                                            $row[2] = 'NO';
                                        }
                                        
                                        $row[3] = number_format((float)($articolo_ordine->getQuantita()*$articolo_ordine->getPrezzoUnitario()),2);

                                        array_push($rows, $row);
                                        
                                        $count_articolo++;
                                    }
                                    
                                    
                                }
                                else{
                                    //non ho articoli
                                    $pdf->MultiCell(500, 10, 'Prodotti non presenti', 0, 'center');
                                }
                               
                                $header = array('Qt', 'Nome', 'CP', 'Prezzo');
                                $pdf->SetFont('Arial','',8);
                                $pdf->BasicTableOrdine($header, $rows);
                                
                                $sconto_cliente = $v_cliente->getSconto($email);
                                $parziale = $ordine_cliente->getCostoTotale();
                                $finale = $v_ordine_cliente->getCostoFinale($v_ordine_cliente->getIdFromData($ordine_cliente->getDataOrdine()));
                                $sconto_ordine = $v_ordine_cliente->getSconto($id_ordine);
                                
                                $sconto = "";
                                if($sconto_cliente != null && $sconto_cliente != ''){
                                    $sconto .= $sconto_cliente."%, ";
                                }
                                if($sconto_ordine != null && $sconto_ordine != '' && $sconto_ordine != 0){
                                    $sconto.= "euro ".$sconto_ordine;
                                }
                                
                                $pdf->SetFont('Arial','',12);                                
                                
                                $pdf->MultiCell(500, 10, 'Totale Parziale: euro '.number_format($parziale,2), 0, 'center');
                                $pdf->MultiCell(500, 10, 'Sconto: '.utf8_decode($sconto), 0, 'center');
                                $pdf->MultiCell(500, 10, 'Totale Finale: euro '.number_format($finale,2), 0, 'center');
                                
                                //salvo il pdf
                                $file_pdf = 'fdv_ordine-'.$v_ordine_cliente->getIdFromData($ordine_cliente->getDataOrdine()).'.pdf';
                                $pdf->Output($file_pdf, 'F');
                                $path = admin_url();
                                _e('<a target="_blank" href="'.$file_pdf.'">'.$file_pdf.'</a>');
?>