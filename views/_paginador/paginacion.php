<?php
$actual = $this->_paginacion['actual'];
$total = $this->_paginacion['total'];
$mensaje = "Página $actual de $total";
?>

<div class="paginacion">
    <form id="form-page" action="" method="POST" style="display:inline-block">
        <input class="numero-de-paginas" name="pagina" id="pagina" type="text" placeholder="<?php print $mensaje; ?>" >
    </form>
    <ul>
        <!--<li class="pagina"><?php print $mensaje; ?></li>-->
        <?php if (isset($this->_paginacion)): ?>
                                           <!--<pre>-->
            <?php
//            echo $link;
            //print_r($this->_paginacion);
            ?>
            <!--</pre>-->
            <?php if ($this->_paginacion['primero']): ?>
                <a href="<?php echo $link . $this->_paginacion['primero']; ?>">
                    <li>&laquo;</li>
                </a>
            <?php else: ?>        
                <li class="disabled">&laquo;</li>
            <?php endif; ?>      
            <?php if ($this->_paginacion['anterior']): ?>
                <a href="<?php echo $link . $this->_paginacion['anterior']; ?>">
                    <li>&#10094;</li>
                </a>
            <?php else: ?>
                <li class="disabled">&#10094;</li>
            <?php endif; ?>
            <?php for ($i = 0; $i < count($this->_paginacion['rango']); $i++): ?>
                <?php if ($this->_paginacion['actual'] == $this->_paginacion['rango'][$i]): ?>
                    <li class="numero activa hide-mobile"><?php echo $this->_paginacion['rango'][$i] ?></li>
                <?php else: ?>
                    <a href="<?php echo $link . $this->_paginacion['rango'][$i]; ?>">
                        <li class="numero hide-mobile"><?php echo $this->_paginacion['rango'][$i] ?></li>
                    </a>
                <?php endif; ?>    
            <?php endfor; ?>    
            <?php if ($this->_paginacion['siguiente']): ?>
                <a href="<?php echo $link . $this->_paginacion['siguiente']; ?>">
                    <li>&#10095;</li>
                </a>
            <?php else: ?>
                <li class="disabled">&#10095;</li>
            <?php endif; ?>    
            <?php if ($this->_paginacion['ultimo']): ?>
                <a href="<?php echo $link . $this->_paginacion['ultimo']; ?>">
                    <li>&raquo;</li>
                </a>
            <?php else: ?>
                <li class="disabled">&raquo;</a></li>
                <?php endif; ?>

        <?php endif; ?>
    </ul>
    <?php // vardump($this->_paginacion); ?>
    <?php
    $ultima = (strlen(($this->_paginacion['ultimo']))) ? $this->_paginacion['ultimo'] : $this->_paginacion['actual'];
    
    ?>
</div>
<script type="text/javascript">
    $('#pagina').focus(function () {
        $(this).removeAttr('placeholder');
    });

    $('#pagina').blur(function () {
        $(this).val('');
        $(this).attr('placeholder', '<?php print $mensaje; ?>');
    });

    $('#pagina').keydown(function (e) {
        if (e.keyCode === 13) {
            var pagina = jQuery("#pagina").val();

            var ultima = <?php echo $ultima;?>;

            if (pagina > ultima || pagina < -1) {
                alert("El número total de páginas es " + ultima);
            } else {
                var form = jQuery("#form-page");
                var urlDestino = "<?php echo $link; ?>" + pagina;
                console.log(urlDestino);
                form.attr("action", urlDestino);
                $(this).removeAttr('value');
                form.submit();
                return true;
            }

        }
    });
</script>