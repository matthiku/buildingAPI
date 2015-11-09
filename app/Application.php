<?php namespace App;

use Laravel\Lumen\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * Get the current HTTP path info.
     *
     * @return string
     */
    public function getPathInfo()
    {
        $query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

        return '/'.ltrim(
            str_replace(
                '?'.$query,
                '',
                str_replace(
                    rtrim(
                        str_replace(
                            last(explode('/', $_SERVER['PHP_SELF'])),
                            '',
                            $_SERVER['SCRIPT_NAME']
                        ),
                    '/'),
                    '',
                    $_SERVER['REQUEST_URI']
                )
            ),
        '/');
    }
}