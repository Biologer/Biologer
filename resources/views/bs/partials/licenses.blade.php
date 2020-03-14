<div class="columns">
    <div class="column">
        <div class="field is-required">
            <label class="label">Korištenje podataka</label>
            <p>Izaberite jedan od načina na koji želite da dijelite svoje podatke.</p>
            <div class="control">
                @if (\App\License::isEnabled(\App\License::CC_BY_SA))
                    <b-tooltip
                        label="Izborom ove opcije slažete se da dijelite sve podatke o nalazima
                            vrsta (osim u slučaju ugroženih vrsta za čiji prikaz taksonomski
                            eksperti odluče da ograniče prikaz)"
                        multilined>
                        <label class="radio">
                            <input type="radio" name="data_license" value="{{ \App\License::CC_BY_SA }}"{{ $dataLicense == \App\License::CC_BY_SA ? ' checked' : '' }}>
                            Javno dostupni podaci (<a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Krijejtiv komons licenca, autorstvo-dijeliti pod istim uslovima</a>)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::OPEN))
                    <b-tooltip
                        label="Izborom ove opcije slažete se da dijelite sve podatke o nalazima
                            vrsta (osim u slučaju ugroženih vrsta za čiji prikaz taksonomski
                            eksperti odluče da ograniče prikaz)"
                        multilined>
                        <label class="radio">
                            <input type="radio" name="data_license" value="{{ \App\License::OPEN }}"{{ $dataLicense == \App\License::OPEN ? ' checked' : '' }}>
                            Javno dostupni podaci (<a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Krijejtiv komons licenca, autorstvo-dijeliti pod istim uslovima</a>)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::CC_BY_NC_SA))
                    <b-tooltip
                        label="Slično gore navedenoj licenci, ali onemogućava komercijalnu
                            upotrebu podataka bez vaše saglasnosti. Vaši podaci će biti
                            dostupni za potrebe zaštite biodiverziteta i izradu naučnih studija."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="data_license" value="{{ \App\License::CC_BY_NC_SA }}"{{ $dataLicense == \App\License::CC_BY_NC_SA ? ' checked' : '' }}>
                            Javno dostupni podaci (<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank">Krijejtiv komons licenca, autorstvo-nekomercijalno-dijeliti pod istim uslovima</a>)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::PARTIALLY_OPEN))
                    <b-tooltip
                        label="Slično kao gore navedene licence, samo što se podaci javno prikazuju
                            u vidu kvadrata veličine 10×10 km. Detaljniji podaci će biti dostupni
                            vama, administratorima web stranice i taksonomskim ekspertima."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="data_license" value="{{ \App\License::PARTIALLY_OPEN }}"{{ $dataLicense == \App\License::PARTIALLY_OPEN ? ' checked' : '' }}>
                            Djelimično dostupni podaci (na nivou kvadrata veličine 10x10 km).<a href="{{ route('licenses.partially-open-license') }}" target="_blank">Detaljnije</a>
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::CLOSED_FOR_A_PERIOD))
                    <b-tooltip
                        label="Ne preporučujemo izbor ove opcije. Samo vi, administratori
                            i taksonomski eksperti će moći da vide podatke."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="data_license" value="{{ \App\License::CLOSED_FOR_A_PERIOD }}"{{ $dataLicense == \App\License::CLOSED_FOR_A_PERIOD ? ' checked' : '' }}>
                            Skriveni podaci (podaci se ne prikazuju na mapama). <a href="{{ route('licenses.closed-license') }}" target="_blank">Detaljnije</a>
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::CLOSED))
                    <b-tooltip
                        label="Ne preporučujemo izbor ove opcije. Samo vi, administratori
                            i taksonomski eksperti će moći da vide podatke."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="data_license" value="{{ \App\License::CLOSED }}"{{ $dataLicense == \App\License::CLOSED ? ' checked' : '' }}>
                            Skriveni podaci (podaci se ne prikazuju na mapama). <a href="{{ route('licenses.closed-license') }}" target="_blank">Detaljnije</a>
                        </label>
                    </b-tooltip>
                @endif
            </div>
        </div>
    </div>

    <div class="column">
        <div class="field is-required">
            <label class="label">Korištenje fotografija</label>
            <p>Izaberite jedan od načina na koji želite da dijelite svoje fotografije.</p>
            <div class="control">
                @if (\App\License::isEnabled(\App\License::CC_BY_SA))
                    <b-tooltip
                        label="Izborom ove opcije slažete se da dijelite sve fotografije koje
                            pošaljete u bazu. Tako poslate slike se mogu koristiti i dijeliti
                            uz navođenje imena autora fotografije."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="image_license" value="{{ \App\License::CC_BY_SA }}"{{ $imageLicense == \App\License::CC_BY_SA ? ' checked' : '' }}>
                            Javne fotografije (<a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Krijejtiv komons licenca, autorstvo-dijeliti pod istim uslovima</a> licence)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::OPEN))
                    <b-tooltip
                        label="Izborom ove opcije slažete se da dijelite sve fotografije koje
                            pošaljete u bazu. Tako poslate slike se mogu koristiti i dijeliti
                            uz navođenje imena autora fotografije."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="image_license" value="{{ \App\License::OPEN }}"{{ $imageLicense == \App\License::OPEN ? ' checked' : '' }}>
                            Javne fotografije (<a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Krijejtiv komons licenca, autorstvo-dijeliti pod istim uslovima</a> licence)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::CC_BY_NC_SA))
                    <b-tooltip
                        label="Slično gore navedenoj licenci, ali onemogućava komercijalnu
                            upotrebu fotografija bez vaše saglasnosti."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="image_license" value="{{ \App\License::CC_BY_NC_SA }}"{{ $imageLicense == \App\License::CC_BY_NC_SA ? ' checked' : '' }}>
                            Javne fotografije (<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank">Krijejtiv komons licenca, autorstvo-nekomercijalno-dijeliti pod istim uslovima</a> licence)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::PARTIALLY_OPEN))
                    <b-tooltip
                        label="Izborom ove opcije slažete se da vaše fotografije budu prikazane na web
                            stranici, ali ne dozvoljavate njihovu dalju upotrebu bez vaše saglasnosti.
                            Slike će dobiti vodeni žig sa podacima o licenci i logom web stranice."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="image_license" value="{{ \App\License::PARTIALLY_OPEN }}"{{ $imageLicense == \App\License::PARTIALLY_OPEN ? ' checked' : '' }}>
                            Fotografije dostupne samo na ovoj stranici (zadržavajući autorstvo)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::CLOSED_FOR_A_PERIOD))
                    <b-tooltip
                        label="Ne preporučujemo izbor ove opcije. Samo vi, administratori i taksonomski
                            eksperti će moći da vide fotografije, zbog čega ostali korisnici web stranice
                            ne mogu provjeriti tačnost vaših nalaza."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="image_license" value="{{ \App\License::CLOSED_FOR_A_PERIOD }}"{{ $imageLicense == \App\License::CLOSED_FOR_A_PERIOD ? ' checked' : '' }}>
                            ‎Skrivene fotografije (slike se uopšte ne prikazuju u javnom dijelu)
                        </label>
                    </b-tooltip>
                @endif

                @if (\App\License::isEnabled(\App\License::CLOSED))
                    <b-tooltip
                        label="Ne preporučujemo izbor ove opcije. Samo vi, administratori i taksonomski
                            eksperti će moći da vide fotografije, zbog čega ostali korisnici web stranice
                            ne mogu provjeriti tačnost vaših nalaza."
                        multilined>
                        <label class="radio">
                            <input type="radio" name="image_license" value="{{ \App\License::CLOSED }}"{{ $imageLicense == \App\License::CLOSED ? ' checked' : '' }}>
                            ‎Skrivene fotografije (slike se uopšte ne prikazuju u javnom dijelu)
                        </label>
                    </b-tooltip>
                @endif
            </div>
        </div>
    </div>
</div>
