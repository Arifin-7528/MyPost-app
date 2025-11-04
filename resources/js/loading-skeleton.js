document.addEventListener("DOMContentLoaded", function () {
    const skeleton = document.getElementById("loading-skeleton");
    const content = document.getElementById("posts-content");

    // If no posts, show content immediately without skeleton
    if (postsData.length === 0) {
        skeleton.style.display = "none";
        content.style.display = "block";
        return;
    }

    // Show skeleton initially
    skeleton.style.display = "grid";
    content.style.display = "none";

    // Set a maximum loading time (e.g., 5 seconds) to prevent infinite loading
    const maxLoadingTime = 5000; // 5 seconds
    let loadingTimeout = setTimeout(function () {
        skeleton.style.display = "none";
        content.style.display = "block";
    }, maxLoadingTime);

    // Function to check if images and videos are loaded
    function checkMediaLoaded() {
        const mediaElements = content.querySelectorAll("img, video");
        let loadedCount = 0;
        const totalMedia = mediaElements.length;

        if (totalMedia === 0) {
            // No media, show content immediately
            clearTimeout(loadingTimeout);
            skeleton.style.display = "none";
            content.style.display = "block";
            return;
        }

        mediaElements.forEach(function (media) {
            if (media.complete || media.readyState >= 3) {
                loadedCount++;
            } else {
                media.addEventListener("load", function () {
                    loadedCount++;
                    if (loadedCount === totalMedia) {
                        clearTimeout(loadingTimeout);
                        skeleton.style.display = "none";
                        content.style.display = "block";
                    }
                });
                media.addEventListener("error", function () {
                    loadedCount++;
                    if (loadedCount === totalMedia) {
                        clearTimeout(loadingTimeout);
                        skeleton.style.display = "none";
                        content.style.display = "block";
                    }
                });
            }
        });

        // If all media are already loaded
        if (loadedCount === totalMedia) {
            clearTimeout(loadingTimeout);
            skeleton.style.display = "none";
            content.style.display = "block";
        }
    }

    // Check media loading after a short delay to ensure DOM is ready
    setTimeout(checkMediaLoaded, 100);
});

// Auto-play videos when content is loaded
setTimeout(function () {
    document.querySelectorAll("video").forEach((v) => {
        v.play().catch((err) => console.log("Autoplay gagal:", err));
    });
}, 100);

// Add hover play/pause functionality for videos
document.querySelectorAll("video").forEach((video) => {
    video.addEventListener("mouseenter", function () {
        this.muted = false; // Unmute on hover
        this.play().catch((err) => console.log("Hover play gagal:", err));
    });
    video.addEventListener("mouseleave", function () {
        this.pause();
        this.muted = true; // Mute back on leave
    });
});
