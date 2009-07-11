<?php if( $comments && $record ): ?>
  <a name="comments"></a>
  <ul>
		<?php foreach($comments AS $comment): ?>
		 <?php if( $comment->getLevel() == 0 ): ?>
		   <?php continue ?>
		 <?php endif ?>
		 <?php include_partial('csComments/comment_row', array('comment' => $comment, 'record' => $record)) ?>
		<?php endforeach ?>
	<ul>
<?php endif;?>
    