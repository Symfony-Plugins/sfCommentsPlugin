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
    $this->setWidgets(array(
        'body'      => new sfWidgetFormTextarea(),
      ));

    $this->widgetSchema->setLabel('body', 'Your Comment');
    
    $this->setValidators(array(
        'body'      => new sfValidatorString(),
      ));
  }
}