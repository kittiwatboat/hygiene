{{-- <div id="loadingOverlay" hidden
    style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(255, 255, 255, 0.7);
    z-index: 9999;
    display: none;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
">
    <img src="{{ asset('logo.png') }}" style="width:200px; height:150px;" class="spinner-image" alt="loading">
    <div class="loading-text">
        กำลังโหลด<span id="dot-loader" class="dot-span">...</span>
    </div>
</div>
<style>
    @keyframes wobble {

        0%,
        100% {
            transform: rotate(0deg);
        }

        25% {
            transform: rotate(3deg);
        }

        75% {
            transform: rotate(-3deg);
        }
    }

    .spinner-image {
        animation: wobble 1.2s ease-in-out infinite;
    }

    .loading-text {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        display: flex;
        align-items: center;
    }

    .dot-span {
        display: inline-block;
        min-width: 1.5em;
        /* ให้เว้นที่ไว้สำหรับ 3 จุด */
        text-align: left;
    }

    @keyframes dotty {
        0% {
            content: "";
        }

        33% {
            content: ".";
        }

        66% {
            content: "..";
        }

        100% {
            content: "...";
        }
    }
</style>
<script>
    const dotEl = document.getElementById("dot-loader");
    let dotCount = 0;
    setInterval(() => {
        dotCount = (dotCount + 1) % 4; // 0, 1, 2, 3
        dotEl.textContent = ".".repeat(dotCount);
    }, 400);


    function loading1(){
        $('#loadingOverlay').fadeIn();
        $('#loadingOverlay').attr('hidden',false);
    }

    function stoploading1(){
        $('#loadingOverlay').attr('hidden',true);
    }
</script> --}}



<style>
    .loading-overlay {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(255, 255, 255, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    z-index: 999;
    animation: fadeIn 0.3s ease-in-out;
}

.loading-overlay.hidden {
    display: none !important;
}

.loading-overlay img {
    width: 50px;
    animation: wobble 1.2s ease-in-out infinite;
}

.loading-text {
    margin-top: 10px;
    font-size: 16px;
    color: #333;
}

@keyframes wobble {
  0%, 100% { transform: rotate(0deg); }
  25% { transform: rotate(3deg); }
  75% { transform: rotate(-3deg); }
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

</style>

<script>
    function showLoading1(selector) {
        const container = document.querySelector(selector);
        if (!container) return;
    
        if (!container.querySelector('.loading-overlay')) {
            const overlay = document.createElement('div');
            overlay.className = 'loading-overlay';
            overlay.innerHTML = `
                <img src="{{ asset('logo.png') }}" alt="loading">
                <div class="loading-text">กำลังโหลด<span class="dot-loader">.</span></div>
            `;
            container.style.position = 'relative';
            container.appendChild(overlay);
        } else {
            container.querySelector('.loading-overlay').classList.remove('hidden');
        }
    
        animateDots(selector);
    }
    
    function hideLoading1(selector) {
        const container = document.querySelector(selector);
        if (!container) return;
    
        const overlay = container.querySelector('.loading-overlay');
        if (overlay) overlay.classList.add('hidden');
    
        clearDotAnimation(selector);
    }
    
    function animateDots(selector) {
        const container = document.querySelector(selector);
        const dotSpan = container.querySelector('.dot-loader');
        if (!dotSpan) return;
    
        let dots = 1;
        const interval = setInterval(() => {
            dots = (dots % 3) + 1;
            dotSpan.textContent = '.'.repeat(dots);
        }, 400);
        container._dotInterval = interval;
    }
    
    function clearDotAnimation(selector) {
        const container = document.querySelector(selector);
        if (container?._dotInterval) {
            clearInterval(container._dotInterval);
            delete container._dotInterval;
        }
    }
    </script>
    