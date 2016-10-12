<h3 id="mensaje" class="error">
        {if ($mensaje)}{$mensaje}{/if}
</h3>

<p>
    &nbsp;
</p>


<a href="{$_layoutParams.root}">Ir al Inicio</a>
<a href="javascript:history.back(1)">Volver a la p&aacute;gina anterior</a>

{if (!Session::estaAutenticado())}
| <a href="{$_layoutParams.root}login">Iniciar sesi&oacute;n</a>
{/if}

