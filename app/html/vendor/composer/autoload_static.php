<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf216a994d8da14b1c7850ae08f3fbf3e
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf216a994d8da14b1c7850ae08f3fbf3e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf216a994d8da14b1c7850ae08f3fbf3e::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitf216a994d8da14b1c7850ae08f3fbf3e::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitf216a994d8da14b1c7850ae08f3fbf3e::$classMap;

        }, null, ClassLoader::class);
    }
}
