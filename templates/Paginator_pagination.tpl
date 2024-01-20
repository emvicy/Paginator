<!-- https://getbootstrap.com/docs/5.3/components/pagination/ -->

<nav aria-label="Page navigation example">
    <ul class="pagination">

        <li class="page-item">
            <a class="page-link" style="width: 64px;text-align: center;" href="{if ($Paginator_iCurrentPage - 1) > 0}?p={$Paginator_iCurrentPage - 1}{/if}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        {assign var=Paginator_iCount value=$Paginator_iNavTabStart}

        {* Iteration *}
        {section name=Paginator_Iteration start=$Paginator_iNavTabStart step=1 loop=$Paginator_iNavTabEnd}

            {* counter *}
            {assign var=Paginator_iCount value=($Paginator_iCount + 1)}

            <li class="page-item" style="width: 65px;text-align: center;">
                <a class="page-link {if $Paginator_iCurrentPage == $Paginator_iCount}active{/if}" href="?p={$Paginator_iCount}">
                    {$Paginator_iCount|floor}
                </a>
            </li>
        {/section}

        <li class="page-item">
            <a class="page-link" style="width: 64px;text-align: center;" href="{if ($Paginator_iCurrentPage + 1) <= $Paginator_iAmountPages}?p={$Paginator_iCurrentPage + 1}{/if}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>