
/* Ajax */

/*
innerText ei toimi Firefoxilla
*/


var lahtopaikka = null;
var maaranpaa = null;
var kyydit_dataTable = []; // DataTable taulukkoon perustiedot
var kyyti_lista=[];
var kyydit; 

// Ilmoita kyytiläinen

function aktivoi_ilmoitaNappi(){
        
        
        $('.btnVapaa').unbind("click"); // Estää ilmoittautumisen useat klikkaukset
     $('.btnVapaa').click(function() {
            
            var btn = $(this);
            console.log($(this).closest('tr')[0].childNodes[0].innerHTML);
            var kyyti_id = $(this).closest('tr')[0].childNodes[0].innerHTML;
            var url = "ilmoittaudu.php";
            
            $.ajax({
                type: "POST",
                dataType: "json",
                url: url,
                data: {"kyyti_id":kyyti_id}, 
                success: function(result)
            {
               
                console.log(result);
                kasittele_palaute(result);
                $('#info-boxi').modal('show');
               
               // Päivitetään tiedot
               hae_kyydit();
            }
         });
            
           
});
}


// Lisätietoja kyydistä riviä klikkaamalla / näytä kartalla

$(function(){

      $('tr').click(function() {
            
            var tr = $(this);
            //console.log($(this).closest('tr')[0].childNodes[0].innerHTML);
            var kyyti_id = $(this).closest('tr')[0].childNodes[0].innerHTML;
            
            var url = "kyyti_info.php";
           
           
            $.ajax({
                type: "POST",
                dataType: "json",
                url: url,
                data: {"kyyti_id":kyyti_id}, 
                success: function(result)
            {
               
                console.log(result);
                nayta_kohde_kartalla(result);
                
                //kasittele_palaute(result);
                //$('#info-boxi').modal('show');
               
               
            }
         });
            
           
        });
});


// Poista kuski

$(function(){

      $('.btnPoistaKuski').click(function() {
            
            var btn = $(this);
            console.log($(this).closest('tr')[0].childNodes[0].innerHTML);
            var kyyti_id = $(this).closest('tr')[0].childNodes[0].innerHTML;
            
            var url = "poistaKyyti.php";
            $.ajax({
                type: "POST",
                url: url,
                data: {"kyyti_id":kyyti_id}, 
                success: function(result)
            {
        
              
              // Päivita #tab-kuski teksti
              
              var uusi_luku = parseInt($('#tab-kuski')[0].innerHTML.split(' ')[1].replace('(', '').replace(')','')) - 1;
              $('#tab-kuski')[0].innerHTML = "Kuski (" + uusi_luku.toString() + ")";
              
              btn.parents('tr').fadeOut(1000);
              btn.parents('tr').remove;
             
            }
         });
            
        
});
});


// Poista ilmoittautuminen

$(function(){

      $('.btnPoistaMatkustaja').click(function() {
            
            var btn = $(this);
            console.log($(this).closest('tr')[0].childNodes[0].innerHTML);
            var kyyti_id = $(this).closest('tr')[0].childNodes[0].innerHTML;
            
            
            var url = "poistaIlmoittautuminen.php";
            $.ajax({
                type: "POST",
                url: url,
                data: {"kyyti_id":kyyti_id}, 
                success: function(result)
            {
             
                //console.log(result);
              
              // Poista nappi
              btn.parents('tr').fadeOut(1000);
              btn.parents('tr').remove;
              
              // Päivita #tab-matkustaja teksti
              
              
              var uusi_luku = parseInt($('#tab-matkustaja')[0].innerHTML.split(' ')[1].replace('(', '').replace(')','')) - 1;
              
              $('#tab-matkustaja')[0].innerHTML = "Matkustaja (" + uusi_luku.toString() + ")";
              
              // Päivitä teksti esim. "1/1" -> "0/1"
              
              var ilmoittautuneet = parseInt($(btn).closest('tr')[0].childNodes[4].innerHTML.split("/")[0]) - 1;
              var max = parseInt($(btn).closest('tr')[0].childNodes[4].innerHTML.split("/")[1]);
              
              var uusi_teksti = String(ilmoittautuneet) +"/"+ String(max);
              
              $(btn).closest('tr')[0].childNodes[4].innerHTML = uusi_teksti;
              
            }
         });
            
            
            //$('#info-boxi').modal('show');
           
});
});



