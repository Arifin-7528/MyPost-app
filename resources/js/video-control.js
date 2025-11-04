document.querySelectorAll('video').forEach(v => {
            v.addEventListener('play', function() {
                document.querySelectorAll('video').forEach(other => {
                    if (other !== v) {
                        other.pause();
                    }
                });
            });
        });

        //     function toggleMute(video) {
        //         if (video.muted) {
        //             video.muted = false;
        //             video.nextElementSibling.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.617.816L4.414 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.414l3.969-3.816a1 1 0 011.616 0z" clip-rule="evenodd"></path></svg>';
        //         } else {
        //             video.muted = true;
        //             video.nextElementSibling.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.617.816L4.414 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.414l3.969-3.816a1 1 0 011.616 0zM12.293 7.293a1 1 0 011.414 0L15 8.586l1.293-1.293a1 1 0 111.414 1.414L16.414 10l1.293 1.293a1 1 0 01-1.414 1.414L15 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L13.586 10l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>';
        //         }
        //     }