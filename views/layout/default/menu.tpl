
    <ul class="navbar blue padding-top">
        {if isset($_layoutParams.menu)}
            {foreach item=it from=$_layoutParams.menu}
                {if isset($it.submenu)}
                    {if isset($_layoutParams.item) && $_layoutParams.item == $it.id}
                        {assign var='_clase' value = 'active'}
                    {else}
                        {assign var='_clase' value = ''}
                    {/if}
                    <li {if isset($it.derecha)}style="float:right;"{/if}  class="{$_clase} dropdown-hover">
                        <a class="{$_item_style}">
                            {$it.titulo}
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <div class="dropdown-content" role="menu">
                            {foreach item=sub from=$it.submenu}
                                <a href="{$sub.enlace}">{$sub.titulo}</a>
                                {/foreach}
                        </div>
                    </li>
                {else}
                    {if ({$_layoutParams.item} && {$it.id} == {$_layoutParams.item}) }
                        {assign var=_item_style value= 'current'}
                    {else}
                        {assign var=_item_style value= ''}
                    {/if}
                    <li {if isset($it.derecha)}class="right"{/if}>
                        <a class="{$_item_style}" href="{$it.enlace}">
                            {$it.titulo}
                        </a>
                    </li>
                {/if}
            {/foreach}
        {/if}
    </ul>
</div>