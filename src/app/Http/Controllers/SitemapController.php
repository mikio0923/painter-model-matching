<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\ModelProfile;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        // 静的ページ
        $staticPages = [
            ['path' => '/',                  'changefreq' => 'daily',   'priority' => '1.0'],
            ['path' => '/models',            'changefreq' => 'daily',   'priority' => '0.9'],
            ['path' => '/jobs',              'changefreq' => 'daily',   'priority' => '0.9'],
            ['path' => '/about',             'changefreq' => 'monthly', 'priority' => '0.6'],
            ['path' => '/faq',               'changefreq' => 'monthly', 'priority' => '0.5'],
            ['path' => '/guideline',         'changefreq' => 'monthly', 'priority' => '0.5'],
            ['path' => '/guide/model',       'changefreq' => 'monthly', 'priority' => '0.5'],
            ['path' => '/guide/painter',     'changefreq' => 'monthly', 'priority' => '0.5'],
            ['path' => '/terms',             'changefreq' => 'yearly',  'priority' => '0.3'],
            ['path' => '/privacy',           'changefreq' => 'yearly',  'priority' => '0.3'],
        ];

        foreach ($staticPages as $page) {
            $urls[] = [
                'loc'        => url($page['path']),
                'lastmod'    => null,
                'changefreq' => $page['changefreq'],
                'priority'   => $page['priority'],
            ];
        }

        // 公開モデル
        ModelProfile::where('is_public', true)
            ->select(['id', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->limit(1000)
            ->each(function ($profile) use (&$urls) {
                $urls[] = [
                    'loc'        => route('models.show', $profile->id),
                    'lastmod'    => $profile->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority'   => '0.7',
                ];
            });

        // 公開中の依頼
        Job::where('status', 'open')
            ->select(['id', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->limit(1000)
            ->each(function ($job) use (&$urls) {
                $urls[] = [
                    'loc'        => route('jobs.show', $job->id),
                    'lastmod'    => $job->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority'   => '0.7',
                ];
            });

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
