<div class="float-right">
    <script>
        function periodo_seleccionar(periodo) {
            if (Form.check('periodo_form')) {
                window.location = _url+'/dte/dashboard?periodo='+encodeURI(periodo);
            }
        }
    </script>
    <form name="periodo_form" id="periodo_form" onsubmit="periodo_seleccionar(this.periodo.value); return false">
        <div class="form-group">
            <label class="control-label sr-only" for="periodoField">Período del dashboard</label>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <a href="<?=$_base?>/dte/dashboard?periodo=<?=$periodo_anterior?>" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i></a>
                        <a href="<?=$_base?>/dte/dashboard?periodo=<?=$periodo_siguiente?>" class="btn btn-default btn-sm"><i class="fas fa-arrow-right"></i></a>
                    </span>
                </div>
                <input type="text" name="periodo" value="<?=$periodo?>" class="form-control check integer text-center" id="periodoField" placeholder="<?=$periodo_actual?>" size="7" onclick="this.select()" />
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" onclick="periodo_seleccionar(document.periodo_form.periodo.value); return false">
                        <span class="fa fa-search"></span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="page-header"><h1>Facturación</h1></div>

<?php
echo View_Helper_Dashboard::cards([
    [
        'icon' => 'far fa-file',
        'quantity' => $n_temporales,
        'title' => 'Temporales',
        'link' => 'dte_tmps',
        'link_title' => 'Explorar documentos',
    ],
    [
        'icon' => 'fas fa-sign-out-alt',
        'quantity' => $n_emitidos,
        'title' => 'Ventas',
        'link' => 'dte_ventas/ver/'.$periodo,
        'link_title' => 'Ver detalle de ventas',
    ],
    [
        'icon' => 'fas fa-sign-in-alt',
        'quantity' => $n_recibidos,
        'title' => 'Compras',
        'link' => 'dte_compras/ver/'.$periodo,
        'link_title' => 'Ver detalle de compras',
    ],
    [
        'icon' => 'fas fa-exchange-alt',
        'quantity' => $n_intercambios,
        'title' => 'Pendientes',
        'link' => 'dte_intercambios/listar',
        'link_title' => 'Bandeja de intercambio',
    ],
]);
?>

<div class="row">
    <!-- PANEL IZQUIERDA -->
    <div class="col-md-3">
        <a class="btn btn-primary btn-lg btn-block" href="documentos/emitir" role="button">
            Emitir documento
        </a>
        <br />
        <!-- menú módulo -->
        <div class="list-group mb-4">
<?php foreach ($nav as $link=>&$info): ?>
            <a href="<?=$_base.'/dte'.$link?>" title="<?=$info['desc']?>" class="list-group-item">
                <i class="<?=$info['icon']?> fa-fw"></i> <?=$info['name']?>
            </a>
<?php endforeach; ?>
        </div>
        <!-- fin menú módulo -->
        <!-- alertas envío libro o propuesta f29 -->
<?php if (!$libro_ventas_existe) : ?>
            <a class="btn btn-info btn-lg btn-block" href="dte_ventas" role="button" title="Ir al libro de ventas">
                <i class="fa fa-exclamation-circle"></i>
                Generar IV <?=$periodo_anterior?>
            </a>
            <br />
<?php endif; ?>
<?php if (!$libro_compras_existe) : ?>
            <a class="btn btn-info btn-lg btn-block" href="dte_compras" role="button" title="Ir al libro de compras">
                <i class="fa fa-exclamation-circle"></i>
                Generar IC <?=$periodo_anterior?>
            </a>
            <br />
<?php endif; ?>
<?php if ($propuesta_f29) : ?>
            <a class="btn btn-info btn-lg btn-block" href="informes/impuestos/propuesta_f29/<?=$periodo_anterior?>" role="button" title="Descargar archivo con la propuesta del formulario 29">
                <i class="fa fa-download"></i>
                Propuesta F29 <?=$periodo_anterior?>
            </a>
            <br />
