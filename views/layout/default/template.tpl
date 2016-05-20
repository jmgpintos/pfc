<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>{$titulo|default:""}{if isset($titulo)} | {/if}{$_layoutParams.configs.app_name}</title>

        <link rel="stylesheet" href="{$_layoutParams.ruta_css}estilos.css">
        <script src="{$_layoutParams.root}public/js/jquery.js" type="text/javascript"></script>
        <script src="{$_layoutParams.root}public/js/jquery.validate.js" type="text/javascript"></script>


        {if isset($_layoutParams.js) && count($_layoutParams.js)}
            {foreach item=js from=$_layoutParams.js}
                <script src="{$js}" type="text/javascript"></script>
            {/foreach}
        {/if}
    </head>
    <body>
        <div id="main">
            <div id="header">
                <h1>
                    {$_layoutParams.configs.app_name}
                </h1>
            </div>
            <div id="top_menu">
                <ul>
                    {if isset($_layoutParams.menu)}
                        {foreach item=it from=$_layoutParams.menu}
                            {if ({$_layoutParams.item} && {$it.id} == {$_layoutParams.item}) }
                                {assign var=_item_style value= 'current'}
                            {else}
                                {assign var=_item_style value= ''}
                            {/if}
                            <li>
                                <a class="{$_item_style}" href="{$it.enlace}">
                                    {$it.titulo}
                                </a>
                            </li>
                        {/foreach}
                    {/if}
                </ul>
            </div>
            <div id="contenido">
                <noscript>
                <p>
                    Para el correcto funcionamiento de la aplicación debe activar el soporte de javascript
                </p>
                </noscript>

                {if isset($_error)}
                    <div id="error"> {$_error}</div>
                {/if}
                {if isset($_mensaje)}
                    <div id="mensaje"> {$_mensaje}</div>
                {/if}
                <h2>
                    {if isset($tituloView)}
                        {$tituloView}
                    {else}
                        {if isset($titulo)}
                            {$titulo }
                        {/if}
                    {/if}
                </h2>
                {include file=$_contenido}

            </div> <!--contenido-->
            <div id="footer">
                <div>{$_layoutParams.configs.app_company}</div>
                <div id="lema">{$_layoutParams.configs.app_slogan}</div>
            </div>
        </div><!--main-->
        <div id="info">
            <strong>ROOT</strong>: {ROOT} | <strong>BASE_URL</strong>: {BASE_URL}<br/>
            {info_sesion()}
        </div>


    </body>
</html>
