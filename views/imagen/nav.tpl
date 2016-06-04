<ul class="navbar right light-grey border">
    <li>
        <a {if $estilo=='list'}class="blue"{/if} href="{$_layoutParams.root}imagen/index/list">
            <i class="fa fa-list"></i>
        </a>
    </li>
    <li>
        <a {if $estilo=='table'}class="blue"{/if} href="{$_layoutParams.root}imagen/index/table">
            <i class="fa fa-table"></i>
        </a>
    </li>
    <li>
        <a {if $estilo=='card'}class="blue"{/if} href="{$_layoutParams.root}imagen/index/card">
            <i class="fa fa-photo"></i>
        </a>
    </li>
</ul>