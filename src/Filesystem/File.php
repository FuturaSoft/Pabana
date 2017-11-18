<?php
namespace Pabana\Filesystem

use finfo;

class File
{
	/**
	 * Holds the file handler resource if the file is opened
	 *
	 * @var \Pabana\Filesystem\Folder
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/Folder
	 */
	private $_oFolder = null;
	
	/**
	 * Current file name
	 *
	 * @var string
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/File#prop._sName
	 */
	private $_sName = null;
	
	/**
	 * Holds the file handler resource if the file is opened
	 *
	 * @var resource
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/File#prop._hHandle
	 */
	private $_hHandle = null;
	
	/**
	 * Enable locking for file reading and writing
	 *
	 * @var bool
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/File#prop._bLock
	 */
	private $_bLock = null;
	
	/**
	 * Current file's absolute path
	 *
	 * @var string
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/File#prop._sPath
	 */
	private $_sPath;
	
	/**
	 * Constructor
	 *
	 * @param string $sPath Path to file
	 * @param bool $bCreate Create file if it does not exist (if true)
	 * @param int $nMode Mode to apply to the folder holding the file
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/File/__construct
	 */
	public function __construct($sPath, $bCreate = false, $nMode = 0755)
	{
		// Get folder of this file and create it if not exist
		$this->oFolder = new Folder(dirname($sPath), $bCreate, $hMode);
		// Set path of file
		$this->sPath = $sPath;
		// Get absolute path of current file
		$this->absolutePath();
		// Check if path is for a file
		$this->name();
		// If bCreate is true and file not exists
		if ($bCreate === true && !$this->exists()) {
			// Create file
			$this->create();
		}
    }

    /**
	 * Append string to file
	 *
	 * @param string $sText Text to add to file
	 * @return int|boolean
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/File/append
	 */
	public function append($sText)
	{
		return file_put_contents($this->sPath, $sText, FILE_APPEND);
	}
	
	/**
	 * Create file
	 *
	 * @return bool
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/File/create
	 */
	public function create()
	{
		// Check if directory is a directory, is writable and if  file not already exists
		if ($this->_oFolder->isDirectory() && $this->_oFolder->isWritable() && !$this->exists()) {
			// Create file
			return touch($this->sPath);
		}
		return false;
	}
	
	/**
	 * Get name of file
	 *
	 * @return string
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/File/name
	 */
	public function name()
	{
		// If name isn't in prop
		if (empty($this->sName)) {
			// Check if file isn't a directory
			if (!is_dir($this->getAbsolutePath())) {
				// Set name of file
				$this->sName = basename($this->getAbsolutePath());
			} else {
				// Exception invalid argument
			}
		}
		return $this->sName;
	}

	public function absolutePath()
	{
		$this->sPath = realpath($this->sPath);
		return $this->sPath;
	}

	/**
	 * Check if path is a file
	 *
	 * @return void
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/File/isFile
	 */
	public function isFile()
	{
		return is_file($this->sPath);
	}

	/**
	 * Get size of file
	 *
	 * @param bool $bHumanReadable Show value as int or human readable (with unit).
	 * @return int|string
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/File/size
	 */
	public function size($bHumanReadable = false)
	{
		$nFilesize = filesize($this->sPath);
		if ($bHumanReadable) {
			$arsSize = array('o','ko','Mo','Go','To','Po','Eo','Zo','Yo');
		    $factor = floor((strlen($nFilesize) - 1) / 3);
		    return sprintf("%.{2}f", $nFilesize / pow(1024, $factor)) . $arsSize[$factor];
		}
		return $nFilesize;
	}

	/**
	 * Copy curent file
	 *
	 * @param string $sDestination Destination of copy.
	 * @param bool $bOverwrite If destination file already exists overwrite it.
	 * @return bool
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/File/copy
	 */
	public function copy($sDestination, $bOverwrite = true)
	{
		if (!$this->exists() || (is_file($sDestination) && !$bOverwrite)) {
			return false;
		}
		return copy($this->sPath, $sDestination);
	}

	/**
	 * Clear PHP's internal stat cache
	 *
	 * @param bool $bAll Clear all cache or only current path cache.
	 * @return void
	 * @link http://pabana.co/en/documentation/1.1/Filesystem/File/clearCache
	 */
	public function clearCache($bAll = false)
	{
		clearstatcache($bAll, $this->sPath);
	}
}
?>