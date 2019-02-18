<?php

    //include "base_sql.php";

    if(!empty($_SESSION['logged']) && !empty($_SESSION['tunnus'])){
              
        $tunnus = $_SESSION['tunnus'];
        
        $hae_kyydit = sprintf("SELECT * FROM japkettu_kyydit");
            
        $result = mysql_query($hae_kyydit);
        
        $maara_kyydit = mysql_num_rows($result);
        
        ?>
        
        <!--HTML koodia -->
        
        <script>$(hae_kyydit());</script>
        <div class="container">
        <div class="row">
            
        <div id="dataTable"></div>
        
        </div>
            
        <div class="row">
            
        
              <div id="map" class="img-rounded"></div>
            
            
       
    
            
        </div> <!--row-->
        
        </div> <!--container-->
              
       
                
            
            
            
            
        
        
       
<?php
    
    }else{
        
       include "cookie_error.html";
        
    }
    
    // Hakee henkilön ilmoittautumiset, palauttaa kyyti_id-listan.
    
    
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
