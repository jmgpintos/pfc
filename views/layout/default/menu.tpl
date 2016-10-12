
{*<nav class="sidenav">
    <img id="titulo" src="img/logo.png" alt="Logo">
    <ul>
        <li class="active">home</li>
        <li>login</li>
        <li>registro</li>
    </ul>
</nav>
<div id="nav-mobile" class="col-tabl-6">
    <h1>
        Banco de imágenes
        <div class="hamburger-menu">
            <span class="hamburger" onclick='document.getElementById("menu-mobile").style.display = "block"'></span><!-- <span class="hamburger-text">Menú</span> -->
        </div>
    </h1>
    <ul id="menu-mobile">
        <li class="active">home</li>
        <li>login</li>
        <li>registro</li>
    </ul>

</div>*}
{*
<ul class="navbar blue padding-bottom">
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
</ul>*}


<nav class="sidenav">
    <img id="titulo" src="img/logo.png" alt="Logo">
    <ul>
    {if isset($_layoutParams.menu)}
        {foreach item=it from=$_layoutParams.menu}
            {if isset($it.submenu)}
                {if isset($_layoutParams.item) && $_layoutParams.item == $it.id}
                    {assign var='_clase' value = 'active'}
                {else}
                    {assign var='_clase' value = ''}
                {/if}
                    <a class="{$_item_style}">
                <li {if isset($it.derecha)}style="float:right;"{/if}  class="{$_clase} dropdown-hover">
                        {$it.titulo}
                        <i class="fa fa-caret-down"></i>
                    <div class="dropdown-content" role="menu">
                        {foreach item=sub from=$it.submenu}
                            <a href="{$sub.enlace}">{$sub.titulo}</a>
                        {/foreach}
                    </div>
                </li>
                    </a>
            {else}
                {if ({$_layoutParams.item} && {$it.id} == {$_layoutParams.item}) }
                    {assign var=_item_style value= 'current'}
                {else}
                    {assign var=_item_style value= ''}
                {/if}
                    <a class="{$_item_style}" href="{$it.enlace}">
                <li {if isset($it.derecha)}class="right"{/if}>
                        {$it.titulo}
                </li>
                    </a>
            {/if}
        {/foreach}
    {/if}
    </ul>
</nav>