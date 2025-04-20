document.addEventListener("DOMContentLoaded", function () {
    // Fungsi untuk memuat peta
    function initMap() {
        // Cari section untuk peta
        const mapContainer = document.querySelector(".osm-map-section");

        if (!mapContainer) return;

        // Buat elemen div untuk peta
        const mapElement = document.createElement("div");
        mapElement.id = "osm-map";
        mapElement.style.height = "400px";
        mapElement.style.width = "100%";
        mapElement.style.marginTop = "10px";
        mapElement.style.marginBottom = "10px";

        // Tambahkan elemen peta ke container
        mapContainer.appendChild(mapElement);

        // Ambil nilai latitude dan longitude dari form
        const latInput = document.querySelector('input[name="latitude"]');
        const lngInput = document.querySelector('input[name="longitude"]');

        if (!latInput || !lngInput) return;

        let latitude = parseFloat(latInput.value) || -7.250445;
        let longitude = parseFloat(lngInput.value) || 112.768845;

        // Inisialisasi peta
        let map = L.map("osm-map").setView([latitude, longitude], 13);

        // Tambahkan layer OpenStreetMap
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        // Tambahkan marker yang bisa di-drag
        let marker = L.marker([latitude, longitude], {
            draggable: true,
        }).addTo(map);

        // Update koordinat saat marker di-drag
        marker.on("dragend", function (event) {
            let position = marker.getLatLng();
            latInput.value = position.lat.toFixed(8);
            lngInput.value = position.lng.toFixed(8);
        });

        // Update marker saat peta di-klik
        map.on("click", function (e) {
            marker.setLatLng(e.latlng);
            latInput.value = e.latlng.lat.toFixed(8);
            lngInput.value = e.latlng.lng.toFixed(8);
        });

        // Invalidate size untuk memastikan peta terrender dengan benar
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    }

    // Inisialisasi peta saat halaman dimuat
    initMap();

    // Tambahkan event listener untuk Livewire
    document.addEventListener("livewire:navigated", initMap);
    document.addEventListener("livewire:load", initMap);
});
