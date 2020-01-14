<?php /* Full caching */

define('FULLCACHE_DEFAULT_TTL', 7200); /* Expiry time in seconds */
define('FULLCACHE_HASH',	'sha1');
define('FULLCACHE_DIR',	$cInc.'/cache/full/');
class FullCache {
	public static $uid = '', $ttl = FULLCACHE_DEFAULT_TTL;
	private static $writen = false;	
	
	public static function Encode($data) {
		self::$uid .= $data . '-';
	}
	
	public static function Key($uid = false) {
		return hash(FULLCACHE_HASH, $uid ? $uid : self::$uid);
	}
	
	public static function Filename($timestamp = false, $uid = false) {
		return FULLCACHE_DIR . ($timestamp ? time() + self::$ttl . '_' : '') . ($uid ? $uid : self::Key()) . ($timestamp ? '' : '.html');
	}
	
	public static function Write($data) {
		$file = self::Filename();
		
		if(!file_exists(FULLCACHE_DIR) && !@mkdir(FULLCACHE_DIR))
			return self::Error('Could not create directory: ' . FULLCACHE_DIR);
		
		if(!$fp = fopen($file, 'w'))
			return self::Error('Unable to open file for writing: ' . $file);
		
		if(!self::$writen) {
			fwrite($fp, "<?php\n\n");
			
			
			fwrite($fp, "if(time() >= \$_" . self::Key() . "_time = " . (time() + self::$ttl) . ") return;\n");	
			
			fwrite($fp, "\$_" . self::Key() . " = true;\n\n");			
			
			foreach($headers = headers_list() as $header) {
				fwrite($fp, "header(" . var_export($header, true) . ");\n");
			}
			
			fwrite($fp, "\n?>");
			
			touch(self::Filename(true));
			chmod(self::Filename(true), 0755);
		}
		
		fwrite($fp, str_replace('<?', '<?php echo \'<?\'; ?>', $data));
		
		fclose($fp);
		
		return $data;
	}
	
	public static function Shutdown() {		
		ob_end_flush();
	}	
	
	private static function Grab() {
		$uid = self::Key();
		
		
		if(file_exists($file = self::Filename())) {
			unset(${'_' . $uid});
			unset(${'_' . $uid . '_time'});	
			
			require $file;			
			
			if(isset(${'_' . $uid}))
				exit;			
			
			if(isset(${'_' . $uid . '_time'})) {
				
				unlink(FULLCACHE_DIR . ${'_' . $uid . '_time'} . '_' . self::Key());
			}
			@chmod($file, 0777);
			unlink($file);
		}
		
		return false;
	}	
	
	public static function Place($ttl) {
		if($ttl !== false)
			self::$ttl = $ttl;
		
		ob_start('FullCache::Write');
		register_shutdown_function('FullCache::Shutdown');
	}	
	
	public static function Live($ttl = false) {		
		self::Grab();		
		self::Place($ttl);
	}	
	
	public static function Delete($hash) {
		$uid = '';
		foreach($hash as $h) {
			$uid .= $h . '-';
		}		
		$uid = self::Key($uid);		
		@chmod($filename = self::Filename(false, $uid), 0777);
		@unlink($filename = self::Filename(false, $uid));		
		
		$times = glob(FULLCACHE_DIR . '/*_' . $uid);
		foreach($times as $file) {
			@chmod($file, 0777);
			@unlink($file);
		}
	}	
	public static function ClearAll() {
		if(!file_exists(FULLCACHE_DIR) && !@mkdir(FULLCACHE_DIR))
			return;
		
		if(!$dir = opendir(FULLCACHE_DIR))
			die(self::Error('Unable to open directory for reading: ' . FULLCACHE_DIR));
		
		while(($entry = readdir($dir)) !== false) {
			if(preg_match('/^(\d+)_(.+)$/', $entry, $matches)) {
				if(time() > (int)$matches[1]) {
					@chmod(FULLCACHE_DIR . $entry, 0777);
					@unlink(FULLCACHE_DIR . $entry);
					@chmod(FULLCACHE_DIR . $matches[2] . '.html', 0777);
					@unlink(FULLCACHE_DIR . $matches[2] . '.html');
				}
			}
		}
		
		closedir($dir);
	}
	
	private static function Error($message) {
		return 'Error: ' . $message;
	}
}