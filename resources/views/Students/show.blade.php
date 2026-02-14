@extends('layout.master')

@section('title', 'Show Student')

@section('link')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>


    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <!-- Export libs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    {{-- PDF Export --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <!-- Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@endsection


@section('body')
    <div style="margin-right: 150px">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="fw-bold">دانش آموز: {{ $student->name . ' ' . $student->family }}</h4>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="studentTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#base-info">اطلاعات
                            اولیه</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contact-info">اطلاعات
                            ارتباطی</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#other-info">سایر اطلاعات</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#overview">نمای کلی</button>
                    </li>
                </ul>
            </div>

            <div class="card-body tab-content">

                <!-- اطلاعات اولیه -->
                <div class="tab-pane fade show active" id="base-info">
                    <div class="row g-3">
                        <div class="col-md-3 text-center">
                            <img src="{{ $student->image == 'default.png' ? asset('images/default.png') : asset('images/students/' . $student->image) }}"
                                width="150" class="img-thumbnail mb-2" alt="student">
                        </div>

                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">نام</label>
                                    <input disabled class="form-control" value="{{ $student->name }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">نام خانوادگی</label>
                                    <input disabled class="form-control" value="{{ $student->family }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">کد ملی</label>
                                    <input disabled class="form-control" value="{{ $student->national_code }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">کد دانش‌آموزی</label>
                                    <input disabled class="form-control" value="{{ $student->student_code }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">کلاس</label>
                                    <input disabled class="form-control"
                                        value="{{ $student->schoolClass->name ?? 'درانتظار کلاس بندی' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">استاد راهنما</label>
                                    <input disabled class="form-control"
                                        value="{{ $student->schoolClass->teacher->name ?? '-' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- اطلاعات ارتباطی -->
                <div class="tab-pane fade" id="contact-info">
                    @if ($phones->isEmpty())
                        <div class="alert alert-danger">
                            برای این دانش آموز، شماره ای ثبت نشده است.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>متعلق به</th>
                                        <th>شماره</th>
                                        <th>فقط مجازی</th>
                                        <th>توضیحات</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($phones as $phone)
                                        <tr>
                                            <td>{{ $phone->phone_for_label }}</td>
                                            <td>{{ $phone->phone_num }}</td>
                                            <td>{{ $phone->is_just_virtual ? 'بله' : 'خیر' }}</td>
                                            <td>{{ $phone->description ?? ' - ' }}</td>
                                            <td>
                                                <a class="btn btn-secondary btn-sm" href="#">ارسال پیامک</a>
                                                <a class="btn btn-secondary btn-sm" href="#">تماس</a>
                                                <a class="btn btn-secondary btn-sm" href="#">کپی</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>


                <!-- سایر اطلاعات -->
                <div class="tab-pane fade" id="other-info">
                    @if ($profile == null)
                        <div class="alert alert-danger">
                            برای این دانش آموز، اطلاعاتی ثبت نشده است. <a href="#">ثبت اطلاعات</a>
                        </div>
                    @else
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">نام پدر</label>
                                <input disabled class="form-control" value="{{ $profile->father_name }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">مدرسه قبلی</label>
                                <input disabled class="form-control" value="{{ $profile->previous_school }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">تاریخ تولد</label>
                                <input disabled class="form-control" value="{{ toJalali($profile->date_of_birth) }}">
                            </div>
                        </div>
                    @endif

                </div>

                <!-- نمای کلی -->
                <div class="tab-pane fade" id="overview">
                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <button disabled id="excelBtn" class="btn btn-success btn-sm">خروجی Excel</button>
                        <button id="pdfBtn" class="btn btn-danger btn-sm">خروجی PDF</button>
                        <button disabled id="printBtn" class="btn btn-secondary btn-sm">چاپ</button>
                    </div>
                    <div id="exportArea">
                        <h5 class="fw-bold ">{{ $student->family . ' - ' . $student->name }}</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>مجموع تأخیرها</th>
                                        <th>مجموع غیبت‌ها</th>
                                        <th>تعداد موارد انضباطی</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $generals['total_delays'] }}</td>
                                        <td>{{ $generals['total_absences'] }}</td>
                                        <td>{{ $generals['total_violations'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h5 class="fw-bold mt-3">گزارش جزئی غیبت ها</h5>
                        <div class="table-responsive">
                            @if ($generals['absents']->isEmpty())
                                <div class="alert alert-success">بدون غیبت</div>
                            @else
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ردیف</th>
                                            <th>تاریخ</th>
                                            <th>موجه/غیرموجه</th>
                                            <th>توضیحات</th>
                                            <th>ثبت کننده</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($generals['absents'] as $absent)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ toJalali($absent->date) }}</td>
                                                <td>{{ $absent->is_excused ? 'موجه' : 'غیرموجه' }}</td>
                                                <td>{{ $absent->description ?? '-' }}</td>
                                                <td>{{ $absent->register->name ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                        </div>

                        <h5 class="fw-bold mt-3">گزارش جزئی تاخیر ها</h5>
                        <div class="table-responsive">
                            @if ($generals['delays']->isEmpty())
                                <div class="alert alert-success">بدون تاخیر</div>
                            @else
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ردیف</th>
                                            <th>تاریخ</th>
                                            <th>موجه/غیرموجه</th>
                                            <th>توضیحات</th>
                                            <th>ثبت کننده</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($generals['delays'] as $delay)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ toJalali($delay->date) }}</td>
                                                <td>{{ $delay->is_excused ? 'موجه' : 'غیرموجه' }}</td>
                                                <td>{{ $delay->description ?? '-' }}</td>
                                                <td>{{ $delay->register->name ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <h5 class="fw-bold mt-3">گزارش جزئی تخلفات</h5>
                        <div class="table-responsive">
                            @if ($generals['violations']->isEmpty())
                                <div class="alert alert-success">بدون تخلف انضباطی</div>
                            @else
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ردیف</th>
                                            <th>عنوان تخلف</th>
                                            <th>تاریخ</th>
                                            <th>توضیحات</th>
                                            <th>ثبت کننده</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($generals['violations'] as $violation)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $violation->case->title }}</td>
                                                <td>{{ toJalali($violation->date) }}</td>
                                                <td>{{ $violation->description ?? '-' }}</td>
                                                <td>{{ $violation->register->name ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('script')
    {{-- PDF Export --}}
    <script>
        document.getElementById('pdfBtn').addEventListener('click', function() {

            html2pdf().set({
                margin: 0.5,
                filename: '{{ $student->name . '-' . $student->family }}.pdf',
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'cm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            }).from(document.getElementById('exportArea')).save().then(() => {
                document.querySelectorAll('.no-export').forEach(el => el.style.display = '');
            });

        });
    </script>

    {{-- Exel Export --}}
    <script>
        document.getElementById('excelBtn').addEventListener('click', function() {

            let wb = XLSX.utils.book_new();

            document.querySelectorAll('#exportArea table').forEach((table, index) => {
                let ws = XLSX.utils.table_to_sheet(table);
                XLSX.utils.book_append_sheet(wb, ws, 'گزارش ' + (index + 1));
            });

            XLSX.writeFile(wb, 'student-report.xlsx');
        });
    </script>

    {{-- Print Export --}}
    <script>
        document.getElementById('printBtn').addEventListener('click', function() {

            let printContent = document.getElementById('exportArea').innerHTML;
            let win = window.open('', '', 'width=900,height=700');

            win.document.write(`
<html dir="rtl">
<head>
    <title>چاپ گزارش</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.rtl.min.css">
</head>
<body class="p-3">
    ${printContent}
</body>
</html>
`);


            win.document.close();
            win.focus();
            win.print();
            win.close();
        });
    </script>
@endsection
