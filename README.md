# GitHook
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/github/license/spryker/git-hook.svg)](https://packagist.org/packages/spryker/git-hook)

GitHook for Spryker. This tool will add git hooks to your .git/hooks directory. 

Currently we have only one hook `pre-commit` this will execute on every commit the applied commands.

## Installation

`composer require --dev spryker/git-hook`

### Setup
Add the Composer Scripts to your composer.json

#### For core

```
  "scripts": {
    "post-install-cmd": [
      "GitHook\\Composer\\Scripts\\HookInstaller::installSprykerHooks",
      "GitHook\\Composer\\Scripts\\HookInstaller::installEcoHooks"
    ],
    "post-update-cmd": [
      "GitHook\\Composer\\Scripts\\HookInstaller::installSprykerHooks",
      "GitHook\\Composer\\Scripts\\HookInstaller::installEcoHooks"
    ]
  }
```
#### For projects

```
  "scripts": {
    "post-install-cmd": [
      "GitHook\\Composer\\Scripts\\HookInstaller::installProjectHooks"
    ],
    "post-update-cmd": [
      "GitHook\\Composer\\Scripts\\HookInstaller::installProjectHooks"
    ]
  }
```

This will copy the git hooks to your .git/hooks directory.

## Configuration

Place a `.githook` file into the root of your project. Inside of this file you can configure the commands.

You can also use your private configuration by creating a `.githook_local` file and ignore it in your projects .gitignore` file.

### Enable commands

To enable commands you need to specify them. An example configuration could look like this:

```
preCommitFileCommands:
    - GitHook\Command\FileCommand\PreCommit\ArchitectureCheckCommand
    - GitHook\Command\FileCommand\PreCommit\CodeStyleCheckCommand
    - GitHook\Command\FileCommand\PreCommit\CodeStyleFixCommand
    - GitHook\Command\FileCommand\PreCommit\PhpMdCheckCommand
    - GitHook\Command\FileCommand\PreCommit\PhpStanCheckCommand

preCommitRepositoryCommands:
    - GitHook\Command\RepositoryCommand\PreCommit\GitAddCommand
```


### PhpStanCheckCommand configuration

```
config:
    phpstan:
        level: 7
        config: .phpstan.neon
```

## Commands

We have two different type of commands. FileCommands will be executed for each file of a given commit. RepositoryCommands will be executed on the whole repository.
