    <div class="row setup-content" id="paso-<?php echo ($showStepFour) ? 6 : 5 ?>">
        <div class="panel panel-primary">
            <div class="panel-heading center">Resumen</div>
            <table class="table" id="table_resumen">
                <thead>
                <tr>
                    <th>Código</th>
                    <th><span class="hidden-sm hidden-xs">Actividad</span></th>
                    <th>Monto Declarado</th>
                    <th>Impuesto Anual</th>
                </tr>
                </thead>
                <tbody></tbody>
                <tfoot></tfoot>
            </table>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-lg activate">Anterior</a>
            <a class="btn btn-primary btn-lg finalize guardar">Guardar</a>
            <a class="btn btn-primary btn-lg finalize liquidar">Declarar</a>
        </div>
    </div>

</div><!-- END statementCtrl -->
