
{if (isset($data) && count($data))}
    <table class="table striped">
       
        {include file=$_layoutParams.includes.cabecera_tabla}
        {foreach item=it from=$data}
            <tr>
{*                <td>{$it.id}</td>*}
                <td>{$it.nombre}</td>
                <td>
                    <a 
                        href="{$_layoutParams.root}public/img/fotos/{$it.nombre_fichero}" 
                        target="_blank"
                        >
                        <img src="{$_layoutParams.root}public/img/fotos/thumbs/thumb_{$it.nombre_fichero}" >
                    </a>
                </td>
                <td>{$it.ancho_px} px</td>

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




{include file="./footer.tpl"}

