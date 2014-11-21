<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    
    
    
    function visualizza_listino($db){
        //La funzione si occupa di visualizzare il listino composto dai tre elementi (Cassetta, Frutta_Verdura, Aggiunta_Cassetta) 
        //presi dal database
       
        visualizza_cassetta($db);
        
        visualizza_frutta_verdura($db);
        
        visualizza_aggiunta_cassetta($db);
    }
        
    
    function visualizza_cassetta($db){
        //stampo a video le cassette
        $cassetta = new Cassetta($db);
        $v_cassetta = new ViewCassetta($cassetta);
        $cassette = array();
        $cassette = $v_cassetta->getCassette();      
        
        _e('
          <table class="table-cassetta" border="1">
            <tr class="riga-intestazione">
                <td>Tipologia Cassetta</td>
                <td>Quantit&agrave; Prodotti</td>
                <td>Peso Approssimativo</td>
                <td>Tipologia Cliente</td>
                <td>Prezzo</td>
            </tr>          
        ');   
        
       if($cassette != false){
           $i=0;          
           while($i < count($cassette)){
               $cassetta = $cassette[$i];
               _e('
                   <tr>
                    <td>'.$cassetta->getTipologiaCassetta().'</td>
                    <td>'.$cassetta->getNumProdotti().'</td>
                    <td>'.$cassetta->getPeso().'</td>
                    <td>'.$cassetta->getCliente().'</td>
                    <td>'.$cassetta->getPrezzo().'</td>
                   </tr>
                ');
               $i++;
           }
       }
               
       echo '</table>';
    }
    
    function visualizza_frutta_verdura($db){
        //stampo a video Frutta_Verdura
       _e('<br><br>');
       $frutta_verdura = new Frutta_Verdura($db);
       $v_frutta_verdura = new ViewFrutta_Verdura($frutta_verdura);
       $array_frutta_verdura = array();
       $array_frutta_verdura = $v_frutta_verdura->getFrutta_Verdura();
       
       _e('
         <table class="table-frutta-verdura" border="1">
            <tr class="riga-intestazione">
                <td>Tipologia Prodotto</td>
                <td>Prodotto</td>
                <td>Prezzo</td>
                <td>Unit&agrave;</td>
            </tr>     
        ');
       if($array_frutta_verdura != false){
                     
           $i=0;
           while($i< count($array_frutta_verdura)){
               $frutta_verdura = $array_frutta_verdura[$i];
               _e('
                   <tr>
                    <td>'.$frutta_verdura->getTipologia().'</td>
                    <td>'.$frutta_verdura->getNome().'</td>
                    <td>'.$frutta_verdura->getPrezzo().'</td>
                    <td>'.$frutta_verdura->getUnita().'</td>
                   </tr>
                ');
               $i++;
           }
       }
        
       _e('</table>');
    }
    
    function visualizza_aggiunta_cassetta($db){
        //stampo a video Aggiunta_Cassetta
       _e('<br><br>');
       $aggiunta_cassetta = new Aggiunta_Cassetta($db);
       $v_aggiunta_cassetta = new ViewAggiunta_Cassetta($aggiunta_cassetta);
       $array_aggiunta_cassetta = array();
       $array_aggiunta_cassetta = $v_aggiunta_cassetta->getAggiunta_Cassetta();
       
       _e('
         <table class="table-aggiunta-cassetta" border="1">
            <tr class="riga-intestazione">
                <td>Tipologia Prodotto</td>
                <td>Prodotto</td>
                <td>Prezzo</td>
                <td>Unit&agrave;</td>
                <td>Peso in Kg (+ o - 10%)</td>
                <td>Note</td>
            </tr>     
        ');
        if($array_aggiunta_cassetta != false){
            $i=0;
            while($i<count($array_aggiunta_cassetta)){
                $aggiunta_cassetta = $array_aggiunta_cassetta[$i];
                _e('
                    <tr>
                        <td>'.$aggiunta_cassetta->getTipologia().'</td>
                        <td>'.$aggiunta_cassetta->getNome().'</td>
                        <td>'.$aggiunta_cassetta->getPrezzo().'</td>
                        <td>'.$aggiunta_cassetta->getUnita().'</td>
                        <td>'.$aggiunta_cassetta->getPeso().'</td>
                        <td>'.$aggiunta_cassetta->getNote().'</td>
                    </tr>
                ');
                $i++;
            }
        }
        _e('</table>');
    }
    
?>
