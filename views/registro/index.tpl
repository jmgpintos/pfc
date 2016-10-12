
<div class="row">
    <div class="col-desk-2"></div>
    <div id="login-box" class="col-desk-2">
        <form name="form1" method="post" action="">
            <input type="hidden" name="enviar" value="1" />

            <div class="col-desk-2 label">
                <label>Nombre: </label>
            </div>
            <div class="col-desk-4">
                <input type="text" name="nombre" value="{if isset($datos.nombre)}{$datos.nombre}{/if}">
            </div>

            <div class="col-desk-2 label">
                <label>Usuario: </label>
            </div>
            <div class="col-desk-4">
                <input type="text" name="usuario" value="{if isset($datos.usuario)}{$datos.usuario}{/if}">
            </div>

            <div class="col-desk-2 label">
                <label>Email: </label>
            </div>
            <div class="col-desk-4">
                <input type="text" name="email" value="{if isset($datos.email)}{$datos.email}{/if}">
            </div>

            <div class="col-desk-2 label">
                <label>Password: </label>
            </div>
            <div class="col-desk-4">
                <input type="password" name="pass">
            </div>
            <div class="col-desk-2 label">
                <label>Confirmar: </label>
            </div>
            <div class="col-desk-4">
                <input type="password" name="confirmar">
            </div>

            <div class="col-desk-2 label"></div>
            <div class="col-desk-4"><input type="submit" value="Enviar"></div>
        </form>
    </div>
</div>