<?php

   /**
 * @package gestione_ordine
 */
/*
Plugin Name: Gestione Ordine
Plugin URI: http://www.fruttidafavola.it
Description: Questo è un plugin personalizzato per fruttidafavola.it che permette la gestione del listino dei prodotti settimanali mediante il caricamento di un file .xls il suo scompattamento ed il caricamento dei dati ottenuti nel database. Viene anche gestito l'ordine mediante un sistema di carrello, senza però che avvenga il pagamento online momentaneamente.
Version: 1.0
Author: Alex Soluzioni Web
Author URI: http://www.alexsoluzioniweb.it/
License: GPLv2 or later
*/

    
    require_once 'salva_listino.php';
    require_once 'visualizza_listino.php';
    require_once 'classi/getClassi.php';
    require_once 'functions.php';
    
    
    //indico la cartella dove è contenuto il plugin
    require_once (dirname(__FILE__) . '/gestione_ordine.php');
    
    
     //Aggiungo il file di stile CSS al plugin
    add_action( 'wp_enqueue_scripts', 'register_plugin_styles_gestione_ordine' );
    add_action( 'admin_enqueue_scripts', 'register_plugin_styles_gestione_ordine' );
    
    //richiamo lo stile
    function register_plugin_styles_gestione_ordine() {
        wp_register_style( 'frutti_da_favola_css', plugins_url('gestione_ordine/css/style.css') );
        wp_enqueue_style('frutti_da_favola_css');
    }
    
    //Aggiungo il file di Javascript al plugin
    add_action( 'wp_enqueue_scripts', 'register_my_script_gestione_ordine' );
    
    function register_my_script_gestione_ordine(){
         wp_register_script('functions-js', plugins_url('gestione_ordine/script/functions.js'));
         wp_register_script('jquery-1.10.2', plugins_url('gestione_ordine/script/jquery-1.10.2.min.js'));
         wp_enqueue_script('functions-js');
         wp_enqueue_script('jquery-1.10.2');
    }    
    
    //Quando attivo il plugin creo le tabelle necessarie nel database
    register_activation_hook(__FILE__,'install_Listino');
    
    function install_Listino(){
        //installo le tabelle
        install_fdv_DB();
    }
    
    //Quando disattivo il plugin rimuovo le tabelle dal database
    register_deactivation_hook( __FILE__, 'remove_Listino');
    
    function remove_Listino(){
        //Rimuovo le tabelle
        
        dropTableCassetta();
        dropTableAggiuntaCassetta();
        dropTableFrutta_Verdura();
        dropTableOrdine();
        dropTableCliente();
        dropTableContatti();
        dropTableOrdineCliente();
        dropTableArticoloOrdine();
    }
    
    
    
    //register_activation_hook( __FILE__ , 'install');
    
   
    function salva_listino2(){   
            
         include 'pages/salva_listino.php';
    }
    
   /* function add_option_page(){
        
        add_menu_page('Gestione Ordine', 'Gestione Ordine', 'administrator', 'gestione_listino', 'salva_listino2');
    }
    
   add_action( 'admin_menu', 'add_option_page' );   
    */
    
   add_shortcode('visualizza_listino', 'visualizza_list');
   function visualizza_list(){
        global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        
         visualizza_listino($wpdb);
        
    }
    
    
    add_shortcode('visualizza_cassetta', 'visualizza_cass');
    function visualizza_cass(){
        global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        visualizza_cassetta($wpdb);
    }
    
    add_shortcode('visualizza_frutta_verdura', 'visualizza_f_v');
    function visualizza_f_v(){
        global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        visualizza_frutta_verdura($wpdb);
    }
    
    add_shortcode('visualizza_aggiunta_cassetta', 'visualizza_a_c');
    function visualizza_a_c(){
        global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        visualizza_aggiunta_cassetta($wpdb);
    }
    
    
    
    add_action('init', 'update');
    function update(){
       
    }
    
    add_shortcode('registrazioneUtente', 'registrazione_utente');
    
    function registrazione_utente(){
        include 'pages/registrazione-utente.php';
    }
    
    add_shortcode('ElaboraOrdine', 'elabora_ordine');
    
    function elabora_ordine(){
        include 'pages/elabora_ordine.php';
    }
    
    //PAGINE PER ORDINARE
    
    add_shortcode('paginaListino', 'pagina_listino');
    
    function pagina_listino(){
        global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        $lista_ordine = new ListaOrdine($wpdb);
        $v_lista_ordine = new ViewListaOrdine($lista_ordine);
        if($v_lista_ordine->getOrdineStatus($v_lista_ordine->getIDUltimoOrdine())==1){
           include 'pages/fai_la_spesa_1.php';
        }
        else{
            _e('Al momento non è possibile effettuare ordinazioni. Vi preghiamo di attendere.<br><br>Ci scusiamo per il disagio.<br>Lo staff di Frutti da Favola');
        }
        
    }
    
    add_shortcode('paginaAggiunteCassetta', 'pagina_aggiunte_cassetta');
    
    function pagina_aggiunte_cassetta(){
        include 'pages/fai_la_spesa_2.php';
    }
    
    add_shortcode('ListenerSpedizione', 'listener_spedizione');
    
    function listener_spedizione(){
        include 'pages/fai_la_spesa_3.php';
    }
    
    add_shortcode('ConfermaOrdine', 'conferma_ordine');
    
    function conferma_ordine(){
        include 'pages/fai_la_spesa_4.php';
    }
    
    add_shortcode('GuidaSpedizione', 'guida_spedizione');
    function guida_spedizione(){
        include 'pages/guida_step_3.php';
    }
    
    
    //FINE PAGINE PER ORDINARE
    
   
  /*  function gestione_menu_fdv(){
        add_menu_page('Gestione Ordine', 'Gestione Ordine', 'frutti_da_favola_admin', 'gestione_listino', 'add_pagina_fdv_admin', plugins_url('images/icon.fw.png', __FILE__),9);
        //add_submenu_page('gestione_listino', 'Gestione Ordine',  'Gestione Ordine', 'administrator', 'aggiorna_ordine', 'add_pagina_amministratore');
        add_submenu_page('gestione_listino', 'Riepilogo Ordini',  'Riepilogo Ordini', 'edit_plugins', 'riepilogo_ordini', 'add_pagina_riepilogo_ordini_fdv_admin');
        add_submenu_page('gestione_listino', 'Riepilogo Clienti', 'Riepilogo Clienti', 'edit_plugins', 'riepilogo_clienti', 'add_pagina_riepilogo_clienti_fdv_admin');
    }
  */  
     function gestione_ordine_menu(){
        add_menu_page('Gestione Ordine', 'Gestione Ordine', 'edit_plugins', 'gestione_listino', 'add_pagina_amministratore', plugins_url('images/icon.fw.png', __FILE__),9);
        //add_submenu_page('gestione_listino', 'Gestione Ordine',  'Gestione Ordine', 'administrator', 'aggiorna_ordine', 'add_pagina_amministratore');
        add_submenu_page('gestione_listino', 'Riepilogo Ordini',  'Riepilogo Ordini', 'edit_plugins', 'riepilogo_ordini', 'add_pagina_riepilogo_ordini');
        add_submenu_page('gestione_listino', 'Riepilogo Clienti', 'Riepilogo Clienti', 'edit_plugins', 'riepilogo_clienti', 'add_pagina_riepilogo_clienti');
                
    }
    
    function menu_clienti(){
        add_menu_page('Ordini', 'Ordini', 'customer', 'ordini', 'add_pagina_ordini_cliente', plugins_url('images/icon.fw.png', __FILE__),15);
        add_menu_page('Cliente', 'Info Cliente', 'customer', 'cliente', 'add_pagina_cliente', plugins_url('images/icon.fw.png', __FILE__),16);
    }
    
    function add_pagina_cliente(){
        include 'pages/pagina_cliente.php';
    }
    function add_pagina_ordini_cliente(){
        include 'pages/ordini_cliente.php';
    }
    
    function add_pagina_amministratore(){
        salva_listino2();
    }
    function add_pagina_riepilogo_ordini(){
        include 'pages/riepilogo_ordini.php';
    }
    function add_pagina_riepilogo_clienti(){
        include 'pages/riepilogo_clienti.php';
    }
    
            
    add_action( 'admin_menu', 'menu_clienti');    
    
    add_action( 'admin_menu', 'gestione_ordine_menu' ); 
    
    
    //faccio partire la sessione
    function register_session(){
        if( !session_id())
            session_start();
    }
    add_action('init','register_session');
  
?>


