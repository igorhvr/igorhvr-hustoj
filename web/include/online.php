<?php
/*
���ݿ�
CREATE TABLE `online` (
  `hash` varchar(32) collate utf8_unicode_ci NOT NULL,
  `ip` varchar(20) character set utf8 NOT NULL default '',
  `ua` varchar(255) character set utf8 NOT NULL default '',
  `refer` varchar(255) collate utf8_unicode_ci default NULL,
  `lastmove` int(10) NOT NULL,
  `firsttime` int(10) default NULL,
  `uri` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`hash`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

 */
/**
 * �ж����δ��Ӧ���û�Ϊ�Ѿ��뿪���û�
 * @var int
 */

define('ONLINE_DURATION', 600);

/**
 * 
 * ���������������û�����ͳ��
 * 
 * @package online
 * @author freefcw
 * @link http://www.missway.cn
 * 
 */
class online{
	/**
	 * database connect
	 * @var databse link
	 */
	protected $db;
	/**
	 * current user ip
	 * @var string
	 */
	protected $ip;
	/**
	 * current user agent
	 * @var string
	 */
	protected $ua;
	/**
	 * cureent user visit web uri
	 * @var string
	 */
	protected $uri;
	/**
	 * session id
	 * @var string
	 */
	protected $hash;
	/**
	 * cureent user refer uri
	 * @var string
	 */
	protected $refer;
	//can add function:
	//example click number count
	protected $click;

	/**
	 * construct fuction,init database link
	 * @return void
	 */
	function __construct()
	{
		$this->ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
		$this->ua = mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);
		$this->uri = mysql_real_escape_string($_SERVER['PHP_SELF']);
		$this->refer = mysql_real_escape_string($_SERVER['HTTP_REFERER']);
		$this->hash = mysql_real_escape_string(session_id());
		//$this->db = new mysqli(DBHOST, DBUSER, DBPASSWORD, )

		//check user existed!
		if($this->exist()){
			//update databse
			$this->update();
		}else{
			//if none, add this record
			$this->addRecord();
		}
		//clean the user who leave our site 
		$this->clean();
	}

	/**
	 * 
	 * return all record!
	 * 
	 * @return array
	 */
	function getAll()
	{
		$ret = array();
		
		$sql = 'SELECT * FROM online';
		$res = mysql_query($sql);
		while($rt = mysql_fetch_object($res)) $ret[] = $rt;
		mysql_free_result($res);
		return $ret;
	}
	/**
	 * 
	 * return specfy record
	 * @var string ip
	 * @return object 
	 */
	function getRecord($ip)
	{
		$sql = "SELECT * FROM online WHERE ip = '$ip'";
		$res = mysql_query($sql);
		if(mysql_num_rows($res)){
			$ret = mysql_fetch_object($res);
		}else{
			return false;
		}
		mysql_free_result($res);
		return $ret;
	}
	
	/**
	 * 
	 * get total count
	 * 
	 * @return int
	 */
	function get_num()
	{
		$sql = 'SELECT count(ip) as nums FROM online';
		$res = mysql_query($sql);
		$ret = mysql_fetch_object($res);
		mysql_free_result($res);
		return $ret->nums;
	}
	/**
	 * check the record exist
	 *
	 * @return boolean 
	 */
	function exist()
	{
		$sql = "SELECT * FROM online WHERE hash = '$this->hash'";
		$res = mysql_query($sql);
		if(mysql_num_rows($res) == 0)
			return false;
		else
			return true;

	}
	/**
	 * add a record
	 *
	 * @return void
	 */
	function addRecord()
	{
		$now = time();
		$sql = "INSERT INTO online(hash, ip, ua, uri, refer, firsttime, lastmove)
				VALUES ('$this->hash', '$this->ip', '$this->ua', '$this->uri', '$this->refer', '$now', '$now')";
		mysql_query($sql);
	}

	/**
	 * update a record
	 *
	 * @return void
	 */
	function update()
	{
		$sql = "UPDATE online
				SET
					ua = '$this->ua',
					uri = '$this->uri',
					refer = '$this->refer',
					lastmove = '".time()."',
					ip = '$this->ip'
				WHERE
					hash = '$this->hash'
				";
		mysql_query($sql);
	}
	/**
	 * clean the duration user
	 *
	 * @return void
	 */
	function clean()
	{
		$sql = 'DELETE FROM online WHERE lastmove<'.(time()-ONLINE_DURATION);
		mysql_query($sql);
	}
}