// Ilmoita kuski

$(function(){
    
    $("#formKuskiksi").submit(function() {
        
        var button = $(this);
        
        var url = "ilmoitaKuski.php";
    
        $.ajax({
           type: "POST",
           dataType: "json",
           url: url,
           data: $("#formKuskiksi").serialize(), 
           success: function(result)
           {
            
            
            kasittele_palaute(result);        
            $('#info-boxi').modal('show');
            
            if (result.status == "success") {
                reset_forms();
            }
            
               
           }
         });
    
    return false;
});
});


function kasittele_palaute(result){
    
    
    if (result.status == "success") {
        $('#info-teksti')[0].innerHTML = '<div class="alert alert-success" role="alert"><p>'+result.message+'</p></div>';
        
    }else if (result.status == "error") {
    
        $('#info-teksti')[0].innerHTML = '<div class="alert alert-danger" role="alert"><p>'+result.message+'</p></div>';
        
    }
}

// Aseta lomake ja kartta lähetyksen jälkeen alkutilaan

function reset_forms(){
    
    $('.form-group').find('input').val("")
    
    $('#map').gmap('clear', 'markers');
    
    $('.form-group').find('input').removeClass('glowing-border');
    
    lahtopaikka = null;
    maaranpaa = null;
    
    $('#map').gmap('option', 'zoom', 5);
    var aloitus_paikka = new google.maps.LatLng(63.1994387,25.9793648);
    $('#map').gmap('option', 'center', aloitus_paikka);
   
    
}


function hae_kyydit(){
    
        
        var url = "hae_kyydit.php";
    
        $.ajax({
           type: "POST",
           dataType: "json",
           url: url,
           //data: $("#formKuskiksi").serialize(), 
           success: function(result)
           {
            
            
            console.log(result);
            
            kyydit = result;
            
            kyyti_lista.splice(0, kyyti_lista.length);  // Tyhjennä kyyti_lista
            kyydit_dataTable.splice(0, kyydit_dataTable.length);
            for(var x=0; x < result.length; x++){
                
                kyydit_dataTable.push([result[x].id, result[x].kuski,result[x].kaupunki_lahtopaikka,
                                       result[x].kaupunki_maaranpaa,
                                       result[x].lahto_aika, result[x].status]);
                //console.log(result[x].kuski);
                //nayta_kohde_kartalla(result[x]);
            }
            
            
           luoHakuTaulukko();
        
            }
        
        
         });
    
    
    
}

function nayta_kohde_kartalla(result){
    
     
    var lahto = $('#map').gmap('addMarker', {
                    'position': new google.maps.LatLng(result.lat_lahtopaikka,result.lon_lahtopaikka),
                    //'icon':"custom_icons/google_maps/car_share.png",
                    'icon':"custom_icons/google_maps/group-2.png",
                    'draggable':false,

                    })
    
    var maaranpaa = $('#map').gmap('addMarker', {
                    'position': new google.maps.LatLng(result.lat_maaranpaa,result.lon_maaranpaa),
                    'icon':"custom_icons/google_maps/car_share.png",
                    //'icon':"custom_icons/google_maps/group-2.png",
                    'draggable':false,

                    })
    
    $('#map').gmap('option', 'zoom', 5);
   
    
    
    maaranpaa.click(function(event) {
                    
                   
                   console.log(result);
                   keskita_kartta(maaranpaa);
                   $('#map').gmap('option', 'zoom', 15);
                   
                   
                 
                });
    
    lahto.click(function(event) {
                   
                   console.log(result);
                   keskita_kartta(lahto);
                   $('#map').gmap('option', 'zoom', 15);
                 
                });
    
    kyyti_lista.push({id:result.id, info:result, lahto:lahto, maaranpaa:maaranpaa});
    
    
}


function poistaMarkkeritKartalta() {
    
    $('#map').gmap('clear', 'markers');
    lahtopaikka = null;
    maaranpaa = null;
    kyyti_lista.splice(0, kyyti_lista.length);
     
    
}

function keskita_kartta(piste){
    
    var lat = piste[0].position.lat();
    var lon = piste[0].position.lng();
    var point = new google.maps.LatLng(lat, lon);
    $('#map').gmap('option', 'center', point); 
    
    
}


