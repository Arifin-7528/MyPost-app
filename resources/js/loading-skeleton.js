document.addEventListener('DOMContentLoaded', function () {
    const skeleton = document.getElementById('loading-skeleton');
    const content = document.getElementById('posts-content');

    // safety: fallback postsData
    const pd = window.postsData || [];
    // jika tidak ada post sama sekali -> langsung tampilkan konten
    if (pd.length === 0) {
        if (skeleton) skeleton.style.display = 'none';
        if (content) content.style.visibility = 'visible';
        // pastikan hover video tetap terpasang kalau ada (edge-case)
        addVideoHoverEvents();
        return;
    }

    // tampilkan skeleton
    if (skeleton) skeleton.style.display = 'grid';
    if (content) content.style.visibility = 'hidden';

    const maxLoadingTime = 5000;
    let shown = false;
    let loadingTimeout = setTimeout(showContent, maxLoadingTime);

    function showContent() {
        if (shown) return;
        shown = true;
        clearTimeout(loadingTimeout);
        if (skeleton) skeleton.remove(); // benar-benar hapus skeleton
        if (content) {
            content.style.removeProperty('visibility');
            content.style.display = 'block';
        }
        addVideoHoverEvents();
    }

    function onMediaLoadedCheck(totalMedia, loadedCount) {
        if (loadedCount >= totalMedia) showContent();
    }

    function checkMediaLoaded() {
        if (!content) return showContent();
        const mediaElements = content.querySelectorAll('img, video');
        const totalMedia = mediaElements.length;

        if (totalMedia === 0) return showContent();

        let loadedCount = 0;

        function tryCount() {
            loadedCount++;
            onMediaLoadedCheck(totalMedia, loadedCount);
        }

        mediaElements.forEach(media => {
            // For images
            if (media.tagName === 'IMG') {
                if (media.complete) {
                    tryCount();
                } else {
                    media.addEventListener('load', tryCount, { once: true });
                    media.addEventListener('error', tryCount, { once: true });
                }
                return;
            }

            // For videos
            if (media.tagName === 'VIDEO') {
                // ensure video is paused & muted initially (in case Blade masih punya autoplay)
                try {
                    media.pause();
                } catch (e) {}
                media.muted = true;

                // If readyState already has enough data, count it
                if (typeof media.readyState === 'number' && media.readyState >= 3) {
                    tryCount();
                } else {
                    // prefer canplaythrough but fallback to loadeddata
                    const onCanPlayThrough = () => {
                        tryCount();
                    };
                    const onError = () => tryCount();

                    media.addEventListener('canplaythrough', onCanPlayThrough, { once: true });
                    media.addEventListener('loadeddata', onCanPlayThrough, { once: true });
                    media.addEventListener('error', onError, { once: true });

                    // Safety: if video doesn't fire events (weird cases), treat as loaded after a short delay
                    setTimeout(() => {
                        // if still not counted, count it to avoid infinite wait
                        if (loadedCount < totalMedia && !media.__counted) {
                            media.__counted = true;
                            tryCount();
                        }
                    }, 3000);
                }
            }
        });
    }

    // run check slightly later to let server-rendered DOM settle
    setTimeout(checkMediaLoaded, 150);

    // observe content for dynamically added posts (in case of live append)
    if (content) {
        const observer = new MutationObserver((mutations) => {
            for (const m of mutations) {
                if (m.type === 'childList' && m.addedNodes.length > 0) {
                    // new media added -> re-check loading (and attach hover events)
                    setTimeout(checkMediaLoaded, 100);
                    addVideoHoverEvents();
                    break;
                }
            }
        });
        observer.observe(content, { childList: true, subtree: true });
    }
});

// fungsi untuk menambahkan event hover pada video (play on hover, pause on leave)
function addVideoHoverEvents() {
    const videos = document.querySelectorAll('#posts-content video');
    videos.forEach(video => {
        if (video.hasAttribute('data-hover-added')) return;
        video.setAttribute('data-hover-added', 'true');

        // Ensure initial state: paused and muted
        try { video.pause(); } catch (e) {}
        video.muted = true;

        video.addEventListener('mouseenter', function () {
            // Play with sound when hovered
            // some browsers still require user gesture; this is hover - usually allowed
            this.muted = false;
            const playPromise = this.play();
            if (playPromise !== undefined) {
                playPromise.catch(err => {
                    // if play failed (autoplay policy), keep it muted and try again without sound
                    console.log('Hover play failed, retry muted:', err);
                    this.muted = true;
                    this.play().catch(e => console.log('Second play failed:', e));
                });
            }
        });

        video.addEventListener('mouseleave', function () {
            this.pause();
            this.muted = true;
            // optionally reset time to 0 for consistent behavior:
            // this.currentTime = 0;
        });

        // keyboard accessibility: play on focus, pause on blur
        video.addEventListener('focus', function () {
            this.muted = false;
            this.play().catch(() => {});
        });
        video.addEventListener('blur', function () {
            this.pause();
            this.muted = true;
        });
    });
}