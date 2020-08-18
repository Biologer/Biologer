<footer class="footer bg-light">
    <div class="container">
        <div class="level">
            <div class="level-left flex-col items-start">
                <a href="{{ route('pages.privacy-policy') }}" title="{{ __('navigation.privacy_policy') }}" target="_blank">
                    {{ __('navigation.privacy_policy') }}
                </a>

                <a href="{{ route('licenses.index') }}" title="{{ __('navigation.licenses') }}" target="_blank">
                    {{ __('navigation.licenses') }}
                </a>
            </div>

            <div class="level-right">
                @include('components.language-selector')
            </div>
        </div>
    </div>
</footer>