$(function(){
    
    $("#mista").focus(function() {
    
            if (!!lahtopaikka) {
    
                var lat = lahtopaikka[0].position.lat();
                var lon = lahtopaikka[0].position.lng();
                var point = new google.maps.LatLng(lat, lon);
                $('#map').gmap('option', 'center', point); 
            }
        });
    });

$(function(){
    
    $("#mihin").focus(function() {
    
            if (!!maaranpaa) {
    
                var lat = maaranpaa[0].position.lat();
                var lon = maaranpaa[0].position.lng();
                var point = new google.maps.LatLng(lat, lon);
                $('#map').gmap('option', 'center', point); 
            }
        });
    });


// Google reverse-geocode

function reverse_geocode_mista(lat, lon) {
            
            var paikka = lat.toString() +", "+ lon.toString();
            var url = "https://maps.googleapis.com/maps/api/geocode/json";
            var api_key = "AIzaSyBoNtorWuVSSkPXQuBoJP7ZljhL2ZaEJM"; // Ei toiminnassa enää
            var osoite;
            $.ajax({
                
                dataType: "json",
                url: url,
                data: {latlng: paikka, key: api_key},
                
                success: function(data){
                    
                    osoite = data.results[0].formatted_address;
                    osoite = osoite.split(",").slice(0, osoite.split(",").length - 1); // Poistetaan "Finland"
                    $('#mista').val(osoite);
                    
                    
                    
                }
    
            });
         
    
}


function reverse_geocode_mihin(lat, lon) {
            
            var paikka = lat.toString() +", "+ lon.toString();
            var url = "https://maps.googleapis.com/maps/api/geocode/json";
            var api_key = "AIzaSyBoNtorWuVSlkPXQuBoJP7ZljhLC2ZaEJM";
            var osoite;
            $.ajax({
                
                dataType: "json",
                url: url,
                data: {latlng: paikka, key: api_key},
                
                success: function(data){
                    
                    osoite = data.results[0].formatted_address;
                    osoite = osoite.split(",").slice(0, osoite.split(",").length - 1); // Poistetaan "Finland"
                    $('#mihin').val(osoite);
                   
                    
                    
                }
    
            });
         
    
}

// Google geocode

function geocode_mista(kaupunki){

            //var kaupunki = "Joensuu";
            var url = "https://maps.googleapis.com/maps/api/geocode/json";
            var api_key = "AIzaSyBoNtorWuVSSkPXQuBoJP7ZljhLC2ZaEJM";
            $.ajax({
                
                dataType: "json",
                url: url,
                data: {address: kaupunki, key: api_key},
                
                success: function(data)
            {
                
                var lat = data.results[0].geometry.location.lat;
                var lon = data.results[0].geometry.location.lng;
                var point = new google.maps.LatLng(lat, lon);
                
                
               
                // Luodaan vain yksi lahtopaikka
                if (lahtopaikka === undefined || lahtopaikka === null) {
                    
                    lahtopaikka = $('#map').gmap('addMarker', {
                    'position': new google.maps.LatLng(lat,lon),
                    //'icon':"custom_icons/google_maps/car_share.png",
                    'icon':"custom_icons/google_maps/group-2.png",
                    'draggable':true,
                    'raiseOnDrag': true
                    }
                    );
                
                lahtopaikka.dragend(function(event) {
                    
                   var lat = event.latLng.lat();
                   var lon = event.latLng.lng();
                   
                   console.log(event.latLng.lng());
                   reverse_geocode_mista(lat, lon);
                   $('#mista_lat').val(lat);
                   $('#mista_lon').val(lon);
                   $('#mista').addClass('glowing-border');
                   
                   
                });
                
            }else{
                
                // Jos lähtöopaikka on olemassa aseta uusi paikka
                
                lahtopaikka[0].setPosition(point);
                
            }
            
             // Zoomaa ja keskittää kartan autocomplete kentän kaupunkiin
                
                $('#map').gmap('option', 'zoom', 12);
                $('#map').gmap('option', 'center', point);   // Toimii
              
            }
         });
          

}



