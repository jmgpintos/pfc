<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{$titulo|default:""}{if isset($titulo)} | {/if}{$_layoutParams.configs.app_name}</title>

        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'>
        <link href="https://fonts.googleapis.com/css?family=PT+Sans|Titillium+Web:600" rel="stylesheet">
        <link rel="stylesheet" href="{$_layoutParams.ruta_css}estilos.css">
        <link rel="stylesheet" href="{$_layoutParams.ruta_css}responsive.css">
        <link rel="stylesheet" href="{$_layoutParams.ruta_css}font-awesome/css/font-awesome.min.css">
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
            <!--includes.header-->
            {include file=$_layoutParams.includes.header}
            <!--FIN includes.header-->

            <!--div contenido-->
            <div id="contenido">
                <noscript>
                <div>Para el correcto funcionamiento de la aplicación debe activar el soporte de javascript</div>
                </noscript>

                <!-- includes.mensajes-->
                {include file=$_layoutParams.includes.mensajes}
                <!-- FIN includes.mensajes-->

                <!-- includes.titulo-->
                {include file=$_layoutParams.includes.titulo}
                <!-- FIN includes.titulo-->

                <!-- $_contenido-->
                {include file=$_contenido}
                <!-- FIN $_contenido-->

            </div> <!--FIN div contenido-->

            <!-- includes.footer-->
            {include file=$_layoutParams.includes.footer}
            <!-- FIN includes.footer-->

            {if DEBUG}
                <!-- includes.info_debug-->
                {include file=$_layoutParams.includes.info_debug}
                <!-- FIN includes.info_debug-->
            {/if}
        </div>

    </body>
</html>
