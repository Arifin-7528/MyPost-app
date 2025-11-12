document.querySelectorAll('video').forEach(v => {
            v.addEventListener('play', function() {
                document.querySelectorAll('video').forEach(other => {
                    if (other !== v) {
                        other.pause();
                    }
                });
            });
        });
