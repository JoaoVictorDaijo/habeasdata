<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita9fb189ab860e4e4c4702e537fc45de5
{
    public static $files = array (
        '3a37ebac017bc098e9a86b35401e7a68' => __DIR__ . '/..' . '/mongodb/mongodb/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PackageVersions\\' => 16,
        ),
        'M' => 
        array (
            'MongoDB\\' => 8,
        ),
        'J' => 
        array (
            'Jean85\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PackageVersions\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/package-versions-deprecated/src/PackageVersions',
        ),
        'MongoDB\\' => 
        array (
            0 => __DIR__ . '/..' . '/mongodb/mongodb/src',
        ),
        'Jean85\\' => 
        array (
            0 => __DIR__ . '/..' . '/jean85/pretty-package-versions/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita9fb189ab860e4e4c4702e537fc45de5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita9fb189ab860e4e4c4702e537fc45de5::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
