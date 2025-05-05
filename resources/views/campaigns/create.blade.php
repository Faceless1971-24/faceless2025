@extends('layouts.base')
@section('title', 'নতুন ক্যাম্পেইন')

@section('styles_before')
    <style>
        /* Custom styling for the form */
        .custom-card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }

        .custom-card-header {
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            color: white;
            border-bottom: none;
            padding: 15px 20px;
        }

        .custom-card-header h3 {
            font-weight: 600;
            margin: 0;
            font-size: 1.25rem;
        }

        .custom-form-section {
            margin-bottom: 25px;
            padding-bottom: 5px;
        }

        .section-heading {
            color: #388E3C;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .section-heading small {
            margin-left: 10px;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #495057;
        }

        .form-control {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 10px 12px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
        }

        .select2-container--default .select2-selection--multiple {
            border-radius: 6px;
            border: 1px solid #ced4da;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #4CAF50;
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
        }

        .custom-file-input {
            border-radius: 6px;
        }

        .btn-custom-success {
            background-color: #4CAF50;
            border-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-custom-success:hover {
            background-color: #388E3C;
            border-color: #388E3C;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .file-upload-container {
            border: 2px dashed #ced4da;
            padding: 20px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 10px;
            transition: all 0.2s;
            background-color: #f8f9fa;
        }

        .file-upload-container:hover {
            border-color: #4CAF50;
        }

        .settings-container {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
        }

        .breadcrumb-custom {
            background-color: transparent;
            padding: 10px 0;
        }

        .breadcrumb-custom .breadcrumb-item {
            color: #6c757d;
        }

        .breadcrumb-custom .breadcrumb-item a {
            color: #4CAF50;
            text-decoration: none;
        }

        .breadcrumb-custom .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        .select-box {
            position: relative;
            background-color: white;
        }

        .required-asterisk {
            color: #dc3545;
            font-weight: bold;
        }

        .select2 {
            width: 100% !important;
        }

        .note-editor.note-frame {
            border-color: #ced4da;
            border-radius: 6px;
        }

        .note-editor.note-frame.note-focus {
            border-color: #4CAF50;
        }

        /* Tooltip styling */
        .tooltip-inner {
            max-width: 250px;
            text-align: right;
        }

        /* Media upload styles */
        .media-preview {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .audio-preview-container {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 6px;
            margin-top: 10px;
        }

        .campaign-type-selector {
            display: flex;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            flex-wrap: wrap;
        }

        .campaign-type-option {
            flex: 1;
            min-width: 150px;
            margin: 5px;
            text-align: center;
            padding: 15px;
            border: 2px solid #ced4da;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .campaign-type-option:hover {
            border-color: #4CAF50;
            background-color: #f0f9f0;
        }

        .campaign-type-option.selected {
            border-color: #4CAF50;
            background-color: #e8f5e9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .campaign-type-option i {
            display: block;
            font-size: 24px;
            margin-bottom: 10px;
            color: #4CAF50;
        }

        .date-range-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt breadcrumb-custom">
                        <li class="breadcrumb-item">ক্যাম্পেইন</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">নতুন ক্যাম্পেইন</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="campaign-page">
            <x-alert />

            <form id="addCampaignForm" action="{{ route('campaigns.store') }}" method="post" enctype="multipart/form-data"
                onSubmit="document.getElementById('saveCampaign').disabled=true;">
                @csrf
                <div class="card">
                    <div class="card-header custom-card-header">
                        <h3 class="block-title">নতুন ক্যাম্পেইন যুক্ত করুন</h3>
                    </div>

                    <div class="block-content block-content-full">
                        <div class="custom-form-section">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">ক্যাম্পেইন শিরোনাম <span
                                                class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" id="title" name="title" required
                                            placeholder="ক্যাম্পেইনের শিরোনাম লিখুন">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">ক্যাম্পেইন বিবরণ <span
                                                class="required-asterisk">*</span></label>
                                        <textarea id="summernoteAddCampaign" class="form-control" name="description"
                                            required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom-form-section">
                            <h2 class="section-heading">
                                ক্যাম্পেইনের ধরন নির্বাচন করুন
                                <small class="text-muted ml-2">
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                        title="ক্যাম্পেইনের অবস্থান পরিধি নির্ধারণ করুন"></i>
                                </small>
                            </h2>

                            <div class="campaign-type-selector">
                                <div class="campaign-type-option" data-type="nationwide">
                                    <i class="fa fa-globe"></i>
                                    <h5>বাংলাদেশ জুড়ে</h5>
                                    <p class="text-muted small">সমগ্র দেশে ক্যাম্পেইন চালানো হবে</p>
                                </div>
                                <div class="campaign-type-option" data-type="division">
                                    <i class="fa fa-map"></i>
                                    <h5>বিভাগ ভিত্তিক</h5>
                                    <p class="text-muted small">নির্দিষ্ট বিভাগে ক্যাম্পেইন চালানো হবে</p>
                                </div>
                                <div class="campaign-type-option" data-type="district">
                                    <i class="fa fa-map-marker"></i>
                                    <h5>জেলা ভিত্তিক</h5>
                                    <p class="text-muted small">নির্দিষ্ট জেলায় ক্যাম্পেইন চালানো হবে</p>
                                </div>
                                <div class="campaign-type-option" data-type="upazila">
                                    <i class="fa fa-map-o"></i>
                                    <h5>উপজেলা ভিত্তিক</h5>
                                    <p class="text-muted small">নির্দিষ্ট উপজেলায় ক্যাম্পেইন চালানো হবে</p>
                                </div>
                                <div class="campaign-type-option" data-type="union">
                                    <i class="fa fa-map-pin"></i>
                                    <h5>ইউনিয়ন ভিত্তিক</h5>
                                    <p class="text-muted small">নির্দিষ্ট ইউনিয়নে ক্যাম্পেইন চালানো হবে</p>
                                </div>
                            </div>

                            <input type="hidden" name="campaign_type" id="campaign_type" value="">
                            <input type="hidden" name="is_nationwide" id="is_nationwide" value="0">
                        </div>

                        <div class="custom-form-section" id="location-selection-container" style="display: none;">
                            <h2 class="section-heading">
                                অবস্থান নির্বাচন করুন
                                <small class="text-muted ml-2">
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                        title="ক্যাম্পেইন চালানোর অবস্থান নির্বাচন করুন"></i>
                                </small>
                            </h2>


                            <div class="row">
                                <!-- Division Dropdown -->
                                <div class="col-md-3" id="division-container">
                                    <div class="form-group">
                                        <label for="division_ids">বিভাগ <span
                                                class="required-asterisk location-required">*</span></label>
                                        <div class="select-box">
                                            <select name="division_ids[]" id="division_ids" class="form-control js-select2"
                                                multiple>
                                                @foreach ($divisions as $division)
                                                    <option value="{{ $division->id }}">
                                                        {{ $division->bn_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- District Dropdown -->
                                <div class="col-md-3" id="district-container" style="display: none;">
                                    <div class="form-group">
                                        <label for="district_ids">জেলা <span
                                                class="required-asterisk location-required">*</span></label>
                                        <div class="select-box">
                                            <select name="district_ids[]" id="district_ids" class="form-control js-select2"
                                                multiple>
                                                <!-- Options will be loaded dynamically -->
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upazila Dropdown -->
                                <div class="col-md-3" id="upazila-container" style="display: none;">
                                    <div class="form-group">
                                        <label for="upazila_ids">উপজেলা <span
                                                class="required-asterisk location-required">*</span></label>
                                        <div class="select-box">
                                            <select name="upazila_ids[]" id="upazila_ids" class="form-control js-select2"
                                                multiple>
                                                <!-- Options will be loaded dynamically -->
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Union Dropdown -->
                                <div class="col-md-3" id="union-container" style="display: none;">
                                    <div class="form-group">
                                        <label for="union_ids">ইউনিয়ন <span
                                                class="required-asterisk location-required">*</span></label>
                                        <div class="select-box">
                                            <select name="union_ids[]" id="union_ids" class="form-control js-select2"
                                                multiple>
                                                <!-- Options will be loaded dynamically -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                        </div>

                        <div class="custom-form-section">
                            <h2 class="section-heading">
                                সময়কাল নির্ধারণ করুন
                                <small class="text-muted ml-2">
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                        title="ক্যাম্পেইন শুরু এবং শেষের তারিখ নির্ধারণ করুন"></i>
                                </small>
                            </h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">শুরুর তারিখ <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control js-datepicker" id="start_date"
                                            name="start_date" required data-week-start="0" data-autoclose="true"
                                            data-today-highlight="true" data-date-format="dd/mm/yyyy">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">শেষের তারিখ <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control js-datepicker" id="end_date" name="end_date"
                                            required data-week-start="0" data-autoclose="true" data-today-highlight="true"
                                            data-date-format="dd/mm/yyyy">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom-form-section">
                            <h2 class="section-heading">
                                মিডিয়া সংযুক্তি
                                <small class="text-muted ml-2">
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                        title="ক্যাম্পেইনের জন্য অডিও, ভিডিও এবং ইমেজ আপলোড করুন"></i>
                                </small>
                            </h2>
                            <!-- Images Upload -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="campaign_images">ইমেজ সংযুক্তি</label>
                                        <div class="file-upload-container">
                                            <input type="file" class="form-control-file" id="campaign_images"
                                                name="campaign_images[]" multiple accept="image/*">
                                            <small class="text-muted mt-2 d-block">JPG, PNG, GIF (সর্বোচ্চ 5MB)</small>
                                        </div>
                                        <div id="image-preview-container"></div>
                                    </div>
                                </div>

                                <!-- Audio Upload -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="campaign_audio">অডিও সংযুক্তি</label>
                                        <div class="file-upload-container">
                                            <input type="file" class="form-control-file" id="campaign_audio"
                                                name="campaign_audio" accept="audio/*">
                                            <small class="text-muted mt-2 d-block">MP3, WAV (সর্বোচ্চ 10MB)</small>
                                        </div>
                                        <div id="audio-preview-container" class="audio-preview-container"
                                            style="display: none;">
                                            <audio id="audio-preview" controls style="width: 100%;"></audio>
                                        </div>
                                    </div>
                                </div>

                                <!-- Video Upload -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="campaign_video">ভিডিও সংযুক্তি</label>
                                        <div class="file-upload-container">
                                            <input type="file" class="form-control-file" id="campaign_video"
                                                name="campaign_video" accept="video/*">
                                            <small class="text-muted mt-2 d-block">MP4, WEBM (সর্বোচ্চ 50MB)</small>
                                        </div>
                                        <div id="video-preview-container" style="display: none;">
                                            <video id="video-preview" controls
                                                style="width: 100%; max-height: 200px;"></video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom-form-section">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="file_paths">ডকুমেন্ট সংযুক্তি</label>
                                        <div class="file-upload-container">
                                            <input type="file" class="form-control-file" id="file_paths" name="file_paths[]"
                                                multiple>
                                            <small class="text-muted mt-2 d-block">PDF, DOCX, XLSX, PPT (সর্বোচ্চ
                                                10MB)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom-form-section">
                            <h2 class="section-heading">ক্যাম্পেইন সেটিংস</h2>
                            <div class="settings-container">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">স্ট্যাটাস <span class="required-asterisk">*</span></label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="draft">অপ্রকাশিত</option>
                                                <option value="publish">প্রকাশ</option>
                                                <option value="scheduled">শিডিউল করা</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="notification_send">নোটিফিকেশন পাঠানো হবে কি? <span
                                                    class="required-asterisk">*</span></label>
                                            <select class="form-control" id="notification_send" name="notification_send">
                                                <option value="no">না</option>
                                                <option value="yes">হ্যাঁ</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="featured">ফিচারড ক্যাম্পেইন?</label>
                                            <select class="form-control" id="featured" name="featured">
                                                <option value="0">না</option>
                                                <option value="1">হ্যাঁ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-4 d-flex justify-content-center">
                            <button type="submit" class="btn btn-custom-success" id="saveCampaign">
                                <i class="fa fa-save mr-1"></i> ক্যাম্পেইন যুক্ত করুন
                            </button>
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
@endsection

@section('scripts')
    <script>
        jQuery(function () { One.helpers(['datepicker', 'select2']); });
    </script>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('.js-select2').select2({
                placeholder: "নির্বাচন করুন",
                allowClear: true
            });

            $(document).ready(function () {
                // Initialize Summernote with improved configuration
                $('#summernoteAddCampaign').summernote({
                    height: 300,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    // Ensure the textarea remains visible
                    callbacks: {
                        onInit: function () {
                            $(this).summernote('code', ''); // Clear any pre-existing content
                            $(this).show(); // Ensure the textarea is visible
                        }
                    }
                });

                // Add custom validation for Summernote
                $('#addCampaignForm').on('submit', function (e) {
                    const description = $('#summernoteAddCampaign').summernote('code').trim();

                    // Remove default Summernote placeholders
                    const cleanDescription = description.replace(/<p><br><\/p>/g, '').trim();

                    if (!cleanDescription) {
                        e.preventDefault();
                        alert('ক্যাম্পেইন বিবরণ দিন');
                        return false;
                    }
                });
            });

            // Campaign Type Selector
            $('.campaign-type-option').on('click', function () {
                const type = $(this).data('type');

                // Remove selected class from all options
                $('.campaign-type-option').removeClass('selected');

                // Add selected class to clicked option
                $(this).addClass('selected');

                // Set campaign type value in hidden field
                $('#campaign_type').val(type);

                // Handle nationwide selection
                if (type === 'nationwide') {
                    $('#is_nationwide').val(1);
                    $('#location-selection-container').hide();
                } else {
                    $('#is_nationwide').val(0);
                    $('#location-selection-container').show();

                    // Show/hide appropriate dropdowns based on selection
                    if (type === 'division') {
                        $('#division-container').show();
                        $('#district-container, #upazila-container, #union-container').hide();
                    } else if (type === 'district') {
                        $('#division-container, #district-container').show();
                        $('#upazila-container, #union-container').hide();
                    } else if (type === 'upazila') {
                        $('#division-container, #district-container, #upazila-container').show();
                        $('#union-container').hide();
                    } else if (type === 'union') {
                        $('#division-container, #district-container, #upazila-container, #union-container').show();
                    }
                }
            });

                // Handle division, district, upazila cascade
            $("#division_ids").change(function () {
                const divisionId = $(this).val();
                if (divisionId) {
                    // Reset and disable lower selects
                    $("#district_ids").html('<option value="">প্রথমে বিভাগ নির্বাচন করুন</option>').prop("disabled", true);
                    $("#upazila_ids").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_ids").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);

                    // Get districts
                    $.ajax({
                        url: '/get-districts/' + divisionId,
                        type: 'GET',
                        success: function (data) {
                            let districtOptions = '<option value="">জেলা নির্বাচন করুন</option>';
                            $.each(data, function (id, bn_name) {
                                districtOptions += `<option value="${id}">${bn_name}</option>`;
                            });
                            $("#district_ids").html(districtOptions).prop("disabled", false);
                        }
                    });
                } else {
                    $("#district_ids").html('<option value="">প্রথমে বিভাগ নির্বাচন করুন</option>').prop("disabled", true);
                    $("#upazila_ids").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_ids").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);
                }
            });

            $("#district_ids").change(function () {
                const districtId = $(this).val();
                if (districtId) {
                    // Reset and disable lower selects
                    $("#upazila_ids").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_ids").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);

                    // Get upazilas
                    $.ajax({
                        url: '/get-upazilas/' + districtId,
                        type: 'GET',
                        success: function (data) {
                            let upazilaOptions = '<option value="">উপজেলা নির্বাচন করুন</option>';
                            $.each(data, function (id, bn_name) {
                                upazilaOptions += `<option value="${id}">${bn_name}</option>`;
                            });
                            $("#upazila_ids").html(upazilaOptions).prop("disabled", false);
                        }
                    });
                } else {
                    $("#upazila_ids").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_ids").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);
                }
            });

            $("#upazila_ids").change(function () {
                const upazilaId = $(this).val();
                if (upazilaId) {
                    // Reset union select
                    $("#union_ids").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);

                    // Get unions
                    $.ajax({
                        url: '/get-unions/' + upazilaId,
                        type: 'GET',
                        success: function (data) {
                            let unionOptions = '<option value="">ইউনিয়ন নির্বাচন করুন</option>';
                            $.each(data, function (id, bn_name) {
                                unionOptions += `<option value="${id}">${bn_name}</option>`;
                            });
                            $("#union_ids").html(unionOptions).prop("disabled", false);
                        }
                    });
                } else {
                    $("#union_ids").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);
                }
            });

            // Media file previews
            // Image Preview
            $('#campaign_images').on('change', function () {
                const files = this.files;
                $('#image-preview-container').empty();

                if (files.length > 0) {
                    for (let i = 0; i < Math.min(files.length, 3); i++) {
                        const file = files[i];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                $('#image-preview-container').append(
                                    `<img src="${e.target.result}" alt="Preview" class="media-preview mt-2" style="max-height: 150px; margin-right: 10px;">`
                                );
                            }
                            reader.readAsDataURL(file);
                        }
                    }

                    if (files.length > 3) {
                        $('#image-preview-container').append(
                            `<p class="text-muted mt-2">+${files.length - 3} আরও ইমেজ</p>`
                        );
                    }
                }
            });

            // Audio Preview
            $('#campaign_audio').on('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#audio-preview').attr('src', e.target.result);
                        $('#audio-preview-container').show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#audio-preview-container').hide();
                }
            });

            // Video Preview
            $('#campaign_video').on('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#video-preview').attr('src', e.target.result);
                        $('#video-preview-container').show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#video-preview-container').hide();
                }
            });

            // Form validation before submission
            $('#addCampaignForm').on('submit', function (e) {
                const campaignType = $('#campaign_type').val();
                const isNationwide = $('#is_nationwide').val() === '1';
                const divisionIds = $('#division_ids').val() || [];
                const districtIds = $('#district_ids').val() || [];
                const upazilaIds = $('#upazila_ids').val() || [];
                const unionIds = $('#union_ids').val() || [];
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                // Basic form validation
                if (!$('#title').val().trim()) {
                    e.preventDefault();
                    alert('ক্যাম্পেইন শিরোনাম দিন');
                    return false;
                }

                if (!$('#summernoteAddCampaign').val().trim()) {
                    e.preventDefault();
                    alert('ক্যাম্পেইন বিবরণ দিন');
                    return false;
                }

                // Campaign type validation
                if (!campaignType) {
                    e.preventDefault();
                    alert('অনুগ্রহ করে ক্যাম্পেইনের ধরন নির্বাচন করুন');
                    return false;
                }

                // Location validation based on campaign type
                if (!isNationwide) {
                    if (campaignType === 'division' && divisionIds.length === 0) {
                        e.preventDefault();
                        alert('অনুগ্রহ করে কমপক্ষে একটি বিভাগ নির্বাচন করুন');
                        return false;
                    } else if (campaignType === 'district' && districtIds.length === 0) {
                        e.preventDefault();
                        alert('অনুগ্রহ করে কমপক্ষে একটি জেলা নির্বাচন করুন');
                        return false;
                    } else if (campaignType === 'upazila' && upazilaIds.length === 0) {
                        e.preventDefault();
                        alert('অনুগ্রহ করে কমপক্ষে একটি উপজেলা নির্বাচন করুন');
                        return false;
                    } else if (campaignType === 'union' && unionIds.length === 0) {
                        e.preventDefault();
                        alert('অনুগ্রহ করে কমপক্ষে একটি ইউনিয়ন নির্বাচন করুন');
                        return false;
                    }
                }

                // Date validation
                if (!startDate) {
                    e.preventDefault();
                    alert('শুরুর তারিখ নির্বাচন করুন');
                    return false;
                }

                if (!endDate) {
                    e.preventDefault();
                    alert('শেষের তারিখ নির্বাচন করুন');
                    return false;
                }

                // Compare dates
                const startDateParts = startDate.split('/');
                const endDateParts = endDate.split('/');

                const startDateObj = new Date(startDateParts[2], startDateParts[1] - 1, startDateParts[0]);
                const endDateObj = new Date(endDateParts[2], endDateParts[1] - 1, endDateParts[0]);

                if (startDateObj > endDateObj) {
                    e.preventDefault();
                    alert('শেষের তারিখ শুরুর তারিখের পরে হতে হবে');
                    return false;
                }

                return true;
            });

            // Enable tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush