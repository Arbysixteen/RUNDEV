<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - RUN DEV</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            color: #1a202c;
        }
        
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            z-index: 1000;
        }
        
        .sidebar-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .sidebar-header h1 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        
        .sidebar-header p {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 10px;
        }
        
        .sidebar-menu a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.1);
        }
        
        .main-content {
            margin-left: 250px;
            padding: 15px;
            max-height: 100vh;
            overflow-y: auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 1.8rem;
            color: #2d3748;
        }
        
        .logout-btn {
            background: #e53e3e;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #718096;
            font-size: 0.9rem;
        }
        
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .chart-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: 350px; /* Fixed height */
        }
        
        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2d3748;
        }
        
        .chart-container {
            height: 280px; /* Fixed chart container height */
            position: relative;
        }
        
        .participants-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .table-header {
            background: #f7fafc;
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .table-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
        }
        
        .table-container {
            overflow-x: auto;
            max-height: 500px; /* Limit table height */
            overflow-y: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 10px 12px; /* Reduced padding */
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        th {
            background: #f7fafc;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.9rem;
        }
        
        td {
            color: #2d3748;
            font-size: 0.85rem; /* Smaller font */
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-belum {
            background: #fed7d7;
            color: #c53030;
        }
        
        .status-lunas {
            background: #c6f6d5;
            color: #22543d;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-edit {
            background: #3182ce;
            color: white;
        }
        
        .btn-delete {
            background: #e53e3e;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.8;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            position: relative;
        }
        
        .close {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }
        
        .close:hover {
            color: #000;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }
        
        .form-input, .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .chart-card {
                height: 300px; /* Smaller height on mobile */
            }
            
            .chart-container {
                height: 230px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .table-container {
                max-height: 400px;
            }
            
            th, td {
                padding: 8px 6px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>🏃‍♂️ RUN DEV</h1>
            <p>Admin Panel</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#" class="active">📊 Dashboard</a></li>
            <li><a href="#participants">👥 Peserta</a></li>
            <li><a href="#statistics">📈 Statistik</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Dashboard Admin</h1>
            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Peserta</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['payment_status']['lunas'] }}</div>
                <div class="stat-label">Sudah Bayar</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['payment_status']['belum'] }}</div>
                <div class="stat-label">Belum Bayar</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format(($stats['payment_status']['lunas'] / max($stats['total'], 1)) * 100, 1) }}%</div>
                <div class="stat-label">Tingkat Pembayaran</div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <div class="chart-card">
                <h3 class="chart-title">Distribusi Kategori Lomba</h3>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <h3 class="chart-title">Status Pembayaran</h3>
                <div class="chart-container">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Participants Table -->
        <div class="participants-table" id="participants">
            <div class="table-header">
                <h3 class="table-title">Daftar Peserta ({{ $stats['total'] }} orang)</h3>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID Peserta</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Kategori</th>
                            <th>WhatsApp</th>
                            <th>Ukuran Baju</th>
                            <th>Status Bayar</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $id => $participant)
                        <tr>
                            <td><strong>{{ $participant['idPeserta'] ?? $id }}</strong></td>
                            <td>{{ $participant['namaLengkap'] ?? '-' }}</td>
                            <td>{{ $participant['email'] ?? '-' }}</td>
                            <td>{{ $participant['kategoriLomba'] ?? '-' }}</td>
                            <td>{{ $participant['nomorWA'] ?? '-' }}</td>
                            <td>{{ $participant['ukuranBaju'] ?? '-' }}</td>
                            <td>
                                <span class="status-badge status-{{ $participant['pembayaran'] ?? 'belum' }}">
                                    {{ ucfirst($participant['pembayaran'] ?? 'belum') }}
                                </span>
                            </td>
                            <td>{{ isset($participant['registrationDate']) ? date('d/m/Y H:i', strtotime($participant['registrationDate'])) : '-' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-edit" onclick="editParticipant('{{ $id }}', {{ json_encode($participant) }})">Edit</button>
                                    <button class="btn btn-delete" onclick="deleteParticipant('{{ $id }}', '{{ $participant['namaLengkap'] ?? 'Unknown' }}')">Hapus</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Peserta</h2>
            <form id="editForm">
                <input type="hidden" id="editId">
                
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="editNama" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" id="editEmail" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kategori Lomba</label>
                    <select id="editKategori" class="form-select" required>
                        <option value="5K">5K</option>
                        <option value="10K">10K</option>
                        <option value="Half Marathon">Half Marathon</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp</label>
                    <input type="text" id="editWA" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Ukuran Baju</label>
                    <select id="editUkuran" class="form-select" required>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Status Pembayaran</label>
                    <select id="editPembayaran" class="form-select" required>
                        <option value="belum">Belum Bayar</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>
                
                <button type="submit" class="btn" style="background: #667eea; color: white; width: 100%; padding: 12px;">Update</button>
            </form>
        </div>
    </div>

    <script>
        // Chart.js configurations
        const categoryData = @json($stats['categories']);
        const paymentData = @json($stats['payment_status']);

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(categoryData),
                datasets: [{
                    data: Object.values(categoryData),
                    backgroundColor: ['#667eea', '#764ba2', '#f093fb']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Payment Chart
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        new Chart(paymentCtx, {
            type: 'pie',
            data: {
                labels: ['Belum Bayar', 'Lunas'],
                datasets: [{
                    data: [paymentData.belum, paymentData.lunas],
                    backgroundColor: ['#e53e3e', '#38a169']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Modal functions
        function editParticipant(id, data) {
            document.getElementById('editId').value = id;
            document.getElementById('editNama').value = data.namaLengkap || '';
            document.getElementById('editEmail').value = data.email || '';
            document.getElementById('editKategori').value = data.kategoriLomba || '';
            document.getElementById('editWA').value = data.nomorWA || '';
            document.getElementById('editUkuran').value = data.ukuranBaju || '';
            document.getElementById('editPembayaran').value = data.pembayaran || 'belum';
            
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function deleteParticipant(id, name) {
            if (confirm(`Apakah Anda yakin ingin menghapus peserta "${name}"?`)) {
                fetch(`/admin/participants/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Peserta berhasil dihapus');
                        location.reload();
                    } else {
                        alert('Gagal menghapus peserta: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus peserta');
                });
            }
        }

        // Edit form submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('editId').value;
            const formData = {
                namaLengkap: document.getElementById('editNama').value,
                email: document.getElementById('editEmail').value,
                kategoriLomba: document.getElementById('editKategori').value,
                nomorWA: document.getElementById('editWA').value,
                ukuranBaju: document.getElementById('editUkuran').value,
                pembayaran: document.getElementById('editPembayaran').value
            };

            fetch(`/admin/participants/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Peserta berhasil diupdate');
                    closeModal();
                    location.reload();
                } else {
                    alert('Gagal mengupdate peserta: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate peserta');
            });
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
