<footer class="footer">
    <div class="container">
        <div class="level">
            <div class="level-left">
                <a href="{{ route('pages.privacy-policy') }}" title="{{ __('navigation.privacy_policy') }}" target="_blank">
                    {{ __('navigation.privacy_policy') }}
                </a>
            </div>

            <div class="level-right">
                @include('components.languageSelector')
            </div>
        </div>
    </div>
</footer>
