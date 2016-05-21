<!DOCTYPE html><html lang="es">
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
    <body class="container khaki">
        <div id="main">
            {include file=$_layoutParams.includes.header}
            <div id="contenido" class="container khaki">
                <noscript>
                <div>Para el correcto funcionamiento de la aplicación debe activar el soporte de javascript</div>
                </noscript>
                {include file=$_layoutParams.includes.mensajes}
                {include file=$_layoutParams.includes.titulo}
                {include file=$_contenido}

            </div> <!--contenido-->
            {include file=$_layoutParams.includes.footer}
            {if DEBUG}
                {include file=$_layoutParams.includes.info_debug}
            {/if}

    </body>
</html>
