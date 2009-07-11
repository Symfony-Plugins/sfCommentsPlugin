<?php

/**
 * comments actions.
 *
 * @package    vandyhw
 * @subpackage comments
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class BasecsCommentsActions extends sfActions
{
  public function executeAdd()
  {
    return $this->renderComponent('csComments', 'add_comment');
  }

  /**
   * executeDo_add_new_comment 
   * 
   * @access public
   * @return void
   */
  public function executeDoAdd(sfWebRequest $request)
  {
    $model = $this->getRequestParameter('model');

    $record_id = $this->getRequestParameter('record_id');

    $record = Doctrine::getTable($model)->find($record_id);

    $commentForm = new DefaultCommentForm();
    $commentForm->bind($this->getRequestParameter('comment'));
    
    if($commentForm->isValid())
    {
      $commentForm->save();
      $comment = $commentForm->getObject();
      if(!$this->addUser($comment, $record))
      {
        $this->getRequest()->setParameter('form', $this->form);
        $this->getUser()->setFlash('notice', 'Please enter a valid E-mail address');
        $this->redirect($this->getRequestParameter('return_uri'));
      }

      $comment_id = $this->getRequestParameter('comment_id');
      $record->addComment($comment, $comment_id);
      $record->save();
    }
    else
    {
      $this->getRequest()->setParameter('form', $this->form);
      $this->getUser()->setFlash('notice', 'Please enter a valid E-mail address');
      $this->redirect($this->getRequestParameter('return_uri'));
    }
    $this->getUser()->setFlash('notice', $this->getAddMessage());
    $this->redirect($this->getRequestParameter('return_uri').'#comment_'.$comment->getId());
  }
  public function getAddMessage()
  {
    return 'Your comment was successfully added!';
  }
  /**
   * handleErrorDo_add_new_comment 
   * 
   * @access public
   * @return void
   */
  public function handleErrorAdd()
  {
    $this->getUser()->setFlash('error', 'An error occurred adding your comment!');

    $name = $this->getRequestParameter('comment_id') ? '#comment_'.$this->getRequestParameter('comment_id'):'#comments';

    $this->redirect($this->getRequestParameter('return_uri').$name);
  }
  
  public function addUser($comment, $record)
  {
    if($params = $this->getRequestParameter('commenter'))
    {
      $formClass = $record->getCommentUserFormClass();
      $this->form = new $formClass();
      $this->form->bind($params);
      if($this->form->isValid())
      {
        if($this->isEmpty($this->form))
        {
          return sfConfig::get('app_comments_AllowAnonymous');
        }
        $tableMethod = $record->getTable()->getCommentUserTableMethod();
        $record->getTable()->$tableMethod($comment, $this->form);
        return true;
      }
      return false;
    }
    return true;
  }

  /**
   * executeEdit_comment 
   * 
   * @access public
   * @return void
   */
  public function executeEdit_comment()
  {
    $comment_id = $this->getRequestParameter('comment_id');
    $this->comment = Doctrine::getTable('Comment')->find($comment_id);
  }

  /**
   * executeDo_edit_comment 
   * 
   * @access public
   * @return void
   */
  public function executeDoEdit()
  {
    $comment_id = $this->getRequestParameter('comment_id');
    $comment = Doctrine::getTable('Comment')->find($comment_id);
    $comment->setBody($this->getRequestParameter('body'));
    $comment->save();

    $this->getUser()->setFlash('notice', 'Your comment was successfully modified!');
    $this->redirect($this->getRequestParameter('return_uri').'#comment_'.$comment->getId());
  }

  /**
   * handleErrorDo_edit_comment 
   * 
   * @access public
   * @return void
   */
  public function handleErrorDoEdit()
  {
    $this->getUser()->setFlash('error', 'An error occurred editing your comment!');

    $name = $this->getRequestParameter('comment_id') ? '#comment_'.$this->getRequestParameter('comment_id'):'#comments';
  }

  /**
   * executeDo_delete_comment 
   * 
   * @access public
   * @return void
   */
  public function executeDoDelete()
  {
    // Setup some needed vars
    $comment_id = $this->getRequestParameter('comment_id');

    // Get the comment we are deleting
    $comment = Doctrine::getTable('Comment')->find($comment_id);
    $comment->getNode()->delete();

    $this->getUser()->setFlash('notice', 'Comment was successfully deleted!');
    $this->redirect($this->getRequestParameter('return_uri').'#comments');
  }
  public function isEmpty($form)
  {
    foreach ($form->getValues() as $value) 
    {
      if(trim($value) != '')
      {
        return false;
      }
    }
    return true;
  }
}
