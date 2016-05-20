


<form name="form1" method="post" action="">
    <input type="hidden" name="enviar" value="1" />

    <p>
        <label>Nombre: </label>
        <input type="text" name="nombre" value="{if isset($datos.nombre)}{$datos.nombre}{/if}">
    </p>

    <p>
        <label>Usuario: </label>
        <input type="text" name="usuario" value="{if isset($datos.usuario)}{$datos.usuario}{/if}">
    </p>

    <p>
        <label>Email: </label>
        <input type="text" name="email" value="{if isset($datos.email)}{$datos.email}{/if}">
    </p>

    <p>
        <label>Password: </label>
        <input type="password" name="pass">
    </p>
    <p>
        <label>Confirmar: </label>
        <input type="password" name="confirmar">
    </p>

    <p>
        <input type="submit" value="Enviar">
    </p>
</form>