<div class="columns">
    <div class="column">
        <div class="field is-required">
            <label class="label">Korištenje podataka</label>
            <p>Izaberite jedan od načina na koji želite da dijelite svoje podatke.</p>
            <div class="control">
                <b-tooltip
                    label="Izborom ove opcije slažete se da dijelite sve podatke o nalazima
                        vrsta (osim u slučaju ugroženih vrsta, za čiji prikaz taksonomski
                        stručnjaci odluče da ograniče prikaz)"
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="{{ \App\License::CC_BY_SA }}"{{ $dataLicense == \App\License::CC_BY_SA ? ' checked' : '' }}>
                        Javno dostupni podaci (<a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Creative Commons autorskopravna licenca, autorstvo – dijeliti pod istim uvjetima</a>)
                    </label>
                </b-tooltip>

                <b-tooltip
                    label="Slično gore navedenoj licenci, ali onemogućava komercijalnu
                        upotrebu podataka bez vaše suglasnosti. Vaši će podaci biti
                        dostupni za potrebe zaštite bioraznolikosti i izradu znanstvenih studija."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="{{ \App\License::CC_BY_NC_SA }}"{{ $dataLicense == \App\License::CC_BY_NC_SA ? ' checked' : '' }}>
                        Javno dostupni podaci (<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank">Creative Commons autorskopravna licenca, autorstvo-nekomercijalno - dijeliti pod istim uvjetima</a>)
                    </label>
                </b-tooltip>

                <b-tooltip
                    label="Slično kao gore navedene licence, samo što se podaci javno prikazuju
                        u vidu kvadrata veličine 10×10 km. Detaljniji podaci će biti vidljivi
                        Vama, Administratorima baze i taksonomskim stručnjacima."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="{{ \App\License::PARTIALLY_OPEN }}"{{ $dataLicense == \App\License::PARTIALLY_OPEN ? ' checked' : '' }}>
                        Dijelomično dostupni podaci (na nivou kvadrata veličine 10×10 km). <a href="{{ route('licenses.partially-open-data-license') }}" target="_blank">Detaljnije</a>
                    </label>
                </b-tooltip>

                <b-tooltip
                    label="Ne preporučujemo izbor ove opcije. Samo Vi, Administratori
                        i taksonomski stručnjaci će moći vidjeti podatke."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="{{ \App\License::TEMPORARILY_CLOSED }}"{{ $dataLicense == \App\License::TEMPORARILY_CLOSED ? ' checked' : '' }}>
                        Privremeno skriveni podaci (podaci se ne prikazuju na kartama tokom prve {{ config('biologer.license_closed_period') }} godine). <a href="{{ route('licenses.temporarily-closed-data-license') }}" target="_blank">Detaljnije</a>
                    </label>
                </b-tooltip>

                <b-tooltip
                    label="Ne preporučujemo izbor ove opcije. Samo Vi, Administratori
                        i taksonomski stručnjaci će moći vidjeti podatke."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="{{ \App\License::CLOSED }}"{{ $dataLicense == \App\License::CLOSED ? ' checked' : '' }}>
                        Skriveni podaci (podaci se ne prikazuju na kartama). <a href="{{ route('licenses.closed-data-license') }}" target="_blank">Detaljnije</a>
                    </label>
                </b-tooltip>
            </div>
        </div>
    </div>

    <div class="column">
        <div class="field is-required">
            <label class="label">Korištenje fotografija</label>
            <p>Izaberite jedan od načina na koji želite da dijelite svoje fotografije.</p>
            <div class="control">
                <b-tooltip
                    label="Izborom ove mogućnosti slažete se da dijelite sve fotografije koje
                        pošaljete u bazu. Tako poslane slike mogu se koristiti i dijeliti
                        uz navođenje imena autora fotografije."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="image_license" value="{{ \App\ImageLicense::CC_BY_SA }}"{{ $imageLicense == \App\ImageLicense::CC_BY_SA ? ' checked' : '' }}>
                        Javne fotografije (<a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Creative Commons autorskopravna licenca, autorstvo - dijeliti pod istim uvjetima</a> licence)
                    </label>
                </b-tooltip>

                <b-tooltip
                    label="Slično gore navedenoj licenci, ali onemogućava komercijalnu
                        upotrebu fotografija bez Vaše suglasnosti."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="image_license" value="{{ \App\ImageLicense::CC_BY_NC_SA }}"{{ $imageLicense == \App\ImageLicense::CC_BY_NC_SA ? ' checked' : '' }}>
                        Javne fotografije (<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank">Creative Commons autorskopravna licenca, autorstvo - nekomercijalno - dijeliti pod istim uvjetima</a> licence)
                    </label>
                </b-tooltip>

                <b-tooltip
                    label="Izborom ove mogućnosti slažete se da Vaše fotografije budu prikazane na web
                        stranici, ali ne dozvoljavate njihovo daljnje korištenje bez Vaše suglasnosti.
                        Slike će dobiti vodeni žig s podacima o licenci i logom web stranice."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="image_license" value="{{ \App\ImageLicense::PARTIALLY_OPEN }}"{{ $imageLicense == \App\ImageLicense::PARTIALLY_OPEN ? ' checked' : '' }}>
                        Fotografije su dostupne samo na ovoj stranici (zadržavajući autorstvo). <a href="{{ route('licenses.partially-open-image-license') }}" target="_blank">Detaljnije</a>
                    </label>
                </b-tooltip>

                <b-tooltip
                    label="Ne preporučujemo izbor ove opcije. Samo Vi, Administratori i taksonomski
                        stručnjaci će moći vidjeti fotografije, zbog čega ostali korisnici web stranice
                        ne mogu proveriti točnost vaših nalaza."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="image_license" value="{{ \App\ImageLicense::CLOSED }}"{{ $imageLicense == \App\ImageLicense::CLOSED ? ' checked' : '' }}>
                        ‎Skrivene fotografije (slike se uopće ne prikazuju u javnom dijelu). <a href="{{ route('licenses.closed-image-license') }}" target="_blank">Detaljnije</a>
                    </label>
                </b-tooltip>
            </div>
        </div>
    </div>
</div>
