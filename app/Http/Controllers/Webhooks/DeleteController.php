<?php

/**
 * DeleteController.php
 * Copyright (c) 2019 james@firefly-iii.org
 *
 * This file is part of Firefly III (https://github.com/firefly-iii).
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace FireflyIII\Http\Controllers\Webhooks;

use FireflyIII\Http\Controllers\Controller;
use FireflyIII\Models\Webhook;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DeleteController
 */
class DeleteController extends Controller
{
    /**
     * DeleteController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // translations:
        $this->middleware(
            static function ($request, $next) {
                app('view')->share('mainTitleIcon', 'fa-bolt');
                app('view')->share('subTitleIcon', 'fa-trash');
                app('view')->share('title', (string) trans('firefly.webhooks'));
                app('view')->share('subTitle', (string) trans('firefly.delete_webhook'));

                return $next($request);
            }
        );
    }

    /**
     * Delete account screen.
     *
     * @return Application|Factory|View
     */
    public function index(Webhook $webhook)
    {
        if (false === config('firefly.allow_webhooks')) {
            Log::channel('audit')->warning('User visits webhook delete page, but webhooks are DISABLED.');

            throw new NotFoundHttpException('Webhooks are not enabled.');
        }
        Log::channel('audit')->info('User visits webhook delete page.');
        $subTitle = (string) trans('firefly.delete_webhook', ['title' => $webhook->title]);
        $this->rememberPreviousUrl('webhooks.delete.url');

        return view('webhooks.delete', compact('webhook', 'subTitle'));
    }
}
