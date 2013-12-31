chaw-source
===========

source code and project management application powering thechaw.com

## GETTING STARTED

If you have CakePHP 1.3, and have setup the cake to be in your PATH

```shell
git clone git@thechaw.com:chaw.git
cd chaw
cake bake db_config
cake schema create Chaw
visit, http://localhost/chaw/
```

### otherwise,

```shell
cd /your/document_root
git clone git://github.com/cakephp/cakephp1x.git
cd cakephp1x
git checkout -b 1.3 origin/1.3
```

```shell
git clone https://github.com/tigefa4u/chaw-source.git
cd chaw
../cake/console/cake -app chaw bake db_config
../cake/console/cake  -app chaw schema create
visit, http://localhost/chaw/
```


### FOR GIT
make sure "git" is in $PATH

create the users...

on OSX
http://osxdaily.com/2007/10/29/how-to-add-a-user-from-the-os-x-command-line-works-with-leopard/

```shell
sudo dscl . -create /Users/git
sudo dscl . -create /Users/git UserShell /bin/bash
sudo dscl . -create /Users/git NFSHomeDirectory /path/to/chaw/content/git/repo

sudo dscl . -create /Users/svn
sudo dscl . -create /Users/svn UserShell /bin/bash
sudo dscl . -create /Users/svn NFSHomeDirectory /path/to/chaw/content/svn/repo
```

on *nix
http://blog.drewolson.org/2008/05/remote-git-repos-on-ubuntu-right-way.html
adduser ...


GOTCHAS
-/etc/sshd_config
AllowUsers is all or has the git and svn users
StrictModes no
