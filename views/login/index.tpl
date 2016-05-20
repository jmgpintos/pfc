
<form name="form1" method="post" action="">
    <input type="hidden" name="enviar" value="1" />

    <p>
        <label>Usuario: </label>
        <input type="text" name="usuario" value="{if isset($datos.usuario)}{$datos.usuario}{/if}">
    </p>

    <p>
        <label>Password: </label>
        <input type="password" name="pass">
    </p>

    <p>
        <input type="submit" value="Enviar">
    </p>
</form>