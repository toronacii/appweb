<!-- Modal -->
<div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Datos de usuario</h4>
    </div>
    <div class="modal-body">
        
        <ul class="list-group">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6"><strong>Correo electrónico</strong></div>
                    <div class="col-md-6"><?php echo $user->email ?></div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6"><strong>Nombre y apellido</strong></div>
                    <div class="col-md-6"><?php echo "$user->nombres $user->apellidos" ?></div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6"><strong><?php echo ($user->tipo_persona) ? 'C.I.' : 'RIF' ?></strong></div>
                    <div class="col-md-6"><?php echo $user->ced_rif ?></div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6"><strong>Teléfono local</strong></div>
                    <div class="col-md-6"><?php echo $user->local ?></div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6"><strong>Teléfono celular</strong></div>
                    <div class="col-md-6"><?php echo $user->celular ?></div>
                </div>
            </li>            
        </ul>
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <a href="<?php echo site_url('gestion_usuario/modificar_perfil'); ?>" class="btn btn-primary">Modificar</a>
    </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->