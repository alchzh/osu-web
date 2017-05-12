{{--
    Copyright 2015-2017 ppy Pty. Ltd.

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
@if(!Auth::user())
<div class="row">
    <div class="col-md-6">
        You need to be <a href="#" class="js-user-link" title="{{ trans("users.anonymous.login_link") }}">logged in</a> to stuff!
    </div>
</div>
@else
<div id="js-store-support-osu" class="row js-store-support-osu store-support-osu"
    data-username="{{ Auth::user()->username }}" data-avatar-url="{{ Auth::user()->user_avatar }}">
    <div class="store-support-osu__user">
        <div class="store-support-osu__user-icon">
            <center>
                <div style="background-image: url('{{ Auth::user()->user_avatar }}');" class="avatar js-avatar"></div>
            </center>
        </div>
        <div class="store-column store-column--full-width store-support-osu__textual-info">
            <div class="store-row">
                <div>
                    <span>
                        {!! Form::label('username', 'Gift a player') !!}
                        {!! Form::text('item[extra_info]', Auth::user()->username, ['id' => 'username', 'class' => 'form-control', 'placeholder' => 'Enter a username', 'autocomplete' => 'off']) !!}
                        <span class="js-error error"></span>
                    </span>
                    <div>Currently have no supporter tag</div>
                    <div>Choose your amount</div>
                </div>
                <div class="price-box">
                    <p class="js-price price text-right">MONEHS</p>
                    <div class="js-duration text-right">
                        1 month
                    </div>
                    <div class="js-price-per-month text-right"></div>
                    <div class="js-discount text-right"></div>
                </div>
            </div>
            <div>
                <div class="js-slider ui-slider ui-slider-horizontal ui-widget ui-widget-content">
                    <div class="ui-slider-handle"></div>
                </div>
                <button type="button" class="js-slider-preset" data-preset-value="8">2 months</button>
                <button type="button" class="js-slider-preset" data-preset-value="12"></button>
                <button type="button" class="js-slider-preset" data-preset-value="16"></button>
                <button type="button" class="js-slider-preset" data-preset-value="20"></button>
                <button type="button" class="js-slider-preset" data-preset-value="22"></button>
                <button type="button" class="js-slider-preset" data-preset-value="24"></button>
                <button type="button" class="js-slider-preset" data-preset-value="26"></button>
                <button type="button" class="js-slider-preset" data-preset-value="52"></button>
            </div>
        </div>
    </div>
</div>
@endif
