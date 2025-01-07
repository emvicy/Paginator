
# Paginator

a Pagination module for Emvicy2 (2.x) PHP Framework: https://github.com/emvicy/Emvicy/tree/2.x

---

## Installation

_cd into the modules folder of your `Emvicy` copy; e.g.:_
~~~bash
cd /var/www/html/modules/;
~~~

_clone `Paginator`_  
~~~bash
git clone --branch 2.x https://github.com/emvicy/Paginator.git Paginator;
~~~

---

## Usage Examples

**In your Emvicy2 Controller/method**

_Request a subset of `oAppTableUser`_  
~~~php
/**
 * @param \MVC\DataType\DTRequestIn $oDTRequestIn
 * @param \MVC\DataType\DTRoute     $oDTRoute
 * @return void
 * @throws \ReflectionException
 */
public function index(DTRequestIn $oDTRequestIn, DTRoute $oDTRoute)
{
    $aAppTableUser = Paginator::calc(
        oView: view(),                 // View Object
        oDb: DB::use()->oAppTableUser, // DB Object
        iMaxProPage: 10,               // how many Items pro Page
        iMaxPaginationTabs: 18,        // max amount of Pagination Tabs
    );

    view()->assign('aAppTableUser', $aAppTableUser);
    view()->autoAssign();
}
~~~

_or, Request a subset also with `where` and `option` settings_  
~~~php
/**
 * @param \MVC\DataType\DTRequestIn $oDTRequestIn
 * @param \MVC\DataType\DTRoute     $oDTRoute
 * @return void
 * @throws \ReflectionException
 */
public function index(DTRequestIn $oDTRequestIn, DTRoute $oDTRoute)
{
    $aDTAppTableUser = Paginator::calc(
        oView: view(),                // View Object
        oDb: DB::use()->oAppTableUser,// DB Object
        aDTDBWhere: [                 // sql WHERE option
            DTDBWhere::create()->set_sKey('id_AppTableGroup')->set_sRelation('>=')->set_sValue(1)
        ],
        aDTDBOption: [                // sql option
            DTDBOption::create()->set_sValue('ORDER BY `name` DESC'))
        ],
        iMaxProPage: 3,               // how many Items pro Page 
        iMaxPaginationTabs: 18,       // max amount of Pagination Tabs
    );

    view()->assign('aAppTableUser', $aAppTableUser);
    view()->autoAssign();
}
~~~

---

**In your Emvicy2 Template**

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
