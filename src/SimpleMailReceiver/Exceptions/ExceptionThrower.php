<?php
/**
 * Created by PhpStorm.
 * User: jlcardosa
 * Date: 27/06/14
 * Time: 19:52
 */

namespace SimpleMailReceiver\Exceptions;

/**
 * Utility for catching PHP errors and converting them to an exception
 * that can be caught at runtime
 *
 * @category ExceptionThrower
 * @package  SimpleMailReceiver\Exceptions
 * @author   jlcardosa <joseluis.cardosamanzano@maviance.com>
 * @license  maviance GmbH 2014
 * @version  SVN: $Id$
 * @link     ExceptionThrower
 */
class ExceptionThrower
{

    /**
     * Start redirecting PHP errors
     *
     * @param int $level PHP Error level to catch (Default = E_ALL & ~E_DEPRECATED)
     * @return mixed
     */
    public function start($level = null)
    {

        if ($level == null)
        {
            $level = E_ALL;
        }
        set_error_handler(array($this, "handleError"), $level);
    }

    /**
     * Stop redirecting PHP errors
     */
    public function stop()
    {
        return restore_error_handler();
    }

    /**
     * Fired by the PHP error handler function.  Calling this function will
     * always throw an exception.
     *
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @param $errcontext
     * @throws \Exception
     * @return bool
     */
    public function handleError($errno, $errstr, $errfile, $errline, $errcontext) // $code, $string, $file, $line, $context)
    {
        // ignore supressed errors
        if (error_reporting() == 0) return;

        throw new \Exception($errstr, $errno);
    }
}