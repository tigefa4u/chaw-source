<?php if ($type == 'svn'):?>
###Revision: [<?php echo $revision;?>]
<?php else: ?>
###Commit: [<?php echo $revision;?>]
<?php endif; ?>

__Author:__ <?php echo $author;?>


__Date:__ <?php echo $commit_date;?>


__Message:__
```<?php echo $message;?>```

<?php if (!empty($changed)):?>
__Changes:__

<?php echo $changed;?>
<?php endif; ?>
