<?php

/**
 * Default Comment Form.
 *
 * @package    form
 * @subpackage Comment
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class DefaultCommentForm extends BaseCommentForm
{
	public function configure()
  {
		parent::configure();
		unset($this['created_at'], $this['updated_at'], $this['approved'], $this['approved_at'], $this['approved_by'], $this['lft'], $this['rgt'], $this['level'], $this['object_id'], $this['object_class'], $this['user_id']);
		$this->widgetSchema->setLabel('body', 'Your Comment');
  }
}