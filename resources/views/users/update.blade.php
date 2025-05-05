@extends('layouts.base')

@section('title', 'সদস্য পরিচালনা')

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">সদস্য</li>
                        <li class="breadcrumb-item">সদস্য পরিচালনা</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('users.index') }}">আপডেট</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection



@section('content')
    <div class="container">
    <div class="update-page">
            <x-alert />

    <form action="{{ route('users.update', ['user' => $user->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">

                <div class="card-header">
                    <h3>সদস্যের তথ্য আপডেট</h3>
                </div>

            <div class="block-content block-content-full">
                <input type="hidden" value="{{ auth()->user()->id }}" id="auth_id">

                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="name">নাম *</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}"
                                required />
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="userid">NID *</label>
                            <input type="text" name="userid" id="userid" class="form-control" value="{{ $user->userid }}"
                                required />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label for="gender">লিঙ্গ *</label>
                        <select name="gender" id="gender" class="form-control" required>
                            <option value="1" {{ $user->gender == 1 ? 'selected' : '' }}>পুরুষ</option>
                            <option value="2" {{ $user->gender == 2 ? 'selected' : '' }}>মহিলা</option>
                            <option value="3" {{ $user->gender == 3 ? 'selected' : '' }}>অন্যান্য</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dob">জন্ম তারিখ</label>
                            <input type="text" name="dob" id="dob" class="form-control js-datepicker" onchange="updateAge()"
                                autocomplete="off" value="{{ $user->dob ? $user->dob->format('d-m-Y') : '' }}"
                                data-week-start="1" data-autoclose="true" data-today-highlight="true"
                                data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="age">বয়স</label>
                            <input type="text" name="age" id="age" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="blood_group">রক্তের গ্রুপ</label>
                        <select name="blood_group" id="blood_group" class="form-control">
                            @foreach (Config::get('constants.blood_groups') as $index => $bg)
                                <option value="{{ $index }}" {{ $user->blood_group == $index ? 'selected' : '' }}>{{ $bg }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">ঠিকানা</label>
                    <input type="text" name="address" id="address" value="{{ $user->address }}" class="form-control" />
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label for="division_id">বিভাগ</label>
                        <select name="division_id" id="division_id" class="form-control js-select2">
                            <option value="">বিভাগ নির্বাচন করুন</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}" {{ old('division_id', $user->division_id ?? '') == $division->id ? 'selected' : '' }}>
                                    {{ $division->bn_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="district_id">জেলা</label>
                        <select name="district_id" id="district_id" class="form-control js-select2">
                            @if (isset($districts))
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}" {{ old('district_id', $user->district_id ?? '') == $district->id ? 'selected' : '' }}>
                                        {{ $district->bn_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="upazila_id">উপজেলা</label>
                        <select name="upazila_id" id="upazila_id" class="form-control js-select2">
                            @if (isset($upazilas))
                                @foreach ($upazilas as $upazila)
                                    <option value="{{ $upazila->id }}" {{ old('upazila_id', $user->upazila_id ?? '') == $upazila->id ? 'selected' : '' }}>
                                        {{ $upazila->bn_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="union_id">ইউনিয়ন</label>
                        <select name="union_id" id="union_id" class="form-control js-select2">
                            @if (isset($unions))
                                @foreach ($unions as $union)
                                    <option value="{{ $union->id }}" {{ old('union_id', $user->union_id ?? '') == $union->id ? 'selected' : '' }}>
                                        {{ $union->bn_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="post_code">পোস্ট কোড</label>
                            <input type="text" name="post_code" id="post_code" value="{{ $user->post_code }}"
                                class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">ফোন *</label>
                            <input type="text" name="phone" id="phone" value="{{ $user->phone }}" class="form-control"
                                required />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">ইমেইল *</label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-control"
                                required />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="photo">ছবি</label>
                            <input type="file" name="photo" id="photo" class="form-control" />
                            <small>png,jpg,jpeg</small><br>
                            <a href="{{ $user->photo_path ? asset($user->photo_path) : '#' }}">ছবি দেখুন</a>
                            </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last_educational_qual">সর্বশেষ শিক্ষাগত যোগ্যতা</label>
                            <input type="text" name="last_educational_qual" id="last_educational_qual"
                                value="{{ $user->last_educational_qual }}" class="form-control" />
                        </div>
                    </div>

                </div>

                <h2 class="content-heading border-bottom mb-4 pb-2">জরুরী যোগাযোগ</h2>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="em_contact_name">নাম</label>
                            <input type="text" name="em_contact_name" id="em_contact_name"
                                value="{{ $user->em_contact_name }}" class="form-control" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="em_contact_relation">সম্পর্ক</label>
                            <input type="text" name="em_contact_relation" id="em_contact_relation"
                                value="{{ $user->em_contact_relation }}" class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="em_contact_phone">ফোন</label>
                            <input type="text" name="em_contact_phone" id="em_contact_phone"
                                value="{{ $user->em_contact_phone }}" class="form-control" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="em_contact_email">ইমেইল</label>
                            <input type="email" name="em_contact_email" id="em_contact_email"
                                value="{{ $user->em_contact_email }}" class="form-control" />
                        </div>
                    </div>


                </div>

                @if($can_see_party_info == true)
                    <div id="hr_info">

                        <h2 class="content-heading border-bottom mb-4 pb-2">পার্টি তথ্য</h2>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="joining_date">যোগদান তারিখ</label>
                                    <input type="text" name="joining_date" id="joining_date"
                                        value="{{ $user->joining_date ? $user->joining_date->format('d-m-Y') : '' }}" autocomplete="off"
                                        data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy"
                                        placeholder="dd-mm-yyyy" class="form-control js-datepicker" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_type_id">সদস্যের ধরন *</label>
                                    <select name="user_type_id" id="user_type_id" class="form-control" required>
                                        <option value="">-সদস্যের ধরন নির্বাচন করুন-</option>
                                        @foreach ($user_types as $user_type)
                                            @php
        $primary_role = $user->roles->firstWhere('pivot.is_primary', 1);
                                            @endphp

                                            <option value="{{ $user_type->id }}" {{ old('user_type_id', $primary_role ? $primary_role->id : '') == $user_type->id ? 'selected' : '' }}>
                                                {{ $user_type->title }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="designation_id">পদবী</label>
                                    <select name="designation_id" id="designation_id" class="form-control js-select2">
                                        <option value="">-পদবী নির্বাচন করুন-</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}" {{ $user->designation_id == $designation->id ? 'selected' : '' }}>
                                                {{ $designation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
                <div class="form-actions">
                    <button type="submit" class="submit-btn">আপডেট করুন</button>
                </div>

            </div>
        </div>
    </form>


        </div>
    </div>

@endsection


@section('styles_before')
    <link rel="stylesheet"
        href="{{ asset('theme/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/js/plugins/select2/css/select2.min.css') }}">
    <style>
        /* Base Styles */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .update-page {
            margin: 20px 0;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin-bottom: 20px;
        }

        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            background: #f8f9fa;
        }

        .card-header h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .card-body {
            padding: 20px;
        }

        /* Form Elements */
        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .section-grid {
            display: grid;
            grid-gap: 15px;
        }

        .basic-info {
            grid-template-columns: 2fr 1fr;
        }

        .person-details {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .location-info {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }

        .contact-info,
        .education-photo,
        .emergency-contact,
        .emergency-contact-info,
        .party-info {
            grid-template-columns: 1fr 1fr;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .form-group small {
            display: block;
            margin-top: 3px;
            color: #6c757d;
        }

        .required {
            color: red;
        }

        /* Button Styles */
        .form-actions {
            margin-top: 25px;
            text-align: center;
        }

        .submit-btn {
            padding: 10px 25px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit-btn:hover {
            background-color: #218838;
        }

        /* Breadcrumb */
        .breadcrumb-wrapper {
            background-color: #f8f9fa;
            padding: 10px 0;
            margin-bottom: 20px;
        }

        .breadcrumb {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item {
            display: inline-flex;
            align-items: center;
        }

        .breadcrumb-item:not(:last-child)::after {
            content: '/';
            margin: 0 8px;
            color: #6c757d;
        }

        .breadcrumb-item a {
            text-decoration: none;
            color: #007bff;
        }

        /* Responsive Design */
        @media (max-width: 768px) {

            .basic-info,
            .emergency-contact,
            .emergency-contact-info,
            .party-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('scripts')
        <script src="{{ asset('theme/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/moment/moment.js') }}"></script>
        <script>
            jQuery(function() {
                One.helpers(['datepicker', 'select2']);
            });
            $(document).ready(function() {
                updateAge();
            })

            $('#duration, #joining_date').change(function () {

                var duration = $('#duration').val();
                var joining_date = $('#joining_date').val();
                var date = joining_date.split('-');
                var temp1 = date[2]+'-'+date[1]+'-'+date[0];
                var temp2 = moment(temp1).add(duration,'M');
                var ending = moment(temp2).format('DD-MM-YYYY');

                $('#ending_date').val(ending);
            });

            function updateAge() {
                var dob = $('#dob').val()
                if (dob.length) {
                    var year = dob.split('-').pop();
                    var currentYear = new Date().getFullYear();
                    $('#age').val((currentYear - parseInt(year)))
                }
            }



            $('#employment_type').change(function () {
                duration_div();
            });

            function duration_div(){
                var employment_type = $('#employment_type').val();

                if(employment_type == 2){
                    $("#duration_div").css('display', 'block');
                }else if(employment_type == 3){
                    $("#duration_div").css('display', 'block');
                }else{
                    $("#duration_div").css('display', 'none');
                }
            }

        </script>
        {{-- At bottom of your form.blade.php or push to stack --}}
    <script>
        $(document).ready(function () {

            // Handle division, district, upazila cascade
            $("#division_id").change(function () {
                const divisionId = $(this).val();
                if (divisionId) {
                    // Reset and disable lower selects
                    $("#district_id").html('<option value="">প্রথমে বিভাগ নির্বাচন করুন</option>').prop("disabled", true);
                    $("#upazila_id").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_id").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);

                    // Get districts
                    $.ajax({
                        url: '/get-districts/' + divisionId,
                        type: 'GET',
                        success: function (data) {
                            let districtOptions = '<option value="">জেলা নির্বাচন করুন</option>';
                            $.each(data, function (id, bn_name) {
                                districtOptions += `<option value="${id}">${bn_name}</option>`;
                            });
                            $("#district_id").html(districtOptions).prop("disabled", false);
                        }
                    });
                } else {
                    $("#district_id").html('<option value="">প্রথমে বিভাগ নির্বাচন করুন</option>').prop("disabled", true);
                    $("#upazila_id").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_id").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);
                }
            });

            $("#district_id").change(function () {
                const districtId = $(this).val();
                if (districtId) {
                    // Reset and disable lower selects
                    $("#upazila_id").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_id").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);

                    // Get upazilas
                    $.ajax({
                        url: '/get-upazilas/' + districtId,
                        type: 'GET',
                        success: function (data) {
                            let upazilaOptions = '<option value="">উপজেলা নির্বাচন করুন</option>';
                            $.each(data, function (id, bn_name) {
                                upazilaOptions += `<option value="${id}">${bn_name}</option>`;
                            });
                            $("#upazila_id").html(upazilaOptions).prop("disabled", false);
                        }
                    });
                } else {
                    $("#upazila_id").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_id").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);
                }
            });

            $("#upazila_id").change(function () {
                const upazilaId = $(this).val();
                if (upazilaId) {
                    // Reset union select
                    $("#union_id").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);

                    // Get unions
                    $.ajax({
                        url: '/get-unions/' + upazilaId,
                        type: 'GET',
                        success: function (data) {
                            let unionOptions = '<option value="">ইউনিয়ন নির্বাচন করুন</option>';
                            $.each(data, function (id, bn_name) {
                                unionOptions += `<option value="${id}">${bn_name}</option>`;
                            });
                            $("#union_id").html(unionOptions).prop("disabled", false);
                        }
                    });
                } else {
                    $("#union_id").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);
                }
            });
        });
    </script>


@endsection



@push('scripts')
    <script>
        $(document).ready(function () {
            $('.js-select2').select2();

            $('#division_id').change(function () {
                let divisionID = $(this).val();
                $('#district_id').prop('disabled', true).html('<option value="">Loading...</option>');
                $('#upazila_id, #union_id, #post_code').prop('disabled', true).val('');
                $.get('/get-districts/' + divisionID, function (data) {
                    let options = '<option value="">-Select District-</option>';
                    data.forEach(d => options += `<option value="${d.id}">${d.bn_name}</option>`);
                    $('#district_id').html(options).prop('disabled', false);
                });
            });

            $('#district_id').change(function () {
                let districtID = $(this).val();
                $('#upazila_id').prop('disabled', true).html('<option value="">Loading...</option>');
                $('#union_id, #post_code').prop('disabled', true).val('');
                $.get('/get-upazilas/' + districtID, function (data) {
                    let options = '<option value="">-Select Upazila-</option>';
                    data.forEach(u => options += `<option value="${u.id}">${u.bn_name}</option>`);
                    $('#upazila_id').html(options).prop('disabled', false);
                });
            });

            $('#upazila_id').change(function () {
                let upazilaID = $(this).val();
                $('#union_id').prop('disabled', true).html('<option value="">Loading...</option>');
                $('#post_code').val('').prop('disabled', true);
                $.get('/get-unions/' + upazilaID, function (data) {
                    let options = '<option value="">-Select Union-</option>';
                    data.forEach(u => options += `<option value="${u.id}">${u.bn_name}</option>`);
                    $('#union_id').html(options).prop('disabled', false);
                });
            });

            $('#union_id').change(function () {
                // Additional logic here for autofilling post code
                $('#post_code').prop('disabled', false);
            });
        });
    </script>
@endpush