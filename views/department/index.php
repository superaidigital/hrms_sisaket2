<!-- 
==========================================
ชื่อไฟล์: index.php
ที่อยู่ไฟล์: views/department/index.php
==========================================
-->
<?php include 'views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark"><i class="fa-regular fa-building text-primary me-2"></i> จัดการหน่วยงาน / ส่วนราชการ</h2>
        <p class="text-muted mt-1 mb-0">เพิ่ม แก้ไข หรือลบ ข้อมูลหน่วยงานในสังกัดทั้งหมด</p>
    </div>
    <div>
        <a href="index.php?action=settings" class="btn btn-light border shadow-sm me-2 text-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> กลับหน้าตั้งค่า
        </a>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addDeptModal">
            <i class="fa-solid fa-plus me-1"></i> เพิ่มหน่วยงาน
        </button>
    </div>
</div>

<div class="modern-card p-0 overflow-hidden shadow-sm border-0 mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3">ชื่อหน่วยงาน / ส่วนราชการ</th>
                    <th class="py-3">หมวดหมู่ / ประเภท</th>
                    <th class="text-end pe-4 py-3">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($departments)): ?>
                <tr><td colspan="3" class="text-center py-4 text-muted">ไม่พบข้อมูลหน่วยงาน</td></tr>
                <?php endif; ?>

                <?php foreach ($departments as $dept): ?>
                <tr>
                    <td class="ps-4 fw-bold text-dark">
                        <?php echo htmlspecialchars($dept['name']); ?>
                    </td>
                    <td>
                        <?php 
                            $badgeClass = 'bg-secondary';
                            if($dept['type'] == 'ส่วนราชการส่วนกลาง') $badgeClass = 'bg-primary-subtle text-primary border-primary-subtle';
                            else if($dept['type'] == 'สถานศึกษาในสังกัด') $badgeClass = 'bg-success-subtle text-success border-success-subtle';
                            else if($dept['type'] == 'หน่วยบริการสาธารณสุข') $badgeClass = 'bg-info-subtle text-info border-info-subtle';
                        ?>
                        <span class="badge <?php echo $badgeClass; ?> border px-3 py-2 rounded-pill">
                            <?php echo htmlspecialchars($dept['type']); ?>
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <button type="button" class="btn btn-sm btn-light text-primary border me-1" data-bs-toggle="modal" data-bs-target="#editDeptModal<?php echo $dept['id']; ?>">
                            <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                        </button>
                        <a href="index.php?action=departments_delete&id=<?php echo $dept['id']; ?>" class="btn btn-sm btn-light text-danger border" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบหน่วยงานนี้?');">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>

                <!-- Modal แก้ไขหน่วยงาน -->
                <div class="modal fade" id="editDeptModal<?php echo $dept['id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content rounded-4 border-0 shadow">
                            <div class="modal-header bg-light border-bottom-0 pb-0 pt-4 px-4">
                                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen text-primary me-2"></i> แก้ไขข้อมูลหน่วยงาน</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="index.php?action=departments_update" method="POST">
                                <input type="hidden" name="id" value="<?php echo $dept['id']; ?>">
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted small">ชื่อหน่วยงาน <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control bg-light" name="name" required value="<?php echo htmlspecialchars($dept['name']); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted small">ประเภท / หมวดหมู่</label>
                                        <select class="form-select bg-light" name="type" required>
                                            <option value="ส่วนราชการส่วนกลาง" <?php echo $dept['type'] == 'ส่วนราชการส่วนกลาง' ? 'selected' : ''; ?>>ส่วนราชการส่วนกลาง</option>
                                            <option value="สถานศึกษาในสังกัด" <?php echo $dept['type'] == 'สถานศึกษาในสังกัด' ? 'selected' : ''; ?>>สถานศึกษาในสังกัด</option>
                                            <option value="หน่วยบริการสาธารณสุข" <?php echo $dept['type'] == 'หน่วยบริการสาธารณสุข' ? 'selected' : ''; ?>>หน่วยบริการสาธารณสุข (รพ.สต.)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                                    <button type="button" class="btn btn-light border fw-bold" data-bs-dismiss="modal">ยกเลิก</button>
                                    <button type="submit" class="btn btn-primary fw-bold shadow-sm">บันทึกการแก้ไข</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal เพิ่มหน่วยงาน -->
<div class="modal fade" id="addDeptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-building text-primary me-2"></i> เพิ่มหน่วยงานใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?action=departments_store" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">ชื่อหน่วยงาน <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light" name="name" required placeholder="เช่น กองสาธารณสุข">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">ประเภท / หมวดหมู่</label>
                        <select class="form-select bg-light" name="type" required>
                            <option value="ส่วนราชการส่วนกลาง">ส่วนราชการส่วนกลาง</option>
                            <option value="สถานศึกษาในสังกัด">สถานศึกษาในสังกัด</option>
                            <option value="หน่วยบริการสาธารณสุข">หน่วยบริการสาธารณสุข (รพ.สต.)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light border fw-bold" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary fw-bold shadow-sm">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>