 
{*<div class="row">
<div class="random-button">
<form method="POST"> 
<input type="submit" name="random" value="R A N D O M" />
</form>
</div>
<div id="container-portada">

<div class="row">
{foreach item=it from=$data}                
{include file=$_layoutParams.includes.lista_imagenes}
{/foreach}
</div>
</div>
*}




<div class="row frontpage">
    <div class="col-desk-1"></div>
    <div class="col-desk-4">
        <div class="tarjeta">
            <div id="ver-img">
                <a href="{$_layoutParams.root}imagen/ver/{$data.id}" title="Pulse para ver info sobre la imagen">
                    <img src="{$_layoutParams.root}public/img/fotos/{$data.nombre_fichero}" />
                </a>
                <div class="pie-de-foto">
                    <div class="tiempo" title="Fecha de subida">
                        <i class="fa fa-clock-o"></i>{$data.fecha_creacion}
                    </div>
                    <div class="categoria" title="Categoría">
                        {$data.categoria}<a href="{$_layoutParams.root}categoria/ver/{$data.id_categoria}" title="Ver imágenes de la categoría"><i class="fa fa-chain"></i></a>
                    </div><br/>
                </div>
            </div>
            {*<ul>
            <li><label>Licencia</label>: {$data.licencia}</li>
            <li><label>Dimensiones</label>: {$data.ancho_px}x{$data.alto_px}</li>
            <li><label>Tamaño</label>: {math equation="floor(x/1024)" x=$data.peso_bytes}Kb</li>
            <li><label>Nombre del fichero</label>: {$data.nombre_fichero}</li>
            <li><label>Nombre</label>: {$data.nombre}</li>
            </ul>*}
            <div class="descripcion">
                {$data.descripcion}
            </div>
        </div>
    </div>
</div>

{* <div  class="paginacion" style="text-align:center;">
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
</div>*}
<script type="text/javascript">
    var r =jQuery("h2");
    var icon = " <div id='random'><a href='/' title='Cargar otra imagen'><i class='fa fa-refresh'></i></a></div>";
    r.html(r.html() + icon);
</script>