function geocode_mihin(kaupunki){

            var url = "https://maps.googleapis.com/maps/api/geocode/json";
            var api_key = "AIzaSyBoNtorWuVSkPXQuBoJPZljhL2ZaEJM";
            $.ajax({
                
                dataType: "json",
                url: url,
                data: {address: kaupunki, key: api_key},
                
                success: function(data)
            {
                
                var lat = data.results[0].geometry.location.lat;
                var lon = data.results[0].geometry.location.lng;
                var point = new google.maps.LatLng(lat, lon);
                
                
               
                // Luodaan vain yksi lahtopaikka
                if (maaranpaa === undefined || maaranpaa === null) {
                    
                    maaranpaa = $('#map').gmap('addMarker', {
                    'position': new google.maps.LatLng(lat,lon),
                    //'icon':"custom_icons/google_maps/car_share.png",
                    'icon':"custom_icons/google_maps/car_share.png",
                    'draggable':true,
                    'raiseOnDrag': true
                    }
                    );
                
                maaranpaa.dragend(function(event) {
                    
                   var lat = event.latLng.lat();
                   var lon = event.latLng.lng();
                   
                   reverse_geocode_mihin(lat, lon);
                   
                   //console.log(osoite);
                   
                   $('#mihin_lat').val(lat);
                   $('#mihin_lon').val(lon);
                   $('#mihin').addClass('glowing-border');
                });
                
            }else{
                
               
                maaranpaa[0].setPosition(point);
                
            }
            
             // Zoomaa ja keskittää kartan autocomplete kentän kaupunkiin
                
                $('#map').gmap('option', 'zoom', 12);
                $('#map').gmap('option', 'center', point);   // Toimii
              
            }
         });
          

}


// Google maps

$(function(){
    
  
    var aloitus_paikka = new google.maps.LatLng(63.1994387,25.9793648);
    $('#map').gmap({'center': aloitus_paikka});
    
   
});




/*Autocomplete setup*/

function lataa_kaupungit(){
    
    
        $.ajax({
        type: "GET",
        url: "kaupungit.xml", // change to full path of file on server
        dataType: "xml",
        success: parseXml,
        complete: setupAC,
        error: function() {
            console.log("XML-tiedoston lataus epäonnistui");
        }
        
    });

        
};


function parseXml(xml){
  kaupungit = [];  
  
  $(xml).find("kaupunki").each(function()
  {
    kaupungit.push($(this).attr("label")+", "+$(this).attr("value"));
  });
}

function setupAC() {
    
 $("input#mista").autocomplete({
     source: kaupungit,
     minLength: 1,
    select: function(event, ui) {
     
        var kaupunki = ui.item.value;
        geocode_mista(kaupunki);
        
     }
 });
 
 $("input#mihin").autocomplete({
     source: kaupungit,
     minLength: 1,
    select: function(event, ui) {
     
        var kaupunki = ui.item.value;
        geocode_mihin(kaupunki);
    
    
     }
 });
 
 
}


/*Datepicker */

$(function(){
    
   jQuery('#datetimepicker').datetimepicker({
    format:'d.m.Y H:i',
    minDate:'-1970/01/01',
    
    inline:false,
    lang:'fi'});
    
});


/*DataTable*/


