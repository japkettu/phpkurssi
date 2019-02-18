<?php

    include "base_sql.php";

    if(!empty($_SESSION['logged']) && !empty($_SESSION['tunnus'])){
        
        $tunnus = $_SESSION['tunnus'];
        $kyyti_id = (int) mysql_real_escape_string($_POST['kyyti_id']);
        
        if(!empty($kyyti_id)){
            
            $tarkista_ilmoittautuminen = sprintf("SELECT * FROM japkettu_ilmoittautumiset WHERE tunnus='%s' AND kyyti_id='%d'", $tunnus, $kyyti_id);
            
            $tarkistus = mysql_query($tarkista_ilmoittautuminen);
            
            $tiedot= hae_kyytitiedot($kyyti_id);
            
            
            
            if($tiedot['ilmoittautuneet'] == $tiedot['max_matkustajat']){
                
                echo json_encode(array('status' => 'error','message'=> 'Ilmoittautumisesi epäonnistui.'));
                break;
            }
            
            if($tiedot['kuski'] == $tunnus){
                
                echo json_encode(array('status' => 'error','message'=> 'Ilmoittautumisesi epäonnistui.'));
                break;
            }
            
            
            if(mysql_num_rows($tarkistus) == 0){
                
                
                $lisaa_ilmoittautuminen = sprintf("Insert INTO japkettu_ilmoittautumiset (kyyti_id, tunnus)
                                      VALUES ('%d','%s');", $kyyti_id, $tunnus);
            
                $output = mysql_query($lisaa_ilmoittautuminen);
                
                
            
                if($output){
                
                   echo json_encode(array('status' => 'success','message'=> 'Ilmoittautumisesi on jätetty.'));
                
                }else{
                
                    echo json_encode(array('status' => 'error','message'=> 'No output Ilmoittautumisesi epäonnistui.'));
                }
            
                
                $tiedot['ilmoittautuneet'] += 1;
                
                paivita_ilmoittautuneet($kyyti_id, $tiedot['ilmoittautuneet']);
                
                
                
                
            }else{
                
               echo json_encode(array('status' => 'error','message'=> 'Ilmoittautumisesi epäonnistui.'));
                
                
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
                
            //echo "Päivitys ok";
                
        }else{
                
            //echo "Database-error";
        
        }
        
        
    }


?>
