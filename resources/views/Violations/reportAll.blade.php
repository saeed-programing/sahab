<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>گزارش موارد انضباطی</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-Xbg45MqvDIk1e563NLpGEulpX6AvL404DP+/iCgW9eFa2BqztiwTexswJo2jLMue" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />

    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables core -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css" />
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

    <!-- Responsive -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.dataTables.min.css" />
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.min.js"></script>

    <!-- Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
</head>

<body>
    <div class="d-flex justify-content-center mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-2">بازگشت به داشبورد</a>
        <a href="{{ route('student-violations.index') }}" class="btn btn-outline-primary">بازگشت به صفحه اصلی تخلفات</a>
    </div>
    <div>
        <table id="reportTable" class="display nowrap" style="width: 100%">
            <thead>
                <th scope="col">نام خانوادگی</th>
                <th scope="col">نام</th>
                <th scope="col">کلاس</th>
                <th scope="col">تاریخ</th>
                <th scope="col">عنوان</th>
                <th scope="col">توضیحات</th>
                <th scope="col">ثبت کننده</th>
                <tr>
                    <!-- ردیف دوم: فیلد جستجو برای هر ستون -->
                    <th><input type="text" placeholder="جستجوی نام خانوادگی" /></th>
                    <th><input type="text" placeholder="جستجوی نام" /></th>
                    <th><input type="text" placeholder="جستجوی کلاس" /></th>
                    <th><input type="text" placeholder="جستجوی تاریخ" /></th>
                    <th><input type="text" placeholder="جستجوی عنوان" /></th>
                    <th><input type="text" placeholder="توضیحات" /></th>
                    <th><input type="text" placeholder="ثبت کننده" /></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allViolations as $violation)
                    <tr>
                        <td>{{ $violation->student->family ?? '—' }}</td>
                        <td>{{ $violation->student->name ?? '—' }}</td>
                        <td>{{ $violation->student->schoolClass->name ?? '—' }}</td>
                        <td>{{ toJalali($violation->date) }}</td>
                        <td>{{ $violation->case->title ?? '—' }}</td>
                        <td>{{ $violation->description ?? '—' }}</td>
                        <td>{{ $violation->register->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // ساخت دیتاتیبل
            const table = $("#reportTable").DataTable({
                responsive: true,
                orderCellsTop: true, // مهم برای قرار دادن فیلد جستجو در بالای جدول
                fixedHeader: true,
                dom: "Bfrtip",
                buttons: [{
                        extend: "copyHtml5",
                        text: "کپی"
                    },
                    {
                        extend: "csvHtml5",
                        text: "CSV"
                    },
                    {
                        extend: "excelHtml5",
                        text: "Excel"
                    },
                    {
                        extend: "pdfHtml5",
                        text: "PDF"
                    },
                    {
                        extend: "print",
                        text: "چاپ"
                    },
                ],
                language: {
                    search: "جستجوی کلی:",
                    lengthMenu: "نمایش _MENU_ ردیف",
                    info: "نمایش _START_ تا _END_ از _TOTAL_ ردیف",
                    paginate: {
                        first: "اول",
                        last: "آخر",
                        next: "بعدی",
                        previous: "قبلی",
                    },
                    emptyTable: "داده‌ای وجود ندارد",
                },
            });

            // جستجو در هر ستون (زنده)
            $("#reportTable thead tr:eq(1) th").each(function(i) {
                $("input", this).on("keyup change", function() {
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
            });
        });
    </script>
</body>

</html>
