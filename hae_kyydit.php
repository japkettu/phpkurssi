<?php


     include "base_sql.php";

         if(!empty($_SESSION['logged']) && !empty($_SESSION['tunnus'])){
        
            //$kyyti_id = (int) mysql_real_escape_string($_POST['kyyti_id']);
            
            
            $kyydit = hae_kaikki();
            
            
            echo json_encode($kyydit);
        
        
         }


    function hae_kaikki(){
        
        
        $hae_kyydit = sprintf("SELECT * FROM japkettu_kyydit");
            
        $haku = mysql_query($hae_kyydit);
        
        $kyydit = array();
        
        while ($row = mysql_fetch_array($haku)){
        
        $kyyti_tiedot = array(
            
            "id"                       => $row['id'],
            "kuski"                    => $row['kuski'],
            "kaupunki_lahtopaikka"     => $row['kaupunki_lahtopaikka'],
            "kaupunki_maaranpaa"       => $row['kaupunki_maaranpaa'],
            "osoite_lahtopaikka"       => $row['osoite_lahtopaikka'],
            "osoite_maaranpaa"         => $row['osoite_maaranpaa'],
            "postinumero_lahtopaikka"  => $row['postinumero_lahtopaikka'],
            "postinumero_maaranpaa"    => $row['postinumero_maaranpaa'],
            "lat_lahtopaikka"          => $row['lat_lahtopaikka'],
            "lon_lahtopaikka"          => $row['lon_lahtopaikka'],
            "lat_maaranpaa"            => $row['lat_maaranpaa'],
            "lon_maaranpaa"            => $row['lon_maaranpaa'],
            "lahto_aika"               => $row['lahto_aika'],
            "max_matkustajat"          => $row['max_matkustajat'],
            "ilmoittautuneet"          => $row['ilmoittautuneet'],
        );
        
        $kyyti_tiedot['status'] =  hae_status($row, $_SESSION['tunnus']); // Lisää painikkeen: kuski, täynnä, vapaa, valittu
        
        array_push($kyydit, $kyyti_tiedot);
         
        }
         
         return $kyydit;
        
    }






    function hae_status($row, $tunnus){
        
            
            $ilmoittautumiset = hae_ilmoittautumiset($tunnus);
            
           if($tunnus == $row['kuski']){
                
                // Kuski
                
                return "<button disabled class='btn btn-warning btn-sm btn-block' >Kuski</button>";
            
            }
            else if(in_array($row['id'], $ilmoittautumiset)){
                                               
                // Jo lmoittauduttu
                
                return "<button disabled class='btn btn-primary btn-sm btn-block' >Valittu</button>";
            
            
            }else if($row['max_matkustajat'] == $row['ilmoittautuneet']){
                
                // Täynnä
                return "<button disabled class='btn btn-danger btn-sm btn-block' >Täynnä</button>";
            
            }else{
                
                // Vapaa
                
                return "<button class='btn btn-success btn-sm btnVapaa btn-block' >Ilmoittaudu</button>";
            
            }
        
        
        
    }
    
    
    
     function hae_ilmoittautumiset($tunnus){
        
        
        $hae_omat = sprintf("SELECT kyyti_id FROM japkettu_ilmoittautumiset WHERE tunnus='%s'", $tunnus);
            
        $haku = mysql_query($hae_omat);
        
        $ilmoittautumiset = array();
        
        while ($row = mysql_fetch_array($haku)){
        
            array_push($ilmoittautumiset, $row['kyyti_id']);
       
        }
        
        return $ilmoittautumiset;
        
    }


?>