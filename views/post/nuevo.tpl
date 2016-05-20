
<form id="form1" method="post" action="{$_layoutParams.root}post/nuevo">
    <input type="hidden" name="guardar" value="1"/>
    <p>
        Título: <br/>
        <input type="text" name="titulo" value="{if isset($datos.titulo)}{$datos.titulo}{/if}"/>
    </p>
    <p>
        Cuerpo: <br/>
        <textarea name="cuerpo" >{if isset($datos.cuerpo)}{$datos.cuerpo}{/if}</textarea>
    </p>
    <p>
        <input type="submit" class="button" value="Guardar" />
    </p>
</form>
