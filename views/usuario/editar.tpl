<div class="row">
    <div class="col">
        <form name="form1" method="post" action="">
            <input type="hidden" name="guardar" value="1" />
            <input type="hidden" name="id" value="{$data.id}" />

            <p>
                <label class="text-teal">Usuario: </label>
                <input class="input border round light-grey" type="text" name="username" value="{if isset($data.username)}{$data.username}{/if}">
            </p>

            <p>
                <label class="text-teal">Nombre: </label>
                <input class="input border round light-grey" type="text" name="nombre" value="{if isset($data.nombre)}{$data.nombre}{/if}">
            </p>

            <p>
                <label class="text-teal">Apellidos: </label>
                <input class="input border round light-grey" type="text" name="apellidos" value="{if isset($data.apellidos)}{$data.apellidos}{/if}">
            </p>

            <p>
                <label class="text-teal">Email: </label>
                <input class="input border round light-grey" type="text" name="email" value="{if isset($data.email)}{$data.email}{/if}">
            </p>

            <p>
                <label class="text-teal">Tel&eacute;fono: </label>
                <input class="input border round light-grey" type="text" name="telefono" value="{if isset($data.telefono)}{$data.telefono}{/if}">
            </p>

            {if Session::accesoViewEstricto(array('admin', 'especial'))}
                <input class="w3-check" type="checkbox" checked="checked">
                <label class="w3-validate">Milk</label>

                <input class="w3-check" type="checkbox">
                <label class="w3-validate">Sugar</label>

                <input class="w3-check" type="checkbox" disabled>
                <label class="w3-validate">Lemon (Disabled)</label>
            {/if}
            <div class="padding-16 margin-top topbar border-blue">
                <div class='right'>
                    <input class='btn blue round' type="submit" value="Enviar">
                    <a class='btn red round' href='{$_layoutParams.root}usuario/index/{$_pagina}'>Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>