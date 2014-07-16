<div class="col-md-4">
    <div class="well">
        <h4>Instrucciones</h4>
        <ol>
            <li>Algunos campos no son modificables, para modificarlos comuníquese con nuestras oficinas</li>
            <li>Si desea modificar su contraseña, debe agregar su contraseña anterior</li>
        </ol>
    </div>
    <?php echo validation_errors() ?>

</div>

<form method="POST" id="formModificarPerfil">
    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading center">Datos principales</div>
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6"><strong>Correo electrónico</strong></div>
                        <div class="col-md-6"><?php echo $user->email ?></div>
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
                        <div class="col-md-6"><strong>Nombres</strong></div>
                        <div class="col-md-6">
                            <input type="text" name="nombres" value="<?php echo set_value('nombres', $user->nombres) ?>" class="form-control textoConAcentos">                        
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6"><strong>Apellidos</strong></div>
                        <div class="col-md-6">
                            <input type="text" name="apellidos" value="<?php echo set_value('apellidos', $user->apellidos) ?>" class="form-control textoConAcentos">                        
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6"><strong>Teléfono local</strong></div>
                        <div class="col-md-6">
                            <input type="text" name="local" value="<?php echo set_value('local', $user->local) ?>" class="form-control formatoTelefonoLocal">
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6"><strong>Teléfono celular</strong></div>
                        <div class="col-md-6">
                            <input type="text" name="celular" value="<?php echo set_value('celular', $user->celular) ?>" class="form-control formatoTelefono">
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6"><strong>Contraseña actual</strong></div>
                        <div class="col-md-6">
                            <input type="password" name="my_password" id="my_password" value="<?php echo (! form_error("my_password")) ? set_value('my_password') : "" ?>" class="form-control">
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6"><strong>Nueva contraseña</strong></div>
                        <div class="col-md-6">
                            <input type="password" name="password" value="<?php echo (! form_error("my_password") && ! form_error("password")) ? set_value('password') : "" ?>" class="form-control password" disabled>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6"><strong>Confirmar contraseña</strong></div>
                        <div class="col-md-6">
                            <input type="password" name="password_confirm" value="<?php echo (! form_error("my_password") && ! form_error("password_confirm")) ? set_value('password_confirm') : "" ?>" class="form-control password" disabled>
                        </div>
                    </div>
                </li>
                <li class="list-group-item text-right">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            <button class="btn btn-primary" type="submit">Guardar cambios</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

</form>


