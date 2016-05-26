
<form id="form1" method="post" action="{$_layoutParams.root}imagen/nuevo" enctype="multipart/form-data">
    <input type="hidden" name="guardar" value="1"/>
    <p>
        Nombre: <br/>
        <input type="text" name="nombre" value="{if isset($datos.nombre)}{$datos.nombre}{/if}"/>
    </p>
    <p>
        <input type="file" name="imagen">
    </p>
    <p>
        <input type="submit" class="button" value="Guardar" />
    </p>
</form>
