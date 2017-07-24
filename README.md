# GithubHooks
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)

GithubHooks Spryker.

### Setup
Add the Composer Scripts to your composer.json

```
  "scripts": {
    "post-install-cmd": [
      "GithubHook\\Composer\\Scripts\\HookInstaller::installHooks"
    ],
    "post-update-cmd": [
      "GithubHook\\Composer\\Scripts\\HookInstaller::installHooks"
    ]
  }
```

This will copy the github hooks to your .git/hooks directory.
