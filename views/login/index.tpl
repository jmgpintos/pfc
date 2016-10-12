<div class="row" >
    <div class="col-desk-2"></div>
    <div id="login-box" class="col-desk-2">
        <form name="form1" method="post" action="">
            <input type="hidden" name="enviar" value="1" />

            <div class="col-desk-2 label"><label>Usuario: </label></div>
            <div class="col-desk-4"><input type="text" name="usuario" value="{if isset($datos.usuario)}{$datos.usuario}{/if}" autofocus></div>

            <div class="col-desk-2 label"><label>Password: </label></div>
            <div class="col-desk-4"><input type="password" name="pass"></div>

            <div class="col-desk-2"></div>
            <div class="col-desk-4"><input type="submit" value="Entrar"></div>
        </form>
    </div>
    <div class="col-desk-1">
        <ul>
            <li>Administrador: admin</li>
            <li>Especial: esp</li>
            <li>Usuario: pepe</li>
            <hr>
            <li>PW: 1234</li>
        </ul>
    </div>
</div>