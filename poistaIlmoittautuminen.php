<?php

    include "base_sql.php";
    
    if(!empty($_SESSION['logged']) && !empty($_SESSION['tunnus'])){
        
        $tunnus = $_SESSION['tunnus'];
        
        $kyyti_id = (int) mysql_real_escape_string($_POST['kyyti_id']);
        
        if(!empty($kyyti_id)){
            
            
            $tarkista_poisto = sprintf("SELECT * from japkettu_ilmoittautumiset where tunnus='%s' AND kyyti_id='%d';", $tunnus, $kyyti_id);
            
            $tarkistus = mysql_query($tarkista_poisto);
            
            $tiedot= hae_kyytitiedot($kyyti_id);
        
            
            // Jos id löytyy
            
            
            if(mysql_num_rows($tarkistus) == 1){
                
                
                $poista_ilmoittautuminen = sprintf("DELETE FROM japkettu_ilmoittautumiset WHERE kyyti_id='%d' AND tunnus='%s';", $kyyti_id, $tunnus);
                                      
            
                $output = mysql_query($poista_ilmoittautuminen);
                
                
                if($output){
                
                    echo "Poisto  Ok";
                
                }else{
                
                    echo "Database-error";
                }
            
                
                
                $tiedot['ilmoittautuneet'] -= 1;
                
                paivita_ilmoittautuneet($kyyti_id, $tiedot['ilmoittautuneet']);
                
                
                
                
            }else{
                
                echo "Poistettavaa ei löytynyt";
                
                
            }
            
            
        }
        
        }
    
     function hae_kyytitiedot($kyyti_id){
        
        // | id | kuski    | lahtopaikka | maaranpaa | max_matkustajat | ilmoittautuneet |
        
        $hae_ilmoittautuneet = sprintf("SELECT * FROM japkettu_kyydit WHERE id='%d'", $kyyti_id);
            
        $haku = mysql_query($hae_ilmoittautuneet);
        
        $row = mysql_fetch_array($haku);
        
        
        $kyyti_tiedot = array(
            
            "id"              => $row['id'],
            "kuski"           => $row['kuski'],
            "lahtopaikka"     => $row['lahtopaikka'],
            "maaranpaa"       => $row['maaranpaa'],
            "max_matkustajat" => $row['max_matkustajat'],
            "ilmoittautuneet" => $row['ilmoittautuneet'],
        );
         
         
         return $kyyti_tiedot;
        
    }
    
    
    
    function paivita_ilmoittautuneet($kyyti_id, $ilmoittautuneet){
        
        
        $paivita_ilmoittautuneet = sprintf("UPDATE japkettu_kyydit SET ilmoittautuneet=%d WHERE id=%d", $ilmoittautuneet, $kyyti_id);
        
        
        $output = mysql_query($paivita_ilmoittautuneet);
                
                
            
        if($output){
                
            echo "Päivitys ok";
                
        }else{
                
            echo "Database-error";
        
        }
        
        
    }
    

?>