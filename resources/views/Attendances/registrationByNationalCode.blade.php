@extends('layout.master')

@section('title', 'Attendance registration')

@section('body')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">حضور غیاب - {{ toJalali($day) }}</h4>
        <div>
            <a href="{{ route('attendances.registration.ByStudentCode', $day) }}" class="btn btn-outline-secondary">حضور غیاب
                با کد دانش آموزی</a>
            <a href="{{ route('attendances.index') }}" class="btn btn-outline-primary">رفتن به صفحه اصلی</a>
        </div>
    </div>


    <!-- فرم جستجو -->
    <div class="form-container">
        <form id="infoForm">
            <div class="mb-3">
                <label for="national_id" class="form-label">کد ملی</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="national_id" name="national_id" inputmode="numeric"
                        maxlength="10" placeholder="کد ملی ۱۰ رقمی را اسکن یا وارد کنید">
                    <button type="button" class="btn btn-outline-primary" id="scanBtn" title="اسکن بارکد">
                        <i class="bi bi-upc-scan fs-4"></i>
                    </button>

                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">جستجو</button>
        </form>
    </div>

    <!-- مودال برای نمایش اطلاعات کاربر -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">اطلاعات کاربر</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formConfirm" method="post" action="{{ route('confirm.student') }}">
                    @csrf
                    <div class="modal-body" id="userInfoBody">
                        <!-- اطلاعات کاربر به صورت داینامیک نمایش داده می‌شود -->

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="confirmBtn">تأیید و ثبت تغییر</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- مودال اسکن بارکد -->
    <div class="modal fade" id="scanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">اسکن بارکد کدملی</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="scanner-container" style="width: 100%; height: 300px; margin:auto;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast برای نمایش پیام‌های خطا و موفقیت -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
        <div id="toastMsg" class="toast align-items-center text-bg-primary border-0">
            <div class="d-flex">
                <div class="toast-body" id="toastBody">پیغام نمونه</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>


@endsection

