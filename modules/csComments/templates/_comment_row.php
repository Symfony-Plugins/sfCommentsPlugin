<?php use_helper('Date'); ?>

<?php $level = ($comment->getNode()->getLevel() - 1); ?>

<li class="comment_row" style="margin-left: <?php echo ($level * 20); ?>px;">
  <a name="comment_<?php echo $comment->getId(); ?>"></a>
  
  <div id="comment_content">
    <div id="author_and_date">
		<?php $poster = $comment->hasCommenter() ? $comment->getCommenter() : 'Anonymous' ?>
      posted by <?php echo $poster ?> <?php echo distance_of_time_in_words(strtotime($comment->getCreatedAt())); ?> ago.
    </div>
  
    <div id="body">
      <?php echo nl2br($comment->getBody()); ?>
    </div>
  
    <div id="links">

        <?php echo link_to_edit_comment('Edit', $record, $comment); ?> | 
        <?php echo link_to_delete_comment('Delete', $record, $comment); ?> | 

      
        <?php echo link_to_add_new_comment('Reply', $record, $comment); ?>

    </div>

    <div id="add_new_comment_form_holder_<?php echo $comment->getId(); ?>"></div>
  </div>

  <br/>
</li>
