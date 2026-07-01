// ==================== GLOBAL STATE ====================
let currentUser = null;
let form1Data = [];
let form2Data = [];
let form3Data = [];
let form4Data = [];
let form5Data = [];

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', () => {
    loadDataFromLocalStorage();
    initializeDropdowns();
    checkLoginStatus();
});

// ==================== LOGIN SYSTEM ====================
function handleLogin(event) {
    event.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const role = document.getElementById('role').value;

    // Simple demo validation
    if (username && password && role) {
        currentUser = { username, role };
        localStorage.setItem('currentUser', JSON.stringify(currentUser));

        // Hide login, show dashboard
        document.getElementById('loginContainer').classList.remove('active');
        document.getElementById('dashboardContainer').classList.add('active');
        document.getElementById('userInfo').textContent = `👤 ${username} (${role})`;

        // Load initial data
        updateDashboard();
    }
}

function logout() {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        currentUser = null;
        localStorage.removeItem('currentUser');
        document.getElementById('loginContainer').classList.add('active');
        document.getElementById('dashboardContainer').classList.remove('active');
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
        document.getElementById('role').value = '';
    }
}

function checkLoginStatus() {
    const storedUser = localStorage.getItem('currentUser');
    if (storedUser) {
        currentUser = JSON.parse(storedUser);
        document.getElementById('loginContainer').classList.remove('active');
        document.getElementById('dashboardContainer').classList.add('active');
        document.getElementById('userInfo').textContent = `👤 ${currentUser.username} (${currentUser.role})`;
        updateDashboard();
    }
}

// ==================== FORM NAVIGATION ====================
function showForm(formId) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });

    // Remove active class from all nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });

    // Show selected section
    document.getElementById(formId).classList.add('active');

    // Add active class to clicked nav item
    event.target.closest('.nav-item').classList.add('active');

    // Update dashboard when showing dashboard
    if (formId === 'dashboard') {
        updateDashboard();
    }
}

// ==================== FORM HANDLERS ====================
// FORM 1: LOGIN MANAGEMENT
function handleForm1(event) {
    event.preventDefault();

    const newUser = {
        id: Date.now(),
        username: document.getElementById('form1_username').value,
        email: document.getElementById('form1_email').value,
        role: document.getElementById('form1_role').value,
        nip: document.getElementById('form1_nip').value,
        status: document.getElementById('form1_status').value,
        dateAdded: new Date().toLocaleDateString('id-ID')
    };

    form1Data.push(newUser);
    saveDataToLocalStorage();
    displayTable1();
    clearForm('form1');
    showNotification('✅ Pengguna berhasil ditambahkan!');
}

