<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit55a3e7b1b712a91e9e4ab91618db5e42
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit55a3e7b1b712a91e9e4ab91618db5e42', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit55a3e7b1b712a91e9e4ab91618db5e42', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit55a3e7b1b712a91e9e4ab91618db5e42::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
