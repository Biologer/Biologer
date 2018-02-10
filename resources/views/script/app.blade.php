<script>
    window.App = <?php echo json_encode([
        'User' => auth()->user(),
        'gmaps' => [
            'apiKey' => config('services.gmaps.key'),
            'center' => territory()->get('center'),
            'load' => false,
        ]
    ]); ?>
</script>
