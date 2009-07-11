<?php

class Doctrine_Commentable_Comment extends Doctrine_Record_Generator
{
    protected $_options = array('className'     => 'Comment',
                                'generateFiles' => true,
                                'generatePath'  => false,
                                'table'         => true,
                                'pluginTable'   => true,
                                'children'      => array(),
                                'parent'        => false);

    /**
     * __construct
     *
     * @param string $options 
     * @return void
     */
    public function __construct(array $options = array())
    {
      if (!isset($options['generatePath'])) 
      {
        $options['generatePath'] = sfConfig::get('sf_lib_dir').'/sfCommentsPlugin';
      }
      $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
    }

    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('subject', 'string', 255);
        $this->hasColumn('body', 'clob');
        $this->hasColumn('author', 'string', 255);
    }

    public function buildRelation()
    {
      $options = array('local'    => 'comment_id',
                       'foreign'  => 'news_id',
                       'refClass' => $this->getOption('table')->getComponentName());
      
      $this->_table->bind(array($this->getOption('parent')->getOption('table')->getComponentName(), $options), Doctrine_Relation::MANY);
    }

    public function buildForeignKeys(Doctrine_Table $table)
    {
        return array();
    }
}