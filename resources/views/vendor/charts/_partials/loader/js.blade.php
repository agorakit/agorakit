<script>
    $(function() {
        $('.charts').each(function() {
            var chart = $(this).find('.charts-chart');
            var loader = $(this).find('.charts-loader');
            var time = loader.data('duration');

            if(loader.hasClass('enabled')) {
                chart.css({visibility: 'hidden'});
                loader.fadeIn(350);

                setTimeout(function() {
                    loader.fadeOut(350, function() {
                        chart.css({opacity: 0, visibility: 'visible'}).animate({opacity: 1}, 350);
                    });
                }, time)
            }
        });
    })
</script>
