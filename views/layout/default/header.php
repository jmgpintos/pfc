<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title><?php if (isset($this->titulo)) echo $this->titulo ?></title>

        <link rel="stylesheet" href="<?php echo $_layoutParams['ruta_css']; ?>estilos.css">
        <script src="<?php echo BASE_URL ?>public/js/jquery.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL ?>public/js/jquery.validate.js" type="text/javascript"></script>


        <?php if (isset($_layoutParams['js']) && count($_layoutParams['js'])): ?>

            <?php for ($i = 0; $i < count($_layoutParams['js']); $i++): ?>
                <script 
                    src="<?php echo $_layoutParams['js'][$i] ?>"
                    type="text/javascript"
                    >
                </script>

            <?php endfor; ?>
        <?php endif; ?>


    </head>
    <body>
        <div id="main">
            <div id="header">
                <h1>
                    <?php echo APP_NAME; ?>
                </h1>
            </div>
            <div id="top_menu">
                <ul>
                    <?php if (isset($_layoutParams['menu'])): ?>
                        <?php $menu = $_layoutParams['menu']; ?>
                        <?php for ($i = 0; $i < count($menu); $i++): ?>
                            <?php
                            if ($item && $menu[$i]['id'] == $item) {
                                $_item_style = 'current';
                            } else {
                                $_item_style = '';
                            }
                            ?>
                            <li id="<?php echo $_item_style ?>">
                                <a href="<?php echo $menu[$i]['enlace'] ?>">
                                    <?php echo $menu[$i]['titulo'] ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <div id="contenido">
                <noscript>
                <p>
                    Para el correcto funcionamiento de la aplicación debe activar el soporte de javascript
                </p>
                </noscript>
                <div id="error"><?php if (isset($this->_error)) echo $this->_error ?></div>