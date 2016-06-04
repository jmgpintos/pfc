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
</h2>
</div>