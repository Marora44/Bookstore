<?php 
	$messages = array();
	if (count($messages) > 0) : ?>
    <div class = "message">
	    <?php foreach ($messages as $message) : ?>
		    <p><?php echo $message ?></p>
		<?php endforeach ?>
	</div>
<?php endif ?>