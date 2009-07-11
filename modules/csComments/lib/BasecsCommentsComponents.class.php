<?php
class BasecsCommentsComponents extends sfComponents
{
  public function executeThread()
  {
    $treeMgr = Doctrine::getTable('Comment')->getTree();
    // $root = $treeMgr->findRoot($this->record->getId());
    
    // $this->comments = null;
    
    // if( $root && $root->getId() )
    // {
      $this->comments = $treeMgr->fetchBranch($this->record->getCommentRootId());
    // }
  }
  public function executeAdd_comment()
  {
    try
    {
      $userFormClass = $this->record->getCommentUserFormClass();
      if(sfContext::getInstance()->getRequest()->hasParameter($userFormClass))
      {
        $this->userForm = sfContext::getInstance()->getRequest()->getParameter($userFormClass);
      } 
      else
      {
        $this->userForm = new $userFormClass();
      }
      $commentFormClass = $this->record->getCommentFormClass();
      if(sfContext::getInstance()->getRequest()->hasParameter($commentFormClass))
      {
        $this->commentForm = sfContext::getInstance()->getRequest()->getParameter($commentFormClass);
      } 
      else
      {
        $this->commentForm = new $commentFormClass();
      }
    }
    catch(Exception $e)
    {
      echo $e;
    }
  }
}