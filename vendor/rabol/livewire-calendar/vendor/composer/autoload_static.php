<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit950084db625deef0458bacc69e6805f8
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Rabol\\LivewireCalendar\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Rabol\\LivewireCalendar\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit950084db625deef0458bacc69e6805f8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit950084db625deef0458bacc69e6805f8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit950084db625deef0458bacc69e6805f8::$classMap;

        }, null, ClassLoader::class);
    }
}
