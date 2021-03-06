<?php
/**
 * 标签类型
 */
define('TAG_TYPE','TAG_TYPE');
/**
 * 内容类型
 */
define('CONTENT_TYPE','CONTENT_TYPE');
/**
 * 扩展数据类型
 */
define('EX_DATA_TYPE','EX_DATA_TYPE');
/**
 * 评论状态
 */
define('COMMENT_STATUS','COMMENT_STATUS');
/**
 * 内容状态
 */
define('CONTENT_STATUS','CONTENT_STATUS');
/**
 * 正常
 */
define('COMMENT_STATUS_NORMAL',1);
/**
 * 等待审核
 */
define('COMMENT_STATUS_WAIT_VERIFY',2);
/**
 * 审核不通过
 */
define('COMMENT_STATUS_VERIFY_NOT_PASS',3);
/**
 * 正常
 */
define('CONTENT_STATUS_NORMAL',1);
/**
 * 等待审核
 */
define('CONTENT_STATUS_WAIT_VERIFY',2);
/**
 * 审核不通过
 */
define('CONTENT_STATUS_VERIFY_NOT_PASS',3);
/**
 * 草稿
 */
define('CONTENT_STATUS_DRAFT',4);
/**
 * 隐藏
 */
define('CONTENT_STATUS_HIDE',5);
/**
 * 文章
 */
define('CONTENT_TYPE_ARTICLE',1);
/**
 * 页面
 */
define('CONTENT_TYPE_PAGE',2);
/**
 * 文章
 */
define('EX_DATA_TYPE_ARTICLE',1);
/**
 * 分类
 */
define('EX_DATA_TYPE_CATEGORY',2);
/**
 * 页面
 */
define('EX_DATA_TYPE_PAGE',3);
/**
 * 评论
 */
define('EX_DATA_TYPE_COMMENT',4);
/**
 * 内容标签
 */
define('TAG_TYPE_CONTENT',1);
return array (
  'COMMENT_STATUS' => 
  array (
    'COMMENT_STATUS_NORMAL' => 
    array (
      'value' => '1',
      'text' => '正常',
    ),
    'COMMENT_STATUS_WAIT_VERIFY' => 
    array (
      'value' => '2',
      'text' => '等待审核',
    ),
    'COMMENT_STATUS_VERIFY_NOT_PASS' => 
    array (
      'value' => '3',
      'text' => '审核不通过',
    ),
  ),
  'CONTENT_STATUS' => 
  array (
    'CONTENT_STATUS_NORMAL' => 
    array (
      'value' => '1',
      'text' => '正常',
    ),
    'CONTENT_STATUS_WAIT_VERIFY' => 
    array (
      'value' => '2',
      'text' => '等待审核',
    ),
    'CONTENT_STATUS_VERIFY_NOT_PASS' => 
    array (
      'value' => '3',
      'text' => '审核不通过',
    ),
    'CONTENT_STATUS_DRAFT' => 
    array (
      'value' => '4',
      'text' => '草稿',
    ),
    'CONTENT_STATUS_HIDE' => 
    array (
      'value' => '5',
      'text' => '隐藏',
    ),
  ),
  'CONTENT_TYPE' => 
  array (
    'CONTENT_TYPE_ARTICLE' => 
    array (
      'value' => '1',
      'text' => '文章',
    ),
    'CONTENT_TYPE_PAGE' => 
    array (
      'value' => '2',
      'text' => '页面',
    ),
  ),
  'EX_DATA_TYPE' => 
  array (
    'EX_DATA_TYPE_ARTICLE' => 
    array (
      'value' => '1',
      'text' => '文章',
    ),
    'EX_DATA_TYPE_CATEGORY' => 
    array (
      'value' => '2',
      'text' => '分类',
    ),
    'EX_DATA_TYPE_PAGE' => 
    array (
      'value' => '3',
      'text' => '页面',
    ),
    'EX_DATA_TYPE_COMMENT' => 
    array (
      'value' => '4',
      'text' => '评论',
    ),
  ),
  'TAG_TYPE' => 
  array (
    'TAG_TYPE_CONTENT' => 
    array (
      'value' => '1',
      'text' => '内容标签',
    ),
  ),
);