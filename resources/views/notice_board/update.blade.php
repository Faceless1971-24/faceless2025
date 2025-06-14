@extends('layouts.base')
@section('title', 'নোটিশ হালনাগাদ')

@section('styles_before')
    <style>
        /* Existing styles from create notice blade */
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

        /* Additional styles for current files */
        #currentFiles li {
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt breadcrumb-custom">
                        <li class="breadcrumb-item">নোটিশ</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">নোটিশ হালনাগাদ</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
        <div class="container">
            <div class="notice-page">
                <x-alert />

                <form id="updateNoticeForm" action="{{ route('notices.update', $notice->id) }}" method="post" enctype="multipart/form-data"
                    onSubmit="document.getElementById('updateNotice').disabled=true;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="noticeId" value="{{ $notice->id }}">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="block-title">নোটিশ হালনাগাদ করুন</h3>
                        </div>

                        <div class="block-content block-content-full">
                            <div class="custom-form-section">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">শিরোনাম <span class="required-asterisk">*</span></label>
                                            <input type="text" class="form-control" id="title" name="title" required
                                                placeholder="নোটিশের শিরোনাম লিখুন" value="{{ $notice->title }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">বিবরণ <span class="required-asterisk">*</span></label>
                                            <textarea id="summernoteUpdateNotice" class="form-control" name="description"
                                                required>{{ $notice->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="custom-form-section">
                                <h2 class="section-heading">
                                    অবস্থান নির্বাচন করুন
                                    <small class="text-muted ml-2">
                                        <i class="fa fa-info-circle" data-toggle="tooltip"
                                            title="আপনি পুরো বাংলাদেশ জুড়ে বা নির্দিষ্ট অঞ্চল নির্বাচন করতে পারবেন।"></i>
                                    </small>
                                </h2>

                                <div id="location-selection-container">
                                    <div class="row">
                                        <!-- Division Dropdown -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="division_ids">বিভাগ</label>
                                                <div class="select-box">
                                                    <select name="division_ids[]" id="division_ids"
                                                        class="form-control js-select2" multiple>
                                                        <option value="all">সমস্ত বিভাগ</option>
                                                        @foreach ($divisions as $division)
                                                            <option value="{{ $division->id }}"
                                                                {{ in_array($division->id, $noticeLocations['division_ids'] ?? []) ? 'selected' : '' }}>
                                                                {{ $division->bn_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- District Dropdown -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="district_ids">জেলা</label>
                                                <div class="select-box">
                                                    <select name="district_ids[]" id="district_ids"
                                                        class="form-control js-select2" multiple>
                                                        <option value="all">সমস্ত জেলা</option>
                                                        @if(!empty($noticeLocations['district_ids']))
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->id }}"
                                                                    {{ in_array($district->id, $noticeLocations['district_ids']) ? 'selected' : '' }}>
                                                                    {{ $district->bn_name }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Upazila Dropdown -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="upazila_ids">উপজেলা</label>
                                                <div class="select-box">
                                                    <select name="upazila_ids[]" id="upazila_ids"
                                                        class="form-control js-select2" multiple>
                                                        <option value="all">সমস্ত উপজেলা</option>
                                                        @if(!empty($noticeLocations['upazila_ids']))
                                                            @foreach ($upazilas as $upazila)
                                                                <option value="{{ $upazila->id }}"
                                                                    {{ in_array($upazila->id, $noticeLocations['upazila_ids']) ? 'selected' : '' }}>
                                                                    {{ $upazila->bn_name }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Union Dropdown -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="union_ids">ইউনিয়ন</label>
                                                <div class="select-box">
                                                    <select name="union_ids[]" id="union_ids" class="form-control js-select2"
                                                        multiple>
                                                        <option value="all">সমস্ত ইউনিয়ন</option>
                                                        @if(!empty($noticeLocations['union_ids']))
                                                            @foreach ($unions as $union)
                                                                <option value="{{ $union->id }}"
                                                                    {{ in_array($union->id, $noticeLocations['union_ids']) ? 'selected' : '' }}>
                                                                    {{ $union->bn_name }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="custom-form-section">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="file_paths">ফাইল সংযুক্তি</label>
                                            <div class="file-upload-container">
                                                <input type="file" class="form-control-file" id="file_paths" name="file_paths[]"
                                                    multiple>
                                                <small class="text-muted mt-2 d-block">PDF অথবা Word</small>
                                            </div>
                                            @if(!empty($notice->file_paths))
                                                <div id="currentFiles" class="mt-2">
                                                    <small class="text-muted">বর্তমান ফাইলসমূহ:</small>
                                                    <ul class="list-unstyled">
                                                        @foreach(json_decode($notice->file_paths, true) as $file)
                                                            <li>
                                                                <a href="{{ asset('notice/files/' . $file) }}" target="_blank">
                                                                    {{ $file }}
                                                                </a>
                                                                <small class="text-muted ml-2">(বিদ্যমান)</small>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="custom-form-section">
                                <h2 class="section-heading">নোটিশ সেটিংস</h2>
                                <div class="settings-container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="status">স্ট্যাটাস <span class="required-asterisk">*</span></label>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="draft" {{ $notice->status == 'draft' ? 'selected' : '' }}>অপ্রকাশিত</option>
                                                    <option value="publish" {{ $notice->status == 'publish' ? 'selected' : '' }}>প্রকাশ</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email_send">ইমেইল পাঠানো হবে কি? <span
                                        class="required-asterisk">*</span></label>
                                <select class="form-control" id="email_send" name="email_send">
                                    <option value="no" {{ $notice->email_send == 'no' ? 'selected' : '' }}>না</option>
                                    <option value="yes" {{ $notice->email_send == 'yes' ? 'selected' : '' }}>হ্যাঁ</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pinned">পিন করুন</label>
                                <select class="form-control" id="pinned" name="pinned">
                                    <option value="0" {{ $notice->pinned == 0 ? 'selected' : '' }}>না</option>
                                    <option value="1" {{ $notice->pinned == 1 ? 'selected' : '' }}>হ্যাঁ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mt-4 d-flex justify-content-center">
                <button type="submit" class="btn btn-custom-success" id="updateNotice"
                    style="background-color: #28a745; color: #ffffff;">
                    <i class="fa fa-save mr-1"></i> নোটিশ হালনাগাদ করুন
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

        // Handle Select All functionality for each dropdown
        function handleSelectAll(selectElement) {
            const $select = $(selectElement);
            const $selectAllOption = $select.find('option[value="all"]');

            $select.on('change', function () {
                const selectedValues = $select.val() || [];
                const isAllSelected = selectedValues.includes('all');

                if (isAllSelected) {
                    // If "Select All" is chosen, select all other options
                    const allOptionValues = $select.find('option')
                        .map(function () {
                            return this.value !== 'all' ? this.value : null;
                        })
                        .get();

                    $select.val(allOptionValues).trigger('change');
                }
            });
        }

        // Apply Select All to all location dropdowns
        handleSelectAll('#division_ids');
        handleSelectAll('#district_ids');
        handleSelectAll('#upazila_ids');
        handleSelectAll('#union_ids');

        // Cascading location selection
        $('#division_ids').change(function () {
            const divisionIds = $(this).val() || [];
            const $districtSelect = $('#district_ids');

            // Reset dependent dropdowns
            $districtSelect.empty().append('<option value="all">সমস্ত জেলা</option>');
            $('#upazila_ids, #union_ids').empty().append('<option value="all">সমস্ত উপজেলা/ইউনিয়ন</option>');

            // Fetch districts if divisions are selected
            if (divisionIds.length > 0 && !divisionIds.includes('all')) {
                $.get('/get-districts/' + divisionIds.join(','), function (data) {
                    data.forEach(function (district) {
                        $districtSelect.append(
                            `<option value="${district.id}">${district.bn_name}</option>`
                        );
                    });
                    $districtSelect.trigger('change');
                });
            }
        });

        $('#district_ids').change(function () {
            const districtIds = $(this).val() || [];
            const $upazilaSelect = $('#upazila_ids');

            // Reset dependent dropdowns
            $upazilaSelect.empty().append('<option value="all">সমস্ত উপজেলা</option>');
            $('#union_ids').empty().append('<option value="all">সমস্ত ইউনিয়ন</option>');

            // Fetch upazilas if districts are selected
            if (districtIds.length > 0 && !districtIds.includes('all')) {
                $.get('/get-upazilas/' + districtIds.join(','), function (data) {
                    data.forEach(function (upazila) {
                        $upazilaSelect.append(
                            `<option value="${upazila.id}">${upazila.bn_name}</option>`
                        );
                    });
                    $upazilaSelect.trigger('change');
                });
            }
        });

        $('#upazila_ids').change(function () {
            const upazilaIds = $(this).val() || [];
            const $unionSelect = $('#union_ids');

            // Reset union dropdown
            $unionSelect.empty().append('<option value="all">সমস্ত ইউনিয়ন</option>');

            // Fetch unions if upazilas are selected
            if (upazilaIds.length > 0 && !upazilaIds.includes('all')) {
                $.get('/get-unions/' + upazilaIds.join(','), function (data) {
                    data.forEach(function (union) {
                        $unionSelect.append(
                            `<option value="${union.id}">${union.bn_name}</option>`
                        );
                    });
                    $unionSelect.trigger('change');
                });
            }
        });

        // Form validation before submission
        $('#updateNoticeForm').on('submit', function (e) {
            const divisionIds = $('#division_ids').val() || [];
            const districtIds = $('#district_ids').val() || [];
            const upazilaIds = $('#upazila_ids').val() || [];
            const unionIds = $('#union_ids').val() || [];

            // Basic form validation
            if (!$('#title').val().trim()) {
                e.preventDefault();
                alert('শিরোনাম দিন');
                return false;
            }

            if (!$('#summernoteUpdateNotice').val().trim()) {
                e.preventDefault();
                alert('বিবরণ দিন');
                return false;
            }

            // Location validation
            if (divisionIds.length === 0 &&
                districtIds.length === 0 &&
                upazilaIds.length === 0 &&
                unionIds.length === 0
            ) {
                e.preventDefault();
                alert('অনুগ্রহ করে কোনো অবস্থান নির্বাচন করুন');
                return false;
            }

            return true;
        });

        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Initialize Summernote
        $('#summernoteUpdateNotice').summernote({
            height: 'auto',
            minHeight: 100,
            maxHeight: 500,
            placeholder: 'বিবরণ লিখুন...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
    </script>
@endpush