{include file="./nav.tpl"}
{if (isset($data) && count($data))}
    <table class="table striped imagenes">

        {include file=$_layoutParams.includes.cabecera_tabla}
        {foreach item=it from=$data}
            <tr>
                {*                <td>{$it.id}</td>*}
                <td>
                    <a 
                        href="{$_layoutParams.root}public/img/fotos/{$it.nombre_fichero}" 
                        target="_blank"
                        >
                        <img src="{$_layoutParams.root}public/img/fotos/thumbs/thumb_{$it.nombre_fichero}" >
                    </a>
                </td>
                <td>{$it.nombre}</td>
                <td>{$it.ancho_px} px x {$it.alto_px} px</td>
                <td>{$it.peso_kb} Kb</td>
                <td>{$it.categoria}</td>
                <td>{$it.licencia}</td>


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
    No hay imágenes
{/if}


{include file="./footer.tpl"}