<?php endif; ?>
<?php if (!$Emisor->config_sii_pass) : ?>
            <div class="card mb-4">
                <div class="card-body text-center">
                    <p class="lead">¿Sabía que si asigna la contraseña del SII de la empresa podría desbloquear funcionalidades adicionales?</p>
                    <p class="small">Por ejemplo la sincronización con el registro de compras y ventas del SII</p>
                </div>
            </div>
<?php endif; ?>
        <!-- fin alertas envío libro o propuesta f29 -->
    </div>
    <!-- FIN PANEL IZQUIERDA -->
    <!-- PANEL CENTRO -->
    <div class="col-md-6">
<?php if ($documentos_rechazados) : ?>
        <!-- alertas documentos rechazados  -->
        <div class="row">
            <div class="col-sm-12">
                <a class="btn btn-danger btn-lg btn-block" href="informes/dte_emitidos/estados/<?=$documentos_rechazados['desde']?>/<?=$documentos_rechazados['hasta']?>" role="button" title="Ir al informe de estados de envíos de DTE">
                    <?=num($documentos_rechazados['total'])?> documento(s) rechazado(s) desde el <?=\sowerphp\general\Utility_Date::format($documentos_rechazados['desde'])?>
                </a>
                <br />
            </div>
        </div>
        <!-- fin alertas documentos rechazados -->
<?php endif; ?>
<?php if ($rcof_rechazados) : ?>
        <!-- alertas rcof rechazados  -->
        <div class="row">
            <div class="col-sm-12">
                <a class="btn btn-danger btn-lg btn-block" href="<?=$_base?>/dte/dte_boleta_consumos/listar/1/dia/D?search=revision_estado:ERRONEO" role="button" title="Ir al informe de estados de envíos de DTE">
                    <?=num($rcof_rechazados['total'])?> RCOF(s) rechazado(s) desde el <?=\sowerphp\general\Utility_Date::format($rcof_rechazados['desde'])?>
                </a>
                <br />
            </div>
        </div>
        <!-- fin alertas rcof rechazados -->
<?php endif; ?>
        <!-- graficos ventas y compras -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="far fa-chart-bar fa-fw"></i> Ventas
                    </div>
                    <div class="card-body">
                        <div id="grafico-ventas"></div>
                        <a href="dte_ventas/ver/<?=$periodo?>" class="btn btn-primary btn-block">Ver libro del período</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="far fa-chart-bar fa-fw"></i> Compras
                    </div>
                    <div class="card-body">
                        <div id="grafico-compras"></div>
                        <a href="dte_compras/ver/<?=$periodo?>" class="btn btn-primary btn-block">Ver libro del período</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- fin graficos ventas y compras -->
        <!-- estado de documentos emitidos SII -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="far fa-chart-bar fa-fw"></i> Estado envíos al SII de documentos emitidos
                    </div>
                    <div class="card-body">
                        <div id="grafico-dte_emitidos_estados"></div>
                        <a href="informes/dte_emitidos/estados/<?=$desde?>/<?=$hasta?>" class="btn btn-primary btn-block">Ver detalles</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- fin estado de documentos emitidos SII -->
        <!-- estado de documentos emitidos receptores -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="far fa-chart-bar fa-fw"></i> Eventos asignados por receptores de documentos emitidos
                    </div>
                    <div class="card-body">
                        <div id="grafico-dte_emitidos_eventos"></div>
                        <p class="small">
<?php foreach (\sasco\LibreDTE\Sii\RegistroCompraVenta::$eventos as $codigo => $evento) : ?>
                            <strong><?=$codigo?></strong>: <?=$evento?>
<?php endforeach; ?>
                        </p>
                        <a href="informes/dte_emitidos/eventos/<?=$desde?>/<?=$hasta?>" class="btn btn-primary btn-block">Ver detalles</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- fin estado de documentos emitidos receptores -->
<?php if ($rcof_estados) : ?>
        <!-- estado de rcof enviados al SII -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="far fa-chart-bar fa-fw"></i> Estado envíos al SII de reportes de consumos de folios (RCOF)
                    </div>
                    <div class="card-body">
                        <div id="grafico-rcof_estados"></div>
                        <a href="dte_boleta_consumos/listar/1/dia/D" class="btn btn-primary btn-block">Ver listado de RCOFs</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- fin estado de rcof enviados al SII -->
