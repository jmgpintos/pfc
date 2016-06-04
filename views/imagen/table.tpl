
{include file="./nav.tpl"}
<div class="content">
    {if (isset($data) && count($data))}
        {foreach item=it from=$data}
            {*            {$it|@print_r}*}
            <div class="row margin card-8 padding-12">
                {assign var=hor value=($it.ancho_px > $it.alto_px)} 

                <div class="third {if !$hor}center{/if}">
                    <a 
                        href="{$_layoutParams.root}public/img/fotos/{$it.nombre_fichero}" 
                        target="_blank"
                        >
                        <img 
                            class="card-4 padding-tiny margin-left"
                            src="{$_layoutParams.root}public/img/fotos/{$it.nombre_fichero}"
                            {if $hor}
                                style="width:95%"
                            {else}
                                style="width:50%;min-height:200px"
                            {/if}
                            >
                    </a>
                </div>
                <div class="twothird container ">
                    <h2 class="text-blue border-bottom">{$it.nombre}</h2>
                    Categor&iacute;a: <strong>{$it.categoria|capitalize}</strong>
                    <div class="margin-bottom">{$it.descripcion|nl2br}</div>
                    <div class="light-blue padding-medium margin-bottom round-large">
                        <strong>Etiquetas: </strong>
                        demonifuge, waddly, ratite, macadam, Sacae, soother, idiotize
                    </div>
                    <div class="third right container blue padding-4 round-large">
                        <div>{$it.ancho_px}px x {$it.alto_px}px</div>
                        <div>{$it.peso_kb} Kb</div>
                        <div>Licencia: {$it.licencia}</div>
                    </div>
                </div>

            </div>
        {/foreach}
    {/if}
</div>

{include file="./footer.tpl"}