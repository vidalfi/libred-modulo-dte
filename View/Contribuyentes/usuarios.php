<ul class="nav nav-pills float-right">
    <li class="nav-item">
        <a href="#" onclick="__.popup('<?=$_base?>/dte/sii/contribuyente_usuarios/<?=$Contribuyente->rut?>-<?=$Contribuyente->dv?>', 850, 700); return false" title="Ver usuarios del contribuyente en el SII" class="nav-link">
            <i class="fa fa-users"></i>
            Usuarios en SII
        </a>
    </li>
    <li class="nav-item">
        <a href="<?=$_base?>/dte/contribuyentes/modificar/<?=$Contribuyente->rut?>" title="Modificar empresa" class="nav-link">
            <i class="fa fa-edit"></i>
            Modificar
        </a>
    </li>
    <li class="nav-item">
        <a href="<?=$_base?>/dte/contribuyentes/seleccionar/<?=$Contribuyente->rut?>" title="Seleccionar empresa" class="nav-link">
            <i class="fa fa-check"></i>
            Seleccionar
        </a>
    </li>
</ul>
<div class="page-header"><h1>Mantenedor de usuarios</h1></div>
<p>Aquí podrá modificar los usuarios autorizados a operar con la empresa <?=$Contribuyente->razon_social?> RUT <?=num($Contribuyente->rut).'-'.$Contribuyente->dv?>, para la cual usted es el usuario administrador.</p>

<script type="text/javascript">
$(function() {
    var url = document.location.toString();
    if (url.match('#')) {
        $('#'+url.split('#')[1]+'-tab').tab('show');
    }
});
</script>

<div role="tabpanel">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item"><a href="#usuarios" aria-controls="usuarios" role="tab" data-toggle="tab" id="usuarios-tab" class="nav-link active" aria-selected="true">Usuarios autorizados</a></li>
        <li class="nav-item"><a href="#dtes" aria-controls="dtes" role="tab" data-toggle="tab" id="dtes-tab" class="nav-link">Documentos por usuario</a></li>
        <li class="nav-item"><a href="#general" aria-controls="general" role="tab" data-toggle="tab" id="general-tab" class="nav-link">General</a></li>
    </ul>
    <div class="tab-content pt-4">

<!-- INICIO USUARIOS AUTORIZADOS -->
<div role="tabpanel" class="tab-pane active" id="usuarios" aria-labelledby="usuarios-tab">
<p>Aquí puede autorizar a otros usuarios, previamente registrados, a trabajar con la empresa.</p>
<p>El usuario se debe <a href="<?=$_base?>/usuarios/registrar">registrar aquí</a> (cierre su sesión primero si desea registrar usted mismo al usuario).</p>
<?php
// inputs y ayuda
$inputs = [['name'=>'usuario', 'check'=>'notempty']];
$permisos_ayuda = '<ul>';
foreach ($permisos_usuarios as $permiso => $info) {
    $permisos_ayuda .= '<li><strong>'.$permiso.'</strong>: '.$info['nombre'].' <small>('.$info['descripcion'].')</small>'.'</li>';
    $inputs[] = ['type'=>'select', 'name'=>'permiso_'.$permiso, 'options'=>['No', 'Si']];
}
$permisos_ayuda .= '</ul>';
// usuarios y sus permisos
$usuarios = [];
foreach ($Contribuyente->getUsuarios() as $u => $p) {
    $permisos = [];
    foreach ($permisos_usuarios as $permiso => $info) {
        $permisos['permiso_'.$permiso] = (int)in_array($permiso, $p);
    }
    $usuarios[] = array_merge(['usuario'=>$u], $permisos);
}
// mantenedor usuarios
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin([
    'id' => 'usuarios',
    'onsubmit' => 'Form.check(\'usuarios\') && Form.checkSend()',
]);
$f->setStyle(false);
echo $f->input([
    'type' => 'js',
    'id' => 'usuarios',
    'label' => 'Usuarios autorizados',
    'titles' => array_merge(['Usuario'], array_keys($permisos_usuarios)),
    'inputs' => $inputs,
    'values' => $usuarios,
]);
$f->setStyle('horizontal');
echo $f->end('Modificar usuarios autorizados');
?>
<div class="card">
    <div class="card-body">
        <p>Debe ingresar el nombre del usuario que desea autorizar y alguno de los permisos:</p>
        <?=$permisos_ayuda,"\n"?>
    </div>
