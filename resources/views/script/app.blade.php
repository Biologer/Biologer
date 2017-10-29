<script>
    window.App = <?php echo json_encode([
        'gmaps' => [
            'apiKey' => config('alciphron.gmaps.api_key'),
            'center' => config('alciphron.gmaps.center'),
            'load' => false,
        ]
    ]); ?>
</script>
