<div class="columns">
    <div class="column">
        <div class="field is-required">
            <label class="label">Data License</label>
            <p>Choose how you would like to share data with the others.</p>
            <div class="control">
                @if (\App\License::isEnabled(\App\License::CC_BY_SA))
                    <b-tooltip
                        label="You agree to share all of the data about species occurrences (except
                        for the endangered species that could be listed out by taxonomic
                        experts)."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="data_license" value="{{ \App\License::CC_BY_SA }}"{{ $dataLicense == \App\License::CC_BY_SA ? ' checked' : '' }}>
                            Open Data (<a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Creative Commons Attribution-ShareAlike</a> licence)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::OPEN))
                    <b-tooltip
                        label="You agree to share all of the data about species occurrences (except
                        for the endangered species that could be listed out by taxonomic
                        experts)."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="data_license" value="{{ \App\License::OPEN }}"{{ $dataLicense == \App\License::OPEN ? ' checked' : '' }}>
                            Open Data (<a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Creative Commons Attribution-ShareAlike</a> licence)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::CC_BY_NC_SA))
                    <b-tooltip
                        label="As above, but excludes commercial use of the data without your
                        agreement. Your data will still be freely used for conservation or
                        scientific purposes."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="data_license" value="{{ \App\License::CC_BY_NC_SA }}"{{ $dataLicense == \App\License::CC_BY_NC_SA ? ' checked' : '' }}>
                            Open Data (<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank">Creative Commons Attribution-NonCommercial-ShareAlike</a> licence)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::PARTIALLY_OPEN))
                    <b-tooltip
                        label="Similar to Open Data, except the data is rescaled to 10x10 km2
                        resolution. Full resolution data is available to you, administrators of
                        the web site and the taxonomic experts."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="data_license" value="{{ \App\License::PARTIALLY_OPEN }}"{{ $dataLicense == \App\License::PARTIALLY_OPEN ? ' checked' : '' }}>
                            Partially open data (in resolution of 10x10 km2). <a href="{{ route('licenses.partially-open-license') }}" target="_blank">Read more</a>
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::CLOSED_FOR_A_PERIOD))
                    <b-tooltip
                        label="We highly discourage you to use this option. Only you, administrators
                        of the web site and the taxonomic experts are allowed to view and use
                        the data."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="data_license" value="{{ \App\License::CLOSED_FOR_A_PERIOD }}"{{ $dataLicense == \App\License::CLOSED_FOR_A_PERIOD ? ' checked' : '' }}>
                            ‎Closed Data (not shown on the maps). <a href="{{ route('licenses.closed-license') }}" target="_blank">Read more</a>
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::CLOSED))
                    <b-tooltip
                        label="We highly discourage you to use this option. Only you, administrators
                        of the web site and the taxonomic experts are allowed to view and use
                        the data."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="data_license" value="{{ \App\License::CLOSED }}"{{ $dataLicense == \App\License::CLOSED ? ' checked' : '' }}>
                            ‎Closed Data (not shown on the maps). <a href="{{ route('licenses.closed-license') }}" target="_blank">Read more</a>
                        </label>
                    </b-tooltip>
                @endif
            </div>
        </div>
    </div>

    <div class="column">
        <div class="field is-required">
            <label class="label">Image License</label>
            <p>Chose how you would like to share images with the others.</p>
            <div class="control">
                @if (\App\License::isEnabled(\App\License::CC_BY_SA))
                    <b-tooltip
                        label="You agree to share all of the images uploaded to the database. The
                        images could be used and redistributed by anyone as long as the
                        author’s contribution is recognized"
                        multilined>
                        <label class="radio">
                            <input type="radio" name="image_license" value="{{ \App\License::CC_BY_SA }}"{{ $imageLicense == \App\License::CC_BY_SA ? ' checked' : '' }}>
                            Share images (<a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Creative Commons Attribution-ShareAlike</a> licence)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::OPEN))
                    <b-tooltip
                        label="You agree to share all of the images uploaded to the database. The
                        images could be used and redistributed by anyone as long as the
                        author’s contribution is recognized"
                        multilined>
                        <label class="radio">
                            <input type="radio" name="image_license" value="{{ \App\License::OPEN }}"{{ $imageLicense == \App\License::OPEN ? ' checked' : '' }}>
                            Share images (<a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Creative Commons Attribution-ShareAlike</a> licence)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::CC_BY_NC_SA))
                    <b-tooltip
                        label="As above, but excludes commercial use of the data without your
                        agreement."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="image_license" value="{{ \App\License::CC_BY_NC_SA }}"{{ $imageLicense == \App\License::CC_BY_NC_SA ? ' checked' : '' }}>
                            Share images (<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank">Creative Commons Attribution-NonCommercial-ShareAlike</a> licence)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::PARTIALLY_OPEN))
                    <b-tooltip
                        label="You agree to share images within the database web site, but restrict
                        any other usages. Images will get clear watermark with copyright
                        information and the web site logo."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="image_license" value="{{ \App\License::PARTIALLY_OPEN }}"{{ $imageLicense == \App\License::PARTIALLY_OPEN ? ' checked' : '' }}>
                            Share images on the site (keep the authorship of the images)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::CLOSED_FOR_A_PERIOD))
                    <b-tooltip
                        label="This action is highly discouraged. Only administrators and the
                        taxonomic experts will be able to view images and species records could
                        not be easily verified and reviewed in the public domain."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="image_license" value="{{ \App\License::CLOSED_FOR_A_PERIOD }}"{{ $imageLicense == \App\License::CLOSED_FOR_A_PERIOD ? ' checked' : '' }}>
                            ‎Restrict images (images are not shown in the public domain)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::CLOSED))
                    <b-tooltip
                        label="This action is highly discouraged. Only administrators and the
                        taxonomic experts will be able to view images and species records could
                        not be easily verified and reviewed in the public domain."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="image_license" value="{{ \App\License::CLOSED }}"{{ $imageLicense == \App\License::CLOSED ? ' checked' : '' }}>
                            ‎Restrict images (images are not shown in the public domain)
                        </label>
                    </b-tooltip>
                @endif
            </div>
        </div>
    </div>
</div>