<?php endif; ?>
    </div>
    <!-- FIN PANEL CENTRO -->
    <!-- PANEL DERECHA -->
    <div class="col-md-3">
        <!-- buscador documentos -->
        <script>
            function buscar(q) {
                window.location = _url+'/dte/documentos/buscar?q='+encodeURI(q);
            }
            $(function(){$('#qField').focus()});
        </script>
        <form name="buscador" onsubmit="buscar(this.q.value); return false">
            <div class="form-group">
                <label class="control-label sr-only" for="qField">Buscar por código documento</label>
                <div class="input-group input-group-lg">
                    <input type="text" name="q" class="form-control" id="qField" placeholder="Buscar DTE..." />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" onclick="buscar(document.buscador.q.value); return false">
                            <span class="fa fa-search"></span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <!-- fin buscador documentos -->
<?php if ($cuota) : ?>
        <!-- dtes usados (totales de emitidos y recibidos) -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa fa-calculator fa-fw"></i>
                Documentos usados
            </div>
            <div class="panel-body text-center p-4">
                <span class="lead text-info"><?=num($n_dtes)?></span> <small class="text-muted"> de <?=num($cuota)?></small><br/>
                <span class="small"><a href="<?=$_base?>/dte/informes/documentos_usados">ver detalle de uso</a></span>
            </div>
        </div>
        <!-- fin dtes usados (totales de emitidos y recibidos) -->
<?php endif; ?>
        <!-- folios disponibles -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="far fa-file-code fa-fw"></i>
                Folios disponibles
                <a href="admin/dte_folios" class="float-right" title="Ir al mantenedor de folios">
                    <i class="fa fa-cogs fa-fw"></i>
                </a>
            </div>
            <div class="card-body">
<?php foreach ($folios as $label => $value) : ?>
                <span><?=$label?></span>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?=$value?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$value?>%;">
                        <?=$value?>%
                    </div>
                </div>
<?php endforeach; ?>
            </div>
        </div>
        <!-- fin folios disponibles -->
        <!-- firma electrónica -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa fa-certificate fa-fw"></i>
                Firma electrónica
                <a href="admin/firma_electronicas" class="float-right" title="Ir al mantenedor de firmas electrónicas">
                    <i class="fa fa-cogs fa-fw"></i>
                </a>
            </div>
            <div class="card-body">
<?php if ($Firma) : ?>
                <p><?=$Firma->getName()?></p>
                <span class="float-right text-muted small"><em><?=$Firma->getID()?></em></span>
<?php else: ?>
                <p>No hay firma asociada al usuario ni a la empresa</p>
<?php endif; ?>
            </div>
        </div>
        <!-- firma electrónica -->
        <a class="btn btn-success btn-lg btn-block" href="admin/respaldos/exportar/all" role="button">
            <span class="fa fa-download"> Generar respaldo
        </a>
    </div>
    <!-- FIN PANEL DERECHA -->
</div>

<script>
Morris.Donut({
    element: 'grafico-ventas',
    data: <?=json_encode($ventas_periodo)?>,
    resize: true
});
Morris.Donut({
    element: 'grafico-compras',
    data: <?=json_encode($compras_periodo)?>,
    resize: true
});
Morris.Bar({
    element: 'grafico-dte_emitidos_estados',
    data: <?=json_encode($emitidos_estados)?>,
    xkey: 'estado',
    ykeys: ['total'],
    labels: ['DTEs'],
    resize: true
});
Morris.Bar({
    element: 'grafico-dte_emitidos_eventos',
    data: <?=json_encode($emitidos_eventos)?>,
    xkey: 'evento',
    ykeys: ['total'],
    labels: ['DTEs'],
    resize: true
});
<?php if ($rcof_estados) : ?>
Morris.Bar({
    element: 'grafico-rcof_estados',
    data: <?=json_encode($rcof_estados)?>,
    xkey: 'estado',
    ykeys: ['total'],
    labels: ['RCOFs'],
    resize: true
});
<?php endif; ?>
</script>
