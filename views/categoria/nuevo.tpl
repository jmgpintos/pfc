
<div class="row">
    <div class="col card-4 padding">

        <form id="form1" method="post" action="{$_layoutParams.root}{$controlador}nuevo">
            <input type="hidden" name="guardar" value="1"/>
            <div class="row">
                <div class="third padding">
                    <div class="row padding-4">
                        <label class="text-teal">Nombre: </label>
                        <input class="input" type="text" name="nombre" value="{if isset($datos.nombre)}{$datos.nombre}{/if}" autofocus/>
                    </div>
                    <div class="row padding-4">
                        <label class="text-teal">Categoría padre</label>
                        <select class="select border-0 white" id="id_categoria" name="id_categoria" autofocus>
                            {foreach from=$categorias item=categoria}
                                <option value="{$categoria.id}"
                                        {if (isset($datos.id_categoria))}
                                            {if ($datos.id_categoria == $categoria.id)}
                                                selected="selected"
                                            {/if}
                                        {/if}>{$categoria.nombre}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="twothird padding">
                    <label class="text-teal">Descripción: </label>
                    <textarea class="input" type="text" name="descripcion" rows="4">{if isset($datos.descripcion)}{$datos.descripcion}{/if}</textarea>
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