<?php

/**
 * PluginComment form.
 *
 * @package    form
 * @subpackage Comment
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginCommentForm extends BaseCommentForm
{
	public function setUp()
	{
		parent::setUp();
    $this->widgetSchema['user_id'] = new sfWidgetFormDoctrineSelect(array('model' => 'Commenter', 'add_empty' => 'Anonymous'));
		unset($this['created_at'], $this['updated_at'], $this['approved_at'], $this['approved_by'], $this['lft'], $this['rgt'], $this['level'], $this['object_id'], $this['object_class']);
	}
}