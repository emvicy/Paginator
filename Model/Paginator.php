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
    public static function calc(\MVC\View $oView, \MVC\DB\Model\Db $oDb, array $aDTDBWhere = array(), array $aDTDBOption = array(), int $iMaxProPage = 1, int $iMaxPaginationTabs = 1): array
    {
        // add the template directory of this module,
        // so that these templates can also be found and used from the primary module.nnen
        $oView->addTemplateDir(
            realpath(__DIR__ . '/../' . '/templates/')
        );

        $iAmountItems = $oDb->count($aDTDBWhere, $aDTDBOption);     # Number of all items in the requested DB table
        $iAmountPages = ceil($iAmountItems / $iMaxProPage);    # Number of individual pagination pages
        $iCurrentPage = (int) ($_GET['p'] ?? 1);                    # current pagination page

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
        Registry::set('aPaginator3Set', [
            'Paginator3_iAmountPages' => $iAmountPages,
            'Paginator3_iCurrentPage' => $iCurrentPage,
            'Paginator3_iNavTabStart' => $iNavTabStart,
            'Paginator3_iNavTabEnd' => $iNavTabEnd,
        ]);

        // assign vars to view
        $oView->assign('Paginator3_iAmountPages', $iAmountPages);
        $oView->assign('Paginator3_iCurrentPage', $iCurrentPage);
        $oView->assign('Paginator3_iNavTabStart', $iNavTabStart);
        $oView->assign('Paginator3_iNavTabEnd', $iNavTabEnd);

        return $aDTTable;
    }

    /**
     * @param \MVC\View        $oView
     * @param \MVC\DB\Model\Db $oDb
     * @param string           $sSqlSelect
     * @param string           $sCountOn
     * @param int              $iMaxProPage
     * @param int              $iMaxPaginationTabs
     * @return \MVC\DB\DataType\DB\TableDataType[]
     * @throws \ReflectionException
     */
    public static function calcOnSql(\MVC\View $oView, \MVC\DB\Model\Db $oDb, string $sSqlSelect = '', string $sCountOn= 'id', int $iMaxProPage = 1, int $iMaxPaginationTabs = 1): array
    {
        // add the template directory of this module,
        // so that these templates can also be found and used from the primary module.nnen
        $oView->addTemplateDir(
            realpath(__DIR__ . '/../' . '/templates/')
        );

        $sSqlCount = preg_replace(
            pattern: '/[^SELECT](.*)FROM/i',
            replacement: ' COUNT(' . preg_replace('/[^a-zA-Z]/', '', $sCountOn) . ') FROM ',
            subject: $sSqlSelect,
            limit: 1
        );

        $iAmountItems = (array_first($oDb->fetchRow(sSql: $sSqlCount)) ?? 0); # Number of all items in the requested DB table
        $iAmountPages = ceil($iAmountItems / $iMaxProPage);    # Number of individual pagination pages
        $iCurrentPage = (int) ($_GET['p'] ?? 1);                    # current pagination page

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

        // Get items from DB Table
        $aDTTable = $oDb->fetchAll(
            sSql: $sSqlSelect . "\nLIMIT " . $iLimitPointer . ", " . $iLimitAmount,
            bReturnDatatypeArray: true
        );

        // save vars to registry
        Registry::set('aPaginator3Set', [
            'Paginator3_iAmountPages' => $iAmountPages,
            'Paginator3_iCurrentPage' => $iCurrentPage,
            'Paginator3_iNavTabStart' => $iNavTabStart,
            'Paginator3_iNavTabEnd' => $iNavTabEnd,
        ]);

        // assign vars to view
        $oView->assign('Paginator3_iAmountPages', $iAmountPages);
        $oView->assign('Paginator3_iCurrentPage', $iCurrentPage);
        $oView->assign('Paginator3_iNavTabStart', $iNavTabStart);
        $oView->assign('Paginator3_iNavTabEnd', $iNavTabEnd);

        return $aDTTable;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function getPaginatorSet() : array
    {
        return (
            (Registry::isRegistered('aPaginator3Set'))
            ? (array) Registry::get('aPaginator3Set')
            : [
                'Paginator3_iAmountPages' => 0,
                'Paginator3_iCurrentPage' => 1,
                'Paginator3_iNavTabStart' => 1,
                'Paginator3_iNavTabEnd' => 1,
            ]
        );
    }
}
