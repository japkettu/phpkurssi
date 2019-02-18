<?php include "base_sql.php"; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekisteröidy käyttäjäksi</title>
 
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
 
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body>
    
    <div class="container">
        
        <?php
        
        if(!empty($_POST['tunnus']) && !empty($_POST['salasana']) && !empty($_POST['etunimi']) && !empty($_POST['sukunimi']) && !empty($_POST['sposti'])){
            
            
            $tunnus = mysql_real_escape_string($_POST['tunnus']);
            $salasana = hash("sha512", mysql_real_escape_string($_POST['salasana']));
            $etunimi = mysql_real_escape_string($_POST['etunimi']);
            $sukunimi = mysql_real_escape_string($_POST['sukunimi']);
            $sposti = mysql_real_escape_string($_POST['sposti']);
            //echo "<p>".$tunnus. "</p>";
            
            
            $tarkista_tunnus = sprintf("SELECT * FROM japkettu_users WHERE tunnus='%s'", $tunnus);
            
            $tunnukset = mysql_query($tarkista_tunnus);
            
            if(mysql_num_rows($tunnukset) == 1){
                
                echo "<p> Käyttäjätunnus varattu </p>";
                echo "<a href='register.php'>Yritä uudelleen</a>";
                
            }else if(xss_filter($tunnus, $etunimi, $sukunimi, $sposti)){
                
                
                echo "<h1>Rekisteröinti epäonnistui</h1>";
                echo "<h3>Tarkista antamasi tiedot, äläkä yritä mitään tyhmää.</h3>";
                echo "<a href='register.php'>Yritä uudelleen</a>";
                
                
                
                
                }else{
                
                // Luodaan uusi tunnus.
                
                $luo_tunnus = sprintf("Insert INTO japkettu_users (tunnus, etunimi, sukunimi, sposti, salasana)
                                      VALUES ('%s', '%s', '%s', '%s', '%s' );", $tunnus, $etunimi, $sukunimi, $sposti, $salasana);
            
                $output = mysql_query($luo_tunnus);
                
                if($output){
                    
                    echo "<h1>Rekisteröinti onnistui</h1>";
                    echo "<a href='index.php'>Palaa etusivulle ja kirjaudu sisään</a>";
                    
                }else{
                    
                    
                    echo "<h1>Rekisteröinti epäonnistui</h1>";
                    echo "<a href='register.php'>Yritä uudelleen</a>";
                    
                }
            }
            
        }else{ ?>
        
        
        
        <h1>Rekisteröidy uudeksi käyttäjäksi</h1>
        
        <form method="post" action="register.php">
            
            <input type="text" name="etunimi" placeholder="Etunimi">
            <br>
            <input type="text" name="sukunimi" placeholder="Sukunimi">
            <br>
            <input type="text" name="sposti" placeholder="Sähköposti">
            <br>
            <input type="text" name="tunnus" placeholder="Käyttäjätunnus">
            <br>    
            <input type="password"  name="salasana" placeholder="Salasana">
            <br>
            <input type="submit" name="register" value="Rekisteröidy">
            
        </form>
            
            
           
            
       <?php
       
       }
       
       
       // Tarkistaa, että nimet ja tunnus sisältää vain sallittuja merkkejä.
       // Palauttaa true, jos vääriä merkkejä havaitaan. Jos kaikki ok, palauttaa false.
       function xss_filter($tunnus, $etunimi, $sukunimi, $sposti){
        
        $nimet = [$tunnus, $etunimi, $sukunimi];
        
        foreach($nimet as $nimi){
        
        if (!preg_match('/^[a-z0-9äöå _-]+$/i', $nimi)){
            
            return true;
            
         }
         
       }
       
       
       if (!filter_var($sposti, FILTER_VALIDATE_EMAIL)) {
            
            return true;
        
        }
       
       
       
       
       return false;
       
        }
    
       
        ?>
        
        
 
  
    </div>
    
    
    
    
    
  </body>
  
  
  
  
  
      
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
