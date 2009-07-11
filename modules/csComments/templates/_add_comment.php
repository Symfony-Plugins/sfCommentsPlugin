<?php use_helper('Form') ?>
<h3>Add New Comment</h3>
<?php echo form_tag('@cscomments_comments_do_add'); ?>

  <?php echo input_hidden_tag('return_uri', $sf_request->getParameter('return_uri')); ?>
  <?php echo input_hidden_tag('comment_id', $sf_request->getParameter('comment_id')); ?>
  <?php echo input_hidden_tag('model', $sf_request->getParameter('model')); ?>
  <?php echo input_hidden_tag('record_id', $sf_request->getParameter('record_id')); ?>

  <?php if(isset($userForm)): ?>
			<?php echo $userForm ?>
	<?php endif ?>
	
	<?php if(isset($commentForm)): ?>
			<?php echo $commentForm ?>
	<?php endif ?>  
	

  <?php echo submit_tag('Add'); ?>
</form>
