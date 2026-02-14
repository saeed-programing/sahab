@extends('layout.master')

@section('title', 'Edit Student')

@section('link')
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
@endsection


@section('body')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">ویرایش اطلاعات دانش اموز</h4>
    </div>
    <div>
        <form action="{{ route('students.update', $student->id) }}" data-confirm="edit" data-confirm-item="اطلاعات دانش آموز"
            method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="alert alert-info text-center col-9" role="alert">
                اطلاعات اولیه (ضروری)
            </div>

            {{-- Image --}}
            <div id="drapContainer"
                class="d-flex mx-auto gap-5 justify-content-center align-items-center drop-image-container">
                <div class="crop-box">
                    <img id="imagePreview" class="imagePreview">
                </div>
                <button type="button" class="btn btn-outline-danger" id="cropButton">برش</button>
            </div>

            <div class="row d-flex">
                <div class="col-3 ">
                    <label for="image" class="form-label">تصویر دانش آموز:</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/*">
                    <img id="croppedPreview" class="croppedPreview border border-primary"
                        src="{{ old('cropped_image', $student->image == 'default.png' ? asset('images/default.png') : asset('images/students/' . $student->image)) }}">
                    <input value="{{ old('cropped_image') }}" type="hidden" name="cropped_image" id="croppedImageInput">
                </div>
                <div class="text text-danger">
                    @error('image')
                        {{ $message }}
                    @enderror
                </div>

            </div>

            {{-- Other information --}}
            <div class="col-md-9">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="name" class="form-label">نام:</label>
                        <input id="name" name="name" type="text" class="form-control"
                            value="{{ old('name', $student->name) }}" />
                        <div class="text text-danger">
                            @error('name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="family" class="form-label">نام خانوادگی:</label>
                        <input id="family" name="family" type="text" class="form-control"
                            value="{{ old('family', $student->family) }}" />
                        <div class="text text-danger">
                            @error('family')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="national_code" class="form-label">کدملی:</label>
                        <input id="national_code" name="national_code" placeholder="مثال: 0123456789" type="text"
                            class="form-control" value="{{ old('national_code', $student->national_code) }}" />
                        <div class="text text-danger">
                            @error('national_code')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="student_code" class="form-label">کد دانش آموزی:</label>
                        <input id="student_code" name="student_code" type="text" class="form-control"
                            placeholder="مثال: 701" value="{{ old('student_code', $student->student_code) }}" />
                        <div class="text text-danger">
                            @error('student_code')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="class_id" class="form-label">کلاس:</label>
                        <select id="class_id" class="form-select" name="class_id">
                            <option value="" selected>لطفا انتخاب کنید</option>
                            @foreach ($classes as $class)
                                <option {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}
                                    value="{{ $class->id }}">
                                    {{ $class->name }}</option>
                            @endforeach
                        </select>
                        <div class="text text-danger">
                            @error('class_id')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info text-center col-9 mt-3">
                اطلاعات ارتباطی
            </div>

            @php
                $phones = old('phones', $phones ?? []);
            @endphp

            <div class="col-12 col-md-9">
                {{-- Add first phone button --}}
                <div id="add-first-phone-wrapper" class="{{ count($phones) ? 'd-none' : '' }}">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-first-phone">
                        + افزودن شماره
                    </button>
                </div>

                {{-- Phones --}}
                <div id="phones-wrapper" class="{{ count($phones) ? '' : 'd-none' }}">
                    @foreach ($phones as $i => $phone)
                        <div class="card mb-3 phone-item">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label">تلفن برای:</label>
                                        <select name="phones[{{ $i }}][phone_for]"
                                            class="form-select phone-for @error("phones.$i.phone_for") is-invalid @enderror">
                                            <option value="">---</option>
                                            @foreach (['Father' => 'پدر', 'Mother' => 'مادر', 'Student' => 'دانش‌آموز', 'Other' => 'سایر'] as $key => $label)
                                                <option value="{{ $key }}" @selected(($phone['phone_for'] ?? '') === $key)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("phones.$i.phone_for")
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- شماره تماس --}}
                                    <div class="col-md-3">
                                        <label class="form-label">شماره تماس:</label>
                                        <input type="text" name="phones[{{ $i }}][phone_num]"
                                            value="{{ $phone['phone_num'] ?? '' }}"
                                            class="form-control phone-num @error("phones.$i.phone_num") is-invalid @enderror"
                                            placeholder="مثلاً 09123456789" />
                                        @error("phones.$i.phone_num")
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- فقط مجازی --}}
                                    <div class="col-md-3">
                                        <div class="form-check mt-4">
                                            <input type="checkbox" name="phones[{{ $i }}][is_just_virtual]"
                                                value="1" class="form-check-input"
                                                {{ isset($phone['is_just_virtual']) && $phone['is_just_virtual'] ? 'checked' : '' }}>
                                            <label class="form-check-label">فقط مجازی</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3 text-end">
                                        <button type="button" class="btn btn-sm btn-danger remove-phone">
                                            حذف
                                        </button>
                                    </div>
                                </div>

                                {{-- توضیحات --}}
                                <div class="mt-3">
                                    <label class="form-label">توضیحات:</label>
                                    <textarea name="phones[{{ $i }}][description]" rows="1"
                                        class="form-control description @error("phones.$i.description") is-invalid @enderror">{{ $phone['description'] ?? '' }}</textarea>
                                    <div class="invalid-feedback">درصورت انتخاب «سایر»، تکمیل این بخش الزامی است</div>
                                    @error("phones.$i.description")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (count($phones))
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-phone">
                        + افزودن شماره
                    </button>
                @endif
            </div>




            <div class="alert alert-info text-center col-9 mt-3" role="alert">
                اطلاعات تکمیلی
            </div>

            <div class="col-md-9">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="father_name" class="form-label">نام پدر:</label>
                        <input id="father_name" name="father_name" type="text" class="form-control"
                            value="{{ old('father_name', $profile->father_name ?? '') }}" />
                        <div class="text text-danger">
                            @error('father_name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="previous_school" class="form-label">نام مدرسه قبلی:</label>
                        <input id="previous_school" name="previous_school" type="text" class="form-control"
                            value="{{ old('previous_school', $profile->previous_school ?? '') }}" />
                        <div class="text text-danger">
                            @error('previous_school')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="date_of_birth" class="form-label">تاریخ تولد:</label>
                        <input id="date_of_birth" name="date_of_birth" placeholder="مثال: 1400/01/01" type="text"
                            class="form-control"
                            value="{{ old('date_of_birth', $profile && $profile->date_of_birth ? toJalali($profile->date_of_birth) : '') }}" />
                        <div class="text text-danger">
                            @error('date_of_birth')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-outline-dark mt-3 mb-5">
                    ویرایش
                </button>
            </div>

        </form>
    </div>
@endsection

@section('script')
    <script>
        let cropper;

        const input = document.getElementById('image');
        const image = document.getElementById('imagePreview');
        const preview = document.getElementById('croppedPreview');
        const dropContainer = document.getElementById('drapContainer');
        const cropButton = document.getElementById('cropButton');
        const form = document.querySelector('form');

        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const url = URL.createObjectURL(file);
            image.src = url;

            if (cropper) cropper.destroy();

            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
            });

            dropContainer.classList.add("show");
        });

        cropButton.addEventListener('click', () => {
            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: 500,
                height: 500
            });

            const base64 = canvas.toDataURL('image/png');
            preview.src = base64;
            preview.style.display = 'block';

            document.getElementById('croppedImageInput').value = base64;

            dropContainer.classList.remove("show");
        });

        form.addEventListener('submit', function(e) {
            if (!document.getElementById('croppedImageInput').value) {
                return;
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let index = {{ count($phones) }};
            const persianRegex = /^[\u0600-\u06FF\s]{3,}$/;

            function validateItem($item) {
                let valid = true;
                const phoneFor = $item.find('.phone-for');
                const phoneNum = $item.find('.phone-num');
                const desc = $item.find('.description');

                phoneFor.removeClass('is-invalid');
                phoneNum.removeClass('is-invalid');
                desc.removeClass('is-invalid');

                if (!phoneFor.val()) {
                    phoneFor.addClass('is-invalid');
                    valid = false;
                }
                if (!/^09\d{9}$/.test(phoneNum.val())) {
                    phoneNum.addClass('is-invalid');
                    valid = false;
                }
                if (phoneFor.val() === 'Other' && !persianRegex.test(desc.val().trim())) {
                    desc.addClass('is-invalid');
                    valid = false;
                }

                return valid;
            }

            function addPhoneItem() {
                $('#phones-wrapper').removeClass('d-none');
                $('#add-first-phone-wrapper').addClass('d-none');

                const html = `
        <div class="card mb-3 phone-item">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">تلفن برای</label>
                        <select name="phones[${index}][phone_for]" class="form-select phone-for">
                            <option value="">انتخاب کنید</option>
                            <option value="Father">پدر</option>
                            <option value="Mother">مادر</option>
                            <option value="Student">دانش‌آموز</option>
                            <option value="Other">سایر</option>
                        </select>
                        <div class="invalid-feedback">الزامی</div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">شماره تماس</label>
                        <input type="text" name="phones[${index}][phone_num]" class="form-control phone-num">
                        <div class="invalid-feedback">شماره معتبر وارد کنید</div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="phones[${index}][is_just_virtual]" value="1"
                                   class="form-check-input">
                            <label class="form-check-label">فقط مجازی</label>
                        </div>
                    </div>

                    <div class="col-md-3 text-end">
                        <button type="button" class="btn btn-sm btn-danger remove-phone">حذف</button>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">توضیحات</label>
                    <textarea name="phones[${index}][description]" rows="1"
                              class="form-control description"></textarea>
                              <div class="invalid-feedback">درصورت انتخاب «سایر»، تکمیل این بخش الزامی است</div>

                </div>
            </div>
        </div>
        `;

                $('#phones-wrapper').append(html);
                if ($('#add-phone').length === 0) {
                    $('#phones-wrapper').after(
                        `<button type="button" id="add-phone" class="btn btn-sm btn-outline-primary mt-2">+ افزودن شماره</button>`
                    );
                }

                index++;
            }

            // دکمه‌ها
            $('#add-first-phone').on('click', addPhoneItem);
            $(document).on('click', '#add-phone', function() {
                const $last = $('.phone-item').last();
                if (!validateItem($last)) return;
                addPhoneItem();
            });
            $(document).on('click', '.remove-phone', function() {
                $(this).closest('.phone-item').remove();
                if ($('.phone-item').length === 0) {
                    $('#phones-wrapper').addClass('d-none');
                    $('#add-first-phone-wrapper').removeClass('d-none');
                    $('#add-phone').remove();
                }
            });

            // اعتبارسنجی زنده
            $(document).on('change blur', '.phone-for, .phone-num, .description', function() {
                validateItem($(this).closest('.phone-item'));
            });
        });
    </script>
@endsection
