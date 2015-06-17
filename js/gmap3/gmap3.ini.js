var actionEvent = function(event, obj, latLng) {
    $("#latLong").val(event.latLng.lat() + ',' +event.latLng.lng());
    $(obj).gmap3({
        getaddress:{
            latLng: latLng,
            callback:function(results){
                $("#objGoogleMaps").val(serialize(results));
            }
        }
    });
}
		
var latLgtAlcaldia = [10.486672523550666 , -66.82352542877197];

var initializeMaps = false;

var initialize = function(){
    
    
    if ($('#latLong').val() == ''){
        var latLongTax = false;
        var info = {
            latLng: latLgtAlcaldia,
            options:{
                content: "<div style='font-weight:bold; width:200px; text-align:center'>Municipio Sucre</div>"
            }
        };
        
    }else{
        var latLongTax = eval('[' + $('#latLong').val() + ']');
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
                                    actionEvent(event, this, marker.getPosition())
                                }
                            }
                        }
                    });
                    actionEvent(event, this, event.latLng)
                }
            }
        },
        infowindow:info
    });
    
    initializeMaps = true;
    //console.log(map);
} 
    


