
<div class='content'>
    {if (isset($data) && count($data))}
        <table class="table striped margin-top">
            {include file=$_layoutParams.includes.cabecera_tabla}
            {foreach item=it from=$data}
                <tr class="{if $it.estado==1}activo{else}inactivo{/if}">
                    {*                <td>{$it.id}</td>*}
                    <td>{$it.nombre}</td>
                    <td>{$it.categoria_padre}</td>


                    {if (Session::accesoView('especial'))}
                        <td>
                            <a href="{$_layoutParams.root}{$controlador}editar/{$it.id}">
                                <i class="fa fa-pencil text-primary"></i>
                            </a>
                            <a href="#"
                               onclick="confirmarBorrar('{$it.nombre}', '{$it.id}')"
                               >
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
                <a class="btn round blue" href="{$_layoutParams.root}{$controlador}nuevo">Agregar categoría</a>
                <a class="btn" href="{$_layoutParams.root}{$controlador}crearAutomatico">Crear 50 categorías</a>
                <a class="btn" href="{$_layoutParams.root}usuario/borrarPruebas">Borrar pruebas</a></div>
        </div>
    {/if}

</div>


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
            <p>Seguro que desea borrar la categoria <span class="destacado" id="nombre-categoria"></span>?</p>
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