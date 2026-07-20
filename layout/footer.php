<!--begin::Footer-->
      <footer class="app-footer d-flex justify-content-between">
        <strong>
          Copyright &copy; 2026;
          <a href="" class="text-decoration-none">Revita Gadis</a>. All rights reserved.
        </strong>
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.1.0/dist/js/adminlte.min.js"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');
        const isMobile = window.innerWidth <= 992;
        if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined && !isMobile) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: { theme: 'os-theme-light', autoHide: 'leave', clickScroll: true },
          });
        }
      });
    </script>

    <!--begin::jQuery + DataTables (dipakai untuk tabel Barang/Mahasiswa/Akun)-->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.8/js/dataTables.bootstrap5.js"></script>
    <!--end::jQuery + DataTables-->

    <script>
      new DataTable('#tabel');
    </script>

      <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
      <script>
        CKEDITOR.replace('alamat', {
          filebrowserBrowseUrl: 'assets/ckfinder/ckfinder.html',
          filebrowserUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
          height: '400px'
        });
      </script>
  </body>
</html>