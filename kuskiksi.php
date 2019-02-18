
<?php

//include "base_sql.php";

if(!empty($_SESSION['logged']) && !empty($_SESSION['tunnus'])){ ?>

<!-- HTML -->
<script>$(lataa_kaupungit());</script>
<div class="container">
      <div class="row">
      
        <div class="col-md-6">

 <form class="form" id="formKuskiksi" >
    
   <div class="form-group">
    <div class="ui-widget"> 
        
        <label for="mista">Hae lähtöpaikka</label>
        <input name="mista" id="mista"  type="text" class="form-control" placeholder="Valitse lähtöpaikka">
        <input name="mista_lat" id="mista_lat" type="hidden">
        <input name="mista_lon" id="mista_lon" type="hidden">   
            
    </div>
   </div>
   

    
    <div class="form-group">
        <div class=="ui-widget">    
            <label for="mihin">Hae määränpää</label>
            <input name="mihin"  id="mihin" type="text" class="form-control" placeholder="Valitse määränpää">
            <input name="mihin_lat" id="mihin_lat" type="hidden">
            <input name="mihin_lon" id="mihin_lon" type="hidden">    
                
        </div>
   </div>
   
   
   <div class="form-group">
        
        <label for="milloin">Lähtöaika</label>
        <input name="milloin" id="datetimepicker"  type="text" class="form-control"  placeholder="1970/01/01 00:00">
        <input name="timestamp" id="dbDateField"  type="hidden">
   </div>
   
   <div class="form-group">
        
        <label for="matkustajat">max. Matkustajat</label>
        <select name="matkustajat" id="matkustajat" class="form-control">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
            <option>6</option>
            <option>7</option>
            <option>8</option>
            <option>9</option>
            <option>10</option>
        </select>
    
   </div>
   
   <div class="form-group">
    <button type="submit" class="btn btn-success form-control">Jätä ilmoitus</button>
   </div> 
                            
</form>
 
        </div>
        

        <div class="col-md-6">
            
            <div id="map" class="img-rounded">
                
                
                
                
                
            </div>
            
            
            
        </div>

    </div>
</div>

 <?php
 
    }else{
        
        include "cookie_error.html";
        
    }
    
    
    ?>
