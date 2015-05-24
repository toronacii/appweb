<script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('js/gmap3/gmap3.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/gmap3/gmap3.ini.js') ?>"></script>

<input type="hidden" name="latLong" id="latLong" value="{{ (taxpayer.lat) ? taxpayer.lat + ',' + taxpayer.long : '' }}"/>

<div class="row setup-content" id="paso-2">
    <div class="well well-sm">
        Para la gestión es muy importante realizar una geo-referenciación de nuestros contribuyentes. Le invitamos a utilizar la herramienta.
        Indique la posición donde se encuentra su empresa y luego presione el botón <a href="#" class="label label-primary activate next" data-paso="3">Siguiente</a>
    </div>
    <div class="col-md-12">
        <div id="map" class="gmap3"></div>
        <div class="pull-right" style="margin-top: 20px">
            <a class="btn btn-primary btn-lg activate">Anterior</a>
            <a class="btn btn-primary btn-lg activate next">Siguiente</a>
        </div>
    </div>
    
</div>

