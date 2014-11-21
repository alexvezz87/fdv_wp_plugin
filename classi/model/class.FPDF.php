<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

    //define('FPDF_FONTPATH', plugins_url().'/gestione_ordine/classi/model/fpdf/font/');
    
    //questo file e la cartella font si trovano nella stessa directory
    require('fpdf/fpdf.php');
    
    class MyFPDF extends FPDF{
        public function __construct() {
            parent::FPDF();           
           
        }
        
        
        public function TwoColumnsTable($header, $data){
            // Header
            $col_header = 0;
            while($col_header < count($header)){
                if($col_header == 0){
                    $this->Cell(30,7,$header[$col_header],1);
                }
                else if($col_header == 1){
                     $this->Cell(110,7,$header[$col_header],1);
                }
                               
                $col_header++;
            }
         
            $this->Ln();
            // Data
            
            $count_rows = 0;
            while($count_rows < count($data)){
                $count_col = 0;
                while($count_col < count($data[$count_rows])){
                    if($count_col == 0){
                        $this->Cell(30,7,$data[$count_rows][$count_col],1);
                    }
                    else if($count_col == 1){
                        $this->Cell(110,7,$data[$count_rows][$count_col],1);
                    }
                    $count_col++;
                }
                $this->Ln();
                $count_rows++;
            }
        }
        
        public function BasicTable($header, $data){
            // Header
            $col_header = 0;
            while($col_header < count($header)){
                if($col_header == 0){
                    $this->Cell(25,7,$header[$col_header],1);
                }
                else if($col_header == 1){
                    $this->Cell(30,7,$header[$col_header],1);
                }
                else if($col_header == 2){
                     $this->Cell(110,7,$header[$col_header],1);
                }
                else if($col_header == 3){
                    $this->Cell(15,7,$header[$col_header],1);
                }
                else{
                    $this->Cell(40,7,$header[$col_header],1);
                }
                
                $col_header++;
            }
         
            $this->Ln();
            // Data
            
            $count_rows = 0;
            while($count_rows < count($data)){
                $count_col = 0;
                while($count_col < count($data[$count_rows])){
                    if($count_col == 0){
                        $this->Cell(25,7,$data[$count_rows][$count_col],1);
                    }
                    else if($count_col == 1){
                        $this->Cell(30,7,$data[$count_rows][$count_col],1);
                    }
                    else if($count_col == 2){
                        $this->Cell(110,7,$data[$count_rows][$count_col],1);
                    }
                    else if($count_col == 3){
                        $this->Cell(15,7,$data[$count_rows][$count_col],1);
                    }
                    else {
                        $this->Cell(40,7,$data[$count_rows][$count_col],1);
                    }
                    
                    
                    $count_col++;
                }
                $this->Ln();
                $count_rows++;
            }
          
             
        }
        
        public function BasicTableOrdine($header, $data){
            // Header
            $col_header = 0;
            while($col_header < count($header)){
                if($col_header == 0){
                    $this->Cell(30,7,$header[$col_header],1);
                }
                else if($col_header == 1){
                    $this->Cell(100,7,$header[$col_header],1);
                }
                else if($col_header == 3){
                     $this->Cell(30,7,$header[$col_header],1);
                }
                else if($col_header == 2){
                     $this->Cell(15,7,$header[$col_header],1);
                }
                
                else{
                    $this->Cell(40,7,$header[$col_header],1);
                }
                
                $col_header++;
            }
         
            $this->Ln();
            // Data
            
            $count_rows = 0;
            while($count_rows < count($data)){
                $count_col = 0;
                while($count_col < count($data[$count_rows])){
                    if($count_col == 0){
                        $this->Cell(30,7,$data[$count_rows][$count_col],1);
                    }
                    else if($count_col == 1){
                        $this->Cell(100,7,$data[$count_rows][$count_col],1);
                    }
                    else if($count_col == 3){
                        $this->Cell(30,7,$data[$count_rows][$count_col],1);
                    }
                    else if($count_col == 2){
                        $this->Cell(15,7,$data[$count_rows][$count_col],1);
                    }
                    
                    else {
                        $this->Cell(40,7,$data[$count_rows][$count_col],1);
                    }
                    
                    
                    $count_col++;
                }
                $this->Ln();
                $count_rows++;
            }
          
             
        }
        
        function Footer()
        {
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Page number
            $this->Cell(0,10,'Page '.$this->PageNo().'',0,0,'C');
        }
        
    }
?>