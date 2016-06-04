<ul class='pagination border round border-dark-grey text-red white'>
<?php if (isset($this->_paginacion)): ?>
    <li>
    <?php if ($this->_paginacion['primero']): ?>
    <a href="<?php echo $link . $this->_paginacion['primero']; ?>">&laquo;</a>
    <?php else: ?>        
    <a href="<?php echo $link . $this->_paginacion['primero']; ?>">&laquo;</a>
    <?php endif; ?>      
    </li>
    <li>
    <?php if ($this->_paginacion['anterior']): ?>
        <a href="<?php echo $link . $this->_paginacion['anterior']; ?>">&#10094;</a>
    <?php else: ?>
        <a href="<?php echo $link . $this->_paginacion['anterior']; ?>">&#10094;</a>
    <?php endif; ?>
    </li>
    <?php for ($i = 0; $i < count($this->_paginacion['rango']); $i++): ?>
    <li>
        <?php if ($this->_paginacion['actual'] == $this->_paginacion['rango'][$i]): ?>
             <a class="light-blue" href="<?php echo $link . $this->_paginacion['rango'][$i]; ?>">
                 <strong><?php echo $this->_paginacion['rango'][$i] ?></strong>
            </a>
        <?php else: ?>
            <a href="<?php echo $link . $this->_paginacion['rango'][$i]; ?>">
                <?php echo $this->_paginacion['rango'][$i] ?>
            </a>
        <?php endif; ?>    
    </li>
    <?php endfor; ?>    
    <li>
    <?php if ($this->_paginacion['siguiente']): ?>
        <a href="<?php echo $link . $this->_paginacion['siguiente']; ?>">&#10095;</a>
    <?php else: ?>
       <a href="<?php echo $link . $this->_paginacion['siguiente']; ?>">&#10095;</a>
    <?php endif; ?>    
    </li>
    <li>
    <?php if ($this->_paginacion['ultimo']): ?>
    <a href="<?php echo $link . $this->_paginacion['ultimo']; ?>">&raquo;</a>
    <?php else: ?>
       <a href="<?php echo $link . $this->_paginacion['ultimo']; ?>">&raquo;</a>
    <?php endif; ?>
    </li>

<?php endif; ?>
</ul>