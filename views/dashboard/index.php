<!-- 
==========================================
ชื่อไฟล์: index.php
ที่อยู่ไฟล์: views/dashboard/index.php
==========================================
-->
<?php include 'views/layout/header.php'; ?>

<!-- นำเข้าไลบรารี Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-chart-pie text-teal me-2" style="color: #0d9488;"></i> ภาพรวมระบบ (Dashboard)</h2>
        <p class="text-muted mt-1 mb-0">สถิติประชากรบุคลากรและกรอบอัตรากำลัง อบจ.ศรีสะเกษ</p>
    </div>
    <div class="text-muted small">
        <i class="fa-regular fa-clock me-1"></i> ข้อมูลอัปเดตล่าสุด: <?php echo date('d/m/Y H:i'); ?>
    </div>
</div>

<!-- ========================================== -->
<!-- แถวที่ 1: การ์ดสรุปตัวเลข (KPIs) -->
<!-- ========================================== -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="modern-card border-start border-4 border-primary bg-white h-100 p-4">
            <div class="text-muted fw-bold small text-uppercase mb-2">บุคลากรทั้งหมดในระบบ</div>
            <div class="d-flex align-items-center">
                <div class="fs-1 fw-bolder text-primary me-3"><?php echo number_format($totalEmployees); ?></div>
                <div class="text-muted">คน</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="modern-card border-start border-4 border-info bg-white h-100 p-4">
            <div class="text-muted fw-bold small text-uppercase mb-2">กรอบอัตรากำลังรวม</div>
            <div class="d-flex align-items-center">
                <div class="fs-1 fw-bolder text-info me-3"><?php echo number_format($totalManpower); ?></div>
                <div class="text-muted">อัตรา</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="modern-card border-start border-4 border-success bg-white h-100 p-4">
            <div class="text-muted fw-bold small text-uppercase mb-2">ตำแหน่งที่มีคนครอง</div>
            <div class="d-flex align-items-center">
                <div class="fs-1 fw-bolder text-success me-3"><?php echo number_format($totalOccupied); ?></div>
                <div class="text-muted">อัตรา <span class="badge bg-success-subtle text-success ms-2"><?php echo $percentOccupied; ?>%</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="modern-card border-start border-4 border-danger bg-white h-100 p-4">
            <div class="text-muted fw-bold small text-uppercase mb-2">ตำแหน่งว่าง (รอสรรหา)</div>
            <div class="d-flex align-items-center">
                <div class="fs-1 fw-bolder text-danger me-3"><?php echo number_format($totalVacant); ?></div>
                <div class="text-muted">อัตรา <span class="badge bg-danger-subtle text-danger ms-2"><?php echo $percentVacant; ?>%</span></div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- แถวที่ 2: ข้อมูลประชากร (Demographics) -->
<!-- ========================================== -->
<div class="row g-4 mb-4">
    <!-- 1. กราฟวงกลม: สัดส่วนประเภทบุคลากร -->
    <div class="col-md-4">
        <div class="modern-card bg-white h-100 p-4">
            <h6 class="fw-bold text-dark mb-4 border-bottom pb-2">สัดส่วนประเภทบุคลากร</h6>
            <div class="position-relative" style="height: 250px; width: 100%;">
                <canvas id="employeeTypeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 2. กราฟพาย: สัดส่วนเพศ -->
    <div class="col-md-4">
        <div class="modern-card bg-white h-100 p-4">
            <h6 class="fw-bold text-dark mb-4 border-bottom pb-2">สัดส่วนเพศ (ชาย/หญิง)</h6>
            <div class="position-relative" style="height: 250px; width: 100%;">
                <canvas id="genderChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 3. กราฟแท่งแนวนอน: โครงสร้างอายุ -->
    <div class="col-md-4">
        <div class="modern-card bg-white h-100 p-4">
            <h6 class="fw-bold text-dark mb-4 border-bottom pb-2">โครงสร้างช่วงอายุ (Generation)</h6>
            <div class="position-relative" style="height: 250px; width: 100%;">
                <canvas id="ageChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- แถวที่ 3: กราฟแท่ง (หน่วยงาน) -->
<!-- ========================================== -->
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="modern-card bg-white h-100 p-4">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">สถานะอัตรากำลังแยกตามส่วนราชการ / หน่วยงาน</h5>
            <div class="position-relative" style="height: 350px; width: 100%;">
                <canvas id="manpowerDeptChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- ส่วน JavaScript สำหรับวาดกราฟ -->
<!-- ========================================== -->
<script>
    Chart.defaults.font.family = "'Sarabun', sans-serif";
    Chart.defaults.color = '#64748b';

    document.addEventListener("DOMContentLoaded", function() {
        
        // --- 1. กราฟประเภทบุคลากร (Doughnut) ---
        new Chart(document.getElementById('employeeTypeChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_keys($typeStats)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($typeStats)); ?>,
                    backgroundColor: ['#0d9488', '#3b82f6', '#f59e0b', '#64748b'],
                    borderWidth: 0, hoverOffset: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true } } } }
        });

        // --- 2. กราฟเพศ (Pie) ---
        new Chart(document.getElementById('genderChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_keys($genderStats)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($genderStats)); ?>,
                    backgroundColor: ['#3b82f6', '#ec4899', '#94a3b8'], // น้ำเงิน(ชาย), ชมพู(หญิง), เทา(ไม่ระบุ)
                    borderWidth: 0, hoverOffset: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true } } } }
        });

        // --- 3. กราฟโครงสร้างอายุ (Bar) ---
        new Chart(document.getElementById('ageChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($ageStats)); ?>,
                datasets: [{
                    label: 'จำนวนบุคลากร (คน)',
                    data: <?php echo json_encode(array_values($ageStats)); ?>,
                    backgroundColor: '#8b5cf6', // ม่วง
                    borderRadius: 4
                }]
            },
            options: { 
                responsive: true, maintainAspectRatio: false, 
                plugins: { legend: { display: false } }, // ซ่อน Legend เพราะมีสีเดียว
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });

        // --- 4. กราฟแท่ง (อัตรากำลังรายสังกัด) ---
        <?php 
            $deptNames = array_keys($deptStats);
            $deptOccupied = array_column($deptStats, 'occupied');
            $deptVacant = array_column($deptStats, 'vacant');
        ?>
        new Chart(document.getElementById('manpowerDeptChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($deptNames); ?>,
                datasets: [
                    { label: 'มีคนครอง', data: <?php echo json_encode($deptOccupied); ?>, backgroundColor: '#10b981', borderRadius: 4 },
                    { label: 'อัตราว่าง', data: <?php echo json_encode($deptVacant); ?>, backgroundColor: '#ef4444', borderRadius: 4 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: { x: { stacked: true, ticks: { maxRotation: 45, minRotation: 45 } }, y: { stacked: true, beginAtZero: true } },
                plugins: { legend: { position: 'top', align: 'end' }, tooltip: { mode: 'index', intersect: false } }
            }
        });
    });
</script>

<?php include 'views/layout/footer.php'; ?>