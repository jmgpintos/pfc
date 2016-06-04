

{if isset($paginacion)}
    <div class="padding-16 margin-top center blue-grey">
        {$paginacion}
    </div>
{/if}

{if (Session::accesoView('especial'))}
    <div class="padding-16 margin-top topbar border-blue">
        <div class='right'>
            <a href="{$_layoutParams.root}imagen/nuevo">Agregar imagen</a> | 
            <a href="{$_layoutParams.root}imagen/crear100">Crear 100 posts</a> | 
            <a href="{$_layoutParams.root}imagen/borrarPruebas">Borrar pruebas</a>
        </div>
    </div>
{/if}