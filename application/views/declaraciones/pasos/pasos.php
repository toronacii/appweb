<form name="declaraciones" id="fDeclaraciones" method="post" action="<?php echo site_url('declaraciones/declarar'); ?>">
    
<div class="row form-group">
    <ul class="nav nav-pills nav-justified thumbnail setup-panel pasos">
        <?php foreach($steps as $index => $description): ?>
        <li class="<?php echo ($index == 2) ? 'active' : 'disabled' ?>"><a href="#paso-<?php echo $index + 1 ?>">
            <h4 class="list-group-item-heading">Paso <?php echo $index + 1 ?></h4>
            <p class="list-group-item-text"><?php echo $description ?></p>
        </a></li>
        <?php endforeach; ?>
    </ul>
</div>