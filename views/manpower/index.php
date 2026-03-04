<!-- 
==========================================
ชื่อไฟล์: index.php
ที่อยู่ไฟล์: views/manpower/index.php
คำอธิบาย: หน้าภาพรวมกรอบอัตรากำลัง (เพิ่มฟีเจอร์สลับมุมมอง การ์ด/ตาราง)
==========================================
-->
<?php include 'views/layout/header.php'; ?>

<style>
    /* สไตล์สำหรับปุ่มสลับมุมมองสีเขียว Teal */
    .btn-outline-teal {
        color: #0d9488;
        border-color: #0d9488;
    }
    .btn-outline-teal:hover, .btn-outline-teal.active {
        color: #fff;
        background-color: #0d9488;
        border-color: #0d9488;
    }
    .text-teal { color: #0d9488 !important; }
</style>

<div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-0"><i class="fa-solid fa-sitemap text-teal me-2"></i> กรอบอัตรากำลัง อบจ.</h2>
        <p class="text-muted mt-1 mb-0">ภาพรวมการจัดสรรอัตรากำลังและตำแหน่งที่ว่าง</p>
    </div>
    <a href="index.php?action=manpower_create" class="btn text-white shadow-sm fw-bold" style="background-color: #0d9488;">
        <i class="fa-solid fa-plus me-1"></i> เพิ่มตำแหน่งใหม่
    </a>
</div>

<!-- สรุปภาพรวม -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="modern-card border-primary border-start border-5 shadow-sm text-center py-4">
            <h6 class="text-muted fw-bold">กรอบทั้งหมด</h6>
            <h2 class="text-primary fw-bold mb-0"><?php echo $totalRequired ?? 0; ?> <span class="fs-6 text-muted fw-normal">อัตรา</span></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card border-success border-start border-5 shadow-sm text-center py-4">
            <h6 class="text-muted fw-bold">คนครอง (มีตัวบุคคล)</h6>
            <h2 class="text-success fw-bold mb-0"><?php echo $totalOccupied ?? 0; ?> <span class="fs-6 text-muted fw-normal">คน</span></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card border-danger border-start border-5 shadow-sm text-center py-4">
            <h6 class="text-muted fw-bold">อัตราว่าง (รอสรรหา)</h6>
            <h2 class="text-danger fw-bold mb-0"><?php echo $totalVacant ?? 0; ?> <span class="fs-6 text-muted fw-normal">อัตรา</span></h2>
        </div>
    </div>
</div>

<!-- ส่วนหัวเรื่อง และ ปุ่มสลับมุมมอง -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 border-bottom pb-2 gap-2">
    <h5 class="fw-bold text-dark mb-0">ข้อมูลแยกตามส่วนราชการ / หน่วยงาน</h5>
    <div class="btn-group shadow-sm" role="group">
        <button type="button" class="btn btn-sm btn-outline-teal fw-bold" id="btn-card-view" onclick="toggleView('card')">
            <i class="fa-solid fa-grip me-1"></i> แบบการ์ด
        </button>
        <button type="button" class="btn btn-sm btn-outline-teal active fw-bold" id="btn-table-view" onclick="toggleView('table')">
            <i class="fa-solid fa-list me-1"></i> แบบตาราง
        </button>
    </div>
</div>

<!-- มุมมองแบบที่ 2: แบบตาราง (Table View) ตั้งเป็นค่าเริ่มต้น -->
<div id="view-table" class="modern-card border-0 shadow-sm p-0 overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted">
                <tr>
                    <th class="ps-4 py-3">ส่วนราชการ / หน่วยงาน</th>
                    <th class="text-center py-3">กรอบทั้งหมด (อัตรา)</th>
                    <th class="text-center text-success py-3">มีคนครอง (อัตรา)</th>
                    <th class="text-center text-danger py-3">อัตราว่าง (อัตรา)</th>
                    <th class="text-center py-3" style="width: 150px;">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($categories)): ?>
                    <?php foreach($categories as $deptName => $stats): ?>
                    <tr>
                        <td class="ps-4 fw-bold text-dark">
                            <i class="fa-regular fa-building text-secondary me-2"></i> <?php echo htmlspecialchars($deptName); ?>
                        </td>
                        <td class="text-center fw-bold bg-light bg-opacity-50"><?php echo $stats['total'] ?? 0; ?></td>
                        <td class="text-center text-success fw-bold"><?php echo $stats['occupied'] ?? 0; ?></td>
                        <td class="text-center text-danger fw-bold bg-danger bg-opacity-10"><?php echo $stats['vacant'] ?? 0; ?></td>
                        <td class="text-center">
                            <a href="index.php?action=manpower_detail&dept=<?php echo urlencode($deptName); ?>" class="btn btn-sm btn-light border text-teal shadow-sm fw-bold">
                                รายละเอียด <i class="fa-solid fa-chevron-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">ไม่มีข้อมูลกรอบอัตรากำลังในระบบ</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- มุมมองแบบที่ 1: แบบการ์ด (Card View) ซ่อนไว้เป็นค่าเริ่มต้น -->
<div id="view-card" class="row g-4 mb-4 d-none">
    <?php if(!empty($categories)): ?>
        <?php foreach($categories as $deptName => $stats): ?>
        <div class="col-md-6 col-lg-4">
            <div class="modern-card border-0 shadow-sm h-100 d-flex flex-column p-4">
                <h5 class="fw-bold mb-4" style="color: #0f766e;"><i class="fa-solid fa-building me-2"></i> <?php echo htmlspecialchars($deptName); ?></h5>
                
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                    <span class="text-muted small fw-bold">กรอบทั้งหมด</span>
                    <span class="fw-bold text-dark"><?php echo $stats['total'] ?? 0; ?> <small>อัตรา</small></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-success bg-opacity-10 rounded">
                    <span class="text-success small fw-bold">มีคนครอง</span>
                    <span class="fw-bold text-success"><?php echo $stats['occupied'] ?? 0; ?> <small>อัตรา</small></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4 p-2 bg-danger bg-opacity-10 rounded">
                    <span class="text-danger small fw-bold">อัตราว่าง</span>
                    <span class="fw-bold text-danger"><?php echo $stats['vacant'] ?? 0; ?> <small>อัตรา</small></span>
                </div>
                
                <div class="mt-auto">
                    <a href="index.php?action=manpower_detail&dept=<?php echo urlencode($deptName); ?>" class="btn btn-light border w-100 fw-bold shadow-sm" style="color: #0d9488;">
                        จัดการทะเบียนคุม <i class="fa-solid fa-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12"><div class="alert alert-warning text-center shadow-sm">ไม่มีข้อมูลกรอบอัตรากำลังในระบบ</div></div>
    <?php endif; ?>
</div>

<!-- JavaScript ควบคุมการสลับมุมมอง -->
<script>
    function toggleView(viewType) {
        const cardView = document.getElementById('view-card');
        const tableView = document.getElementById('view-table');
        const btnCard = document.getElementById('btn-card-view');
        const btnTable = document.getElementById('btn-table-view');

        if (viewType === 'card') {
            cardView.classList.remove('d-none');
            tableView.classList.add('d-none');
            btnCard.classList.add('active');
            btnTable.classList.remove('active');
        } else {
            cardView.classList.add('d-none');
            tableView.classList.remove('d-none');
            btnTable.classList.add('active');
            btnCard.classList.remove('active');
        }
    }
</script>

<?php include 'views/layout/footer.php'; ?>