<?php include 'views/layout/header.php'; ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-0">ทะเบียนประวัติบุคลากร</h2>
        <p class="text-muted">ค้นหาและจัดการข้อมูลประวัติข้าราชการและพนักงาน</p>
    </div>
    <div class="d-flex gap-2">
        <!-- ปุ่มส่งออกเป็น Excel -->
        <a href="index.php?action=export_excel" class="btn btn-light border shadow-sm fw-semibold text-secondary" title="ส่งออกข้อมูลทั้งหมดเป็นไฟล์ Excel">
            <i class="fa-solid fa-file-excel text-success me-1"></i> ส่งออก Excel
        </a>
        
        <!-- ปุ่มเพิ่มบุคลากร -->
        <a href="index.php?action=create" class="btn text-white fw-semibold shadow-sm" style="background: linear-gradient(to right, #14b8a6, #10b981);">
            <i class="fa-solid fa-plus me-1"></i> เพิ่มบุคลากร
        </a>
    </div>
</div>

<div class="modern-card">
    
    <!-- ฟอร์มค้นหาข้อมูล -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-4">
            <form action="index.php" method="GET" class="d-flex gap-2">
                <input type="hidden" name="action" value="employees">
                <div class="input-group shadow-sm border-0 rounded-3">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control border-start-0" name="search" placeholder="ค้นหา ชื่อ, นามสกุล, ตำแหน่ง..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button class="btn btn-outline-secondary" type="submit">ค้นหา</button>
                </div>
                <?php if(!empty($search_term)): ?>
                    <a href="index.php?action=employees" class="btn btn-light border" title="ล้างการค้นหา"><i class="fa-solid fa-xmark text-danger"></i></a>
                <?php endif; ?>
            </form>
        </div>
        <div class="col-md-6 col-lg-8 text-md-end mt-2 mt-md-0 pt-2">
            <span class="text-muted small fw-bold">พบข้อมูลทั้งหมด: <span class="text-teal"><?php echo $total_rows; ?></span> รายการ</span>
        </div>
    </div>

    <!-- ตารางรายชื่อ -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="bg-white">
                <tr>
                    <th class="ps-3">ข้อมูลบุคลากร</th>
                    <th>สายงาน / ระดับ</th>
                    <th>สังกัด</th>
                    <th class="text-center">สถานะ</th>
                    <th class="text-end pe-3">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($employees as $emp): ?>
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($emp['first_name']); ?>&background=14b8a6&color=fff" class="rounded-circle me-3" width="45" height="45">
                            <div>
                                <div class="fw-bold text-dark"><?php echo $emp['prefix'] . $emp['first_name'] . ' ' . $emp['last_name']; ?></div>
                                <div class="text-muted" style="font-size: 0.8rem;"><?php echo $emp['emp_code']; ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark"><?php echo $emp['position']; ?></div>
                        <div class="text-muted" style="font-size: 0.8rem;"><span class="text-teal" style="color: #0d9488;"><?php echo $emp['level'] ? $emp['level'] : '-'; ?></span> | <?php echo $emp['employee_type']; ?></div>
                    </td>
                    <td>
                        <div class="text-secondary fw-medium"><?php echo $emp['department']; ?></div>
                    </td>
                    <td class="text-center">
                        <span class="badge rounded-pill px-3 py-2 <?php echo $emp['status'] == 'ปฏิบัติงาน' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle'; ?>">
                            <?php echo $emp['status']; ?>
                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <a href="index.php?action=show&id=<?php echo $emp['id']; ?>" class="btn btn-sm btn-light text-teal border border-teal-200 fw-bold" style="color: #0d9488; background-color:#f0fdfa;" title="ดูประวัติ">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="index.php?action=edit&id=<?php echo $emp['id']; ?>" class="btn btn-sm btn-light text-primary border" title="แก้ไขข้อมูล">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="index.php?action=delete&id=<?php echo $emp['id']; ?>" class="btn btn-sm btn-light text-danger border" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลของคุณ <?php echo $emp['first_name']; ?>?');" title="ลบข้อมูล">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if(empty($employees)): ?>
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-folder-open fs-1 text-light mb-3 d-block"></i>
                        ไม่พบข้อมูลบุคลากรที่คุณค้นหา
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ส่วนแบ่งหน้า (Pagination) -->
    <?php if($total_pages > 1): ?>
    <?php 
        // สร้าง String สำหรับแนบค่าการค้นหาไปกับลิงก์แบ่งหน้า (เพื่อให้ตอนกดหน้า 2 ยังจำคำค้นหาไว้ได้)
        $search_query = !empty($search_term) ? "&search=" . urlencode($search_term) : ""; 
    ?>
    <nav aria-label="Page navigation" class="mt-4 pt-3 border-top">
        <ul class="pagination justify-content-center mb-0">
            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link text-teal" href="index.php?action=employees&page=<?php echo $page - 1; ?><?php echo $search_query; ?>">ก่อนหน้า</a>
            </li>
            
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                    <a class="page-link <?php echo $page == $i ? 'text-white' : 'text-teal'; ?>" 
                       style="<?php echo $page == $i ? 'background-color: #0d9488; border-color: #0d9488;' : ''; ?>"
                       href="index.php?action=employees&page=<?php echo $i; ?><?php echo $search_query; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            
            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                <a class="page-link text-teal" href="index.php?action=employees&page=<?php echo $page + 1; ?><?php echo $search_query; ?>">ถัดไป</a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>