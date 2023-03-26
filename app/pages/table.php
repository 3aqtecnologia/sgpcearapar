        <div class="card pd-20 pd-sm-40">
          <h6 class="card-body-title">Basic Responsive DataTable</h6>
          <p class="mg-b-20 mg-sm-b-30">Searching, ordering and paging goodness will be immediately added to the table, as shown in this example.</p>

          <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-15p">First name</th>
                  <th class="wd-15p">Last name</th>
                  <th class="wd-20p">Position</th>
                  <th class="wd-15p">Start date</th>
                  <th class="wd-10p">Salary</th>
                  <th class="wd-25p">E-mail</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Tiger</td>
                  <td>Nixon</td>
                  <td>System Architect</td>
                  <td>2011/04/25</td>
                  <td>$320,800</td>
                  <td>t.nixon@datatables.net</td>
                </tr>
                <tr>
                  <td>Garrett</td>
                  <td>Winters</td>
                  <td>Accountant</td>
                  <td>2011/07/25</td>
                  <td>$170,750</td>
                  <td>g.winters@datatables.net</td>
                </tr>

              </tbody>
            </table>
          </div><!-- table-wrapper -->
        </div><!-- card -->

        <script src="../lib/jquery/jquery.js"></script>
        <script src="../lib/popper.js/popper.js"></script>
        <script src="../lib/bootstrap/bootstrap.js"></script>
        <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
        <script src="../lib/jquery-toggles/toggles.min.js"></script>
        <script src="../lib/highlightjs/highlight.pack.js"></script>
        <script src="../lib/datatables/jquery.dataTables.js"></script>
        <script src="../lib/datatables-responsive/dataTables.responsive.js"></script>
        <script src="../lib/select2/js/select2.min.js"></script>

        <script src="../js/amanda.js"></script>
        <script>
          $(function() {
            'use strict';

            $('#datatable1').DataTable({
              responsive: true,
              language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
              }
            });

            $('#datatable2').DataTable({
              bLengthChange: false,
              searching: false,
              responsive: true
            });

            // Select2
            $('.dataTables_length select').select2({
              minimumResultsForSearch: Infinity
            });

          });
        </script>
        </body>

        </html>
