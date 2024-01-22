<?php
/**
 * Paginator.php
 *
 * @package Emvicy
 * @copyright ueffing.net
 * @author Guido K.B.W. Üffing <emvicy@ueffing.net>
 * @license GNU GENERAL PUBLIC LICENSE Version 3. See application/doc/COPYING
 */

/**
 * @name $PaginatorModel
 */
namespace Paginator\Model;

use MVC\DataType\DTDBOption;

/**
 * Paginator
 */
class Paginator
{
    /**
     * @param \MVC\DB\Model\Db $oDb
     * @param int $iMaxProPage
     * @param int $iMaxPaginationTabs
     * @param \MVC\View $oView
     * @param array $aDTDBOption
     * @return \MVC\DB\DataType\DB\TableDataType[]
     * @throws \ReflectionException
     */
    public static function calc(\MVC\DB\Model\Db $oDb, int $iMaxProPage = 1, int $iMaxPaginationTabs = 1, \MVC\View $oView, array $aDTDBOption = array())
    {
        // add the template directory of this module,
        // so that these templates can also be found and used from the primary module.nnen
        $oView->addTemplateDir(
            realpath(__DIR__ . '/../' . '/templates/')
        );

        $iAmountClients = $oDb->count();                           # Number of all items in the requested DB table
        $iAmountPages = ceil($iAmountClients / $iMaxProPage); # Number of individual pagination pages
        $iCurrentPage = (int) get($_GET['p'], 1);   # current pagination page

        // Corrections
        ($iCurrentPage < 1) ? $iCurrentPage = 1 : false;
        ($iCurrentPage > $iAmountPages) ? $iCurrentPage = $iAmountPages : false;

        // Db Limit start, amount
        $iLimitPointer = (int) (($iCurrentPage - 1) * $iMaxProPage);
        $iLimitAmount = $iMaxProPage;

        // Corrections
        ($iMaxPaginationTabs > $iAmountPages) ? $iMaxPaginationTabs = $iAmountPages : false;

        // Pagination Tabs
        $iNavTabStart = ($iCurrentPage - ($iMaxPaginationTabs / 2));
        $iNavTabEnd = ($iCurrentPage + ($iMaxPaginationTabs / 2));

        // Corrections
        if ($iNavTabStart < 0)
        {
            $iNavTabEnd += abs($iNavTabStart);
            $iNavTabStart = 0;
        }

        if ($iNavTabEnd > $iAmountPages)
        {
            $iNavTabStart -= ($iNavTabEnd - $iAmountPages);
            $iNavTabEnd = $iAmountPages;
        }

        $aDTDBOption[] = DTDBOption::create()->set_sValue('LIMIT ' . $iLimitPointer . ', ' . $iLimitAmount);

        // Get items from DB Table
        $aDTTable = $oDb->retrieve(aDTDBOption: $aDTDBOption);

        // assign vars
        $oView->assign('Paginator_iAmountPages', $iAmountPages);
        $oView->assign('Paginator_iCurrentPage', $iCurrentPage);
        $oView->assign('Paginator_iNavTabStart', $iNavTabStart);
        $oView->assign('Paginator_iNavTabEnd', $iNavTabEnd);

        return $aDTTable;
    }
}