</div>
</div>
<!-- FIN USUARIOS AUTORIZADOS -->

<!-- INICIO DOCUMENTOS POR USUARIOS -->
<div role="tabpanel" class="tab-pane" id="dtes" aria-labelledby="dtes-tab">
<p>Aquí puede asignar los documentos que un usuario puede emitir.</p>
<?php
echo $f->begin([
    'action' => '../usuarios_dtes/'.$Contribuyente->rut,
    'id' => 'usuarios_dtes',
    'onsubmit' => 'Form.check(\'usuarios_dtes\')',
]);
$usuarios_dtes = [];
$aux = $Contribuyente->getDocumentosAutorizados();
$documentos_autorizados = [];
foreach ($aux as $d) {
    $documentos_autorizados[$d['codigo']] = $d['tipo'];
}
$inputs = [['name'=>'usuario', 'check'=>'notempty', 'attr'=>'readonly="readonly"']];
foreach ($documentos_autorizados as $codigo => $tipo) {
    $inputs[] = ['type'=>'select', 'name'=>'dte_'.$codigo, 'options'=>['No', 'Si']];
}
$autorizados = $Contribuyente->getDocumentosAutorizadosPorUsuario();
foreach ($Contribuyente->getUsuarios() as $u => $p) {
    $documentos = [];
    foreach ($documentos_autorizados as $codigo => $tipo) {
        if (!empty($autorizados[$u])) {
            $documentos['dte_'.$codigo] = (int)in_array($codigo, $autorizados[$u]);
        } else {
            $documentos['dte_'.$codigo] = 0;
        }
    }
    $usuarios_dtes[] = array_merge(['usuario'=>$u], $documentos);
}
$f = new \sowerphp\general\View_Helper_Form();
$f->setStyle(false);
echo $f->input([
    'type' => 'table',
    'id' => 'usuarios_dtes',
    'label' => 'DTEs x usuarios',
    'titles' => array_merge(['Usuario'], array_keys($documentos_autorizados)),
    'inputs' => $inputs,
    'values' => $usuarios_dtes,
]);
$f->setStyle('horizontal');
echo $f->end('Guardar documentos por usuarios');
?>
<div class="card">
    <div class="card-body">
        <p>Documentos que la empresa tiene autorizados en LibreDTE:</p>
        <ul>
<?php foreach ($documentos_autorizados as $codigo => $tipo) : ?>
            <li><strong><?=$codigo?></strong>: <?=$tipo?></li>
<?php endforeach; ?>
        </ul>
    </div>
</div>
</div>
<!-- FIN DOCUMENTOS POR USUARIOS -->

<!-- INICIO GENERAL -->
<div role="tabpanel" class="tab-pane" id="general" aria-labelledby="general-tab">
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa fa-cogs"></i>
            Configuración general usuarios
        </div>
        <div class="card-body">
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin([
    'action' => '../usuarios_general/'.$Contribuyente->rut,
    'id' => 'general',
    'onsubmit' => 'Form.check(\'general\')',
]);
echo $f->input([
    'type' => 'select',
    'name' => 'config_usuarios_auth2',
    'label' => '¿Requerir Auth2?',
    'options' => ['No es obligatorio', 'Sólo usuarios administradores', 'Todos los usuarios autorizados'],
    'value' => $Contribuyente->config_usuarios_auth2,
    'help' => 'Esto mejora la seguridad exigiendo que usuarios autorizados usen doble factor de autenticación',
]);
echo $f->end('Guardar configuración');
?>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa fa-user-secret"></i>
            Administrador de la empresa
        </div>
        <div class="card-body">
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin([
    'action' => '../transferir/'.$Contribuyente->rut,
    'id' => 'transferir',
    'onsubmit' => 'Form.check(\'transferir\') && Form.checkSend(\'¿Está seguro de querer transferir la empresa al nuevo usuario?\')',
]);
echo $f->input([
    'name' => 'usuario',
    'label' => 'Usuario',
    'value' => $Contribuyente->getUsuario()->usuario,
    'check' => 'notempty',
    'help' => 'Usuario que actúa como administrador principal de la empresa en LibreDTE',
]);
echo $f->end('Cambiar usuario administrador');
?>
        </div>
    </div>
</div>
<!-- FIN GENERAL -->

    </div>
</div>
