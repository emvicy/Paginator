
# Paginator

a Pagination module for Emvicy2 (2.x) PHP Framework: https://github.com/emvicy/Emvicy/tree/2.x

---

## Install

cd into the modules folder and clone your copy:

~~~bash
git clone --branch 2.x https://github.com/emvicy/Paginator.git
~~~

---

## Usage Examples

**In your Emvicy2 Controller**

_Request a subset of `oAppTableUser`_  
~~~php
$aDTAppTableUser = Paginator::calc(
    oView: view()                  // View Object
    oDb: DB::use()->oAppTableUser, // DB Object
    iMaxProPage: 3,                // how many Items pro Page 
    iMaxPaginationTabs: 18,        // max amount of Pagination Tabs
);
~~~

_or, Request a subset also with `where` and `option` settings_  
~~~php
$aDTAppTableUser = Paginator::calc(
    oView: view(),                // View Object
    oDb: DB::use()->oAppTableUser, // DB Object
    aDTDBWhere: [                 // sql WHERE option
        DTDBWhere::create()->set_sKey('id_AppTableGroup')->set_sRelation('>=')->set_sValue(1)
    ],
    aDTDBOption: [                // sql option
        DTDBOption::create()->set_sValue('ORDER BY `name` DESC'))
    ],
    iMaxProPage: 3,               // how many Items pro Page 
    iMaxPaginationTabs: 18,       // max amount of Pagination Tabs
);
~~~

_assign the Result `$aDTAppTableUser` to your View_  
~~~php
view()->assign('aDTAppTableUser', $aDTAppTableUser);
~~~

---

**In your Emvicy2 Template**

_include this Paginator template_  
~~~php
{include file="Paginator_pagination.tpl"}
~~~

_full Template example_  
~~~php
{include file="Paginator_pagination.tpl"}

<table class="table table-responsive-sm table-sm table-hover">
    <!--column names-->
    <thead>
    <tr>
        <th>id</th>
        <th>email</th>
        <th>nickname</th>
        <th>Forename</th>
        <th>Lastname</th>
        <th>Date</th>
    </tr>
    </thead>
    <!--content-->
    <tbody>
    {nocache}
    {foreach item=oAppTableUser from=$aAppTableUser}
    <tr>
        <td>{$oAppTableUser->get_id()}</td>
        <td>{$oAppTableUser->get_email()}</td>
        <td>{$oAppTableUser->get_nickname()}</td>
        <td>{$oAppTableUser->get_forename()}</td>
        <td>{$oAppTableUser->get_lastname()}</td>
        <td>{$oAppTableUser->get_stampCreate()}</td>
    </tr>
    {/foreach}
    {/nocache}
    </tbody>
</table>

{include file="Paginator_pagination.tpl"}
~~~
