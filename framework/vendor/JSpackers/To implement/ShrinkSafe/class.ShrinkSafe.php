<?php
/**
 * Class ShrinkSafe
 * @package Minify
 */

/**
 * Compress Javascript using the Closure Compiler
 * 
 * You must set $jarFile and CACHE_FOLDER before calling the minify functions.
 * Also, depending on your shells environment, you may need to specify
 * the full path to java in $javaExecutable or use putenv() to setup the
 * Java environment.
 * 
 * <code>
 * ShrinkSafe::$jarFile = '/path/to/compiler.jar';
 * ShrinkSafe::CACHE_FOLDER = '/tmp';
 * $code = ShrinkSafe::minifyJs($code);
 * </code>
 * 
 * @package Minify
 * @author Stephen Clay <steve@mrclay.org>
 * @author Kristian Kück (created this file based on class 'Minify_YUICompressor')
 * @copyright 2010 Kristian Kück
 */
class ShrinkSafe
{
    /**
     * Filepath of the Closure Compiler jar file. This must be set before
     * calling minifyJs().
     *
     * @var string
     */
    public static $jarFile = './lib/shrinksafe-r23079.jar';
    
    /**
     * Minify a Javascript string
     * 
     * @param string $js
     * 
     * @param array $options (verbose is ignored)
     * 
     * @see http://www.dojotoolkit.org/reference-guide/shrinksafe/index.html
     * 
     * @return string 
     */
    public function minify($content, $file, $options=array(), $type='js')
    {
        self::_prepare();
        exec(self::_getCmd($options, $type, $file), $output);
        
        return implode(PHP_EOL, $output);
    }
    
    private function _getCmd($userOptions, $type, $file)
    {
        $o = array_merge(
            array()
            ,$userOptions
        );
        $cmd = escapeshellcmd(JAVA_EXECUTABLE) . ' -jar ' . escapeshellarg(self::$jarFile) . ' ' . escapeshellarg($file);
        
        return $cmd;
    }
    
    private static function _prepare()
    {
        if(!is_file(self::$jarFile))
        {
            new log('ShrinkSafe: problems with the .jar-file. check also JAVA_EXECUTABLE in config.php.');
        }
    }
}
