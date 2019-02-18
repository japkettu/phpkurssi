<?php

    include "base_sql.php";
    
    if(!empty($_SESSION['logged']) && !empty($_SESSION['tunnus'])){
        
        $tunnus = $_SESSION['tunnus'];
        
        $kyyti_id = (int) mysql_real_escape_string($_POST['kyyti_id']);
        
        if(!empty($kyyti_id)){
            
            
            $tarkista_poisto = sprintf("SELECT * FROM japkettu_kyydit WHERE kuski='%s' AND id='%d'", $tunnus, $kyyti_id);
            
            $tarkistus = mysql_query($tarkista_poisto);
            
            $tiedot= hae_kyytitiedot($kyyti_id);
            
            
            if($tiedot['kuski'] != $tunnus){
                
                echo "Et voi poistaa toisten tietoja !";
                break;
            }
            
            // Jos id löytyy
            
            
            if(mysql_num_rows($tarkistus) == 1){
                
                
                $poista_kyyti = sprintf("DELETE from japkettu_kyydit where id='%d';", $kyyti_id);
                                      
            
                $output = mysql_query($poista_kyyti);
                
                
                if($output){
                
                    echo "Poisto  Ok";
                
                }else{
                
                    echo "Database-error";
                }
            
                
                
                poista_ilmoittautuneet($kyyti_id);
                
                
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
    
    
    
    function poista_ilmoittautuneet($kyyti_id){
        
        
        
        $poista_ilmoittautuneet = sprintf("DELETE from japkettu_ilmoittautumiset where kyyti_id='%d' ;", $kyyti_id);
                                      
            
        $output = mysql_query($poista_ilmoittautuneet);
                
                
        if($output){
                
                    //echo "Poisto  Ok";
                
        }else{
                
                    //echo "Database-error";
        }
        
        
        
        
    }
    
 
    
    

?>