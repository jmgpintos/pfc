{if isset($navegacion)}
    <div class="padding-16 margin-top center blue-grey">
    <div  class="paginacion" style="text-align:center;">
        <ul>
            {if ($primero.num)}
                <a href="{$primero.num}""><li class="numero {$primero.estilo}"><i class="fa fa-backward"></i></li></a>
                    {else}
                <li class="{$primero.estilo}"><i class="fa fa-backward"></i></li>
                {/if}
                {if ($anterior.num)}
                <a href="{$anterior.num}""><li class="numero {$anterior.estilo}"><i class="fa fa-chevron-left"></i></li></a>
                    {else}
                <li class="{$anterior.estilo}"><i class="fa fa-chevron-left"></i></li>
                {/if}
                {if ($siguiente.num)}
                <a href="{$siguiente.num}"><li class="numero {$siguiente.estilo}"><i class="fa fa-chevron-right"></i></li></a>
                    {else}
                <li class="{$siguiente.estilo}"><i class="fa fa-chevron-right"></i></li>
                {/if}
                {if ($ultimo.num)}
                <a href="{$ultimo.num}"><li class="numero {$ultimo.estilo}"><i class="fa fa-forward"></i></li></a>
                    {else}
                <li class="{$ultimo.estilo}"><i class="fa fa-forward"></i></li>
                {/if}
        </ul>
    </div>
    </div>
{/if}