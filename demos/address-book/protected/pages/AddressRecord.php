<?php
/**
 * Auto generated by prado-cli.php on 2007-01-16 06:44:16.
 */
class AddressRecord extends TActiveRecord
{
	public static $_tablename='addresses';

	/**
	 * @var integer $id
	 * @soapproperty
	 */
	public $id;

	
	
	/**
	 * @var string $username
	 * @soapproperty
	 */
	public $username;

	
	
	/**
	 * @var string $phone
	 * @soapproperty
	 */
	public $phone;

	
	/**
	 * @return AddressRecord
	 */
	public static function finder()
	{
		return self::getRecordFinder('AddressRecord');
	}
}
?>