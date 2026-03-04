<!-- 
==========================================
ชื่อไฟล์: index.php
ที่อยู่ไฟล์: views/menu/index.php
==========================================
-->
<?php include 'views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-bars-staggered text-warning me-2"></i> จัดการเมนูระบบ</h2>
        <p class="text-muted mt-1 mb-0">ตั้งค่าการเปิด/ปิด การแสดงผลเมนูในแถบด้านข้าง (Sidebar)</p>
    </div>
    <div>
        <a href="index.php?action=settings" class="btn btn-light border shadow-sm text-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> กลับหน้าตั้งค่า
        </a>
    </div>
</div>

<div class="modern-card p-0 overflow-hidden shadow-sm border-0 mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3">ลำดับ</th>
                    <th class="py-3">ชื่อเมนู</th>
                    <th class="py-3 text-center">ไอคอน (Icon)</th>
                    <th class="py-3 text-center">ลิงก์ภายใน (Action)</th>
                    <th class="text-end pe-5 py-3">สถานะ (เปิด/ปิด)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menus as $index => $menu): ?>
                <tr>
                    <td class="ps-4 fw-bold text-muted"><?php echo $menu['sort_order']; ?></td>
                    <td class="fw-bold text-dark">
                        <i class="fa-solid <?php echo htmlspecialchars($menu['icon']); ?> text-secondary me-2"></i> 
                        <?php echo htmlspecialchars($menu['menu_name']); ?>
                    </td>
                    <td class="text-center text-muted">
                        <code><?php echo htmlspecialchars($menu['icon']); ?></code>
                    </td>
                    <td class="text-center text-muted">
                        <code>?action=<?php echo htmlspecialchars($menu['action_name']); ?></code>
                    </td>
                    <td class="text-end pe-5">
                        <!-- ปุ่ม Switch สลับสถานะ -->
                        <div class="form-check form-switch fs-4 d-flex justify-content-end mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" style="cursor: pointer;"
                                   onchange="window.location.href='index.php?action=menus_toggle&id=<?php echo $menu['id']; ?>'" 
                                   <?php echo $menu['is_active'] == '1' ? 'checked' : ''; ?>>
                        </div>
                        <div class="small <?php echo $menu['is_active'] == '1' ? 'text-success' : 'text-danger'; ?> mt-1 fw-bold">
                            <?php echo $menu['is_active'] == '1' ? 'เปิดใช้งาน' : 'ซ่อนเมนู'; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="alert alert-info border-0 shadow-sm d-flex align-items-center">
    <i class="fa-solid fa-circle-info fs-4 me-3 text-info"></i>
    <div>
        <strong>หมายเหตุ:</strong> การปิดสถานะเมนู จะทำให้ผู้ใช้งานมองไม่เห็นเมนูนั้นๆ ในแถบด้านซ้ายมือ (Sidebar) แต่หากผู้ใช้รู้ URL ก็ยังสามารถเข้าถึงหน้านั้นได้ (สำหรับซ่อนเมนูชั่วคราว)
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>