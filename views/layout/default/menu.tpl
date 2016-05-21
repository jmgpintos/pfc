<div id="top_menu">
    <ul>
        {if isset($_layoutParams.menu)}
            {foreach item=it from=$_layoutParams.menu}
                {if ({$_layoutParams.item} && {$it.id} == {$_layoutParams.item}) }
                    {assign var=_item_style value= 'current'}
                {else}
                    {assign var=_item_style value= ''}
                {/if}
                <li>
                    <a class="{$_item_style}" href="{$it.enlace}">
                        {$it.titulo}
                    </a>
                </li>
            {/foreach}
        {/if}
    </ul>
</div>