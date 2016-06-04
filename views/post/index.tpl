
{if (isset($posts) && count($posts))}
    <table class="table striped">
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
                    <td>
                        <a href="{$_layoutParams.root}{$controlador}editar/{$it.id}">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a href="{$_layoutParams.root}{$controlador}eliminar/{$it.id}">
                            <i class="fa fa-close text-red"></i>
                        </a>
                    </td>
                {/if}
            </tr>
        {/foreach}
    </table>
{else}
    No hay posts
{/if}

{include file=$_layoutParams.includes.paginacion}

{if (Session::accesoView('especial'))}
    <p>
        <a href="{$_layoutParams.root}post/nuevo">Agregar post</a> | 
        <a href="{$_layoutParams.root}post/crear100">Crear 100 posts</a> | 
        <a href="{$_layoutParams.root}post/borrarPruebas">Borrar pruebas</a>
    </p>
{/if}

