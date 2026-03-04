<?php include 'views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-user-shield text-teal me-2" style="color: #0d9488;"></i> จัดการผู้ใช้งานและสิทธิ์</h2>
        <p class="text-muted mt-1 mb-0">เพิ่ม ลบ กำหนดสิทธิ์ และรีเซ็ตรหัสผ่าน</p>
    </div>
    <div>
        <a href="index.php?action=settings" class="btn btn-light border shadow-sm me-2 text-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> กลับหน้าตั้งค่า
        </a>
        <button type="button" class="btn text-white shadow-sm" style="background: linear-gradient(to right, #14b8a6, #10b981);" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fa-solid fa-plus me-1"></i> เพิ่มผู้ใช้งาน
        </button>
    </div>
</div>

<div class="modern-card p-0 overflow-hidden shadow-sm border-0 mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3">ชื่อ-นามสกุล</th>
                    <th class="py-3">ชื่อผู้ใช้ (Username)</th>
                    <th class="py-3 text-center">สิทธิ์การใช้งาน</th>
                    <th class="py-3">วันที่สร้างบัญชี</th>
                    <th class="text-end pe-4 py-3">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td class="ps-4 fw-bold text-dark">
                        <div class="d-flex align-items-center">
                            <div class="bg-teal bg-opacity-10 text-teal rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; color: #0d9488;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <?php echo htmlspecialchars($user['full_name']); ?>
                            <?php if($user['id'] == $_SESSION['user_id']) echo " <span class='badge bg-primary ms-2'>ฉัน</span>"; ?>
                        </div>
                    </td>
                    <td class="text-secondary"><?php echo htmlspecialchars($user['username']); ?></td>
                    <td class="text-center">
                        <?php if($user['role'] == 'admin'): ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill"><i class="fa-solid fa-key me-1"></i> Admin</span>
                        <?php else: ?>
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 rounded-pill"><i class="fa-solid fa-user-tie me-1"></i> HR / User</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-secondary small"><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                    <td class="text-end pe-4">
                        <!-- ปุ่มแก้ไข -->
                        <button type="button" class="btn btn-sm btn-light text-primary border me-1" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['id']; ?>" title="แก้ไขข้อมูล">
                            <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                        </button>

                        <!-- ปุ่มลบ -->
                        <?php if($user['id'] != $_SESSION['user_id']): ?>
                            <a href="index.php?action=users_delete&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-light text-danger border" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบบัญชีผู้ใช้ <?php echo htmlspecialchars($user['username']); ?>?');" title="ลบบัญชี">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-sm btn-light text-muted border disabled"><i class="fa-solid fa-trash"></i></button>
                        <?php endif; ?>
                    </td>
                </tr>

                <!-- Modal สำหรับแก้ไขผู้ใช้งาน (ถูกสร้างขึ้นมาสำหรับแต่ละคน) -->
                <div class="modal fade" id="editUserModal<?php echo $user['id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content rounded-4 border-0 shadow">
                            <div class="modal-header bg-light border-bottom-0 pb-0 pt-4 px-4">
                                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-user-pen text-primary me-2"></i> แก้ไขข้อมูลผู้ใช้งาน</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="index.php?action=users_update" method="POST">
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted small text-uppercase">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control bg-light" name="full_name" required value="<?php echo htmlspecialchars($user['full_name']); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted small text-uppercase">ชื่อผู้ใช้ (Username) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control bg-light" name="username" required value="<?php echo htmlspecialchars($user['username']); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted small text-uppercase">รหัสผ่านใหม่ <span class="text-primary fw-normal">(ปล่อยว่างไว้หากไม่ต้องการเปลี่ยน)</span></label>
                                        <input type="password" class="form-control bg-light" name="password" minlength="6" placeholder="พิมพ์รหัสผ่านใหม่ที่นี่">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted small text-uppercase">สิทธิ์การใช้งาน (Role)</label>
                                        <select class="form-select bg-light" name="role" required>
                                            <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>เจ้าหน้าที่ HR ทั่วไป (User)</option>
                                            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>ผู้ดูแลระบบ (Admin)</option>
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

<!-- Modal สำหรับเพิ่มผู้ใช้งาน (อันเดิม) -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="addUserModalLabel"><i class="fa-solid fa-user-plus text-teal me-2" style="color: #0d9488;"></i> เพิ่มบัญชีผู้ใช้งาน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?action=users_store" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light" name="full_name" required placeholder="เช่น สมชาย ใจดี">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">ชื่อผู้ใช้ (Username) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light" name="username" required placeholder="ใช้สำหรับ Login">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">รหัสผ่าน <span class="text-danger">*</span></label>
                        <input type="password" class="form-control bg-light" name="password" required minlength="6" placeholder="อย่างน้อย 6 ตัวอักษร">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">สิทธิ์การใช้งาน (Role)</label>
                        <select class="form-select bg-light" name="role" required>
                            <option value="user">เจ้าหน้าที่ HR ทั่วไป (User)</option>
                            <option value="admin">ผู้ดูแลระบบ (Admin)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light border fw-bold" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn text-white fw-bold shadow-sm" style="background-color: #0d9488;">บันทึกบัญชีใหม่</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>