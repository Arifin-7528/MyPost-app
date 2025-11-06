document.addEventListener("DOMContentLoaded", function () {
    const skeleton = document.getElementById("loading-skeleton");
    const content = document.getElementById("posts-content");

    // jika tidak ada post sama sekali 
    if (typeof postsData !== 'undefined' && postsData.length === 0) {
        skeleton.style.display = "none";
        content.style.display = "block";
        return;
    }

    // tampilkan skeleton loading
    skeleton.style.display = "grid";
    content.style.display = "none";

    // maksimum waktu loading
    const maxLoadingTime = 5000; 
    let loadingTimeout = setTimeout(function () {
        skeleton.style.display = "none";
        content.style.display = "block";
    }, maxLoadingTime);

    // cek semua media
    function checkMediaLoaded() {
        const mediaElements = content.querySelectorAll("img, video");
        let loadedCount = 0;
        const totalMedia = mediaElements.length;

        if (totalMedia === 0) {
            // tidak ada media, langsung tampilkan konten
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

        // jika semua media sudah dimuat
        if (loadedCount === totalMedia) {
            clearTimeout(loadingTimeout);
            skeleton.style.display = "none";
            content.style.display = "block";
        }
    }

    // cek media setelah DOM siap
    setTimeout(checkMediaLoaded, 100);
});

// autoplay semua video setelah halaman dimuat
setTimeout(function () {
    document.querySelectorAll("video").forEach((v) => {
        v.play().catch((err) => console.log("Autoplay gagal:", err));
    });
}, 100);

// menambahkan event hover untuk video  
document.querySelectorAll("video").forEach((video) => {
    video.addEventListener("mouseenter", function () {
        this.muted = false; 
        this.play().catch((err) => console.log("Hover play gagal:", err));
    });
    video.addEventListener("mouseleave", function () {
        this.pause();
        this.muted = true;  
    });
});
