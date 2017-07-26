# GitHook
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)

GitHook for Spryker.

### Setup
Add the Composer Scripts to your composer.json

```
  "scripts": {
    "post-install-cmd": [
      "GitHook\\Composer\\Scripts\\HookInstaller::installProjectHooks"
      "GitHook\\Composer\\Scripts\\HookInstaller::installCoreHooks"
    ],
    "post-update-cmd": [
      "GitHook\\Composer\\Scripts\\HookInstaller::installProjectHooks"
      "GitHook\\Composer\\Scripts\\HookInstaller::installCoreHooks"
    ]
  }
```

This will copy the git hooks to your .git/hooks directory.
