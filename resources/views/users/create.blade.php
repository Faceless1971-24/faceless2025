@extends('layouts.base')
@section('title', 'নতুন সদস্য')

@section('breadcrumb')
    <div class="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">সদস্য</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="">নতুন সদস্য</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="registration-page">
            <x-alert />

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3>নতুন সদস্য যোগ </h3>
                    </div>

                    <div class="card-body">
                        <!-- Basic Information Section -->
                        <div class="section">
                            <div class="section-grid basic-info">
                                <div class="form-group name-field">
                                    <label for="name">নাম <span class="required">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required />
                                </div>

                                <div class="form-group nid-field">
                                    <label for="userid">এনআইডি <span class="required">*</span></label>
                                    <input type="text" name="userid" id="userid" value="{{ old('userid') }}" required />
                                </div>
                            </div>

                            <div class="section-grid person-details">
                                <div class="form-group">
                                    <label for="gender">লিঙ্গ <span class="required">*</span></label>
                                    <select name="gender" id="gender" required>
                                        <option value="1" {{ old('gender') == 1 ? 'selected' : '' }}>পুরুষ</option>
                                        <option value="2" {{ old('gender') == 2 ? 'selected' : '' }}>মহিলা</option>
                                        <option value="3" {{ old('gender') == 3 ? 'selected' : '' }}>অন্যান্য</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="dob">জন্ম তারিখ</label>
                                    <input type="text" name="dob" id="dob" class="js-datepicker" onchange="updateAge()"
                                        autocomplete="off" value="{{ old('dob') }}" data-week-start="1" data-autoclose="true"
                                        data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" />
                                </div>

                                <div class="form-group">
                                    <label for="age">বয়স</label>
                                    <input type="text" name="age" id="age" value="{{ old('age') }}" readonly />
                                </div>

                                <div class="form-group">
                                    <label for="blood_group">রক্তের গ্রুপ</label>
                                    <select name="blood_group" id="blood_group">
                                        @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $key => $group)
                                            <option value="{{ $key + 1 }}" {{ old('blood_group') == $key + 1 ? 'selected' : '' }}>
                                                {{ $group }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label for="address">ঠিকানা</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" />
                            </div>
                        </div>

                        <!-- Address Section -->
                       <div class="section address-section">
    <div class="section-grid location-info">

        <!-- Division -->
        <div class="form-group">
            <label for="division_id">বিভাগ</label>
            <select name="division_id" id="division_id" class="js-select2">
                <option value="">-বিভাগ নির্বাচন করুন-</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->id }}" 
                        {{ old('division_id', $user->division_id ?? '') == $division->id ? 'selected' : '' }}>
                        {{ $division->bn_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- District -->
        <div class="form-group">
            <label for="district_id">জেলা</label>
            <select name="district_id" id="district_id" class="js-select2" 
                {{ old('district_id', $user->district_id ?? '') ? '' : 'disabled' }}>
                <option value="">-জেলা নির্বাচন করুন-</option>
                @if(old('district_id', $user->district_id ?? false) && isset($districts))
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}" 
                            {{ old('district_id', $user->district_id ?? '') == $district->id ? 'selected' : '' }}>
                            {{ $district->bn_name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Upazila -->
        <div class="form-group">
            <label for="upazila_id">উপজেলা</label>
            <select name="upazila_id" id="upazila_id" class="js-select2"
                {{ old('upazila_id', $user->upazila_id ?? '') ? '' : 'disabled' }}>
                <option value="">-উপজেলা নির্বাচন করুন-</option>
                @if(old('upazila_id', $user->upazila_id ?? false) && isset($upazilas))
                    @foreach ($upazilas as $upazila)
                        <option value="{{ $upazila->id }}" 
                            {{ old('upazila_id', $user->upazila_id ?? '') == $upazila->id ? 'selected' : '' }}>
                            {{ $upazila->bn_name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Union -->
        <div class="form-group">
            <label for="union_id">ইউনিয়ন</label>
            <select name="union_id" id="union_id" class="js-select2"
                {{ old('union_id', $user->union_id ?? '') ? '' : 'disabled' }}>
                <option value="">-ইউনিয়ন নির্বাচন করুন-</option>
                @if(old('union_id', $user->union_id ?? false) && isset($unions))
                    @foreach ($unions as $union)
                        <option value="{{ $union->id }}" 
                            {{ old('union_id', $user->union_id ?? '') == $union->id ? 'selected' : '' }}>
                            {{ $union->bn_name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>





                                <div class="form-group">
                                    <label for="post_code">পোস্ট কোড</label>
                                    <input type="text" name="post_code" id="post_code" disabled />
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="section">
                            <div class="section-grid contact-info">
                                <div class="form-group">
                                    <label for="phone">ফোন <span class="required">*</span></label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" maxlength="14" required />
                                </div>

                                <div class="form-group">
                                    <label for="email">ইমেইল <span class="required">*</span></label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required />
                                </div>
                            </div>
                        </div>

                        <!-- Education and Photo -->
                        <div class="section">
                            <div class="section-grid education-photo">
                                <div class="form-group">
                                    <label for="last_educational_qual">শেষ শিক্ষা যোগ্যতা</label>
                                    <input type="text" name="last_educational_qual" id="last_educational_qual" value="{{ old('last_educational_qual') }}" />
                                </div>

                                <div class="form-group">
                                    <label for="photo">ছবি</label>
                                    <input type="file" name="photo" id="photo" />
                                    <small>png,jpg,jpeg</small>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="section">
                            <h2 class="section-title">জরুরি যোগাযোগ</h2>

                            <div class="section-grid emergency-contact">
                                <div class="form-group">
                                    <label for="em_contact_name">নাম</label>
                                    <input type="text" name="em_contact_name" id="em_contact_name" value="{{ old('em_contact_name') }}" />
                                </div>

                                <div class="form-group">
                                    <label for="em_contact_relation">সম্পর্ক</label>
                                    <input type="text" name="em_contact_relation" id="em_contact_relation" value="{{ old('em_contact_relation') }}" />
                                </div>
                            </div>

                            <div class="section-grid emergency-contact-info">
                                <div class="form-group">
                                    <label for="em_contact_phone">ফোন</label>
                                    <input type="text" name="em_contact_phone" id="em_contact_phone" value="{{ old('em_contact_phone') }}" maxlength="14" />
                                </div>

                                <div class="form-group">
                                    <label for="em_contact_email">ইমেইল</label>
                                    <input type="email" name="em_contact_email" id="em_contact_email" value="{{ old('em_contact_email') }}" />
                                </div>
                            </div>
                        </div>

                        <!-- Party Information -->
                        <div class="section">
                            <h2 class="section-title">পার্টি তথ্য</h2>

                            <div class="section-grid party-info">
                                <div class="form-group">
                                    <label for="joining_date">যোগদান তারিখ</label>
                                    <input type="text" name="joining_date" id="joining_date" value="{{ old('joining_date') }}"
                                        autocomplete="off" data-week-start="1" data-autoclose="true" data-today-highlight="true"
                                        data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" class="js-datepicker" />
                                </div>

                                <div class="form-group">
                                    <label for="user_type_id">সদস্যের ধরন <span class="required">*</span></label>
                                    <select name="user_type_id" id="user_type_id" required>
                                        <option value="">-সদস্যের ধরন নির্বাচন করুন-</option>
                                        @foreach ($user_types as $user_type)
                                            <option value="{{ $user_type->id }}" {{ old('user_type_id') == $user_type->id ? 'selected' : '' }}>
                                                {{ $user_type->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label for="designation_id">পদবি</label>
                                <select name="designation_id" id="designation_id" class="js-select2">
                                    <option value="">-পদবি নির্বাচন করুন-</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="submit-btn">সংরক্ষণ করুন</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles_before')
<link rel="stylesheet" href="{{ asset('theme/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('theme/js/plugins/select2/css/select2.min.css') }}">
<style>
    /* Base Styles */
    .container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .registration-page {
        margin: 20px 0;
    }
    
    .card {
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
    
    .contact-info, .education-photo, .emergency-contact, .emergency-contact-info, .party-info {
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
    
    .form-group input, .form-group select {
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
        .basic-info, .emergency-contact, .emergency-contact-info, .party-info {
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
    jQuery(function(){
        // Initialize plugins
        $('.js-datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            weekStart: 1
        });
        
        $('.js-select2').select2({
            width: '100%'
        });
    });

    function updateAge() {
        var dob = $('#dob').val()
        if(dob.length) {
            var year = dob.split('-').pop();
            var currentYear = new Date().getFullYear();
            $('#age').val((currentYear - parseInt(year)))
        }
    }

    $('#duration, #joining_date').change(function () {
        var duration = $('#duration').val();
        var joining_date = $('#joining_date').val();
        var date = joining_date.split('-');
        var temp1 = date[2]+'-'+date[1]+'-'+date[0];
        var temp2 = moment(temp1).add(duration,'M');
        var ending = moment(temp2).format('DD-MM-YYYY');

        $('#ending_date').val(ending);
    });
</script>

@endsection

@push('scripts')
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

            $('#union_id').change(function () {
                // ekhane jodi kono logic thake to autofill post code, sheta add korte parish
                $('#post_code').prop('disabled', false);
            });
        });
    </script>
@endpush