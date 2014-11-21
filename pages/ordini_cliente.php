<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

    //ottengo la mail
    global $current_user;
    get_currentuserinfo();
    
    $email = $current_user->user_email;
    $username = $current_user->user_login;
    $ordini = new OrdineCliente($email, null);
    $v_ordini = new ViewOrdineCliente($ordini);
    $cliente = new Cliente($username, $email);
    $v_cliente = new ViewCliente($cliente);
    
    $id_cliente = $v_cliente->isCliente($email);
    
    
    $lista_ordini = $v_ordini->get_IDs_From_Cliente($id_cliente);
    
    
    _e('<h2>Riepilogo ordini effettuati</h2>');
    
    if(count($lista_ordini) > 0){
        //ho degli ordini effettuati
        
        _e('<table  class="mia_tabella">
                <tr style="font-weight:bold">
                    <th>ID Ordine</th>
                    <th>Data Ordine</th>
                    <th>Costo Totale</th>
                    <th>Costo Finale</th>
                    <th>Note</th>
                </tr>            
        ');
        
         $i=0;
         while($i < count($lista_ordini)){
             $temp_ordine = new OrdineCliente(null, null);
             $temp_ordine = $v_ordini->getOrdineCliente($lista_ordini[$i]);
             $id_ordine = $lista_ordini[$i];
             
             _e('
                 <tr class="c-'.$i.'" >
                    <td style="cursor:pointer" onClick="show_ordine('.$i.')">'.$id_ordine.'</td>
                    <td>'.$temp_ordine->getDataOrdine().'</td>
                    <td>€ '.number_format((float)$temp_ordine->getCostoTotale(),2).'</td>
                    <td>€ '.number_format((float)$v_ordini->getCostoFinale($v_ordini->getIdFromData($temp_ordine->getDataOrdine())),2).'</td>
                    <td>'.$temp_ordine->getNote().'</td>
                 </tr>
                 <tr id="dss-sub-'.$i.'" onClick="hide_ordine('.$i.')" style="display:none"  >
                    <td colspan="5" > ');
                    
                        include 'tabella_articoli_ordine.php';
                    
          _e('      </td>
                 </tr>
             ');
          
           _e('<script type="text/javascript" language="javascript">
                         
                            function show_ordine(count){
                            
                                    document.getElementById("dss-sub-"+count).style.display = \'inline\';
                                                                       
                            }    
                            
                            function hide_ordine(count){
                                document.getElementById("dss-sub-"+count).style.display = \'none\';

                            }
                           
                            
                    
                        
                    </script>');
             
             $i++;
         }
        
        _e('</table>');
        
    }
    else{
        _e('Non sono presenti ordini.');
    }
    
    
    
?>