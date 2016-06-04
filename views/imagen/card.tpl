{include file="./nav.tpl"}
<div class="">
    {if (isset($data) && count($data))}
        {*            {$it|@print_r}*}
        <div class="row-padding margin-top">
            {$row=0}
            {foreach item=it from=$data}
                {if ($it@iteration%3-1)==0}
                    {$row=$row+1}
                    {$col=1}
                </div>
                <div class="row-padding margin-top">
                {else}
                    {$col=$col+1}
                {/if} 

                <div class="third">
                    <div class="card-2"> <a 
                            href="{$_layoutParams.root}public/img/fotos/{$it.nombre_fichero}" 
                            target="_blank"
                            >
                            <img id="r{$row}c{$col}" src="{$_layoutParams.root}public/img/fotos/{$it.nombre_fichero}" style="width:100%">
                        </a>
                        <div class="container">
                            <h4>{$it.nombre}</h4>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    {/if}
</div>

{include file="./footer.tpl"}

