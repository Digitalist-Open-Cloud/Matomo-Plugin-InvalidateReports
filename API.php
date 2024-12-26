<?php

namespace Piwik\Plugins\InvalidateReports;

use Piwik\Piwik;
use Piwik\API\Request as APIRequest;
use Piwik\Period\Range;
use Piwik\Site;

/**
 * API for plugin InvalidateReports
 *
 * @method static \Piwik\Plugins\InvalidateReports\API getInstance()
 */
class API extends \Piwik\Plugin\API
{
    public function invalidate($siteIds, $segment, $months, $date = null)
    {
        // @todo: add possibility to invalidate report from a report view
        // This is the now unused $date argument.
        Piwik::checkUserHasSuperUserAccess();
        $dates   = [];
        list($minDate, $maxDate) = Site::getMinMaxDateAcrossWebsites($siteIds);

        if ($months > 0) {
            $minDate = $maxDate->subMonth($months);
        }

        $range = new Range('day', $minDate->toString() . ',' . $maxDate->toString());

        foreach ($range->getSubperiods() as $subPeriod) {
            $dates[] = $subPeriod->getDateStart();
        }

        return APIRequest::processRequest('CoreAdminHome.invalidateArchivedReports', [
          'format'  => 'json',
          'idSites' => $siteIds,
          'period'  => false,
          'dates'   => implode(',', $dates),
          'segment' => $segment,
          'cascadeDown' => false
        ]);
    }
}
