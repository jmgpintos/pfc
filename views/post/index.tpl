<h2>Últimos Posts</h2>
{if (isset($posts) && count($posts))}
    <table>
       <thead>
            <tr>
                {foreach item=it from=$columnas}
                    <th>{$it}</th>
                    {/foreach}
            </tr>    
        </thead>
        {foreach item=it from=$posts}
            <tr>
                <td>{$it.id}</td>
                <td>{$it.titulo}</td>
                <td>{$it.cuerpo}</td>

                {if (Session::accesoView('especial'))}
                    <td><a href="{$_layoutParams.root}post/editar/{$it.id}">Editar</a></td>
                    <td><a href="{$_layoutParams.root}post/eliminar/{$it.id}">Eliminar</a></td>

                {/if}
            </tr>
        {/foreach}
    </table>
{else}
    No hay posts
{/if}


{if isset($paginacion)} {$paginacion}{/if}

{if (Session::accesoView('especial'))}
    <p>
        <a href="{$_layoutParams.root}post/nuevo">Agregar post</a> | 
        <a href="{$_layoutParams.root}post/crear100">Crear 100 posts</a> | 
        <a href="{$_layoutParams.root}post/borrarPruebas">Borrar pruebas</a>
    </p>
{/if}

