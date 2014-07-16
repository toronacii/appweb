function imprimeDireccion(bigObj, inner, all){

    if (all || false){
        $.post('post.php',{
            post : serialize(bigObj)
        },function(data){
            $(inner).append(data);
        });
    } else {
        bigObj = bigObj[0].address_components;
        html = '';
        for (i in bigObj){
            var obj = bigObj[i];
            html += obj.long_name;
            html += '<ul>';
            for (j in obj.types){
                html += '<li>' + obj.types[j] + '</li>';
            }
            html += '</ul>'
        }
        $(inner).append(html);
			
    }
}
		
var latLgtAlcaldia = [10.486672523550666 , -66.82352542877197];


initializeMaps = false;

function initialize(){
    
    
    if ($('#labelatLong').text() == ''){
        var latLongTax = false;
        var info = {
            latLng: latLgtAlcaldia,
            options:{
                content: "<div style='font-weight:bold; width:200px; text-align:center'>Municipio Sucre</div>"
            }
        };
        
    }else{
        var latLongTax = eval('[' + $('#labelatLong').text() + ']');
        var info = {
            latLng: latLongTax,
            options:{
                content: "<div style='font-weight:bold; width:300px; text-align:center'>" + $('#razon_social').text() + "</div>"
            }
        };
    }
    
    var center = latLongTax || latLgtAlcaldia;
    
    var map = $("#map").gmap3({
        map: {
            options: {
                center : center,
                zoom: 15
            },
            events: {
                click: function(map, event){
                    $(this).gmap3({
                        clear: {
                            name: "marker"
                        },
                        marker: {
                            latLng: event.latLng,
                            options: {
                                draggable: true
                            },
                            events: {
                                dragend: function(marker, event){
                                    $("#latLong").val(event.latLng.lat()+ ' , ' +event.latLng.lng());
                                    $('#labelatLong').text($("#latLong").val());
                                    $(this).gmap3({
                                        getaddress:{
                                            latLng:marker.getPosition(),
                                            callback:function(results){
                                                $("#objGoogleMaps").val(serialize(results));
                                            }
                                        }
                                    });
                                }
                            }
                        }
                    });
                    $("#latLong").val(event.latLng.lat()+ ' , ' +event.latLng.lng());
                    $('#labelatLong').text($("#latLong").val());
                    $(this).gmap3({
                        getaddress:{
                            latLng:event.latLng,
                            callback: function(results){
                                $("#objGoogleMaps").val(serialize(results));
                            }
                        }
                    });
							
                }
            }
        },
        infowindow:info
    });
    
    initializeMaps = true;
    //console.log(map);
} 
    


