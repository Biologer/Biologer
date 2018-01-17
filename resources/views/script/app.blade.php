<script>
    window.App = <?php echo json_encode([
        'User' => auth()->user(),
        'gmaps' => [
            'apiKey' => config('biologer.gmaps.api_key'),
            'center' => config('biologer.gmaps.center'),
            'load' => false,
        ]
    ]); ?>
</script>
