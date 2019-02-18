<?php

    include "base_sql.php";

    if(!empty($_SESSION['logged']) && !empty($_SESSION['tunnus'])){
        
        
        $kuski = $_SESSION['tunnus'];
        
        // Osoitteet
        $mista = mysql_real_escape_string($_POST['mista']);
        $mihin = mysql_real_escape_string($_POST['mihin']);
        // Koordinaatit
        $mista_lat = mysql_real_escape_string($_POST['mista_lat']);
        $mista_lon = mysql_real_escape_string($_POST['mista_lon']);
        $mihin_lat = mysql_real_escape_string($_POST['mihin_lat']);
        $mihin_lon = mysql_real_escape_string($_POST['mihin_lon']);
        
        // Aika
        $milloin = mysql_real_escape_string($_POST['milloin']);
        
        
        
        // Matkustajat
        $matkustajat = (int) mysql_real_escape_string($_POST['matkustajat']);
        
        $koordinaatit = array($mista_lat, $mista_lon, $mihin_lat, $mihin_lon);
        
        $koordinaatit_array = muokkaa_koordinaatit($koordinaatit);
        
        
        $mista_array = muokkaa_osoite($mista);
        $mihin_array = muokkaa_osoite($mihin);
        
        
        if(!empty($mista_array) && !empty($mihin_array) && !empty($milloin) && (0 < $matkustajat) && ($matkustajat < 11)){
            
            $milloin = muokkaa_aika($milloin);
            
            $lisaa_kyyti = sprintf("Insert INTO japkettu_kyydit (kuski,
                                   kaupunki_lahtopaikka, lat_lahtopaikka, lon_lahtopaikka, osoite_lahtopaikka, postinumero_lahtopaikka,
                                   kaupunki_maaranpaa, lat_maaranpaa, lon_maaranpaa, osoite_maaranpaa, postinumero_maaranpaa,
                                   lahto_aika, max_matkustajat)
                                      VALUES ('%s', '%s', %.12f, %.12f, '%s', '%s',
                                      '%s', %.12f, %.12f, '%s', '%s',
                                      '%s', %d);",
                                      $kuski,
                                      $mista_array['kaupunki'],
                                      $koordinaatit_array['mista_lat'], $koordinaatit_array['mista_lon'],
                                      $mista_array['osoite'], $mista_array['postinumero'],
                                      $mihin_array['kaupunki'],
                                      $koordinaatit_array['mihin_lat'], $koordinaatit_array['mihin_lon'],
                                      $mihin_array['osoite'], $mihin_array['postinumero'],
                                      $milloin,
                                      $matkustajat);
            
                
            $output = mysql_query($lisaa_kyyti);
            
/*+-------------------------+-------------+------+-----+---------+----------------+
| id                      | int(11)     | NO   | PRI | NULL    | auto_increment |
| kuski                   | varchar(50) | NO   |     | NULL    |                |
| kaupunki_lahtopaikka    | varchar(50) | NO   |     | NULL    |                |
| kaupunki_maaranpaa      | varchar(50) | NO   |     | NULL    |                |
| osoite_lahtopaikka      | varchar(50) | NO   |     | NULL    |                |
| osoite_maaranpaa        | varchar(50) | NO   |     | NULL    |                |
| postinumero_lahtopaikka | varchar(5)  | NO   |     | NULL    |                |
| postinumero_maaranpaa   | varchar(5)  | NO   |     | NULL    |                |
| lat_lahtopaikka         | double      | NO   |     | NULL    |                |
| lon_lahtopaikka         | double      | NO   |     | NULL    |                |
| lat_maaranpaa           | double      | NO   |     | NULL    |                |
| lon_maaranpaa           | double      | NO   |     | NULL    |                |
| lahto_aika              | datetime    | NO   |     | NULL    |                |
| max_matkustajat         | int(11)     | NO   |     | NULL    |                |
| ilmoittautuneet         | int(11)     | NO   |     | NULL    |                |
+-------------------------+-------------+------+-----+---------+----------------+*/
            
            if($output){
                
                 echo json_encode(array('status' => 'success','message'=> 'Ilmoittautumisesi on jätetty'));
                
            }else{
                
                echo json_encode(array('status' => 'error','message'=> 'Ilmoittautumisesi epäonnistui'));
                //echo json_encode(array('status' => 'error','message'=> $lisaa_kyyti));
            }
            
            
        }else{
            
            echo json_encode(array('status' => 'error','message'=> 'Ilmoittautumisesi epäonnistui'));
            
        }
    
    
    }else {
        
         include "cookie_error.html";
        
    }
    
    
    // Testaa osoitetiedot, palauttaa tiedot php-array muodossa.
    // Puutteelliset osoitetiedot johtavat virhesanomaan ja toiminnon keskeyttämiseen.
    
    function muokkaa_osoite($osoite){
        
        $pituus = count(explode(",", $osoite));

        if (preg_match('/^[a-z0-9äöåÄÖÅ, .\-]+$/i', $osoite)){  // Korjaile ääkköset -> '/i' ei toimi ääkkösillä

                $kaupunki_tiedot = explode(",", $osoite)[$pituus - 1];                
                $katu_tiedot = explode(",", $osoite)[$pituus - 2];
                
                
                $postinumero = (int) explode(" ", $kaupunki_tiedot)[1];
                $kaupunki = explode(" ", $kaupunki_tiedot)[2];
                
                $tiedot = array(
                                
                                "osoite" => $katu_tiedot,
                                "postinumero" => $postinumero,
                                "kaupunki" => $kaupunki
                                );
                
                
                foreach($tiedot as $x){

                    if(empty($x)){

                            echo json_encode(array('status' => 'error','message'=> 'Puutteelliset osoitetiedot.  Osoitetietojen täytyy sisältää myös postinumero.'));
                            exit(1);
                            
                    } 
                
                }
                
                return $tiedot;
            
        }else{
            
            echo json_encode(array('status' => 'error','message'=> 'Virheellinen osoite.'));
            exit(1);
        }

        
        
        
    }
    
    

    // Testaa että koordinaatit sisältävät vain numeroita tai pisteen. 'Pakkaa' ja palauttaa koordinaatit php-array muodossa.
    
    
    function muokkaa_aika($milloin){
        
        date_default_timezone_set("Europe/Helsinki");


        return date( 'Y-m-d H:i:s', strtotime($milloin));
        
        
        
    }
    
    
    function muokkaa_koordinaatit($koordinaatit){
        
        $regex_paikka = '/^[0-9.]*$/';
        
        
        foreach($koordinaatit as $paikka){

            if(empty($paikka)){
                
                echo json_encode(array('status' => 'error','message'=> 'Paikkatietoja puuttuu. Muistitko raahata ikonia kartalla?'));
                
                exit(1);
                
            }else{
            
                    if(preg_match($regex_paikka, $paikka)){

                        //return true;

                    }else{
            
                        echo json_encode(array('status' => 'error','message'=> 'Virheellinen koordinaatti'));
                        exit(1);
            
                    }
            }
    }
        
        $koordinaatit_array = array(
                                
                                "mista_lat" => doubleval($koordinaatit[0]),
                                "mista_lon" => doubleval($koordinaatit[1]),
                                "mihin_lat" => doubleval($koordinaatit[2]),
                                "mihin_lon" => doubleval($koordinaatit[3])
                                    
                                    );
        
        
        
        return $koordinaatit_array;
    
        
    }


?>
