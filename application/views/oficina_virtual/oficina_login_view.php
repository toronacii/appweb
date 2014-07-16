<div id="oficinaVirtualH" <?php if (isset($log)) echo 'style="float:none;"'; ?>>

    <div class="page-header"><h2 class="center">Bienvenido a la Oficina Virtual de la Dirección de Rentas Municipales</h2></div>

    <?php echo form_open(base_url(), array('class' => 'form-signin', "role"=>"form"));?>
    <fieldset>
        <?php echo validation_errors(); ?>
        <?php if (isset($errorValidacion)): ?>
            <div class="alert alert-danger"><?php echo $errorValidacion ?></div>
        <?php endif; ?>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
            <input class="form-control" placeholder="Correo electrónico" name="user" id="user" type="text" value="<?=set_value('user');?>" autofocus required>
        </div>

        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
            <input class="form-control" placeholder="Contraseña" required="required" name="pass" type="password">
        </div>

        <div class="form-group margin-top-sm" style="margin-top:15px">
            <p><small>
                <a class="sinMargenLink" href='#' id='olvido_contrasena' rel="tooltip" title="Recuperar mi contraseña" data-toggle="modal" data-target="#myModal">He olvidado mi contraseña</a> <span>|</span> 
                <a href='<?=site_url()?>/gestion_usuario/registro' rel="tooltip" title="¿Es la primera vez que utilizas la Oficina Virtual? Regístrate">Regístrate</a>
            </small></p>
            
        </div>
        <input class="btn btn-lg btn-primary btn-block" type="submit" value="Acceder">
    </fieldset>
    <?php echo form_close() ?>


</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Recuperación de contraseña</h4>
    </div>
    <div class="modal-body">
        
        <div class="alert alert-danger" id="modal-error" style="display: none"></div>
        <div class="alert alert-success" id="modal-success" style="display: none"></div>

        <div class="input-group">
            <input type="text" id="email" class="form-control" placeholder="Correo electrónico">
            <span class="input-group-addon"><i class="fa fa-spinner fa-spin loading" style="display:none"> </i></span>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="modal-close">Cerrar</button>
        <button type="button" class="btn btn-primary" id="modal-submit">Recuperar</button>
    </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- VALIDACIONES PARA OLVIDO SU CONTRASENA -->

<?php if (isset($_GET['olvido_password'])): ?>

<script type="text/javascript">
$(function(){
    $('#olvido_contrasena').click();
});
</script>

<?php endif; ?>
