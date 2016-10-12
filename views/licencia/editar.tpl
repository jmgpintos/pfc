
<div class="row">
    <div class="col card-4 padding">

        <form id="form1" method="post" action="">
            <input type="hidden" name="guardar" value="1"/>
            <div class="row">
                <div class="third padding">
                    <div class="row padding-4">
                        <label class="text-teal">Nombre: </label>
                        <input class="input" type="text" name="nombre" value="{if isset($data.nombre)}{$data.nombre}{/if}"/>
                    </div>
                </div>
                <div class="twothird padding">
                    <label class="text-teal">Descripción: </label>
                    <textarea class="input" type="text" name="descripcion" rows="4">{if isset($data.descripcion)}{$data.descripcion}{/if}</textarea>
                </div>
            </div>
            <div class="padding-16 margin-top topbar border-blue">
                <div class='right'>
                    <input class='btn blue round' type="submit" value="Guardar">
                    <a class='btn red round' href='{$_layoutParams.root}{$controlador}index/{$_pagina}'>Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>