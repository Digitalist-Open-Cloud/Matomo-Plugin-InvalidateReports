<?php

/**
 * InnoCraft - the company of the makers of Piwik Analytics, the free/libre analytics platform
 *
 * @link    https://www.innocraft.com
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\InvalidateReports;

use Piwik\Piwik;
use Piwik\View;
use Piwik\Plugins\InvalidateReports\API as InvalidateReportsAPI;
use Piwik\API\Request as APIRequest;
use Piwik\Request;

/**
 *
 */
class Controller extends \Piwik\Plugin\ControllerAdmin
{
    public function index()
    {
        Piwik::checkUserHasSuperUserAccess();

        $view = new View('@InvalidateReports/admin');
        $this->setBasicVariablesView($view);

        $view->availableRanges   = $this->getAvailableRanges();

        return $view->render();
    }

    public function invalidateReports()
    {
        $siteIds = Request::fromRequest()->getStringParameter('idSites', '');
        $segment = APIRequest::getRawSegmentFromRequest();
        $months = Request::fromRequest()->getStringParameter('months', '');
        $api = new InvalidateReportsAPI();
        return $api->invalidate($siteIds, $segment, $months);
    }

    protected function getAvailableRanges()
    {
        return [
            0  => Piwik::translate('InvalidateReports_AllData'),
            24 => Piwik::translate('InvalidateReports_XMonths', 24),
            12 => Piwik::translate('InvalidateReports_XMonths', 12),
            6  => Piwik::translate('InvalidateReports_XMonths', 6),
            3  => Piwik::translate('InvalidateReports_XMonths', 3),
            1  => Piwik::translate('InvalidateReports_LastMonth'),
        ];
    }
}
