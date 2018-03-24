<script>
    window.App = <?php echo json_encode([
        'locale' => app()->getLocale(),
        'supportedLocales' => LaravelLocalization::getSupportedLocales(),
        'User' => auth()->user(),
        'gmaps' => [
            'apiKey' => config('services.gmaps.key'),
            'center' => territory()->get('center'),
            'load' => false,
        ],
        'i18n' => \App\Support\Localization::strings(),
    ]); ?>
</script>
