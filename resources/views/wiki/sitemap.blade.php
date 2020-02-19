{{--
    Copyright (c) ppy Pty Ltd <contact@ppy.sh>.

    This file is part of osu!web. osu!web is distributed with the hope of
    attracting more community contributions to the core ecosystem of osu!.

    osu!web is free software: you can redistribute it and/or modify
    it under the terms of the Affero GNU General Public License version 3
    as published by the Free Software Foundation.

    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    See the GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
--}}
@extends('master')

@section('content')
    @component('layout._page_header_v4', ['params' => [
        'links' => [
            [
                'title' => trans('layout.header.help.index'),
                'url' => wiki_url('Main_Page'),
            ],
            [
                'title' => trans('layout.header.help.sitemap'),
                'url' => route('wiki.sitemap'),
            ],
        ],
        'linksBreadcrumb' => true,
        'section' => trans('layout.header.help._'),
        'subSection' => trans('wiki.sitemap.title'),
        'theme' => 'help',
    ]])
    @endcomponent

    <div class="osu-page osu-page--wiki">
        <div class="wiki-page">
            <div class="wiki-page__content">
                <ul>
                    @foreach ($sitemap as $key => $value)
                        @include('wiki._sitemap_section', ['titles' => $titles, 'sitemap' => $value, 'parent' => "{$parent}/{$key}"])
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
