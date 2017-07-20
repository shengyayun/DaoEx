<?php
namespace DaoEx\Test;

/**
 * 
 */
class Users{
	/**
	* 
	* @var int(10) unsigned
	*/
	public $id;

	/**
	* 用户名
	* @var varchar(255)
	*/
	public $name;

	/**
	* 
	* @var varchar(255)
	*/
	public $email;

	/**
	* 
	* @var varchar(255)
	*/
	public $password;

	/**
	* 
	* @var varchar(100)
	*/
	public $remember_token;

	/**
	* 
	* @var timestamp
	*/
	public $created_at;

	/**
	* 
	* @var timestamp
	*/
	public $updated_at;

	/**
	* primary key
	* @var string
	*/
	public $primary = 'id';

	/**
	* auto_increment
	* @var array
	*/
	public $auto = ['id'];

}
