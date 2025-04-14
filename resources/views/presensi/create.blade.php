@extends('layouts.presensi')
@section('header')
<style>
        .appHeader {
        background: linear-gradient(to right, #28a745, #007bff) !important;
    }
    </style>
    <!-- App Header -->
    <div class="appHeader text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 200px;
        }

        .jam-digital-malasngoding {
            background-color: #27272783;
            position: absolute;
            top: 65px;
            right: 10px;
            z-index: 9999;
            width: 150px;
            border-radius: 10px;
            padding: 5px;
        }

        .jam-digital-malasngoding p {
            color: #fff;
            font-size: 16px;
            text-align: left;
            margin-top: 0;
            margin-bottom: 0;
        }
        .webcam-capture {
            position: relative;
        }
        .face-status {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 5px 10px;
            border-radius: 5px;
            z-index: 100;
            font-weight: bold;
            background-color: rgba(255, 193, 7, 0.7);
            color: white;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/posenet"></script>
@endsection
@section('content')
<div class="row" style="margin-top: 60px">
        <div class="col">
            <input type="hidden" id="lokasi">
            <div class="webcam-capture"></div>
            <div class="face-status">Mohon hadapkan wajah Anda ke kamera</div>
        </div>
    </div>
    <div class="jam-digital-malasngoding">
        <p>{{ $hariini }}</p>
        <p id="jam"></p>
        <p>{{ $jamkerja->nama_jam_kerja }}</p>
        <p>Mulai : {{ date('H:i', strtotime($jamkerja->awal_jam_masuk)) }}</p>
        <p>Masuk : {{ date('H:i', strtotime($jamkerja->jam_masuk)) }}</p>
        <p>Akhir : {{ date('H:i', strtotime($jamkerja->akhir_jam_masuk)) }}</p>
        <p>Pulang : {{ date('H:i', strtotime($jamkerja->jam_pulang)) }}</p>
    </div>
    <div class="row">
        <div class="col">
            @if ($cek > 0)
                <button id="takeabsen" class="btn btn-danger btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Pulang
                </button>
            @else
                <button id="takeabsen" class="btn btn-primary btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Masuk
                </button>
            @endif
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>

    <audio id="notifikasi_in">
        <source src="{{ asset('assets/sound/notifikasi_in.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_out">
        <source src="{{ asset('assets/sound/notifikasi_out.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="radius_sound">
        <source src="{{ asset('assets/sound/radius.mp3') }}" type="audio/mpeg">
    </audio>
@endsection

@push('myscript')

<script>
        // Kode jam dan fungsi set tetap sama
        
        let isWajahTerdeteksi = false;
        let poseNet;
        let takeabsenBtn = document.getElementById('takeabsen');
        
        async function initPoseDetection() {
            const faceStatus = document.querySelector('.face-status');
            
            try {
                // Load PoseNet
                faceStatus.textContent = 'Memuat sistem deteksi...';
                poseNet = await posenet.load({
                    architecture: 'MobileNetV1',
                    outputStride: 16,
                    inputResolution: { width: 320, height: 240 },
                    multiplier: 0.5
                });
                
                faceStatus.textContent = 'Sistem deteksi siap';
                
                // Mulai deteksi
                const video = document.querySelector('.webcam-capture video');
                video.addEventListener('play', () => {
                    setInterval(async () => {
                        const pose = await poseNet.estimateSinglePose(video, {
                            flipHorizontal: true
                        });
                        
                        // Deteksi keypoints wajah
                        const nose = pose.keypoints.find(k => k.part === 'nose');
                        const leftEye = pose.keypoints.find(k => k.part === 'leftEye');
                        const rightEye = pose.keypoints.find(k => k.part === 'rightEye');
                        const leftShoulder = pose.keypoints.find(k => k.part === 'leftShoulder');
                        const rightShoulder = pose.keypoints.find(k => k.part === 'rightShoulder');
                        
                        // Set threshold untuk deteksi
                        const faceThreshold = 0.3;
                        const shoulderThreshold = 0.2;
                        
                        // Cek deteksi wajah dan pundak
                        const isWajahTerdeteksi = 
                            nose.score > faceThreshold && 
                            leftEye.score > faceThreshold && 
                            rightEye.score > faceThreshold;
                            
                        const isPundakTerdeteksi = 
                            leftShoulder.score > shoulderThreshold && 
                            rightShoulder.score > shoulderThreshold;
                        
                        // Update status
                        if (isWajahTerdeteksi && isPundakTerdeteksi) {
                            faceStatus.textContent = '✓ Posisi Tepat';
                            faceStatus.style.backgroundColor = 'rgba(40, 167, 69, 0.7)';
                            takeabsenBtn.removeAttribute('disabled');
                        } else {
                            let statusText = [];
                            if (!isWajahTerdeteksi) statusText.push('Wajah');
                            if (!isPundakTerdeteksi) statusText.push('Pundak');
                            
                            faceStatus.textContent = `Posisikan ${statusText.join(' & ')} ke Kamera`;
                            faceStatus.style.backgroundColor = 'rgba(255, 193, 7, 0.7)';
                            takeabsenBtn.setAttribute('disabled', 'disabled');
                        }
                    }, 500);
                });
                
            } catch (error) {
                console.error('Error:', error);
                faceStatus.textContent = 'Gagal memuat sistem deteksi';
                faceStatus.style.backgroundColor = 'rgba(220, 53, 69, 0.7)';
            }
        }

        // Inisialisasi Webcam dengan konfigurasi yang sudah ada
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        // Attach webcam dan mulai deteksi
        Webcam.attach('.webcam-capture');
        
        // Start detection setelah DOM loaded
        document.addEventListener('DOMContentLoaded', async () => {
            await tf.ready();
            await initPoseDetection();
        });

        // Modifikasi event click takeabsen
        $("#takeabsen").click(function(e) {
            if (this.hasAttribute('disabled')) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Pastikan posisi wajah dan pundak terdeteksi dengan benar',
                    icon: 'warning'
                });
                return;
            }

            Webcam.snap(function(uri) {
                image = uri;
            });
            var lokasi = $("#lokasi").val();
            // Sisanya tetap sama seperti kode original
        });

        // Kode untuk map dan geolocation tetap sama
    </script>
    <script type="text/javascript">
        window.onload = function() {
            jam();
        }

        function jam() {
            var e = document.getElementById('jam'),
                d = new Date(),
                h, m, s;
            h = set(d.getHours());
            m = set(d.getMinutes());
            s = set(d.getSeconds());

            e.innerHTML = h + ':' + m + ':' + s;

            setTimeout('jam()', 1000);
        }

        function set(e) {
            e = e < 10 ? '0' + e : e;
            return e;
        }
    </script>
    <script>
        var notifikasi_in = document.getElementById('notifikasi_in');
        var notifikasi_out = document.getElementById('notifikasi_out');
        var radius_sound = document.getElementById('radius_sound');
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture');

        var lokasi = document.getElementById('lokasi');
        var map, marker, circle;

        function initMap() {
            map = L.map('map').setView([0, 0], 18);
            L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(map);
            marker = L.marker([0, 0]).addTo(map);

            var lokasi_kantor = "{{ $lok_kantor->lokasi_cabang }}";
            var lok = lokasi_kantor.split(",");
            var lat_kantor = parseFloat(lok[0]);
            var long_kantor = parseFloat(lok[1]);
            var radius = "{{ $lok_kantor->radius_cabang }}";

            circle = L.circle([lat_kantor, long_kantor], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: parseFloat(radius)
            }).addTo(map);
        }

        function updatePosition(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            lokasi.value = lat + "," + lng;
            if (map) {
                map.setView([lat, lng], 18);
                marker.setLatLng([lat, lng]);
            }
            console.log("Posisi diperbarui: " + lat + ", " + lng);
        }

        function errorCallback(error) {
            console.log("Error: " + error.message);
        }

        if (navigator.geolocation) {
            initMap();
            navigator.geolocation.watchPosition(updatePosition, errorCallback, {
                enableHighAccuracy: true,
                timeout: 1000,
                maximumAge: 0
            });
        }

        $("#takeabsen").click(function(e) {
            Webcam.snap(function(uri) {
                image = uri;
            });
            var lokasi = $("#lokasi").val();
            $.ajax({
                type: 'POST',
                url: '/presensi/store',
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    lokasi: lokasi,
                    kode_jam_kerja: "{{ $kode_jam_kerja }}"
                },
                cache: false,
                success: function(respond) {
                    var status = respond.split("|");
                    if (status[0] == "success") {
                        if (status[2] == "in") {
                            notifikasi_in.play();
                        } else {
                            notifikasi_out.play();
                        }
                        Swal.fire({
                            title: 'Berhasil !',
                            text: status[1],
                            icon: 'success'
                        })
                        setTimeout("location.href='/dashboard'", 3000);
                    } else {
                        if (status[2] == "radius") {
                            radius_sound.play();
                        }
                        Swal.fire({
                            title: 'Error !',
                            text: status[1],
                            icon: 'error'
                        })
                    }
                }
            });
        });
    </script>
@endpush


