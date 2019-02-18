<?php

    include "base_sql.php";?>
    
    <div class="container">
      <div class="row">
        
        
<?php
    if(!empty($_SESSION['logged']) && !empty($_SESSION['tunnus'])){

        $tunnus = $_SESSION['tunnus'];
        
        $hae_ajot = sprintf("SELECT * FROM japkettu_kyydit WHERE kuski='%s'", $tunnus);
            
        $omat_ajot = mysql_query($hae_ajot);
        
        $ajot = mysql_num_rows($omat_ajot); 
        $ilmoittautumiset = hae_ilmoittautumiset($tunnus);
                     ?>
        <ul class="nav nav-tabs">
            <li class="active"><a id="tab-kuski" data-toggle="tab" href="#kuski">Kuski (<?php echo $ajot; ?>)</a></li>
            <li ><a id="tab-matkustaja" data-toggle="tab" href="#matkustaja">Matkustaja (<?php echo count($ilmoittautumiset);?>)</a></li>
            
        </ul>
      
        <div class="tab-content">
            <div id="kuski" class="tab-pane fade in active">
        <?php
        
            tulostaAjot($omat_ajot);
        ?>
        
         </div>
        
        <div id="matkustaja" class="tab-pane fade">
            
<?php
        
        // Tulostaa ilmoittautumiset.
       
            tulostaIlmoittautumiset($ilmoittautumiset);
            
            ?>
        
            
        </div>
        
            </div>
        </div>
    </div>

   <?php }else{
        
        include "cookie_error.html";
        
    }
    
      function tulostaAjot($omat_ajot){

         echo"<table class='table table-hover'>";
         echo   "<thead>
                    <tr>
                    <th>Id</th>
                    <th>Kuski</th>
                    <th>Lähtöpaikka</th>
                    <th>Määränpää</th>
                    <th>Ilmoittautuneet</th>
                    </tr>
                </thead>";
                
                
                // <tr data-toggle="modal" data-id="3" data-target="#orderModal">
                
        while($row = mysql_fetch_array($omat_ajot)){
            
            echo "<tr><td>" . $row['id'] . "</td><td>" . $row['kuski'] . "</td><td>" . $row['kaupunki_lahtopaikka'] ."</td><td>" .$row['kaupunki_maaranpaa'] ."</td><td>".$row['ilmoittautuneet']."/".$row['max_matkustajat']."</td><td><button class='btn btn-danger btn-sm btnPoistaKuski' >Poista</button></td><tr>";
            
            
        }
        
        echo"</table>";
        
    }
    
   
    function tulostaIlmoittautumiset($ilmoittautumiset){
        
        echo"<table class='table table-hover'>";
        echo   "<thead>
                    <tr>
                    <th>Id</th>
                    <th>Kuski</th>
                    <th>Lähtöpaikka</th>
                    <th>Määränpää</th>
                    <th>Ilmoittautuneet</th>
                    </tr>
                </thead>";
                
                
        foreach( $ilmoittautumiset as $i ){        
            
            
            $haku = sprintf("SELECT * FROM japkettu_kyydit WHERE id='%d'", $i);
            
            $ilmoittautuminen = mysql_query($haku);
        
            
            while($row = mysql_fetch_array($ilmoittautuminen)){
            
                echo "<tr><td>" . $row['id'] . "</td><td>" . $row['kuski'] . "</td><td>" . $row['kaupunki_lahtopaikka'] ."</td><td>" .$row['kaupunki_maaranpaa'] ."</td><td>".$row['ilmoittautuneet']."/".$row['max_matkustajat']."</td><td><button class='btn btn-danger btn-sm btnPoistaMatkustaja' >Poista</button></td><tr>";
            
            }
         
         
        }
        echo"</table>";
        
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


