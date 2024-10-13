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
use MVC\Registry;

/**
 * Paginator
 */
class Paginator
{
    /**
     * @param \MVC\View        $oView
     * @param \MVC\DB\Model\Db $oDb
     * @param array            $aDTDBWhere
     * @param array            $aDTDBOption
     * @param int              $iMaxProPage
     * @param int              $iMaxPaginationTabs
     * @return \MVC\DB\DataType\DB\TableDataType[]
     * @throws \ReflectionException
     */
    public static function calc(\MVC\View $oView, \MVC\DB\Model\Db $oDb, array $aDTDBWhere = array(), array $aDTDBOption = array(), int $iMaxProPage = 1, int $iMaxPaginationTabs = 1)
    {
        // add the template directory of this module,
        // so that these templates can also be found and used from the primary module.nnen
        $oView->addTemplateDir(
            realpath(__DIR__ . '/../' . '/templates/')
        );

        $iAmountItems = count($oDb->retrieve(aDTDBWhere: $aDTDBWhere, aDTDBOption: $aDTDBOption));  # Number of all items in the requested DB table
        $iAmountPages = ceil($iAmountItems / $iMaxProPage);                                    # Number of individual pagination pages
        $iCurrentPage = (int) get($_GET['p'], 1);                                                   # current pagination page

        // Corrections
        ($iCurrentPage < 1) ? $iCurrentPage = 1 : false;
        ($iCurrentPage > $iAmountPages) ? $iCurrentPage = $iAmountPages : false;

        // Db Limit start, amount
        $iLimitPointer = (int) (($iCurrentPage - 1) * $iMaxProPage);
        ($iLimitPointer < 0) ? $iLimitPointer = 0 : false;
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
        $aDTTable = $oDb->retrieve(aDTDBWhere: $aDTDBWhere, aDTDBOption: $aDTDBOption);

        // save vars to registry
        Registry::set('aPaginatorSet', [
            'Paginator_iAmountPages' => $iAmountPages,
            'Paginator_iCurrentPage' => $iCurrentPage,
            'Paginator_iNavTabStart' => $iNavTabStart,
            'Paginator_iNavTabEnd' => $iNavTabEnd,
        ]);

        // assign vars to view
        $oView->assign('Paginator_iAmountPages', $iAmountPages);
        $oView->assign('Paginator_iCurrentPage', $iCurrentPage);
        $oView->assign('Paginator_iNavTabStart', $iNavTabStart);
        $oView->assign('Paginator_iNavTabEnd', $iNavTabEnd);

        return $aDTTable;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function getPaginatorSet() : array
    {
        return (
            (Registry::isRegistered('aPaginatorSet'))
            ? (array) Registry::get('aPaginatorSet')
            : [
                'Paginator_iAmountPages' => 0,
                'Paginator_iCurrentPage' => 1,
                'Paginator_iNavTabStart' => 1,
                'Paginator_iNavTabEnd' => 1,
            ]
        );
    }
}
