<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdd158f16834c1d000caa25bcea8272d7
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DiDom\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DiDom\\' => 
        array (
            0 => __DIR__ . '/..' . '/imangazaliev/didom/src/DiDom',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdd158f16834c1d000caa25bcea8272d7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdd158f16834c1d000caa25bcea8272d7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdd158f16834c1d000caa25bcea8272d7::$classMap;

        }, null, ClassLoader::class);
    }
}