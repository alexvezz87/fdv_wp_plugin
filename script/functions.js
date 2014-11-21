/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function abilita(checkButton){
    
    if(checkButton.checked){
        document.getElementById('registra-utente').removeAttribute('disabled');
    }
    else{
        document.getElementById('registra-utente').disabled=true;
    }
}

var prodotti_dentro_cassetta = 0;
var totale_ordine = 0;

function add_Prodotto(id_tabella, id_prodotto, top, left, nome, prezzo, unita){
    //La funzione aggiunge un elmento al div dei prodotti per comporre la cesta
    prodotti_dentro_cassetta++;
    //setto l'identificativo della tabella per lo sprite
    var id="";
    if(id_tabella === 1){
        //frutta e verdura
        id = "fv-immagine-prodotto";
    }
    else if(id_tabella === 2){
        id = "ac-immagine-prodotto";
    }
    
    var qt = document.getElementById("prodotto-qt-"+id_tabella+"-"+id_prodotto).value;
    //rendo la qt un valore calcolabile
    qt = qt.replace(",",".");
    document.getElementById("prodotto-qt-"+id_tabella+"-"+id_prodotto).value = qt;
    var qt_display = qt;
    //riscrivo il valore a video
    //document.getElementById("prodotto-qt-"+id_tabella+"-"+id_prodotto).value = qt;
    qt = parseFloat(qt);
    
    
    document.getElementById("button-add-"+id_tabella+"-"+id_prodotto).style.display = 'none';
    document.getElementById("prodotto-qt-"+id_tabella+"-"+id_prodotto).disabled=true;
    document.getElementById("prod-"+id_tabella+"-"+id_prodotto).style.display= 'none';
    totale_ordine += (qt*prezzo);
    
    var text_prod = "";
    if(prodotti_dentro_cassetta === 1){
        text_prod = "prodotto";
    }
    else if(prodotti_dentro_cassetta > 1){
        text_prod = "prodotti";
    }
    document.getElementById("num-prodotti").innerHTML = "<input type=\"text\" name=\"num-prodotti\" value=\""+prodotti_dentro_cassetta+"\" style=\"display:none\"><span style=\"font-size:1.5em; font-weight:bold\">"+prodotti_dentro_cassetta+"</span> "+text_prod+" nella cassetta.<br>Totale cassetta: <span style=\"font-size:1.5em; font-weight:bold\">€ "+roundTo(totale_ordine,2)+"</span>";
    
    var html = "";
    html += "<div class=\"prodotto pari\" style=\"padding:5px\" id=\"prodotto-"+id_tabella+"-"+id_prodotto+"\">";
    html +=     "<input type=\"text\" name=\"id-tabella-"+prodotti_dentro_cassetta+"\" value=\""+id_tabella+"\" style=\"display:none\"> ";
    html +=     "<input type=\"text\" name=\"id-prodotto-"+prodotti_dentro_cassetta+"\" value=\""+id_prodotto+"\" style=\"display:none\"> ";
   
    html +=     "<table style=\"width:100%\" cellpadding=\"3\" >";
    html +=         "<tr >";
    html +=             "<td rowspan=\"2\" class=\"td-immagine\"><div class=\""+id+"\" style=\"background-position:-"+left+"px -"+top+"px; height:56px; width:86px\"></div></td>";
    html +=             "<td style=\"font-size:0.8em\">"+nome+"</td>";
    html +=             "<td style=\"width:30px;\">Qt.</td>";
    html +=             "<td rowspan=\"2\" ><input title=\"Rimuovi dalla cassetta\" class=\"remove-button\" onClick=\"remove_Prodotto("+id_tabella+", "+id_prodotto+", "+prezzo+")\" type=\"button\" name=\"togli\" value=\"\"><br><input title=\"Aggiorna quantità\" class=\"refresh-button\" onClick=\"aggiorna_Prodotto("+id_tabella+", "+id_prodotto+", "+prezzo+")\" type=\"button\" name=\"aggiungi\" value=\"\"></td>"; 
    html +=         "</tr>";
    html +=         "<tr>";
    html +=             "<td><strong>€ "+prezzo+"</strong> <span style=\"font-size:0.8em\">a "+unita+"</span></td>";
    html +=             "<td><input id=\"prodotto-aggiunta-qt-"+id_tabella+"-"+id_prodotto+"\" style=\"width:30px; font-size:0.7em\" type=\"text\" value=\""+qt_display+"\" name=\"qt-"+prodotti_dentro_cassetta+"\"></td>";
    html +=         "</tr>";
    html +=     "</table>";
    html += "</div>";    
    
    var content = document.createElement('div');
    content.innerHTML = html;
    document.getElementById("prodotti-aggiunti").appendChild(content);
    
 
}

