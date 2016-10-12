
{if (isset($data) && count($data))}

    <div class="row imagen-ver">
        <div class="col-desk-1"></div>
        <div class="col-desk-4">
            <h3>{$data.nombre}</h3>
            <div class="col-desk-4 tarjeta">
                <div id="ver-img">
                    <img src="{$_layoutParams.root}public/img/fotos/{$data.nombre_fichero}" />
                    <div class="pie-de-foto">
                        <div class="tiempo" title="Fecha de subida">
                            <i class="fa fa-clock-o"></i>{$data.fecha_creacion}
                        </div>
                        <div class="categoria" title="Categoría">
                            {$data.categoria}<a href="{$_layoutParams.root}categoria/ver/{$data.id_categoria}" title="Ver imágenes de la categoría"><i class="fa fa-chain"></i></a>
                        </div><br/>
                    </div>
                </div>
                <ul>
                    <li><label>Licencia</label>: {$data.licencia}</li>
                    <li><label>Dimensiones</label>: {$data.ancho_px}x{$data.alto_px}</li>
                    <li><label>Tamaño</label>: {math equation="floor(x/1024)" x=$data.peso_bytes}Kb</li>
                    <li><label>Nombre del fichero</label>: {$data.nombre_fichero}</li>
                </ul>
            </div>
            <div class="col-desk-2 col-der">
                <dl>
                    <i class="fa fa-tags"></i><br>etiquetas, ...
                </dl>
            </div>
            <div class="row">
            </div>
            <div class="descripcion">
                <dt><label>Descripcion:</label></dt><dd>{$data.descripcion}</dd>
            </div>
        </div>
    </div>
    {include file=$_layoutParams.includes.navegacion}

    {if (Session::accesoView('especial'))}
        <td><a href="{$_layoutParams.root}imagen/editar/{$data.id}">Editar</a></td>
        <td><a href="{$_layoutParams.root}imagen/eliminar/{$data.id}">Eliminar</a></td>

    {/if}
{else}
    {*No hay imágenes*}
{/if}



{include file="./footer.tpl"}

