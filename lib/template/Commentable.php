<?php



class Doctrine_Template_Commentable extends Doctrine_Template
{
  /**
   * Array of Commentable options
   */  
  protected $_options = array(
                          'Commenter' => array(
                              'enabled'       =>   true,
                              'model'         =>  'Commenter',
                              'table_method'  =>  'addCommenter',
                              )
  );

  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);    
    $this->_plugin = new Doctrine_Commentable($options);
  }

  public function getCommentUserFormClass()
  {
    return 'DefaultCommenterForm';
  }
  
  public function getCommentFormClass()
  {
    return 'DefaultCommentForm';
  }
  
  public function addComment($comment, $parent_id = null)
  {
    $object = $this->getInvoker();

    if(!$object['id'])
    {
      $object->save();
    }

    if ($parent_id) 
    {
      $parent = Doctrine::getTable('Comment')->find($parent_id);
      $parent->getNode()->addChild($comment);
    }
    
    $object['Comments'][] = $comment;
  }
  
  public function getCommentThread($root_id)
  {
    return Doctrine::getTable('Comment')->getTree()->fetchBranch($root_id);  
  }

  public function addCommentFromArray($commentArr)
  {
    foreach ($commentArr as $key => $value) {
      $comment = new Comment();
      $comment->$key = $value;
      $this->addComment($comment);
    }
  }

  public function getNumComments()
  {
    $object = $this->getInvoker();
    return $object['Comments']->count();
  }

  public function setUp()
  {
    $this->_plugin->initialize($this->_table);
  }
}