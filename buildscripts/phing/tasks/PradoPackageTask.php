<?php

require_once 'phing/Task.php';

/**
 * Task to run phpDocumentor for PRADO API docs.
 */
class PradoPackageTask extends Task
{
  protected $filelists = array();
  protected $output;
  protected $strip=false;

  function setOutput(PhingFile $file)
  {
  	$this->output=$file;
  }

  function setStrip($value)
  {
  	$this->strip = (boolean)$value;
  }

      /**
     * Supports embedded <filelist> element.
     * @return FileList
     */
    function createFileList() {
        $num = array_push($this->filelists, new FileList());
        return $this->filelists[$num-1];
    }

  	function main()
	{
        $project = $this->getProject();

        $content = '';
        $files=array();
            // append the files in the filelists
            foreach($this->filelists as $fl)
            {
            	$fromDir = $fl->getDir($project);
            	foreach($fl->getFiles($project) as $file)
            	{
            		$src  = new PhingFile($fromDir,$file);
            		$files[] = $file;
            		$content .= file_get_contents($src->getAbsolutePath());
            	}
            }

          $content = $this->processPhp($content,$files);
        file_put_contents($this->output->getAbsolutePath(), $content);
	}

	function processPhp($content,$files)
	{
		$content = preg_replace('/^\s*Prado::trace.*\s*;\s*$/mu','',$content);
		$content = preg_replace('/(PradoBase::using|Prado::using|require_once|include_once)\s*\(.*?\);/mu','',$content);
		$content = str_replace('Prado::', 'PradoBase::', $content);
		if($this->strip)
			$content=$this->strip_comments($content);
		$content=$this->strip_empty_lines($content);
		$content="<?php".$this->getFileComment($files).preg_replace('/(\?>\s?|<\?php\s?)/mu','',$content)."\n?>";
		return $content;
	}

function strip_comments($source)
{
	$tokens = token_get_all($source);
	/* T_ML_COMMENT does not exist in PHP 5.
	* The following three lines define it in order to
	* preserve backwards compatibility.
	*
	* The next two lines define the PHP 5-only T_DOC_COMMENT,
	* which we will mask as T_ML_COMMENT for PHP 4.
	*/
	if (!defined('T_ML_COMMENT')) {
		@define('T_ML_COMMENT', T_COMMENT);
	} else {
		@define('T_DOC_COMMENT', T_ML_COMMENT);
	}
	$output = '';
	foreach ($tokens as $token) {
		if (is_string($token)) {
			// simple 1-character token
			$output .= $token;
		} else {
			// token array
			list($id, $text) = $token;
			switch ($id) {
				case T_COMMENT:
				case T_ML_COMMENT: // we've defined this
				case T_DOC_COMMENT: // and this
					// no action on comments
					break;
				default:
				// anything else -> output "as is"
				$output .= $text;
				break;
			}
		}
	}
	return $output;
}

function strip_empty_lines($string)
{
	$string = preg_replace("/[\r\n]+[\s\t]*[\r\n]+/", "\n", $string);
	$string = preg_replace("/^[\s\t]*[\r\n]+/", "", $string);
	return $string;
}
function getFileComment($files)
{
	$lastupdate=date('Y/m/d H:i:s');
	$year=date('Y');
	$fileList=array();
	foreach($files as $file)
		$fileList[] = " *   $file";
	$fileListStr = implode("\n", $fileList);
$comments="
/**
 * Last Update: $lastupdate
 *
 * Do not modify this file manually. This file was auto-generated by combining
 * the following classes from the Prado framework.
 *
 * Files:
{$fileListStr}
 *
 * @author Qiang Xue <qiang.xue@gmail.com>, Wei Zhuo <weizhuo@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-{$year} PradoSoft
 * @license http://www.pradosoft.com/license/
 */

";
	return $comments;
}
}
?>