<?php

namespace Ollieread\Admin\Actions;

use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Articles\Operations\GetRecentComments;
use Ollieread\Core\Support\Action;

class Dashboard extends Action
{
    public function __invoke()
    {
        $comments = (new GetRecentComments)->perform();
        /*$analytics = [
            'pages'     => (new Analytics)->get(env('GA_VIEW_ID'), ['ga:landingPagePath'], ['ga:hits']),
            'referrers' => (new Analytics)->get(env('GA_VIEW_ID'), ['ga:fullReferrer'], ['ga:hits']),
        ];
        usort($analytics['pages'], [$this, 'sortAnalytics']);
        usort($analytics['referrers'], [$this, 'sortAnalytics']);
        $analytics['pages']     = array_merge(...$analytics['pages']);
        $analytics['referrers'] = array_merge(...$analytics['referrers']);*/
        $analytics = [];
        $articles  = (new GetArticles)
            ->setNotReleased(true)
            ->setLimit(10)
            ->perform();

        return $this->response()->view('admin.dashboard', compact('comments', 'articles', 'analytics'));
    }

    private function sortAnalytics($a, $b): int
    {
        $aValue = array_values($a)[0];
        $bValue = array_values($b)[0];

        return ($aValue > $bValue) ? -1 : 1;
    }
}
