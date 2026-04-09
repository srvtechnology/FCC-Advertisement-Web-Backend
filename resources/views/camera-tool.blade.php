<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Billboard ROI Sender</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 1rem;
            box-sizing: border-box;
        }

        .canvas-container {
            width: 100%;
            max-width: 100%;
            overflow: auto;
            border: 1px solid #ccc;
            margin-bottom: 1rem;
            background: #f9f9f9;
        }

        canvas {
            display: block;
            max-width: 100%;
            height: auto;
        }

        input[type="file"],
        button {
            display: block;
            width: 100%;
            max-width: 300px;
            margin-bottom: 1rem;
            padding: 0.5rem;
            font-size: 1rem;
        }

        .button-row {
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex-wrap: wrap;
        }

        video {
            max-width: 100%;
            margin-bottom: 1rem;
        }

        @media (min-width: 600px) {
            .button-row {
                flex-direction: row;
            }

            button {
                max-width: none;
                width: auto;
            }
        }
    </style>
</head>

<body x-data="billboardApp()" x-init="init()">
    <h2>Upload or Capture Image</h2>

    <!-- File Upload -->
    <input type="file" accept="image/*" @change="loadImage($event)" />

    <!-- Camera Capture -->
    <button @click="startCamera()">Use Camera</button>
    <video x-ref="video" autoplay playsinline style="display:none;" @dblclick="switchCamera()"></video>

    <button x-show="isCameraActive" @click="captureImage()">Capture Image</button>

    <!-- Canvas -->
    <div class="canvas-container">
        <canvas x-ref="canvas" @mousedown="handleMouseDown($event)"></canvas>
    </div>

    <!-- Buttons -->
    <div class="button-row">
        <button @click="sendToAPI()">Send to API</button>
        <button @click="resetPoints()">Reset Points</button>
    </div>

    <script>
        function billboardApp() {
            return {
                image: null,
                canvas: null,
                ctx: null,
                points: [],
                draggingIndex: null,
                imageFile: null,
                imageScale: 1,
                isCameraActive: false,
                cameraFacing: 'environment', // or 'user'


                init() {
                    this.canvas = this.$refs.canvas;
                    this.ctx = this.canvas.getContext('2d');

                    this.canvas.addEventListener('mousemove', (e) => this.onDrag(e));
                    document.addEventListener('mouseup', () => this.draggingIndex = null);
                },

                loadImage(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const img = new Image();
                    img.onload = () => {
                        const maxWidth = window.innerWidth - 40;
                        const scale = Math.min(1, maxWidth / img.width);

                        this.canvas.width = img.width * scale;
                        this.canvas.height = img.height * scale;

                        this.ctx.drawImage(img, 0, 0, this.canvas.width, this.canvas.height);
                        this.image = img;
                        this.imageScale = scale;
                        this.points = [];
                        this.drawOverlay();
                    };
                    img.src = URL.createObjectURL(file);
                    this.imageFile = file;
                },

                startCamera() {
                    this.isCameraActive = true;
                    const video = this.$refs.video;
                    video.style.display = 'block';

                    const constraints = {
                        video: {
                            facingMode: this.cameraFacing
                        }
                    };

                    navigator.mediaDevices.getUserMedia(constraints)
                        .then(stream => {
                            video.srcObject = stream;
                        })
                        .catch(err => {
                            alert("Camera access failed: " + err);
                            this.isCameraActive = false;
                        });
                },
                switchCamera() {
                    // Toggle between 'user' and 'environment'
                    this.cameraFacing = this.cameraFacing === 'user' ? 'environment' : 'user';

                    // Stop current stream
                    const video = this.$refs.video;
                    if (video.srcObject) {
                        video.srcObject.getTracks().forEach(track => track.stop());
                    }

                    // Restart camera with new facing mode
                    this.startCamera();
                },



                captureImage() {
                    const video = this.$refs.video;
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0);

                    const img = new Image();
                    img.onload = () => {
                        const maxWidth = window.innerWidth - 40;
                        const scale = Math.min(1, maxWidth / img.width);

                        this.canvas.width = img.width * scale;
                        this.canvas.height = img.height * scale;

                        this.ctx.drawImage(img, 0, 0, this.canvas.width, this.canvas.height);
                        this.image = img;
                        this.imageScale = scale;
                        this.points = [];
                        this.drawOverlay();

                        // Save image blob to send later
                        canvas.toBlob(blob => {
                            this.imageFile = new File([blob], "captured_image.png", {
                                type: "image/png"
                            });
                        }, 'image/png');

                        // Stop camera
                        if (video.srcObject) {
                            const tracks = video.srcObject.getTracks();
                            tracks.forEach(track => track.stop());
                            video.srcObject = null;
                        }

                        this.isCameraActive = false;
                        video.style.display = 'none';
                    };

                    img.src = canvas.toDataURL();
                },

                getScaledPoint(x, y) {
                    return {
                        x: x / this.imageScale,
                        y: y / this.imageScale
                    };
                },

                handleMouseDown(e) {
                    const rect = this.canvas.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;

                    for (let i = 0; i < this.points.length; i++) {
                        const pt = this.points[i];
                        if (Math.hypot(pt.x - x, pt.y - y) < 10) {
                            this.draggingIndex = i;
                            return;
                        }
                    }

                    if (this.points.length < 4) {
                        this.points.push({
                            x,
                            y
                        });
                        this.drawOverlay();
                    }
                },

                onDrag(e) {
                    if (this.draggingIndex !== null) {
                        const rect = this.canvas.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;
                        this.points[this.draggingIndex] = {
                            x,
                            y
                        };
                        this.drawOverlay();
                    }
                },

                drawOverlay() {
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                    if (this.image) {
                        this.ctx.drawImage(this.image, 0, 0, this.canvas.width, this.canvas.height);
                    }

                    if (this.points.length > 0) {
                        this.ctx.strokeStyle = 'black';
                        this.ctx.fillStyle = 'red';
                        this.ctx.lineWidth = 2;
                        this.ctx.beginPath();
                        this.ctx.moveTo(this.points[0].x, this.points[0].y);
                        for (let i = 1; i < this.points.length; i++) {
                            this.ctx.lineTo(this.points[i].x, this.points[i].y);
                        }
                        if (this.points.length === 4) {
                            this.ctx.closePath();
                        }
                        this.ctx.stroke();
                        this.points.forEach(pt => {
                            this.ctx.beginPath();
                            this.ctx.arc(pt.x, pt.y, 5, 0, Math.PI * 2);
                            this.ctx.fill();
                        });
                    }
                },

                resetPoints() {
                    this.points = [];
                    this.drawOverlay();
                },

                sendToAPI() {
                    if (this.points.length !== 4) {
                        alert('Please mark 4 points first');
                        return;
                    }

                    const xs = this.points.map(p => Math.round(p.x / this.imageScale));
                    const ys = this.points.map(p => Math.round(p.y / this.imageScale));

                    const roi = [
                        Math.min(...xs),
                        Math.min(...ys),
                        Math.max(...xs),
                        Math.max(...ys)
                    ].join(',');

                    const formData = new FormData();
                    formData.append('roi', roi);
                    formData.append('image', this.imageFile);
                    // console.log('Sending ROI:', roi);
                    // console.log('Sending Image:', this.imageFile);


                    fetch("/server/api/analyze-image", {
                            method: "POST",
                            body: formData,
                            headers: {
                                "Accept": "application/json",
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                                // no need for Content-Type; browser sets it automatically
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            const result = {
                                height: data.height,
                                width: data.width
                            };

                            console.log('HEIGHT_WIDTH:', JSON.stringify(data));

                            // Send to Laravel cache
                            fetch("/server/api/store-cache", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "Accept": "application/json",
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        data: data
                                    })
                                })
                                .then(res => res.json())
                                .then(resp => console.log('Cache Store Response:', resp))
                                .catch(err => console.error('Cache Store Error:', err));
                        })


                        .catch(error => console.error('API Error:', error));
                }

            };
        }
    </script>
</body>

</html>
