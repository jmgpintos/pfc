<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title><?php if (isset($this->titulo)) echo $this->titulo ?></title>

        <link rel="stylesheet" href="<?php echo $_layoutParams['ruta_css']; ?>estilos.css">
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
                            }else{
                                $_item_style = '';
                            }
                            ?>
                            <li id="<?php echo $_item_style?>">
                                <a href="<?php echo $menu[$i]['enlace'] ?>">
                                    <?php echo $menu[$i]['titulo'] ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <div id="contenido">