function luoHakuTaulukko(){

 $('#dataTable').html( '<table cellpadding="0" cellspacing="0" border="0" class="display" id="haku"></table>' );
 
  
    
 var table = $('#haku').DataTable( {
        "data": kyydit_dataTable,
        "columns": [
            /*{
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },*/
            { "title": "id" },
            { "title": "kuski" },
            { "title": "lähtö" },
            { "title": "määränpää"},
            { "title": "aika"},
            {"title": "status"}
            
        ],
        
        "order": [[1, 'asc']],
        "stateSave": true,
        "iDisplayLength": 5,
        "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
        
        
        /*
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }]*/
        
});
 
    // Ilmoittatutuminen ei muuten mahdollista. Aktivointi pitää suorittaa jälkeen päin, koska taulukkokin luodaan vasta AJAX-kutsun jälkeen.
    aktivoi_ilmoitaNappi();
    
    $('#haku thead th').each( function () {
        var otsikko = $('#haku thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" placeholder="'+otsikko+'" />' );
    } );
    
    table.columns().every( function () {
        var that = this;
        $( 'input', this.header() ).on( 'keyup change', function () {
            that
                .search( this.value )
                .draw();
        } );
    } );
   
   $('#haku tbody').on('click', 'tr', function (e) {
        
        
        // Varmistetaan ettei napin painallus avaa lisäinfoa
        var target = $(e.target);
        
        
        console.log(target[0]);
        if (!target.is("button")) { 
       
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            //Poista kartalta
            poistaMarkkeritKartalta();
        }
        else {
            
           // console.log($(this).parent().parent().attr("id"));
            
                
                
            
            // Open this row
            row.child( luoAlaTaulukko(row.data()) ).show();
            
            
            
            // Lisää karttaikonille toiminto
            
            aktivoiIkonit();
            
            
            // Poista entinen kartalta
            poistaMarkkeritKartalta();
            
            // Näytä kartalla
            nayta_kohde_kartalla(haeLisaTiedot(row.data()[0]));
            tr.addClass('shown');
            
   
        }
        
        
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
   
        
        
        }
    } );
   
   // Aktivoi ilmoitapainike myös muilla sivuilla
   $('#haku').on( 'draw.dt', function () {
    
        aktivoi_ilmoitaNappi();
        
    } );
   
   
   
   asetaInput();
   
 
 

}



function aktivoiIkonit(){
    
    $('.map_lahtopaikka').on('click', function (e) {
        
        var target = $(e.target);
       
        
          keskita_kartta(kyyti_lista[0].lahto);
        $('#map').gmap('option', 'zoom', 15);
        
        
        });
    
    $('.map_maaranpaa').on('click', function () {
        
          keskita_kartta(kyyti_lista[0].maaranpaa);
        $('#map').gmap('option', 'zoom', 15);
        
        
        });
    
    
}

function luoAlaTaulukko ( d ) {
    // `d` is the original data object for the row
    
    var id = d[0];
    var tiedot = haeLisaTiedot(id);
    
    return '<table id="alaTaulukko" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td>Osoite lähtöpaikka:</td>'+
            '<td>'+tiedot.osoite_lahtopaikka+', '+tiedot.postinumero_lahtopaikka+', '+tiedot.kaupunki_lahtopaikka+'&nbsp;&nbsp;<i class="fa fa-crosshairs fa-lg map_lahtopaikka"></i></td>'+
        '</tr>'+
        '<tr>'+
            '<td>Osoite määränpää:</td>'+
            '<td>'+tiedot.osoite_maaranpaa+', '+tiedot.postinumero_maaranpaa+', '+tiedot.kaupunki_maaranpaa+'&nbsp;&nbsp;<i class="fa fa-crosshairs fa-lg map_maaranpaa"></i></td>'+
        '</tr>'+
        '<tr>'+
            '<td>Ilmoittautuneet:</td>'+
            '<td>'+tiedot.ilmoittautuneet+'/'+tiedot.max_matkustajat+'</td>'+
        '</tr>'+
    '</table>';
}



function haeLisaTiedot(id){
    
    
     for(var x=0; x < kyydit.length; x++){
        
        
        if (id == kyydit[x].id) {
            
            
            return kyydit[x];
            
        }
        
        
     }
    
    
}


// Asettaa DataTables sarakkeiden search tekstin näkyville, jos tila on tallennettu selaimen muistiin.
// Muutoin search kentän teksti piilossa, mutta taulukon filtteröinti edelleen päällä.
function asetaInput(){
    
var tila = JSON.parse(localStorage.getItem("DataTables_haku_/index.php"));
var sarakkeet = $('#haku thead tr th');

sarakkeet.each(function(index){

search_string = tila.columns[index].search.search;

if ( search_string !== ""){
$(this).find('input').val(search_string);
}
});
    
    
    
}

/*
id: "177"
ilmoittautuneet: "0"
kaupunki_lahtopaikka: "Joensuu"
kaupunki_maaranpaa: "Kuopio"
kuski: "japkettu"
lahto_aika: "2015-06-01 23:46:00"
lat_lahtopaikka: "62.603301695262"
lat_maaranpaa: "62.8933347"
lon_lahtopaikka: "29.751513403613"
lon_maaranpaa: "27.687921568848"
max_matkustajat: "3"
osoite_lahtopaikka: "Siltakatu 34"
osoite_maaranpaa: "Maaherrankatu 21"
*/


