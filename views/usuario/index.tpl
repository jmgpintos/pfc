
{if (isset($data) && count($data))}
    <table class="table striped margin-top">
        <thead>
            <tr>
                {foreach item=it from=$columnas}
                    <th>{$it}</th>
                    {/foreach}
            </tr>    
        </thead>
        {foreach item=it from=$data}
            <tr class="{if $it.estado==1}activo{else}inactivo{/if}">
                <td>{$it.id}</td>
                <td>{$it.nombre}</td>
                <td>{$it.estado}</td>
                <td>{$it.username}</td>
                <td>{$it.email}</td>
                <td>{$it.ultimo_acceso}</td>

                {if (Session::accesoView('especial'))}
                    <td><a href="{$_layoutParams.root}usuario/editar/{$it.id}">Editar</a></td>
                    <td><a href="{$_layoutParams.root}usuario/eliminar/{$it.id}">Eliminar</a></td>

                {/if}
            </tr>
        {/foreach}
    </table>
 <div class="padding-16 margin-top center blue-grey">
{if isset($paginacion)}{$paginacion}{/if}
</div>

{else}
    No hay usuarios
{/if}



{if (Session::accesoView('especial'))}

    <div class="padding-16 margin-top topbar border-blue">
        <div class='right'>
            <a href="{$_layoutParams.root}post/nuevo">Agregar post</a> | 
            <a href="{$_layoutParams.root}usuario/nuevoUsuarioAuto">Crear 20 usuarios</a> | 
            <a href="{$_layoutParams.root}usuario/borrarPruebas">Borrar pruebas</a></div>
    </div>
{/if}

