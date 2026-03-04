<!-- 🌟 เนื้อหาแต่ละหน้าจะถูกแทรกด้านบนบรรทัดนี้ 🌟 -->
            
            </div> <!-- End main-container -->
        </div> <!-- End page-content -->
    </div> <!-- End wrapper -->

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script ควบคุมระบบทั้งหมด -->
    <script>
        // 1. ==========================================
        // แก้ปัญหา Bootstrap Modal จอเทา/กดไม่ได้
        // โดยการย้าย Modal ทุกตัวในหน้าไปไว้ที่ <body> ชั้นนอกสุด
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                document.body.appendChild(modal);
            });
        });

        // 2. ==========================================
        // โหลดสถานะเมนูจาก LocalStorage (ความจำของเบราว์เซอร์)
        // ==========================================
        document.addEventListener('DOMContentLoaded', () => {
            if (window.innerWidth > 992) {
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    document.getElementById('sidebar').classList.add('collapsed');
                    document.getElementById('page-content').classList.add('expanded');
                }
            }
        });

        // 3. ==========================================
        // ฟังก์ชันสลับการ ย่อ/ขยาย Sidebar
        // ==========================================
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const pageContent = document.getElementById('page-content');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth <= 992) {
                // สำหรับมือถือ/แท็บเล็ต
                sidebar.classList.toggle('mobile-show');
                overlay.classList.toggle('mobile-show');
            } else {
                // สำหรับคอมพิวเตอร์ Desktop
                sidebar.classList.toggle('collapsed');
                pageContent.classList.toggle('expanded');
                
                // บันทึกสถานะลงใน Browser
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            }
        }

        // จัดการเมื่อมีการดึงขอบหน้าจอ (Resize)
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const pageContent = document.getElementById('page-content');
            const overlay = document.getElementById('sidebar-overlay');

            if (window.innerWidth > 992) {
                sidebar.classList.remove('mobile-show');
                overlay.classList.remove('mobile-show');
                
                if(localStorage.getItem('sidebarCollapsed') === 'true') {
                    sidebar.classList.add('collapsed');
                    pageContent.classList.add('expanded');
                }
            } else {
                sidebar.classList.remove('collapsed');
                pageContent.classList.remove('expanded');
            }
        });
    </script>

</body>
</html>