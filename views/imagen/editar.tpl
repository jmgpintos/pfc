EDITAR
{*{$data|@print_r}*}
{if (isset($data) && count($data))}

    <div class="row">
        <div class="col-desk-6">
            <div id="editar-img" class="tarjeta">
                <img src="{$_layoutParams.root}public/img/fotos/{$data.nombre_fichero}" />
                <div class="pie-de-foto">
                    <div class="tiempo" title="Fecha de subida">
                        <i class="fa fa-clock-o"></i>{$data.fecha_creacion}
                    </div>
                    <div class="categoria" title="Categoría">
                        {$data.categoria}<a href="{$_layoutParams.root}categoria/ver/{$data.id_categoria}" title="Ver imágenes de la categoría"><i class="fa fa-chain"></i></a>
                    </div><br/>
                    <div class="col-desk-2 col-izq">
                        <ul>
                            <li><label>Licencia</label>: {$data.licencia}</li>
                            <li><label>Dimensiones</label>: {$data.ancho_px}x{$data.alto_px}</li>
                            <li><label>Tamaño</label>: {math equation="floor(x/1024)" x=$data.peso_bytes}Kb</li>
                            <li><label>Nombre del fichero</label>: {$data.nombre_fichero}</li>
                            <li><label>Nombre</label>: {$data.nombre}</li>
                        </ul>
                        <form method="get">
                            <div>
                                <label>Categoria</label>: 
                                <select name="cmbCategoria"> 
                                    {foreach from=$cmbCategorias item=it} 
                                        <option value="{$it.id}" {if ($it.id == $data.id_categoria)}selected{/if}>{$it.nombre} 
                                        {/foreach} 
                                </select>
                            </div>
                            <div>
                                <label><i class="fa fa-tags"></i></label><br><textarea rows="5"></textarea>
</div>
                                <input type="submit" />
</form>
                    </div>
                    <div class="col-desk-4 col-der">
                        <dl>
                            <dt><label>Descripcion:</label></dt><dd>{$data.descripcion}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div  class="paginacion" style="text-align:center;">
        <ul>
            {if ($primero.num)}
                <a href="{$primero.num}""><li class="numero {$primero.estilo}"><i class="fa fa-backward"></i></li></a>
                    {else}
                <li class="numero {$primero.estilo}"><i class="fa fa-backward"></i></li>
                {/if}
                {if ($anterior.num)}
                <a href="{$anterior.num}""><li class="numero {$anterior.estilo}"><i class="fa fa-chevron-left"></i></li></a>
                    {else}
                <li class="numero {$anterior.estilo}"><i class="fa fa-chevron-left"></i></li>
                {/if}
                {if ($siguiente.num)}
                <a href="{$siguiente.num}"><li class="numero {$siguiente.estilo}"><i class="fa fa-chevron-right"></i></li></a>
                    {else}
                <li class="numero {$siguiente.estilo}"><i class="fa fa-chevron-right"></i></li>
                {/if}
                {if ($ultimo.num)}
                <a href="{$ultimo.num}"><li class="numero {$ultimo.estilo}"><i class="fa fa-forward"></i></li></a>
                    {else}
                <li class="numero {$ultimo.estilo}"><i class="fa fa-forward"></i></li>
                {/if}
        </ul>
    </div>

    {if (Session::accesoView('especial'))}
        <td><a href="{$_layoutParams.root}imagen/editar/{$data.id}">Editar</a></td>
        <td><a href="{$_layoutParams.root}imagen/eliminar/{$data.id}">Eliminar</a></td>

    {/if}
{else}
    {*No hay imágenes*}
{/if}




{include file="./footer.tpl"}

