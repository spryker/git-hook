<?php

$autoloader = function ($className) {

    $classNameMap = [
        '_generated\AcceptanceTesterActions' => __DIR__ . '/../../../tests/_support/_generated/AcceptanceTesterActions.php',
        '_generated\ConsoleTesterActions' => __DIR__ . '/../../../tests/_support/_generated/ConsoleTesterActions.php',
        '_generated\NoGuyActions' => __DIR__ . '/../../../tests/_support/_generated/NoGuyActions.php',
        'AcceptanceTester' => __DIR__ . '/../../../tests/_support/AcceptanceTester.php',
        'ConsoleTester' => __DIR__ . '/../../../tests/_support/ConsoleTester.php',
        'FunctionalTester' => __DIR__ . '/../../../tests/_support/FunctionalTester.php',
        'NoGuy' => __DIR__ . '/../../../tests/_support/NoGuy.php',
        'UnitTester' => __DIR__ . '/../../../tests/_support/UnitTester.php',
        'YvesAcceptanceTester' => __DIR__ . '/../../../tests/_support/YvesAcceptanceTester.php',
        'ZedAcceptanceTester' => __DIR__ . '/../../../tests/_support/ZedAcceptanceTester.php',
    ];

    if (isset($classNameMap[$className])) {
        require $classNameMap[$className];

        return true;
    }

    return false;
};

spl_autoload_register($autoloader);
