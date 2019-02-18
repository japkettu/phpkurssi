<?php

        include "base_sql.php";

         if(!empty($_SESSION['logged']) && !empty($_SESSION['tunnus'])){
        
            $kyyti_id = (int) mysql_real_escape_string($_POST['kyyti_id']);
            
            
            $kyyti_tiedot = hae_kyytitiedot($kyyti_id);
            
            
            echo json_encode($kyyti_tiedot);
        
        
         }
         
         
         
    function hae_kyytitiedot($kyyti_id){
        
       
        
        $hae_ilmoittautuneet = sprintf("SELECT * FROM japkettu_kyydit WHERE id='%d'", $kyyti_id);
            
        $haku = mysql_query($hae_ilmoittautuneet);
        
        $row = mysql_fetch_array($haku);
        
        
        $kyyti_tiedot = array(
            
            "id"                       => $row['id'],
            "kuski"                    => $row['kuski'],
            "kaupunki_lahtopaikka"     => $row['kaupunki_lahtopaikka'],
            "kaupunki_maaranpaa"       => $row['kaupunki_maaranpaa'],
            "osoite_lahtopaikka"       => $row['osoite_lahtopaikka'],
            "osoite_maaranpaa"         => $row['osoite_maaranpaa'],
            "lat_lahtopaikka"          => $row['lat_lahtopaikka'],
            "lon_lahtopaikka"          => $row['lon_lahtopaikka'],
            "lat_maaranpaa"            => $row['lat_maaranpaa'],
            "lon_maaranpaa"            => $row['lon_maaranpaa'],
            "lahto_aika"               => $row['lahto_aika'],
            "max_matkustajat"          => $row['max_matkustajat'],
            "ilmoittautuneet"          => $row['ilmoittautuneet'],
        );
         
         
         return $kyyti_tiedot;
        
    }

?>