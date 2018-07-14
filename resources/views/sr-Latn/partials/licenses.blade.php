<div class="columns">
    <div class="column">
        <div class="field is-required">
            <label class="label">Korišćenje podataka</label>
            <p>Izaberite jedan od načina na koji želite da delite svoje podatke.</p>
            <div class="control">
                <b-tooltip
                    label="Izborom ove opcije slažete se da delite sve podatke o nalazima
                        vrsta (osim u slučaju ugroženih vrsta za čiji prikaz taksonomski
                        eksperti odluče da ograniče prikaz)"
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="{{ \App\License::CC_BY_SA }}"{{ $preferences->data_license == \App\License::CC_BY_SA ? ' checked' : '' }}>
                        Javno dostupni podaci (<a href="https://creativecommons.org/licenses/by-sa/4.0/">Krijejtiv komons licenca, autorstvo-deliti pod istim uslovima</a>)
                    </label>
                </b-tooltip>
                <b-tooltip
                    label="Slično gore navedenoj licenci, ali onemogućava komercijalnu
                        upotrebu podataka bez vaše saglasnosti. Vaši podaci će biti
                        dostupni za potrebe zaštite biodiverziteta i izradu naučnih studija."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="{{ \App\License::CC_BY_NC_SA }}"{{ $preferences->data_license == \App\License::CC_BY_NC_SA ? ' checked' : '' }}>
                        Javno dostupni podaci (<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/">Krijejtiv komons licenca, autorstvo-nekomercijalno-deliti pod istim uslovima</a>)
                    </label>
                </b-tooltip>
                <b-tooltip
                    label="Slično kao gore navedene licence, samo što se podaci javno prikazuju
                        u vidu kvadrata veličine 10×10 km. Detaljniji podaci će biti dostupni
                        vama, administratorima veb stranice i taksonomskim ekspertima."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="{{ \App\License::PARTIALLY_OPEN }}"{{ $preferences->data_license == \App\License::PARTIALLY_OPEN ? ' checked' : '' }}>
                        Delimično dostupni podaci (na nivou kvadrata veličine 10x10 km).<a href="{{ route('pages.partially-open-license') }}" target="_blank">Detaljnije</a>
                    </label>
                </b-tooltip>
                <b-tooltip
                    label="Ne preporučujemo izbor ove opcije. Samo vi, administratori
                        i taksonomski eksperti će moći da vide podatke."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="{{ \App\License::CLOSED }}"{{ $preferences->data_license == \App\License::CLOSED ? ' checked' : '' }}>
                        Skriveni podaci (podaci se ne prikazuju na mapama). <a href="{{ route('pages.closed-license') }}" target="_blank">Detaljnije</a>
                    </label>
                </b-tooltip>
            </div>
        </div>
    </div>

    <div class="column">
        <div class="field is-required">
            <label class="label">Korišćenje fotografija</label>
            <p>Izaberite jedan od načina na koji želite da delite svoje fotografije.</p>
            <div class="control">
                <b-tooltip
                    label="Izborom ove opcije slažete se da delite sve fotografije koje
                        pošaljete u bazu. Tako poslate slike se mogu koristiti i deliti
                        uz navođenje imena autora fotografije."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="image_license" value="{{ \App\License::CC_BY_SA }}"{{ $preferences->image_license == \App\License::CC_BY_SA ? ' checked' : '' }}>
                        Javne fotografije (<a href="https://creativecommons.org/licenses/by-sa/4.0/">Krijejtiv komons licenca, autorstvo-deliti pod istim uslovima</a> licence)
                    </label>
                </b-tooltip>
                <b-tooltip
                    label="Slično gore navedenoj licenci, ali onemogućava komercijalnu
                        upotrebu fotografija bez vaše saglasnosti."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="image_license" value="{{ \App\License::CC_BY_NC_SA }}"{{ $preferences->image_license == \App\License::CC_BY_NC_SA ? ' checked' : '' }}>
                        Javne fotografije (<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/">Krijejtiv komons licenca, autorstvo-nekomercijalno-deliti pod istim uslovima</a> licence)
                    </label>
                </b-tooltip>
                <b-tooltip
                    label="Izborom ove opcije slažete se da vaše fotografije budu prikazane na veb
                        stranici, ali ne dozvoljavate njihovu dalju upotrebu bez vaše saglasnosti.
                        Slike će dobiti vodeni žig sa podacima o licenci i logom veb stranice."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="image_license" value="{{ \App\License::PARTIALLY_OPEN }}"{{ $preferences->image_license == \App\License::PARTIALLY_OPEN ? ' checked' : '' }}>
                        Fotografije dostupne samo na ovoj stranici (zadržavajući autorstvo)
                    </label>
                </b-tooltip>
                <b-tooltip
                    label="Ne preporučujemo izbor ove opcije. Samo vi, administratori i taksonomski
                        eksperti će moći da vide fotografije, zbog čega ostali korisnici veb stranice
                        ne mogu proveriti tačnost vaših nalaza."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="image_license" value="{{ \App\License::CLOSED }}"{{ $preferences->image_license == \App\License::CLOSED ? ' checked' : '' }}>
                        ‎Skrivene fotografije (slike se uopšte ne prikazuju u javnom delu)
                    </label>
                </b-tooltip>
            </div>
        </div>
    </div>
</div>
