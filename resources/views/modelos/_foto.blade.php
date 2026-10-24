<div class="mb-3">
    <label class="form-label">Imagen de referencia del modelo (opcional):</label>
    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
        <input type="file" id="modeloImagenArchivo" name="imagen" class="form-control" accept="image/*">
        <button type="button" id="modeloBtnTomarFoto" class="btn btn-outline-primary">Tomar foto</button>
        <button type="button" id="modeloBtnCancelarFoto" class="btn btn-outline-secondary d-none">Cancelar</button>
    </div>
    <div id="modeloSelectorCamaraWrap" class="mb-2 d-none">
        <label class="form-label small mb-1">Selecciona cámara:</label>
        <select id="modeloSelectorCamara" class="form-select" style="max-width: 320px;"></select>
    </div>
    <video id="modeloVideoCamara" class="d-none border rounded" autoplay playsinline muted style="max-width: 320px; width: 100%;"></video>
    <canvas id="modeloCanvasFoto" class="d-none"></canvas>
    <div id="modeloPreviewFoto" class="d-none mt-2">
        <img id="modeloFotoPreview" src="" alt="Vista previa" class="img-thumbnail" style="max-width: 220px;">
    </div>
</div>

<script>
(() => {
    const inputImagen = document.getElementById('modeloImagenArchivo');
    const video = document.getElementById('modeloVideoCamara');
    const canvas = document.getElementById('modeloCanvasFoto');
    const preview = document.getElementById('modeloPreviewFoto');
    const previewImg = document.getElementById('modeloFotoPreview');
    const btnTomarFoto = document.getElementById('modeloBtnTomarFoto');
    const btnCancelarFoto = document.getElementById('modeloBtnCancelarFoto');
    const selectorCamara = document.getElementById('modeloSelectorCamara');
    const selectorCamaraWrap = document.getElementById('modeloSelectorCamaraWrap');

    if (!inputImagen || !video || !canvas || !preview || !previewImg || !btnTomarFoto || !btnCancelarFoto || !selectorCamara || !selectorCamaraWrap) {
        return;
    }

    let streamCamera = null;
    let cameraDevices = [];

    function mostrarPreview(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }

    async function detectarCamaras() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) return [];

        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            return devices.filter(device => device.kind === 'videoinput');
        } catch (error) {
            return [];
        }
    }

    function poblarSelectorCamaras() {
        selectorCamara.innerHTML = '';

        if (cameraDevices.length > 1) {
            selectorCamaraWrap.classList.remove('d-none');
            cameraDevices.forEach((device, index) => {
                const option = document.createElement('option');
                option.value = device.deviceId;
                option.textContent = device.label || `Cámara ${index + 1}`;
                selectorCamara.appendChild(option);
            });
            if (!selectorCamara.value) {
                selectorCamara.value = cameraDevices[0].deviceId;
            }
        } else {
            selectorCamaraWrap.classList.add('d-none');
        }
    }

    function cerrarCamara() {
        if (streamCamera) {
            streamCamera.getTracks().forEach(track => track.stop());
            streamCamera = null;
        }

        video.srcObject = null;
        video.classList.add('d-none');
        btnCancelarFoto.classList.add('d-none');
        btnTomarFoto.textContent = 'Tomar foto';
    }

    function prepararArchivoJPEG(file) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.onload = () => {
                    const maxSide = 1600;
                    let { width, height } = img;
                    if (width > maxSide || height > maxSide) {
                        const ratio = Math.min(maxSide / width, maxSide / height);
                        width = Math.round(width * ratio);
                        height = Math.round(height * ratio);
                    }

                    const targetCanvas = document.createElement('canvas');
                    targetCanvas.width = width;
                    targetCanvas.height = height;
                    const ctx = targetCanvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    targetCanvas.toBlob((blob) => {
                        if (!blob) {
                            resolve(file);
                            return;
                        }

                        const compressed = new File([blob], `modelo-${Date.now()}.jpg`, { type: 'image/jpeg' });
                        resolve(compressed);
                    }, 'image/jpeg', 0.85);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    async function abrirCamara(deviceId = null) {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            inputImagen.setAttribute('capture', 'environment');
            inputImagen.click();
            return;
        }

        try {
            const permisoInicial = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            permisoInicial.getTracks().forEach(track => track.stop());
        } catch (error) {
            // El navegador bloqueó el acceso; se cae al input de archivo como respaldo.
        }

        cameraDevices = await detectarCamaras();
        poblarSelectorCamaras();

        const selectedDeviceId = deviceId || selectorCamara.value || cameraDevices[0]?.deviceId;
        const constraints = selectedDeviceId
            ? {
                video: { deviceId: { exact: selectedDeviceId } },
                audio: false,
            }
            : {
                video: { facingMode: 'environment' },
                audio: false,
            };

        try {
            streamCamera = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = streamCamera;
            video.classList.remove('d-none');
            btnCancelarFoto.classList.remove('d-none');
            btnTomarFoto.textContent = 'Capturar foto';
        } catch (error) {
            try {
                streamCamera = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user' },
                    audio: false,
                });
                video.srcObject = streamCamera;
                video.classList.remove('d-none');
                btnCancelarFoto.classList.remove('d-none');
                btnTomarFoto.textContent = 'Capturar foto';
            } catch (fallbackError) {
                inputImagen.setAttribute('capture', 'environment');
                inputImagen.click();
            }
        }
    }

    selectorCamara.addEventListener('change', async () => {
        if (streamCamera) {
            cerrarCamara();
        }
        await abrirCamara(selectorCamara.value);
    });

    btnTomarFoto.addEventListener('click', async () => {
        if (!streamCamera) {
            await abrirCamara();
            return;
        }

        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth || 1280;
        canvas.height = video.videoHeight || 720;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        canvas.toBlob(async (blob) => {
            if (!blob) return;

            const file = new File([blob], `modelo-${Date.now()}.jpg`, { type: 'image/jpeg' });
            const compressedFile = await prepararArchivoJPEG(file);
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(compressedFile);
            inputImagen.files = dataTransfer.files;
            mostrarPreview(compressedFile);
            cerrarCamara();
        }, 'image/jpeg', 0.85);
    });

    btnCancelarFoto.addEventListener('click', () => {
        inputImagen.value = '';
        preview.classList.add('d-none');
        previewImg.src = '';
        cerrarCamara();
    });

    inputImagen.addEventListener('change', () => {
        if (inputImagen.files && inputImagen.files[0]) {
            mostrarPreview(inputImagen.files[0]);
        }
    });
})();
</script>
