# SE_Assignment [![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://github.com/ixgoh/SE_Assignment/blob/master/LICENSE) [![CodeFactor](https://www.codefactor.io/repository/github/ixgoh/se_assignment/badge)](https://www.codefactor.io/repository/github/ixgoh/se_assignment) 
Repo for SEG2202 Software Engineering Assignment

Installation of IDE
1. Obtain a [student license from JetBrains](https://www.jetbrains.com/student/).
2. Install PhpStorm

## Installation Guide: Ubuntu
#### Git, PHP and Composer
Install Git, PHP and Composer globally 
```bash
sudo apt install git curl php php-curl php-cli php-xml php-mbstring 
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
```

#### Set up Git configuration
Run the following commands in Terminal. (Replace ```FULL_NAME``` and ```GITHUB_EMAIL``` with correct details.)
```bash
git config --global user.name "FULL_NAME"
git config --global user.email "GITHUB_EMAIL"
```

#### Project Cloning
1. Launch PhpStorm
2. Check Out from Version Control -> Git
3. URL to Repo: ```https://github.com/ixgoh/SE_Assignment.git```
4. Clone the project.

#### Composer Dependencies
Install Composer dependencies:
```bash
cd PhpstormProjects/SE_Assignment
composer install
```

#### Preparing Environment Variable
1. Get a new copy of .env
```bash
cp .env.example .env
nano .env
```
2. Setup Git Hook
```bash
cp pre-commit .git/hooks/pre-commit
```
3. Replace ```INSERT-SECRET-KEY``` as ```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```
4. Modify database related credentials as required.