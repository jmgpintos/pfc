<div class="container blue ">
    <h2>
        {if isset($tituloView)}
            {$tituloView}
        {else}
            {if isset($titulo)}
                {$titulo }
            {/if}
        {/if}
        {if isset($cuenta)}
            &mdash; {$cuenta}
        {/if}
        {if (Session::accesoView('especial'))}
            {if isset($ponerBtnNuevo)}
                <a 
                    class="btn small round light-blue right"
                    href="{$_layoutParams.root}{$controlador}nuevo"
                    title="{$ponerBtnNuevo}">
                    +
                </a>
            {/if}
        {/if}
    </h2>
</div>