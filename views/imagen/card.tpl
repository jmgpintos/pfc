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
                <script>
                    $(){
                    $(".imagen-card").on("hover", function () {
                        onhover = "mostrarInfo()"
                        }
                        
                        function mostrarInfo() {
                            console.log(this);
                            var el = "#" + imagen + " .info";
                            $(el).toggleClass("hide")
                        }
                    }
                </script>
                <div class="third">
                    <div id="r{$row}c{$col}" class="card-2 imagen-card" >
                        <a 
                            href="{$_layoutParams.root}public/img/fotos/{$it.nombre_fichero}" 
                            target="_blank"
                            >
                            <img                                 
                                src="{$_layoutParams.root}public/img/fotos/{$mids_dir}{$mid_prefix}{$it.nombre_fichero}" 
                                style="width:100%"
                                >
                        </a>
                        <div class="container">
                            <h4>{$it.nombre}</h4>
                        </div>
                        <div class="info hide">
                            XXXXXXXXXXXXXX
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    {/if}
</div>

{include file="./footer.tpl"}

