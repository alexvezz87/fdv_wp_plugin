<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

    $temp_ao = new ArticoloOrdine(null, null, null);
    $v_temp_ao = new ViewArticoloOrdine($temp_ao);
    //Ottengo tutti i valori di un determinato ordine
    $lista_prodotti = $v_temp_ao->get_IDs_From_Ordine($id_ordine);
    if(count($lista_prodotti)>0){
        //ho dei prodotti
        _e('<table cellpadding="5"  style="width:100%; float:left; font-size:1em">
                <tr style="font-weight:bold">
                    <td>Qt</td>
                    <td>Prodotto</td>
                    <td>CP</td>
                    <td>Prezzo unitario</td>
                    <td>Prezzo Totale</td>
                </tr>            
        ');
        $j=0;
        while($j < count($lista_prodotti)){
            $temp_ao = $v_temp_ao->getArticolo_Ordine($lista_prodotti[$j]);
            //trovo la tabella e di conseguenza il prodotto in questione
            //id_tabella == 0 --> cassetta
            //id_tabella == 1 --> frutta_verdura
            //id_tabella == 2 --> aggiunta_cassetta
            $prodotto = array();
            global $wpdb;
            
            $qt = $temp_ao->getQuantita().' x '.$temp_ao->getUnitaMisura();
            $prodotto['nome'] = $temp_ao->getNomeArticolo();
            $prodotto['prezzo'] = $temp_ao->getPrezzoUnitario();
            
            
            
            
//            if($temp_ao->getID_Tabella() == 0){
//                //tabella cassetta
//                //devo ottenere foto, nome prodotto e prezzo
//                $cassetta = new Cassetta($wpdb);
//                $v_cassetta = new ViewCassetta($cassetta);                               
//                $prodotto['foto'] = $v_cassetta->getID_Foto($temp_ao->getID_Prodotto());
//                $prodotto['nome'] = $v_cassetta->getTipologiaCassetta($temp_ao->getID_Prodotto());
//                $prodotto['prezzo'] = $v_cassetta->getPrezzo($temp_ao->getID_Prodotto());
//                $qt.= '';
//            }
//            else if($temp_ao->getID_Tabella() == 1){
//                //tabella frutta_verdura
//                //devo ottenere foto, nome prodotto e prezzo
//                $frutta_verdura = new Frutta_Verdura($wpdb);
//                $v_frutta_verdura = new ViewFrutta_Verdura($frutta_verdura);
//                $prodotto['foto'] = $v_frutta_verdura->getID_Foto($temp_ao->getID_Prodotto());
//                $prodotto['nome'] = $v_frutta_verdura->getNomeProdotto($temp_ao->getID_Prodotto());
//                $prodotto['prezzo'] = $v_frutta_verdura->getPrezzo($temp_ao->getID_Prodotto());
//                $qt.= ' '.$v_frutta_verdura->getUnita($temp_ao->getID_Prodotto());
//            }
//            else if($temp_ao->getID_Tabella() == 2){
//                //tabella aggiunta_cassetta
//                //devo ottenere foto, nome prodotto e prezzo
//                $aggiunta_cassetta = new Aggiunta_Cassetta($wpdb);
//                $v_aggiunta_cassetta = new ViewAggiunta_Cassetta($aggiunta_cassetta);
//                $prodotto['foto'] = $v_aggiunta_cassetta->getID_Foto($temp_ao->getID_Prodotto());
//                $prodotto['nome'] = $v_aggiunta_cassetta->getNomeProdotto($temp_ao->getID_Prodotto());
//                $prodotto['prezzo'] = $v_aggiunta_cassetta->getPrezzo($temp_ao->getID_Prodotto());
//                $qt.= ' '.$v_aggiunta_cassetta->getUnita($temp_ao->getID_Prodotto());
//            }
//            else{
//                _e('Errore nell\'identificare la tabella.');
//            }

            $prezzo_totale = str_replace('.', ',', number_format((float)$temp_ao->getQuantita()*(float)$prodotto['prezzo'] ,2));
            $cp = $temp_ao->getCassettaPersonalizzata();
            if($cp == 0){
                $cp = 'NO';
            }
            
            _e('
                <tr>
                    <td>'.$qt.'</td>
                    <td>'.$prodotto['nome'].'</td>
                    <td>'.$cp.'</td>
                    <td>€ '.$prodotto['prezzo'].'</td>
                    <td>€ '.$prezzo_totale.'</td>
                </tr>
            ');
            $j++;
        }

        _e('</table>');
    }
    else{
        //non ho dei prodotti
        _e('Nessun prodotto presente nell\'ordine.');
    }


?>