<?php
    $type = $_SESSION['type'] ?? null;
    $message = $_SESSION['message'] ?? '';
?>

<?php if ($type !== null) { ?>
    <div class="alert alert-<?php echo $type ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

        <?php if (is_array($message)) { ?>
            <ul class="list-unstyled pb-0 mb-0">
                <?php foreach ($message as $messageItem) { ?> 
                    <li><?php echo htmlspecialchars_decode(stripslashes($messageItem)); ?></li>
                <?php } ?>
            </ul>
        <?php } else { ?> 
            <?php echo htmlspecialchars_decode(stripslashes($message)); ?>
        <?php } ?>
    </div>
<?php } ?>