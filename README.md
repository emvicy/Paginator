
# Paginator

a Pagination module for Emvicy PHP Framework: https://github.com/emvicy/Emvicy

## Install

cd into the modules folder and clone your copy:

~~~bash
git clone --branch 1.x https://github.com/emvicy/Paginator.git
~~~

## Usage Examples

_Request a subset of User_  
~~~php
$aDTFooModelTableUser = Paginator::calc(
    oView: view()                 // View Object
    oDb: DB::$oFooModelTableUser, // DB Object
    iMaxProPage: 3,               // how many Items pro Page 
    iMaxPaginationTabs: 18,       // max amount of Pagination Tabs
);
~~~

_Request a subset of User_
~~~php
$aDTFooModelTableUser = Paginator::calc(
    oView: view(),                // View Object
    oDb: DB::$oFooModelTableUser, // DB Object
    aDTDBOption: [                // sql option    
        DTDBOption::create()->set_sValue('ORDER BY `name` DESC'))
    ],
    iMaxProPage: 3,               // how many Items pro Page 
    iMaxPaginationTabs: 18,       // max amount of Pagination Tabs
);
~~~

_include this Paginator template_  
~~~php
{include file="Paginator_pagination.tpl"}
~~~

