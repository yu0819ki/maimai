<?php namespace Yu0819ki\MaimaiModRedirect;

/**
 * The simple Redirector
 *
 * @author yu0819ki<yu0819ki@gmail.com>
 * @package maimai-mod-redirect
 */
class Redirector
{
    /**
     *
     * @param  string  $path   the path of redirecting to
     * @param  integer $status HTTP status code (301,302 and 307 are expedted)
     * @return void    exit.
     */
    public static function execute($path, $status = 302)
    {
        header('Location: ' . $path, true, intval($status, 10));
        exit;
    }
}