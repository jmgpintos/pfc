
<div class="row">
    <div {*id="container-portada"*}>
        <div class="row frontpage">
            {foreach item=it from=$data}
                {include file=$_layoutParams.includes.lista_imagenes}
            {/foreach}
        </div>

    </div>
</div>
        
        {$paginacion}