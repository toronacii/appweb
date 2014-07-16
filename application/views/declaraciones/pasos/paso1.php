<div class="row setup-content" id="paso-1">
    <div class="well well-sm">
        Estos son los datos más recientes que guardamos en nuestros archivos, 
        es importante que los mantengas actualizados para recibir notificaciones importantes sobre tu cuenta.
        Para modificarlos, escribe sobre los campos habilitados.
        Si están correctos, omite este paso y presiona el botón <a href="#" class="label label-primary activate next" data-paso="2">Siguiente</a>
    </div>
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading center">Información del contribuyente</div>
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">                                
                            <div><strong>N° cuenta nueva</strong></div>
                            <div><?php echo $datos_contribuyente->numero_cuenta ?></div>
                        </div>
                        <div class="col-md-6">                                
                            <div><strong>N° cuenta renta</strong></div>
                            <div><?php echo $datos_contribuyente->cuenta_renta ?></div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">                                
                            <div><strong>Razón social</strong></div>
                            <div id="razon_social"><?php echo $datos_contribuyente->razon_social ?></div>
                        </div>
                        <div class="col-md-6">                                
                            <div><strong>RIF</strong></div>
                            <div><?php echo $datos_contribuyente->rif ?></div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">                                
                            <div><strong>Dirección</strong></div>
                            <div><?php echo $datos_contribuyente->direccion ?></div>
                        </div>
                        <div class="col-md-6">                                
                            <div><strong>Correo electrónico</strong></div>
                            <div><?php echo $this->session->userdata('usuario_appweb')->email ?></div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-primary validate-paso-1">
            <div class="panel-heading center">Información editable del contribuyente</div>
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="resp_legal">Responsable legal</label>
                            <input type="text" name="toolbar[resp_legal]" class="form-control validate" id="resp_legal" data-validate-rules="required|texto" value="<?php echo $datos_contribuyente->resp_legal ?>"/>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="ci_resp_legal">C.I. Responsable legal</label>
                            <input type="text" name="toolbar[ci_resp_legal]" class="form-control validate" id="ci_resp_legal" data-validate-rules="required|numeric" value="<?php echo $datos_contribuyente->ci_resp_legal ?>"/>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="tlf_local">Teléfono local</label>
                            <input type="text" name="toolbar[local]" id="tlf_local" class='form-control formatoTelefonoLocal validate' data-validate-rules="required|tlf[local]" value="<?php echo $datos_contribuyente->local ?>"/>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="tlf_celular">Teléfono celular</label>
                            <input type="text" name="toolbar[celular]" id="tlf_celular" class='form-control formatoTelefonoCelular validate' data-validate-rules="required|tlf[celular]" value="<?php echo $datos_contribuyente->celular ?>"/>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-12">
        <a class="btn btn-primary btn-lg activate pull-right next" data-paso="2">Siguiente</a>
    </div>
    
</div>