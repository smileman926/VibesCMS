<?php /* Transforms your PHPVibe 
website to 
fast-serving static html pages */
$b_folder = $cInc.vSTATIC_FOLD;
$pre_folder = $b_folder.date("yW").'/';
if ( ! is_writable(dirname($pre_folder))) {
//Create weekly cache folder
@mkdir($pre_folder, 0755);	
@touch($pre_folder);
@chmod($pre_folder, 0755);
//Security
@copy($cInc.'/lib/index.html', $pre_folder.'index.html');
@copy($cInc.'/uploads/.htaccess', $pre_folder.'.htaccess');
}	
if ( ! is_writable(dirname($pre_folder))) {
//Use main folder if failed
define('vSTATIC_DIR',	$b_folder);
} else {
//Use weekly folder if all ok
define('vSTATIC_DIR',	$pre_folder);	
}
class FullCache {
	public static $uid = '', $ttl = vSTATIC_TTL;
	private static $writen = false;	
	
	public static function Encode($data) {
		self::$uid .= $data . '-';
	}
	
	public static function Key($uid = false) {
		return hash(vSTATIC_HASH, $uid ? $uid : self::$uid);
	}
	
	public static function Filename($timestamp = false, $uid = false) {
		return vSTATIC_DIR . ($timestamp ? time() + self::$ttl . '_' : '') . ($uid ? $uid : self::Key()) . ($timestamp ? '' : '.html');
	}
	
	public static function Write($data) {
		$file = self::Filename();
		
		if(!file_exists(vSTATIC_DIR) && !@mkdir(vSTATIC_DIR))
			return self::Error('Could not create directory: ' . vSTATIC_DIR);
		
		if(!$fp = fopen($file, 'w')) 
		{
			$file = date('z').$file;
		} 
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
		$data = str_replace('<?', '<?php echo \'<?\'; ?>', $data);
		$data = FullCache::sanitizeoutput($data);
		fwrite($fp, $data);
		
		fclose($fp);
		
		return $data;
	}
	public function sanitizeoutput($buffer) {

    $search = array(
        '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/(\s)+/s'       // shorten multiple whitespace sequences
    );

    $replace = array(
        '>',
        '<',
        '\\1'
    );

    $buffer = preg_replace($search, $replace, $buffer);

    return $buffer;
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
				
				unlink(vSTATIC_DIR . ${'_' . $uid . '_time'} . '_' . self::Key());
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
		
		$times = glob(vSTATIC_DIR . '/*_' . $uid);
		foreach($times as $file) {
			@chmod($file, 0777);
			@unlink($file);
		}
	}	
	public static function ClearAll() {
		global $log, $db;
		if(!file_exists(vSTATIC_DIR) && !@mkdir(vSTATIC_DIR)) {
			return;
	}
			if(!isset($log)) {$log = array();}

	$folders = glob( vSTATIC_DIR, GLOB_ONLYDIR );
	
	//$log[] = maybe_serialize($folders);
	foreach ($folders as $fold) {
        if (is_dir($fold)) {
			@touch($fold);
            @chmod($fold, 0777);
            if(@rmdir($fold)) {
			$log[] = '<div class="msg-win">Removed folder ' .$fold .'</div><br />';
			} else {
			$log[] = '<div class="msg-warning">Failed to remove the folder ' .$fold .' - Will try removing individual files! </div><br />';
			}
        }
	}
		$flist = glob(vSTATIC_DIR."{*.html*}", GLOB_BRACE);
		//$log = $flist;
		if($flist) {
		foreach ($flist as $filename)  {
		if($filename){	
        if( strpos($filename,'index.html') == false) {	
		$log[] = $db->remove_file($filename); 
		}
		}
		}
		
		}
		/* Remove empty trash */
		$tlist = glob(vSTATIC_DIR."{[^\.]}", GLOB_BRACE);
		if($tlist) {
		foreach ($tlist as $filename)  {	
        if($filename && !is_dir($filename)){			
        if( strpos($filename,'index.html') == false) {	
		$log[] = $db->remove_file($filename); 
		}
		}
		}
		}
		return $log;
	}
	
	private static function Error($message) {
		global $cInc;
		return 'Error: ' . str_replace($cInc, '', $message);
	}
}