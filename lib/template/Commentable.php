<?php



class Doctrine_Template_Commentable extends Doctrine_Template
{
  public function __construct(array $options = array())
  {
      $this->_plugin = new Doctrine_Commentable($options);
  }

  public function setUp()
  {
      $this->_plugin->initialize($this->_table);
  }
}


// 
//  Commentable.php
//  csActAsCommentablePlugin
//  
//  Created by Brent Shaffer on 2009-01-29.
//  Copyright 2008 Centre{source}. Al9 rights reserved.
// 

class Doctrine_Template_Commentable_Old extends Doctrine_Template
{    
  /**
   * Array of Commentable options
   */  
  protected $_options = array(
                          'Comment' => array(
                              'form_class' => 'DefaultCommentForm'
                              ), 
                          'Commenter' => array(
                              'enabled'       =>   true,
                              'model'         =>  'Commenter',
                              'table_method'  =>  'addCommenter',
                              ),
                          'relations' => array(
                              'refClass' =>   false,
  ));


  /**
   * Constructor for Commentable Template
   *
   * @param array $options 
   * @return void
   * @author Brent Shaffer
   */
  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }


  public function setup()
  {
    $refClass = $this->_options['relations']['refClass'];
    $refClass = $refClass or get_class($this->getInvoker()).'Comment';
    $linkTable = new Doctrine_Table();
  }


  /**
   * Set table definition for Commentable behavior
   *
   * @return void
   * @author Brent Shaffer
   */
  public function setTableDefinition()
  {
    $name = 'comment_root_id';
    $type = 'integer';
    $length = 8;
    $options = array();
    $this->hasColumn($name, $type, $length, $options);
    $this->addListener(new Doctrine_Template_Listener_Commentable($this->_options));
  }
  
  public function addComment($comment, $root_id = null)
  {
    $object = $this->getInvoker();
    $comment->setObjectClass(get_class($object));
    if(!$object['id'])
    {
      $object->save();
    }
    $comment->setObjectId($object->getId());

    $root = $root_id ? Doctrine::getTable('Comment')->find($root_id) : ($object->getCommentRootId() ? Doctrine::getTable('Comment')->find($object->getCommentRootId()) : $this->addCommentRoot());

    $root->getNode()->addChild($comment);
  }
  
  public function getCommentThread()
  {
    return Doctrine::getTable('Comment')->getTree()->fetchBranch($this->getInvoker()->getCommentRootId());  
  }
  
  public function addCommentRoot()
  {
    $object = $this->getInvoker();
    $root = $object->createCommentRoot();
    $root->refresh();
    $object->setCommentRootId($root->getId());
    return $root;
  }
  
  public function createCommentRoot()
  {
    $object = $this->getInvoker();
    $root = new Comment();
    $root->setBody('root');
    $root->setObjectClass(get_class($object));
    if(!$object['id'])
    {
      $object->save();
    }
    $root->setObjectId($object->getId());
    $root->save();
    Doctrine::getTable('Comment')->getTree()->createRoot($root);
    return $root;
  }
  
  public function addCommentFromArray($commentArr)
  {
    foreach ($commentArr as $key => $value) {
      $comment = new Comment();
      $comment->$key = $value;
      $this->addComment($comment);
    }
  }
  
  public function getCommentsQueryTableProxy($id = null)
  {
    $query = Doctrine::getTable('Comment')->createQuery();
    return $this->addCommentsQueryTableProxy($query, $id);
  }
  
  public function addCommentsQueryTableProxy(&$query, $id)
  {
    $query->addWhere('Comment.object_class = ?', get_class($this->getInvoker()));
    if($id)
    {
      $query->addWhere('Comment.object_id = ?', $id);
    }
    return $query;
  }
  
  public function getCommentsTableProxy($id = null)
  {
    return $this->getCommentsQueryTableProxy($id)->execute();
  }
  
  // Hacks to mimic Doctrine Relationship
  public function getComments()
  {
    $object = $this->getInvoker();
    return $object->getTable()->getComments($object->getId());
  }

  // Hacks to mimic Doctrine Relationship  
  public function setComments($comments)
  {
    foreach ($comments as $comment) {
      $this->addCommentFromArray($comment);
    }
  }
  
  // Can be overriden in your models to use custom forms
  public function getCommentFormClass()
  {
    return $this->_options['comment']['form_class'];
  }
  
  // Defaults to the form of your commenter class.  
  // Can be overriden in your models.
  public function getCommentUserFormClass()
  {
    return sfConfig::get('app_comments_commenter_class').'Form';
  }
  
  public function getCommentUserTableMethodTableProxy()
  {
    return $this->_options['Commenter']['table_method'];
  }
  
  public function addCommenterTableProxy($comment, $form)
  {
    $userClass = $this->_options['Commenter']['model'];
    $user = new $userClass();
    foreach ($form->getValues() as $key => $value) {
      $user->$key = $value;
    }
    // $user->save();
    $comment['Commenter'] = $user;
    $comment->save();
  }
  
  public function getNumComments()
  {
    $q = Doctrine::getTable('Comment')
            ->createQuery()
            ->where('object_id = ?', $this->getInvoker()->getCommentRootId());
            
    $count = $q->count();
    return $count ? $count - 1 : 0;
  }
}
