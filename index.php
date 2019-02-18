<?php include "base_sql.php"; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kyytiläinen</title>
 
    <!-- Bootstrap -->
    
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
      
    <link href="css/custom_bootstrap.min.css" rel="stylesheet">
    
    <!-- Font awesome-->
    <link href="font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <!--Datetimepicker-->
    <link href="datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
     
   
    <!--Javascript-->
    
    <!--JQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!--JQuery UI -->
    <!--DataTables-->
    <script src="http://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
    
    <!-- Google maps -->
    
    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script src="js/jquery.ui.map.js"></script>
    
    <!-- Muut -->  
    <script src="datetimepicker/jquery.datetimepicker.js"></script>
    <script src="toiminnot.js"></script>
    
    <!--custom style -->
    
    
    
    <style>
      body {
        padding-top: 100px;
        }
        
    @media screen and (max-width: 768px)
    {
    body {
      padding-top: 75px;
        }
    }
    
    .dropdown-menu {
        min-width: 200px
      }
    
    .glowing-border {
    border: 2px solid #61dd45;
    border-radius: 7px;
    }  
      
    .glowing-border:focus { 
    outline: none;
    border-color: #9ecaed;
    box-shadow: 0 0 10px #9ecaed;
    }
    
    /*    
    @media only screen and (min-width : 768px) {
    
    .dropdown:hover .dropdown-menu {
        display: block;
    }
      } */
    
    
    td.details-control {
    background: url('custom_icons/details_open.png') no-repeat center center;
    cursor: pointer;
    }
    tr.shown td.details-control {
      background: url('custom_icons/details_close.png') no-repeat center center;
    }
    
    tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }

    
    #map {
        margin: 0;
        padding: 0;
        height: 500px;
        
      }
      
    
      
      
    input:focus {
        border-color: #5cb85c;
        box-shadow: 0 0 5px rgba(207, 220, 0, 0.4);
    }
      
      
    </style>
    
 
    
  </head>
  <body>
    
    
    <!-- Navbar -->
                               <!--    -->
    <nav class="navbar navbar-inverse navbar-fixed-top" id="navbar">
      
        <div class="container">
                
          <div class="navbar-header">
            
            
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                    
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
        
            </button>
                 
          </div> <!-- Navbar-header-->
          
            
          <div class="collapse navbar-collapse" id = "navbar-collapse">
              
              
            <ul class="nav navbar-nav">
              
                   <li> <a class="brand" href="index.php"><i class="fa fa-home fa-fw"></i>&nbsp; Kyytiläinen</a></li> <!--Aina näkyvillä-->
                
                                                                          
                <?php
                  if(!empty($_SESSION['logged']) && !empty($_SESSION['tunnus'])){
                  //if(1==1){
                  
                  ?>
                    
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['tunnus']; ?></a>
                    <ul class="dropdown-menu">
                      <li><a href="index.php?p=ilmoita_kyyti">Ilmoittaudu kuskiksi</a>
                      <li><a href="index.php?p=ilmoittautumiset">Omat ilmoittautumiset</a>
                      <li><a href="index.php?p=haku">Haku</a>
                      <li class="divider"></li>
                      <li><a href="logout.php">Kirjaudu ulos</a>
                    </ul>
                  </li>
                  
                <?php }
                  elseif(!empty($_POST['tunnus']) && !empty($_POST['salasana'])){
                    
                    $tunnus = mysql_real_escape_string($_POST['tunnus']);
                    $salasana = hash("sha512", mysql_real_escape_string($_POST['salasana']));
                    
                    $tarkista_kirjautuminen = sprintf("SELECT * FROM japkettu_users WHERE tunnus='%s' AND salasana='%s';",
                                                      $tunnus, $salasana);
                    
                    
                    
                    $tulos = mysql_query($tarkista_kirjautuminen);
                    
                    if(mysql_num_rows($tulos) == 1){
                      
                      
                      $rivi = mysql_fetch_array($tulos);
                      $sposti = $row['sposti'];
                      $_SESSION['tunnus'] = $tunnus;
                      $_SESSION['sposti'] = $sposti;
                      $_SESSION['logged'] = 1;
                      
                      echo "<meta http-equiv='refresh' content='2;index.php'>";
                      
                      
                    }
                    else{
                      
                
                      
                      echo "<li> <a href='index.php'> Kirjautuminen epäonnistui </a>";
                      
                    }
                    
                
                ?>
                
                  
                <?php  
                  }else{
                  
                  ?>
                  
                  
                    
                  <li class="dropdown" id="menuLogin">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="navLogin">Kirjaudu</a>
                    <div class="dropdown-menu" style="padding:20px;">
                      <form class="form" id="formLogin" method="post" action="index.php">
                            
                          <div class="input-group">
                            
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input name="tunnus" id="tunnus" type="text" class="form-control" placeholder="Tunnus">
                            
                          </div>
                          
                          <div class="input-group">
                            
                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                            <input name="salasana" id="salasana" type="password" class="form-control" placeholder="Salasana">
                          </div>
                          <input type="submit" id="btnLogin" class="btn btn-primary form-control" value="Kirjaudu">
                          
                          
                            
                      </form>
                          
                    </div>
                  </li>
                  
                  <li><a href="register.php">Rekisteröidy</a></li>
                    
                 <?php   
                  }
                  
                  ?>
                
                </ul>
              
              
            </div> <!-- collapse -->
      
          </div> <!-- container -->
      
    </nav>
    
    
    
    <?php
      
      $page = $_GET['p']; 
      if(empty($page)){
        
        include("etusivu.php");
        
      }
    
    
    ?>
    
        
          <?php
            
            $page = $_GET['p'];
            
            // Tarkista onko kirjautunut
            
            if(!empty($_SESSION['logged']) && !empty($_SESSION['tunnus'])){ 
              
              switch($page){
                
                case "ilmoita_kyyti": 
              
                  include('kuskiksi.php');
                  break;
                
                case "ilmoittautumiset": 
              
                  include('omatAjot.php');
                  break;
                
                case "haku": 
              
                  include('haku.php');
                  break;
       
       
              }
            
            }
          
            ?>
    
    <!--Ilmoittautumis / info boksi-->
    
  <div id="info-boxi" class="modal fade" role="dialog"
    aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
        
        <button type="button" class="close" data-dismiss="modal">x</button>
        <div id="info-teksti" class="modal-body"><p></p></div>
          
        <div class="modal-footer">
          
          <button id="info-button" type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Ok</button>
          
        </div>
      </div>
      </div> 
    </div>
  </div>
  
  
  <div id="dialog">
    
    <p></p>
    
  </div>
   
       <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="ajax.js"></script>
   
    
  </body>
</html>
