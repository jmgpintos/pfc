<div class='content'>
    {if (isset($data) && count($data))}
        <table class="table striped border margin-top">
            {include file=$_layoutParams.includes.cabecera_tabla}
            {foreach item=it from=$data}
                <tr>
                    <td>{$it.nombre}</td>
                    <td>{$it.descripcion}</td>


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
        
        {include file=$_layoutParams.includes.paginacion}

    {else}
        No hay datos
    {/if}



    {if (Session::accesoView('especial'))}

        <div class="padding-16 margin-top topbar border-blue">
            <div class='right'>
                <a class="btn round blue" href="{$_layoutParams.root}post/nuevo">Agregar post</a>
                <a class="btn " href="{$_layoutParams.root}usuario/nuevoUsuarioAuto">Crear 20 usuarios</a>
                <a class="btn  " href="{$_layoutParams.root}usuario/borrarPruebas">Borrar pruebas</a></div>
        </div>
    {/if}

</div>