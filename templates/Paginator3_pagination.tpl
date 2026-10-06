<!-- https://getbootstrap.com/docs/5.3/components/pagination/ -->

{nocache}
<nav aria-label="Page navigation example">
    <ul class="pagination">

        <li class="page-item">
            <a class="page-link" style="width: 64px;text-align: center;" href="{if ($Paginator3_iCurrentPage - 1) > 0}?p={$Paginator3_iCurrentPage - 1}{/if}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        {assign var=Paginator3_iCount value=$Paginator3_iNavTabStart|floor}

        {* Iteration *}
        {section name=Paginator3_Iteration start=$Paginator3_iNavTabStart step=1 loop=$Paginator3_iNavTabEnd}

            {* counter *}
            {assign var=Paginator3_iCount value=($Paginator3_iCount + 1)}

            <li class="page-item" style="width: 65px;text-align: center;">
                <a class="page-link {if $Paginator3_iCurrentPage == $Paginator3_iCount}active{/if}" href="?p={$Paginator3_iCount}">
                    {$Paginator3_iCount}
                </a>
            </li>
        {/section}

        <li class="page-item">
            <a class="page-link" style="width: 64px;text-align: center;" href="{if ($Paginator3_iCurrentPage + 1) <= $Paginator3_iAmountPages}?p={$Paginator3_iCurrentPage + 1}{/if}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
{/nocache}
