<script>
    window.App = <?php echo json_encode([
        'gmaps' => [
            'apiKey' => config('biologer.gmaps.api_key'),
            'center' => config('biologer.gmaps.center'),
            'load' => false,
        ]
    ]); ?>
</script>
