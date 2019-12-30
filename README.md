Game Overflow
===========================================


[![Latest Stable Version](https://poser.pugx.org/pamo18/ramverk1-project/v/stable)](https://packagist.org/packages/pamo18/ramverk1-project)
[![Gitter](https://badges.gitter.im/pamo18/community.svg)](https://gitter.im/pamo18/community?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

[![Build Status](https://travis-ci.org/pamo18/ramverk1-project.svg?branch=master)](https://travis-ci.org/pamo18/ramverk1-project)

[![Build Status](https://scrutinizer-ci.com/g/pamo18/ramverk1-project/badges/build.png?b=master)](https://scrutinizer-ci.com/g/pamo18/ramverk1-project/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pamo18/ramverk1-project/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pamo18/ramverk1-project/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/pamo18/ramverk1-project/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/pamo18/ramverk1-project/?branch=master)

This is a project for the Ramverk1 course at Blekinge Tekniska Högskola

The aim is to make a Stack Overflow clone complete with Q&A, comments and tags.

This is how you install the module into an existing [Anax](https://github.com/canax/anax-ramverk1-me) installation.

Install using composer.

```
composer require pamo18/ramverk1-project
```

Copy all the necessary files to the current framework.


```
bash vendor/pamo18/ramverk1-project/script/post-composer-require.bash
```

Dont forget to add the namespace to the autoloader in your composer file.

```
Pamo\\: src/
```

Dependency
------------------

This is an Anax module and primarly intended to be used together with the Anax framework.  This module includes controllers, views, css and navigation.



License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.



```
Copyright (c) 2020 Paul Moreland (paul.moreland@lidkoping.se)
```
