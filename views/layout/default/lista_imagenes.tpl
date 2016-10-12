<div class="col-desk-2 col-tabl-3">
    <div class="tarjeta text-center">
        <div class="editar"><a href="{$_layoutParams.root}imagen/editar/{$it.id}" title="Editar"><i class="fa  fa-pencil"></i></a></div>
        <div class="titulo">{$it.nombre_fichero}</div>
        <a href="{$_layoutParams.root}imagen/ver/{$it.id}" title="Pulse para ver info sobre la imagen">
            <img src="{$_layoutParams.root}public/img/fotos/{$it.nombre_fichero}">
        </a>
        <div class="nombre-foto">
            {$it.nombre}
        </div>
        <div class="pie-de-foto">
            <div class="tiempo" title="Fecha de subida">
                <i class="fa fa-clock-o"></i>{$it.fecha_creacion}
            </div>
            <div class="categoria" title="Categoría">
                {$it.categoria}<a href="categoria/ver/{$it.id_categoria}" title="Ver imágenes de la categoría"><i class="fa fa-chain"></i></a>
            </div><br/>
            <div class="info" title="Información sobre la imagen">
                <i class="fa  fa-info-circle"></i>   
                {$it.ancho_px}x{$it.alto_px}
                {math equation="floor(x/1024)" x=$it.peso_bytes}Kb
                {$it.licencia}
            </div>
        </div>
    </div>
</div>