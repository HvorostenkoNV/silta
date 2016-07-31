<?
// Singltone
trait singltone
	{
	protected static $objectInstance = false;
	final public static function GetInstance()
		{
		$className = get_called_class();
		if(self::$objectInstance[$className]) return self::$objectInstance[$className];

		$objectInstance = new $className;
		$className::PrepareObject($objectInstance);

		self::$objectInstance[$className] = $objectInstance;
		return self::$objectInstance[$className];
		}

	final private function __clone()     {}
	final private function __construct() {}
	protected static function PrepareObject($objectInstance = false) {}
	}
?>