function displayTable1() {
    const tbody = document.querySelector('#table1 tbody');
    tbody.innerHTML = '';

    form1Data.forEach((user, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${user.username}</td>
            <td>${user.email}</td>
            <td>${user.role}</td>
            <td>${user.nip}</td>
            <td><span class="status-badge status-${user.status.toLowerCase().replace(' ', '-')}">${user.status}</span></td>
            <td>
                <button class="btn-edit" onclick="editForm1(${user.id})">✏️ Edit</button>
                <button class="btn-delete" onclick="deleteForm1(${user.id})">🗑️ Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function deleteForm1(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
        form1Data = form1Data.filter(u => u.id !== id);
        saveDataToLocalStorage();
        displayTable1();
        showNotification('✅ Pengguna berhasil dihapus!');
    }
}

function editForm1(id) {
    const user = form1Data.find(u => u.id === id);
    if (user) {
        document.getElementById('form1_username').value = user.username;
        document.getElementById('form1_email').value = user.email;
        document.getElementById('form1_role').value = user.role;
        document.getElementById('form1_nip').value = user.nip;
        document.getElementById('form1_status').value = user.status;
        deleteForm1(id);
    }
}

// FORM 2: PENERIMA MANFAAT
function handleForm2(event) {
    event.preventDefault();

    const newPenerima = {
        id: Date.now(),
        nik: document.getElementById('form2_nik').value,
        nama: document.getElementById('form2_nama').value,
        alamat: document.getElementById('form2_alamat').value,
        rtrw: document.getElementById('form2_rtrw').value,
        kategori: document.getElementById('form2_kategori').value,
        status: document.getElementById('form2_status').value,
        keterangan: document.getElementById('form2_keterangan').value,
        dateAdded: new Date().toLocaleDateString('id-ID')
    };

    form2Data.push(newPenerima);
    saveDataToLocalStorage();
    displayTable2();
    clearForm('form2');
    showNotification('✅ Data penerima berhasil ditambahkan!');
    updateDashboard();
}

function displayTable2() {
    const tbody = document.querySelector('#table2 tbody');
    tbody.innerHTML = '';

    form2Data.forEach((penerima, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${penerima.nik}</td>
            <td>${penerima.nama}</td>
            <td>${penerima.rtrw}</td>
            <td>${penerima.kategori}</td>
            <td><span class="status-badge status-${penerima.status.toLowerCase().replace(' ', '-')}">${penerima.status}</span></td>
            <td>
                <button class="btn-edit" onclick="editForm2(${penerima.id})">✏️ Edit</button>
                <button class="btn-delete" onclick="deleteForm2(${penerima.id})">🗑️ Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function filterTable2() {
    const filter = document.getElementById('filterPenerima').value.toLowerCase();
    const table = document.getElementById('table2');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const nik = row.cells[1].textContent.toLowerCase();
        const nama = row.cells[2].textContent.toLowerCase();

        if (nik.includes(filter) || nama.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

function deleteForm2(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data penerima ini?')) {
        form2Data = form2Data.filter(p => p.id !== id);
        saveDataToLocalStorage();
        displayTable2();
        showNotification('✅ Data penerima berhasil dihapus!');
        updateDashboard();
    }
}

function editForm2(id) {
    const penerima = form2Data.find(p => p.id === id);
    if (penerima) {
        document.getElementById('form2_nik').value = penerima.nik;
        document.getElementById('form2_nama').value = penerima.nama;
        document.getElementById('form2_alamat').value = penerima.alamat;
        document.getElementById('form2_rtrw').value = penerima.rtrw;
        document.getElementById('form2_kategori').value = penerima.kategori;
        document.getElementById('form2_status').value = penerima.status;
        document.getElementById('form2_keterangan').value = penerima.keterangan;
        deleteForm2(id);
    }
}

// FORM 3: JADWAL DISTRIBUSI
function handleForm3(event) {
    event.preventDefault();

    const newJadwal = {
        id: Date.now(),
        idJadwal: 'JDW-' + Date.now().toString().slice(-6),
        tanggal: document.getElementById('form3_tanggal').value,
        waktu: document.getElementById('form3_waktu').value,
        lokasi: document.getElementById('form3_lokasi').value,
        penanggung: document.getElementById('form3_penanggung').value,
        menu: document.getElementById('form3_menu').value,
        target: document.getElementById('form3_target').value,
        dateAdded: new Date().toLocaleDateString('id-ID')
    };

    form3Data.push(newJadwal);
    saveDataToLocalStorage();
    displayTable3();
    updateForm4Jadwal();
    updateForm5Jadwal();
    clearForm('form3');
    showNotification('✅ Jadwal distribusi berhasil dibuat!');
    updateDashboard();
}

function displayTable3() {
    const tbody = document.querySelector('#table3 tbody');
    tbody.innerHTML = '';

    form3Data.forEach((jadwal, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${jadwal.idJadwal}</td>
            <td>${jadwal.tanggal}</td>
            <td>${jadwal.waktu}</td>
            <td>${jadwal.lokasi}</td>
            <td>${jadwal.target}</td>
            <td>
                <button class="btn-view" onclick="viewForm3Detail(${jadwal.id})">👁️ Lihat</button>
                <button class="btn-delete" onclick="deleteForm3(${jadwal.id})">🗑️ Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function viewForm3Detail(id) {
    const jadwal = form3Data.find(j => j.id === id);
    if (jadwal) {
        const modal = document.getElementById('modal');
        document.getElementById('modalTitle').textContent = 'Detail Jadwal Distribusi';
        document.getElementById('modalBody').innerHTML = `
            <p><strong>ID Jadwal:</strong> ${jadwal.idJadwal}</p>
            <p><strong>Tanggal:</strong> ${jadwal.tanggal}</p>
            <p><strong>Waktu:</strong> ${jadwal.waktu}</p>
            <p><strong>Lokasi:</strong> ${jadwal.lokasi}</p>
            <p><strong>Penanggung Jawab:</strong> ${jadwal.penanggung}</p>
            <p><strong>Target Penerima:</strong> ${jadwal.target}</p>
            <p><strong>Menu Makanan:</strong></p>
            <p style="background: #f5f5f5; padding: 10px; border-radius: 5px;">${jadwal.menu}</p>
        `;
        modal.classList.add('active');
    }
}

function deleteForm3(id) {
    if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
        form3Data = form3Data.filter(j => j.id !== id);
        saveDataToLocalStorage();
        displayTable3();
        updateForm4Jadwal();
        updateForm5Jadwal();
        showNotification('✅ Jadwal berhasil dihapus!');
        updateDashboard();
    }
}

// FORM 4: INPUT DISTRIBUSI
function handleForm4(event) {
    event.preventDefault();

    const newDistribusi = {
        id: Date.now(),
        jadwalId: document.getElementById('form4_jadwal').value,
        penerimaId: document.getElementById('form4_penerima').value,
        porsi: document.getElementById('form4_porsi').value,
        petugas: document.getElementById('form4_petugas').value,
        status: document.getElementById('form4_status').value,
        waktu: document.getElementById('form4_waktu').value,
        keterangan: document.getElementById('form4_keterangan').value,
        dateAdded: new Date().toLocaleDateString('id-ID')
    };

    form4Data.push(newDistribusi);
    saveDataToLocalStorage();
    displayTable4();
    clearForm('form4');
    showNotification('✅ Data distribusi berhasil dicatat!');
    updateDashboard();
}

function displayTable4() {
    const tbody = document.querySelector('#table4 tbody');
    tbody.innerHTML = '';

    form4Data.forEach((distribusi, index) => {
        const penerima = form2Data.find(p => p.id == distribusi.penerimaId);
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${penerima ? penerima.nama : 'N/A'}</td>
            <td>${distribusi.porsi}</td>
            <td>${distribusi.petugas}</td>
            <td><span class="status-badge status-${distribusi.status.toLowerCase().replace(/ /g, '-').replace('didistribusikan', 'gagal')}">${distribusi.status}</span></td>
            <td>${new Date(distribusi.waktu).toLocaleDateString('id-ID')}</td>
            <td>
                <button class="btn-view" onclick="viewForm4Detail(${distribusi.id})">👁️ Lihat</button>
                <button class="btn-delete" onclick="deleteForm4(${distribusi.id})">🗑️ Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function filterTable4() {
    const filter = document.getElementById('filterStatusDistribusi').value;
    const table = document.getElementById('table4');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const status = row.cells[4].textContent;

        if (filter === '' || status.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

function viewForm4Detail(id) {
    const distribusi = form4Data.find(d => d.id === id);
    if (distribusi) {
        const penerima = form2Data.find(p => p.id == distribusi.penerimaId);
        const modal = document.getElementById('modal');
        document.getElementById('modalTitle').textContent = 'Detail Penyaluran';
        document.getElementById('modalBody').innerHTML = `
            <p><strong>Penerima Manfaat:</strong> ${penerima ? penerima.nama : 'N/A'}</p>
            <p><strong>NIK:</strong> ${penerima ? penerima.nik : 'N/A'}</p>
            <p><strong>Porsi:</strong> ${distribusi.porsi}</p>
            <p><strong>Petugas Penyalur:</strong> ${distribusi.petugas}</p>
            <p><strong>Status:</strong> ${distribusi.status}</p>
            <p><strong>Waktu Penyaluran:</strong> ${new Date(distribusi.waktu).toLocaleString('id-ID')}</p>
            <p><strong>Keterangan:</strong> ${distribusi.keterangan || 'Tidak ada'}</p>
        `;
        modal.classList.add('active');
    }
}

function deleteForm4(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data distribusi ini?')) {
        form4Data = form4Data.filter(d => d.id !== id);
        saveDataToLocalStorage();
        displayTable4();
        showNotification('✅ Data distribusi berhasil dihapus!');
        updateDashboard();
    }
}

function loadPenerimaBerdasarkanJadwal() {
    const jadwalId = document.getElementById('form4_jadwal').value;
    const peneerimaSelect = document.getElementById('form4_penerima');
    peneerimaSelect.innerHTML = '<option value="">-- Pilih Penerima --</option>';

    if (jadwalId) {
        const jadwal = form3Data.find(j => j.id == jadwalId);
        if (jadwal) {
            let penerimaBerdasarkanTarget = form2Data;
            if (jadwal.target !== 'Semua') {
                penerimaBerdasarkanTarget = form2Data.filter(p => p.kategori === jadwal.target);
            }

            penerimaBerdasarkanTarget.forEach(penerima => {
                const option = document.createElement('option');
                option.value = penerima.id;
                option.textContent = penerima.nama;
                peneerimaSelect.appendChild(option);
            });
        }
    }
}

// FORM 5: DOKUMENTASI
function handleForm5(event) {
    event.preventDefault();

    const file = document.getElementById('form5_foto').files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const newDokumentasi = {
                id: Date.now(),
                distribusiId: document.getElementById('form5_distribusi').value,
                judul: document.getElementById('form5_judul').value,
                keterangan: document.getElementById('form5_keterangan').value,
                foto: e.target.result,
                dateAdded: new Date().toLocaleDateString('id-ID')
            };

            form5Data.push(newDokumentasi);
            saveDataToLocalStorage();
            displayGallery();
            clearForm('form5');
            showNotification('✅ Dokumentasi berhasil diupload!');
            updateDashboard();
        };
        reader.readAsDataURL(file);
    }
}

function displayGallery() {
    const gallery = document.getElementById('gallery');
    gallery.innerHTML = '';

    form5Data.forEach(doc => {
        const item = document.createElement('div');
        item.className = 'gallery-item';
        item.innerHTML = `
            <img src="${doc.foto}" class="gallery-image" onclick="viewPhoto(${doc.id})">
            <div class="gallery-info">
                <h4>${doc.judul}</h4>
                <p>${doc.keterangan}</p>
                <button class="btn-delete" onclick="deleteForm5(${doc.id})" style="margin-top: 10px; width: 100%;">🗑️ Hapus</button>
            </div>
        `;
        gallery.appendChild(item);
    });

    if (form5Data.length === 0) {
        gallery.innerHTML = '<p style="text-align: center; color: #999;">Belum ada dokumentasi</p>';
    }
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const container = document.getElementById('previewContainer');
            container.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    }
}

function viewPhoto(id) {
    const doc = form5Data.find(d => d.id === id);
    if (doc) {
        const modal = document.getElementById('photoModal');
        document.getElementById('photoTitle').textContent = doc.judul;
        document.getElementById('photoPreview').src = doc.foto;
        document.getElementById('photoDesc').textContent = doc.keterangan;
        modal.classList.add('active');
    }
}

function deleteForm5(id) {
    if (confirm('Apakah Anda yakin ingin menghapus dokumentasi ini?')) {
        form5Data = form5Data.filter(d => d.id !== id);
        saveDataToLocalStorage();
        displayGallery();
        showNotification('✅ Dokumentasi berhasil dihapus!');
        updateDashboard();
    }
}

// ==================== UTILITY FUNCTIONS ====================
function clearForm(formId) {
    const form = document.querySelector(`#${formId} form`);
    if (form) {
        form.reset();
        document.getElementById('previewContainer').innerHTML = '';
    }
}

function closeModal() {
    document.getElementById('modal').classList.remove('active');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.remove('active');
}

function showNotification(message) {
    alert(message);
}

// ==================== LOCAL STORAGE ====================
function saveDataToLocalStorage() {
    localStorage.setItem('form1Data', JSON.stringify(form1Data));
    localStorage.setItem('form2Data', JSON.stringify(form2Data));
    localStorage.setItem('form3Data', JSON.stringify(form3Data));
    localStorage.setItem('form4Data', JSON.stringify(form4Data));
    localStorage.setItem('form5Data', JSON.stringify(form5Data));
}

function loadDataFromLocalStorage() {
    form1Data = JSON.parse(localStorage.getItem('form1Data')) || [];
    form2Data = JSON.parse(localStorage.getItem('form2Data')) || [];
    form3Data = JSON.parse(localStorage.getItem('form3Data')) || [];
    form4Data = JSON.parse(localStorage.getItem('form4Data')) || [];
    form5Data = JSON.parse(localStorage.getItem('form5Data')) || [];

    displayTable1();
    displayTable2();
    displayTable3();
    displayTable4();
    displayGallery();
}

// ==================== DROPDOWN INITIALIZATION ====================
function initializeDropdowns() {
    updateForm4Petugas();
    updateForm4Jadwal();
    updateForm5Jadwal();
}

function updateForm4Jadwal() {
    const select = document.getElementById('form4_jadwal');
    const selectedValue = select.value;
    select.innerHTML = '<option value="">-- Pilih Jadwal --</option>';

    form3Data.forEach(jadwal => {
        const option = document.createElement('option');
        option.value = jadwal.id;
        option.textContent = `${jadwal.tanggal} - ${jadwal.lokasi}`;
        select.appendChild(option);
    });

    select.value = selectedValue;
}

function updateForm5Jadwal() {
    const select = document.getElementById('form5_distribusi');
    const selectedValue = select.value;
    select.innerHTML = '<option value="">-- Pilih Kegiatan --</option>';

    form4Data.forEach(distribusi => {
        const jadwal = form3Data.find(j => j.id == distribusi.jadwalId);
        if (jadwal) {
            const option = document.createElement('option');
            option.value = distribusi.id;
            option.textContent = `${jadwal.tanggal} - ${jadwal.lokasi}`;
            select.appendChild(option);
        }
    });

    select.value = selectedValue;
}

function updateForm4Petugas() {
    const select = document.getElementById('form4_petugas');
    const petugas = ['Budi Santoso', 'Siti Nurhaliza', 'Ahmad Wijaya', 'Eka Putri', 'Dedi Gunawan'];
    select.innerHTML = '<option value="">-- Pilih Petugas --</option>';

    petugas.forEach(p => {
        const option = document.createElement('option');
        option.value = p;
        option.textContent = p;
        select.appendChild(option);
    });
}

// ==================== DASHBOARD UPDATE ====================
function updateDashboard() {
    document.getElementById('totalPenerima').textContent = form2Data.length;
    document.getElementById('totalJadwal').textContent = form3Data.length;
    
    // Count distribusi hari ini
    const today = new Date().toISOString().split('T')[0];
    const distribusiHariIni = form4Data.filter(d => d.waktu.includes(today));
    const totalPorsiHariIni = distribusiHariIni.reduce((sum, d) => sum + parseInt(d.porsi), 0);
    document.getElementById('distribusiHariIni').textContent = totalPorsiHariIni;
    
    document.getElementById('totalDokumentasi').textContent = form5Data.length;

    // Update charts
    updateCategoryChart();
    updateStatusChart();
}

function updateCategoryChart() {
    const container = document.getElementById('categoryChart');
    const kategoriCount = {};

    form2Data.forEach(penerima => {
        kategoriCount[penerima.kategori] = (kategoriCount[penerima.kategori] || 0) + 1;
    });

    let html = '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">';
    for (const [kategori, count] of Object.entries(kategoriCount)) {
        const percentage = (count / form2Data.length * 100).toFixed(0);
        html += `
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px; border-radius: 8px; text-align: center;">
                <strong>${kategori}</strong><br>
                <span style="font-size: 1.5rem; font-weight: bold;">${count}</span><br>
                <span style="font-size: 0.8rem;">${percentage}%</span>
            </div>
        `;
    }
    html += '</div>';

    if (form2Data.length === 0) {
        html = '<p style="text-align: center; color: #999;">Belum ada data penerima</p>';
    }

    container.innerHTML = html;
}

function updateStatusChart() {
    const container = document.getElementById('statusChart');
    const statusCount = {
        'Sudah Diserahkan': 0,
        'Belum Diambil': 0,
        'Gagal Didistribusikan': 0
    };

    form4Data.forEach(distribusi => {
        statusCount[distribusi.status]++;
    });

    let html = '<div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">';
    for (const [status, count] of Object.entries(statusCount)) {
        let color = 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)';
        if (status === 'Belum Diambil') color = 'linear-gradient(135deg, #f7b731 0%, #ffa502 100%)';
        if (status === 'Gagal Didistribusikan') color = 'linear-gradient(135deg, #f5576c 0%, #ff5757 100%)';

        html += `
            <div style="background: ${color}; color: white; padding: 10px; border-radius: 8px; text-align: center;">
                <strong>${status}</strong><br>
                <span style="font-size: 1.5rem; font-weight: bold;">${count}</span>
            </div>
        `;
    }
    html += '</div>';

    if (form4Data.length === 0) {
        html = '<p style="text-align: center; color: #999;">Belum ada data distribusi</p>';
    }

    container.innerHTML = html;
}