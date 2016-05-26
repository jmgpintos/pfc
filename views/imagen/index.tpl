
{if (isset($data) && count($data))}
    <table class="table striped">
       <thead>
            <tr>
                {foreach item=it from=$columnas}
                    <th>{$it}</th>
                    {/foreach}
            </tr>    
        </thead>
        {foreach item=it from=$data}
            <tr>
                <td>{$it.id}</td>
                <td>{$it.nombre}</td>
                <td>
                    <a href="{$_layoutParams.root}public/img/fotos/{$it.nombre_fichero}">
                        <img src="{$_layoutParams.root}public/img/fotos/thumbs/thumb_{$it.nombre_fichero}" >
                    </a>
                </td>

                {if (Session::accesoView('especial'))}
                    <td><a href="{$_layoutParams.root}imagen/editar/{$it.id}">Editar</a></td>
                    <td><a href="{$_layoutParams.root}imagen/eliminar/{$it.id}">Eliminar</a></td>

                {/if}
            </tr>
        {/foreach}
    </table>
{else}
    No hay imágenes
{/if}


{if isset($paginacion)}{$paginacion}{/if}

{if (Session::accesoView('especial'))}
    <p>
        <a href="{$_layoutParams.root}imagen/nuevo">Agregar imagen</a> | 
        <a href="{$_layoutParams.root}imagen/crear100">Crear 100 posts</a> | 
        <a href="{$_layoutParams.root}imagen/borrarPruebas">Borrar pruebas</a>
    </p>
{/if}

