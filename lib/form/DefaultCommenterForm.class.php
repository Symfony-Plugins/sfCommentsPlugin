<?php

/**
 * Default Comment Form.
 *
 * @package    form
 * @subpackage Comment
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class DefaultCommenterForm extends CommenterForm
{
	public function configure()
  {
		parent::configure();
		$this->validatorSchema['email'] = new sfValidatorEmail(array('required' => false));
		// $this->validatorSchema['username'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
  }
}