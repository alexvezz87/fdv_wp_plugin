<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it
    
    function install_fdv_DB(){
       
        try{            
            
            createOrdine();
            createCassetta();                        
            createFrutta_Verdura();         
            createAggiunta_Cassetta();
            createCliente();
            createTableContatti();
            createOrdineCliente();
            createArticoloOrdine();
            return true;
                        
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
    function createOrdineCliente(){
        //istanzio la variabile globale per il database
        global $wpdb;
        //equire_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //do un prefisso alle tabelle che vado a creare
        $wpdb->prefix = 'wp_fdv_';
        try{
            //creazione tabella OrdineCliente
            if (!empty ($wpdb->charset))
                $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
            if (!empty ($wpdb->collate))
                $charset_collate .= " COLLATE {$wpdb->collate}";
            
            $table_ordine_cliente = $wpdb->prefix.'Ordine_Cliente';
            $query_crea_ordine_cliente = "CREATE TABLE IF NOT EXISTS $table_ordine_cliente(
                                ID INT NOT NULL auto_increment PRIMARY KEY, 
                                ID_Cliente INT NOT NULL,
                                ID_Ordine INT NOT NULL,
                                Costo_Totale VARCHAR(50) NOT NULL,
                                Costo_Finale VARCHAR(50),
                                Data_Ordine DATETIME NOT NULL,
                                Note TEXT
                        );{$charset_collate}";
            
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($query_crea_ordine_cliente);   
            
            return true;
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
    function createArticoloOrdine(){
        //istanzio la variabile globale per il database
        global $wpdb;
        //equire_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //do un prefisso alle tabelle che vado a creare
        $wpdb->prefix = 'wp_fdv_';
        
        try{
            //creazione della tabella ArticoloOrdine
            if (!empty ($wpdb->charset))
                $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
            if (!empty ($wpdb->collate))
                $charset_collate .= " COLLATE {$wpdb->collate}";
                
            $table_articolo_ordine = $wpdb->prefix.'Articolo_Ordine';
            $query_crea_articolo_ordine = "CREATE TABLE IF NOT EXISTS $table_articolo_ordine (
                            ID INT NOT NULL auto_increment PRIMARY KEY,
                            ID_Prodotto  INT NOT NULL,
                            ID_Tabella  INT NOT NULL,
                            ID_Ordine INT NOT NULL,
                            CP INT,
                            Quantita VARCHAR(20) NOT NULL,
                            Prezzo_Unitario VARCHAR(40) NOT NULL
                    );{$charset_collate}";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($query_crea_articolo_ordine);   
            
            return true;
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
    function createOrdine(){
        
        //istanzio la variabile globale per il database
        global $wpdb;
        //equire_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //do un prefisso alle tabelle che vado a creare
        $wpdb->prefix = 'wp_fdv_';
        
        try{
            //creazione tabella ordine
            if (!empty ($wpdb->charset))
                $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
            if (!empty ($wpdb->collate))
                $charset_collate .= " COLLATE {$wpdb->collate}";
                
            $table_ordine = $wpdb->prefix.'Ordine';
            $query_crea_ordine = "CREATE TABLE IF NOT EXISTS $table_ordine (
                            ID INT NOT NULL auto_increment PRIMARY KEY,
                            Apertura_Ordine DATETIME,
                            Chiusura_Ordine DATETIME,
                            Ordine_Aperto INT DEFAULT 0 NOT NULL                            
                    );{$charset_collate}";
            
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $query_crea_ordine);   
            
            return true;
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
    
    function createAggiunta_Cassetta(){
        //istanzio la variabile globale per il database
        global $wpdb;
        //equire_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //do un prefisso alle tabelle che vado a creare
        $wpdb->prefix = 'wp_fdv_';
        
        try{
             //creazione tabella aggiunta cassetta
           $table_aggiunta_cassetta = $wpdb->prefix.'Aggiunta_Cassetta';
           
           if (!empty ($wpdb->charset))
                $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
            if (!empty ($wpdb->collate))
                $charset_collate .= " COLLATE {$wpdb->collate}";
           
           $query_crea_aggiunta = "CREATE TABLE {$table_aggiunta_cassetta} (
                        ID INT NOT NULL auto_increment PRIMARY KEY,
                        Tipologia_Prodotto VARCHAR(100) NOT NULL,
                        Nome_Prodotto VARCHAR(200) NOT NULL,
                        Prezzo VARCHAR(50),
                        Unita VARCHAR(50),
                        Peso VARCHAR(50),
                        Note TEXT,
                        ID_Foto VARCHAR(300)
                );{$charset_collate}";
           
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $query_crea_aggiunta);  
            
            
            return true;
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
    function createFrutta_Verdura(){
        //istanzio la variabile globale per il database
        global $wpdb;
        //equire_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //do un prefisso alle tabelle che vado a creare
        $wpdb->prefix = 'wp_fdv_';
        
        try{
            //Creazione tabella frutta e verdura
           $table_frutta_verdura = $wpdb->prefix.'Frutta_Verdura';
           
           if (!empty ($wpdb->charset))
                $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
            if (!empty ($wpdb->collate))
                $charset_collate .= " COLLATE {$wpdb->collate}";

          
           $query_crea_frutta_verdura = "CREATE TABLE {$table_frutta_verdura} (
                                    ID INT NOT NULL auto_increment PRIMARY KEY,
                                    Tipologia_Prodotto VARCHAR(100) NOT NULL,
                                    Nome_Prodotto VARCHAR(200) NOT NULL,
                                    Prezzo VARCHAR(50),
                                    Unita VARCHAR(50),
                                    ID_Foto VARCHAR(300)
                            );{$charset_collate}";
           
           require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
           dbDelta( $query_crea_frutta_verdura );  
           return true;
          
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
    function createCassetta(){
        //istanzio la variabile globale per il database
        global $wpdb;
        //equire_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //do un prefisso alle tabelle che vado a creare
        $wpdb->prefix = 'wp_fdv_';
        
        try{
            //Creazione tabella cassetta
            $table_cassetta = $wpdb->prefix.'Cassetta';
           
            if (!empty ($wpdb->charset))
                $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
            if (!empty ($wpdb->collate))
                $charset_collate .= " COLLATE {$wpdb->collate}";
            
            $query_crea_cassetta = "CREATE TABLE {$table_cassetta} (
                            ID INT NOT NULL auto_increment PRIMARY KEY,
                            Tipologia_Cassetta VARCHAR(200) NOT NULL,
                            Num_Prodotti TEXT,
                            Peso VARCHAR(100),
                            Cliente VARCHAR(100),
                            Prezzo VARCHAR(50),
                            ID_Foto VARCHAR(300)
                    );{$charset_collate}";
                    
           //echo $query_crea_cassetta;
           require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
           dbDelta( $query_crea_cassetta );
           return true;
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
    //CREO IL CLIENTE
    function createCliente(){
        //istanzio la variabile globale per il database
        global $wpdb;
        //equire_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //do un prefisso alle tabelle che vado a creare
        $wpdb->prefix = 'wp_fdv_';
        //Creazione della tabelal cliente
        try{
            $table_cliente = $wpdb->prefix.'Cliente';
            
            if (!empty ($wpdb->charset))
                $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
            if (!empty ($wpdb->collate))
                $charset_collate .= " COLLATE {$wpdb->collate}";            
            
            $query_crea_cliente = "CREATE TABLE {$table_cliente} (
                    ID INT NOT NULL auto_increment PRIMARY KEY,
                    username VARCHAR(200),
                    email VARCHAR(100) NOT NULL,
                    tipo VARCHAR(1) NOT NULL,
                    nominativo VARCHAR(300) NOT NULL,
                    codice_fiscale VARCHAR(16),
                    partita_iva VARCHAR(50),
                    sconto VARCHAR(50)
                );{$charset_collate}";
                
           require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
           dbDelta( $query_crea_cliente );
           return true;
        }
        catch(Exception $e){
            _e($e);
            
        }
    }
    
    function createTableContatti(){
        //istanzio la variabile globale per il database
        global $wpdb;
        //equire_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //do un prefisso alle tabelle che vado a creare
        $wpdb->prefix = 'wp_fdv_';
        //creazione della tabella contatti
        try{
            $table_contatti = $wpdb->prefix.'Contatti';
            if (!empty ($wpdb->charset))
                $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
            if (!empty ($wpdb->collate))
                $charset_collate .= " COLLATE {$wpdb->collate}";
                
            $query_crea_contatti = "CREATE TABLE {$table_contatti} (
                        ID INT NOT NULL auto_increment PRIMARY KEY,
                        email VARCHAR(100) NOT NULL,
                        ind_fatt_via VARCHAR(200),
                        ind_fatt_civ VARCHAR(10),
                        ind_fatt_cap VARCHAR(10),
                        ind_fatt_cit VARCHAR(200),
                        ind_fatt_prv VARCHAR(100),
                        ind_sped_via VARCHAR(200),
                        ind_sped_civ VARCHAR(10),
                        ind_sped_cap VARCHAR(10),
                        ind_sped_cit VARCHAR(200),
                        ind_sped_prv VARCHAR(100),
                        telefono VARCHAR(50),
                        cellulare VARCHAR(50),
                        fax VARCHAR(50)                        
                        );{$charset_collate}"; 
                        
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
           dbDelta( $query_crea_contatti );
           return true;                        
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
     
    function dropTableOrdineCliente(){
        //Cancello la tabella OrdineCliente
        global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."Ordine_Cliente;";
            $wpdb->query($query);
            return true;
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
    function dropTableArticoloOrdine(){
        //cancello la tabella Articolo_Ordine
        global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."Articolo_Ordine;";
            $wpdb->query($query);
            return true;
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
    function dropTableContatti(){
        //Cancello la tabella Contatti
        global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."Contatti;";
            $wpdb->query($query);
            return true;
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
    
    function dropTableCliente(){
        //Cancello la tabella Cliente
         global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."Cliente;";
            $wpdb->query($query);
            
            return true;
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }

    
    function dropTableOrdine(){
        //La funzione cancella la tabella Ordine
         global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."Ordine;";
            $wpdb->query($query);
            
            return true;
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
    function dropTableCassetta(){
        //la funzione cancella la tabella Cassetta
         global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        try{
            
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."Cassetta;";
            $wpdb->query($query);
            
           
            return TRUE;
        }
        catch(Exception $e){
            _e($e);
            return FALSE;
        }
        
    }
    
    function dropTableAggiuntaCassetta(){
        //la funzione cancella la tabella Aggiunta_Cassetta
         global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        try{
            
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."Aggiunta_Cassetta;";
            $wpdb->query($query);
		
            return TRUE;
        }
        catch(Exception $e){
            _e($e);
            return FALSE;
        }
    }
    
    function dropTableFrutta_Verdura(){
        //la funzione cancella la tabella Frutta_Verdura
         global $wpdb;
        $wpdb->prefix = "wp_fdv_";
        try{
            
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."Frutta_Verdura;";
            $wpdb->query($query);
            return TRUE;
        }
        catch(Exception $e){
            _e($e);
            return FALSE;
        }
    }
    
?>