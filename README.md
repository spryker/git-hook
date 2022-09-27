# GitHook
[![CI](https://github.com/spryker/git-hook/actions/workflows/ci.yml/badge.svg)](https://github.com/spryker/git-hook/actions/workflows/ci.yml)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat)](https://phpstan.org/)
[![License](https://img.shields.io/github/license/spryker/git-hook.svg)](https://packagist.org/packages/spryker/git-hook)

GitHook for Spryker. This tool will add Git hooks to your local hooks directory.

Currently, we have only one hook named `pre-commit`.
This will execute on every commit the applied commands.

## Installation
```
composer require --dev spryker/git-hook
```
### Setup
Add the Composer scripts to your composer.json

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

This will copy the git hooks to your `.git/hooks/` directory.

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
    - GitHook\Command\RepositoryCommand\PreCommit\ValidateBranchNameCommand
    - GitHook\Command\RepositoryCommand\PreCommit\GitAddCommand
```

See the full list of available commands in `src/GitHook/Command/FileCommand/` and `src/GitHook/Command/RepositoryCommand/`.

Note: `GitHook\Command\RepositoryCommand\PreCommit\GitAddCommand` will automatically add all new files into commit, when other commands changed the files ( `CodeStyleFixCommand` does it for example), so use it only when you really need it!

### PhpStanCheckCommand configuration

```
config:
    phpstan:
        level: 8
        config: .phpstan.neon
```

## Commands

We have two different type of commands. FileCommands will be executed for each file of a given commit. RepositoryCommands will be executed on the whole repository.
