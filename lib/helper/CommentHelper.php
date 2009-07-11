<?php
function link_to_add_new_comment($name, $record, $comment = null)
{
  use_helper('Javascript');

  $params = array();
  $params['update'] = ($comment && $comment->getId()) ? 'add_new_comment_form_holder_' . $comment->getId() : 'add_new_comment_form_holder';
			
  $params['url'] = '@cscomments_comments_add';
  
  $comment_id = $comment ? $comment->getId() : null;

  $params['with'] = "'comment_id=".$comment_id."&model=".get_class($record)."&record_id=".$record->getId()."&return_uri=".urlencode(sfContext::getInstance()->getRequest()->getUri())."'";
  
  return link_to_remote($name, $params);
}


function link_to_edit_comment($name, $record, $comment)
{
  use_helper('Javascript');
  
  $params = array('update' => 'add_new_comment_form_holder_'.$comment->getId(),
                  'url'    => '@cscomments_comments_edit',
                  'with'   => "'comment_id=".$comment->getId()."&model=".get_class($record)."&record_id=".$record->getId()."&return_uri=".urlencode(sfContext::getInstance()->getRequest()->getUri())."'");

  return link_to_remote($name, $params);
}


function link_to_delete_comment($name, $record, $comment)
{
  return link_to($name, '@cscomments_comments_delete?comment_id='.$comment->getId().'&record_id='.$record->getId().'&model='.get_class($record).'&return_uri='.urlencode(sfContext::getInstance()->getRequest()->getUri()), 'confirm=Are you sure you with to delete this comment?');
}


function get_doctrine_comments($record)
{
  sfContext::getInstance()->getResponse()->addStylesheet('/sfDoctrineCommentsPlugin/css/comments.css');
  
  return get_component('csComments', 'thread', array('record' => $record));
}


function can_edit_comment($comment)
{
  $sf_user = sfContext::getInstance()->getUser();

  if ($sf_user->isAuthenticated())
  {
    include(sfConfig::get('sf_plugins_dir').'/sfDoctrineCommentsPlugin/config/doctrine/schemaConfig.php');

    $userModelId = $config['user_model_id'];

    $commentUserId = $comment->get('User')->get($userModelId);
    $loggedInUserId = $sf_user->getUserId();
    
    if ($commentUserId == $loggedInUserId)
    {
      return true;
    }
  }

  return false;
}
