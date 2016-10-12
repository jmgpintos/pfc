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
                        <img src="{$_layoutParams.root}public/img/fotos/{$thumbs_dir}{$thumb_prefix}{$it.nombre_fichero}" >
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
                            <a href="#"
                               onclick="confirmarBorrar('{$it.nombre}', '{$it.id}')"
                               >
                                <i class="fa fa-close text-red"></i>
                            </a>
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


<!--modal confirmación borrado-->
<div id="confirmarBorrar" class="modal">
    <div class="modal-content red">
        <header class="container">
            <span onclick="document.getElementById('confirmarBorrar').style.display = 'none'" 
                  class="closebtn">&times;</span>
            <h2>
                {if isset($tituloEliminar)}
                    {$tituloEliminar}
                {else}
                    Borrar registro
                {/if}
            </h2>
        </header>
        <div class="container white">
            <p>Seguro que desea borrar la imagen <span class="destacado" id="nombre-imagen"></span>?</p>
        </div>
        <footer class="container padding-12 white border-red topbar">
            <div class="right">
                <a id="linkBorrar" class="btn red padding" href="{$_layoutParams.root}{$controlador}eliminar/">Si</a>
                <span class="btn round padding " onclick="document.getElementById('confirmarBorrar').style.display = 'none'" 
                      >No</span>
            </div>
        </footer>
    </div>
</div>