@section('link')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const form = document.getElementById('infoForm');
        const userModal = new bootstrap.Modal(document.getElementById('userModal'));

        const toast = new bootstrap.Toast(document.getElementById('toastMsg'), {
            delay: 5000
        });
        const toastBody = document.getElementById('toastBody');

        const scanModalEl = document.getElementById('scanModal');
        const scanModal = new bootstrap.Modal(scanModalEl);
        const scanBtn = document.getElementById('scanBtn');

        const nationalInput = document.getElementById('national_id');
        const descriptionInput = document.getElementById('description');

        // فوکوس خودکار روی اینپوت کدملی
        window.addEventListener('load', () => nationalInput.focus());

        // فوکوس خودکار روی اینپوت توضیحات در مودال
        document.getElementById('userModal').addEventListener('shown.bs.modal', () => {
            const descriptionInput = document.getElementById('description');
            if (descriptionInput) {
                descriptionInput.focus();
            }
        });


        //سابمیت خودکار فرم بعد از وارد کردن کدملی
        let submitting = false;

        nationalInput.addEventListener('input', () => {
            // حذف فاصله و کاراکترهای غیرعددی
            const value = nationalInput.value.replace(/\D/g, '');
            nationalInput.value = value;

            if (submitting) return;

            // اگر طول کد ملی رسید به ۱۰ رقم
            if (value.length === 10) {
                // بررسی صحت فرمت کد ملی
                if (/^\d{10}$/.test(value)) {
                    // سابمیت خودکار
                    submitting = true;
                    form.requestSubmit();
                } else {
                    showToast('فرمت کد ملی صحیح نیست', 'warning');
                }
            }
        });

        // بررسی نوع سیستم برای نمایش یا عدم نمایش دکمه اسکنر
        function isMobileOrTablet() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }
        if (!isMobileOrTablet()) {
            scanBtn.style.display = 'none';
        } else {
            scanBtn.style.display = 'inline-flex';
        }

        scanBtn.addEventListener('click', () => {
            scanModal.show();
        });

        /* شروع اسکنر بعد از باز شدن مودال */
        let html5QrScanner = null;

        scanModalEl.addEventListener('shown.bs.modal', () => {
            if (!html5QrScanner) {
                html5QrScanner = new Html5Qrcode("scanner-container");

                html5QrScanner.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: 250
                    },
                    (decodedText) => {
                        // فقط اعداد ۱۰ رقمی کد ملی
                        if (/^\d{10}$/.test(decodedText)) {
                            nationalInput.value = decodedText;
                            html5QrScanner.stop().then(() => {
                                scanModal.hide();
                                form.requestSubmit();
                            }).catch(err => console.error(err));
                        }
                    },
                    (errorMessage) => {
                        // اسکن ناموفق هر frame
                    }
                ).catch(err => {
                    showToast('دسترسی به دوربین امکان‌پذیر نیست', 'danger');
                    html5QrScanner.clear().catch(err => console.error(err));
                    html5QrScanner = null;
                    document.getElementById("scanner-container").innerHTML = '';
                    scanModal.hide();
                });
            }
        });

        /* توقف اسکنر بعد از بستن مودال */
        scanModalEl.addEventListener('hidden.bs.modal', () => {
            if (html5QrScanner) {
                html5QrScanner.stop().catch(() => {});
                html5QrScanner = null;
                document.getElementById("scanner-container").innerHTML = '';
            }
        });

        // ارسال فرم
        form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const data = Object.fromEntries(new FormData(form).entries());

                // اطمینان از انگلیسی بودن اعدادی که ارسال می شوند
                data.national_id = convertPersianDigits(data.national_id);


                if (!data.national_id) {
                    return showToast('فیلد کدملی را کامل کنید', 'danger');
                }

                if (!/^\d{10}$/.test(data.national_id)) {
                    return showToast('فرمت کد ملی وارد شده صحیح نیست', 'danger');
                }
                try {
                    // ارسال درخواست به سرور
                    const searchUrl = "{{ route('search.student.ByNationalCode') }}"
                    const res = await fetch(searchUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });
                    // دریافت پاسخ JSON
                    const result = await res.json();

                    // بررسی وضعیت موفقیت
                    let currentUser = null;
                    let delayMinutes = 0;

                    if (result.status === 'success' && result.user) {
                        currentUser = result.user;

                        // محاسبه تاخیر
                        const startTime = "7:20";
                        const arrivalTime = getCurrentTime();
                        delayMinutes = calculateDelay(startTime, arrivalTime);

                        // ساخت بخش تاخیر
                        let delaySection = '';
                        if (delayMinutes <= 0) {
                            // حالت بدون تأخیر
                            delaySection = `<p><strong>تأخیر :</strong> ${delayMinutes} دقیقه</p>`;
                        } else if (delayMinutes >= 1 && delayMinutes <= 10) {
                            // حالت 1: تأخیر 1 تا 10 دقیقه
                            delaySection = `
                    <div class="mb-3">
                        <p><strong>تأخیر :</strong> ${delayMinutes} دقیقه</p>
                        <input type="hidden" name="delay" value="${delayMinutes}">
                    </div>

                    <div class="form-check mb-3">
                        <input type="hidden" name="is_excused" value=false>
                        <input type="checkbox" name="is_excused" id="delayJustified" class="form-check-input" value=true>
                        <label class="form-check-label" for="delayJustified">تأخیر موجه است</label>
                    </div>
                    `;
                        } else {
                            // حالت 2: تأخیر 11 دقیقه به بالا
                            delaySection = `
                    <div class="mb-3">
                        <p><strong>تأخیر :</strong> ${delayMinutes} دقیقه</p>
                        <input type="hidden" name="delay" value="${delayMinutes}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label"><strong>توضیحات:</strong></label>
                        <textarea id="description" name="description" required class="form-control" rows="3"
                            placeholder="دلیل تأخیر را بنویسید..."></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input type="hidden" name="is_excused" value=false>
                        <input type="checkbox" name="is_excused" id="delayJustified" class="form-check-input" value=true>
                        <label class="form-check-label" for="delayJustified">تأخیر موجه است</label>
                    </div>
                    `;
                        }

                        //     if (delayMinutes <= 10) {
                        //         delaySection = `
                    // <div class="mb-3">
                    //     <p><strong>تأخیر :</strong> ${delayMinutes} دقیقه</p>
                    //     <input type="hidden" name="delay" value="${delayMinutes}">
                    // </div>

                    // <div class="form-check mb-3">
                    //     <input type="hidden" name="is_excused" value=false>
                    //     <input type="checkbox" name="is_excused" id="delayJustified" class="form-check-input" value=true>
                    //     <label class="form-check-label" for="delayJustified">تأخیر موجه است</label>
                    // </div>
                    // `;
                        //     } else if (delayMinutes > 10) {
                        //         delaySection = `
                    // <div class="mb-3">
                    //     <p><strong>تأخیر :</strong> ${delayMinutes} دقیقه</p>
                    //     <input type="hidden" name="delay" value="${delayMinutes}">
                    // </div>
                    // <div class="mb-3">
                    //     <label for="description" class="form-label"><strong>توضیحات:</strong></label>
                    //     <textarea id="description" name="description" required class="form-control" rows="3"
                    //         placeholder="دلیل تأخیر را بنویسید..."></textarea>
                    // </div>
                    // <div class="form-check mb-3">
                    //     <input type="hidden" name="is_excused" value=false>
                    //     <input type="checkbox" name="is_excused" id="delayJustified" class="form-check-input" value=true>
                    //     <label class="form-check-label" for="delayJustified">تأخیر موجه است</label>
                    // </div>
                    // `;
                        //     } else {
                        //         delaySection = `<p><strong>تأخیر :</strong> ${delayMinutes} دقیقه</p>`;
                        //     }

                        // نمایش اطلاعات کاربر در modal
                        document.getElementById('userInfoBody').innerHTML = `
    <div class="row">
        <div class="col-6">
            <input type="hidden" name="student_id" value="${currentUser.id}">
            <input type="hidden" name="date" value={{ $day }}>

            <p><strong>نام:</strong> ${currentUser.name}</p>
            <p><strong>نام خانوادگی:</strong> ${currentUser.family}</p>
            <p><strong>کد ملی:</strong> ${currentUser.national_code}</p>
        </div>
        <div class="col-6">
            <p><strong>تاریخ روز:</strong> {{ toJalali($day) }}</p>
            <input type="hidden" name="date" value="{{ $day }}">
            <p><strong>ساعت ثبت:</strong> ${getCurrentTime()}</p>
            ${delaySection}
        </div>
    </div>
    `;

                        // نمایش modal
                        userModal.show();

                    } else {
                        showToast(result.message || 'کاربری یافت نشد', 'danger');
                    }

                } catch (error) {
                    console.error('خطا در fetch:', error);
                    showToast('خطا در ارتباط با سرور', 'danger');
                } finally {}
                submitting = false;
            }


        );

        // نمایش Toast
        function showToast(message, type = 'primary') {
            toastBody.innerText = message;
            document.getElementById('toastMsg').className = `toast text-bg-${type} show`;
            toast.show();
        }


        // تابع گرفتن تاریخ شمسی
        function getPersianDate(showWeekday = true) {
            const today = new Date();

            // تنظیم فرمت فارسی برای تقویم شمسی
            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                calendar: 'persian'
            };

            // ساخت فرمت فارسی
            const persianDate = new Intl.DateTimeFormat('fa-IR-u-nu-latn', options).format(today);
            // خروجی بالا شبیه "1404/07/16" خواهد بود

            if (showWeekday) {
                const weekday = new Intl.DateTimeFormat('fa-IR', {
                    weekday: 'long',
                    calendar: 'persian'
                }).format(today);
                return `${persianDate} (${weekday})`;
            }

            return persianDate;
        }


        // تابع گرفتن ساعت فعلی
        function getCurrentTime() {
            const today = new Date();
            return today.toLocaleTimeString('fa-IR', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }


        // تبدیل اعداد فارسی زمان فعلی به انگلیسی
        function convertPersianDigits(str) {
            return str.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
        }

        // تابع محاسبه تاخیر به دقیقه
        function calculateDelay(startTime, arrivalTime) {
            // حذف ثانیه‌ها
            let arrival = arrivalTime.split(':').slice(0, 2).join(':');

            // تبدیل اعداد فارسی به انگلیسی
            startTime = convertPersianDigits(startTime);
            arrival = convertPersianDigits(arrival);

            const [startHour, startMinute] = startTime.split(':').map(Number);
            const [arrivalHour, arrivalMinute] = arrival.split(':').map(Number);

            const startTotal = startHour * 60 + startMinute;
            const arrivalTotal = arrivalHour * 60 + arrivalMinute;

            const diff = arrivalTotal - startTotal;
            return diff > 0 ? diff : 0;
        }
    </script>

    {{-- auto refresh  --}}
    <script>
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>

@endsection
