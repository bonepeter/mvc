Readme
======


====================================
Production/Testing Environment Setup
====================================

- Apache, PHP, Mysql install

- Copy Framework directory to website

- Open install\index.php and follow the instructions


=============================
Development Environment Setup
=============================
Code check: (Run in server)
> cd /var/www/html/framework
> phploc src
> phpcpd src
> phpmd src text cleancode,codesize,controversial,design,naming,unusedcode
> phpcs src

> rm -r phpdoc
> phpdoc -t phpdoc -d . --template="responsive-twig"

