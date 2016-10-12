<div>
    {if isset($tituloView) or isset($titulo)}
        <h2>
            {if isset($tituloView)}
                {$tituloView}
            {else}
                {if isset($titulo)}
                    {$titulo }
                {/if}
            {/if}
            {if isset($cuenta)}
                <div id="cuenta">{$cuenta}</div>
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
    {/if}
</div>
<script type="text/javascript">
    var el = jQuery("#cuenta");
    var cuenta = jQuery("#cuenta").html();
    if (cuenta >= 10000) {
        el.css('font-size', '45%')
    } else if (cuenta >= 1000) {
        el.css('font-size', '58%')
    } else if (cuenta >= 100) {
        el.css('font-size', '77%')
    }
</script>