function remove_Prodotto(id_tabella, id_prodotto, prezzo){
    //La funzione rimuove un elemento dal div
    //tolgo un elemento
    var qt = document.getElementById("prodotto-qt-"+id_tabella+"-"+id_prodotto).value;
   
    prodotti_dentro_cassetta--;
    totale_ordine = totale_ordine - (qt*prezzo);
    
    var text_prod = "";
    if(prodotti_dentro_cassetta == 1){
        text_prod = "prodotto";
    }
    else if(prodotti_dentro_cassetta > 1){
        text_prod = "prodotti";
    }
    else if(prodotti_dentro_cassetta == 0){
        totale_ordine = 0;
    }
    document.getElementById("num-prodotti").innerHTML = "<input type=\"text\" name=\"num-prodotti\" value=\""+prodotti_dentro_cassetta+"\" style=\"display:none\"><span style=\"font-size:1.5em; font-weight:bold\">"+prodotti_dentro_cassetta+"</span> "+text_prod+" nella cassetta.<br>Totale cassetta: <span style=\"font-size:1.5em; font-weight:bold\">€ "+roundTo(totale_ordine,2)+"</span>";
    
    var node = document.getElementById("prodotto-"+id_tabella+"-"+id_prodotto);
    node.parentElement.removeChild(node);
    document.getElementById("button-add-"+id_tabella+"-"+id_prodotto).style.display = 'block';
    document.getElementById("prodotto-qt-"+id_tabella+"-"+id_prodotto).removeAttribute('disabled');
    document.getElementById("prod-"+id_tabella+"-"+id_prodotto).style.display= 'block';
    //document.getElementById("prodotti-aggiunti").removeChild(document.getElementById("prodotto-"+id_tabella+"-"+id_prodotto));
    
}

function aggiorna_Prodotto(id_tabella, id_prodotto, prezzo){
    //la funzione aggiorna il prodotto selezionato
    var qt = document.getElementById("prodotto-qt-"+id_tabella+"-"+id_prodotto).value;
    var new_qt = document.getElementById("prodotto-aggiunta-qt-"+id_tabella+"-"+id_prodotto).value;
    
    //sistemo le quantità ottenute in modo che siano calcolabili
    
    new_qt = new_qt.replace(",", ".");
    var new_qt_display = new_qt;
    qt = parseFloat(qt);
    new_qt = parseFloat(new_qt);
    
    //controlli
    var diff_qt = new_qt - qt;
    
    if(diff_qt > 0){
        //alert("sommo");
        //ho aggiunto della quantita, quindi sommo la differenza
        if(totale_ordine < 0){
            totale_ordine = 0;
        }
        totale_ordine += (diff_qt * prezzo);
    }
    else if(diff_qt < 0){
        //alert("sottraggo");
        //ho tolto della quantità, quindi sottraggo la differenza
         if(totale_ordine < 0){
            totale_ordine = 0;
        }
        totale_ordine = totale_ordine + (diff_qt * prezzo);
    }
    else{
        //non fare niente
    }
    
    document.getElementById("prodotto-aggiunta-qt-"+id_tabella+"-"+id_prodotto).value = new_qt_display;   
    document.getElementById("prodotto-qt-"+id_tabella+"-"+id_prodotto).value = new_qt_display;
    
    var text_prod = "";
    if(prodotti_dentro_cassetta == 1){
        text_prod = "prodotto";
    }
    else if(prodotti_dentro_cassetta > 1){
        text_prod = "prodotti";
    }
    else if(prodotti_dentro_cassetta == 0){
        totale_ordine = 0;
    }
    document.getElementById("num-prodotti").innerHTML = "<input type=\"text\" name=\"num-prodotti\" value=\""+prodotti_dentro_cassetta+"\" style=\"display:none\"><span style=\"font-size:1.5em; font-weight:bold\">"+prodotti_dentro_cassetta+"</span> "+text_prod+" nella cassetta.<br>Totale cassetta: <span style=\"font-size:1.5em; font-weight:bold\">€ "+roundTo(totale_ordine,2)+"</span>";
    
}

function roundTo(value, decimals){
  var i = value * Math.pow(10, decimals);
  i = Math.round(i);
  return i / Math.pow(10, decimals);
}

