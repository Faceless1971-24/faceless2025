            @extends('layouts.base')

@section('title', 'Notice Board')

@section('styles_before')


    <style>
        #notices-table_filter {
            display: flex;
            justify-content: flex-end;
        }

        #notices-table_filter .form-control-sm {
            margin-left: 0;
        }

        #notices-table thead tr {
            background-color: #2c3e50;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>
@endsection


@section('breadcrumb')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                নোটিশ বোর্ড
            </h1>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">নোটিশ</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="#">নোটিশ ম্যানেজ করুন</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('content')

                <x-alert />

                <div class="block block-rounded">
                    <div class="block-header ">
                        <h3 class="block-title">নোটিশ তালিকা</h3>
                        <div class="block-options">
                            @if($can_create)
                            <a href="{{ route('notices.create') }}"  class="btn btn-sm btn-info">
                                <i class="fa fa-plus mr-1"></i> নতুন নোটিশ
                            </a>

                            @endif
                        </div>
                    </div>

                    <div class="block-content block-content-full">
                        @php
    $columns = [
        [
            'data' => 'pinned',
            'name' => 'pinned',
            'visible' => false,
            'sortable' => false
        ],
        ['data' => 'title', 'name' => 'title', 'searchable' => true, 'th' => 'শিরোনাম'],
        ['data' => 'created_at', 'name' => 'created_at', 'th' => 'তারিখ'],
    ];

    if ($canManage) {
        $columns[] = ['data' => 'status', 'name' => 'status', 'th' => 'স্ট্যাটাস'];
    }

    $columns[] = ['data' => 'pinned', 'name' => 'pinned', 'th' => 'পিন করা'];
    $columns[] = ['data' => 'action', 'name' => 'action', 'th' => 'অ্যাকশন'];

    $url = route('get.notices');
                        @endphp

                        <x-datatable :columns="$columns" :url="$url" id="notices-table"
                            class="table table-bordered table-striped table-vcenter">
                            <thead class="thead-dark">
                                <tr>
                                    <th hidden></th>
                                    <th>শিরোনাম</th>
                                    <th>নিয়োজিত বিভাগ</th>
                                    <th>প্রকাশের তারিখ</th>

                                    @if ($canManage)
                                        <th>স্ট্যাটাস</th>
                                    @endif

                                    <th>পিন করা</th>
                                    <th class="text-right">অ্যাকশন</th>
                                </tr>
                            </thead>
                        </x-datatable>
                    </div>
                </div>

                <!-- Add Notice Modal -->


    <!-- Update Notice Modal -->
    <x-modal id="updateNoticeModal" title="নোটিশ হালনাগাদ করুন" size="lg">
        <div class="mb-3 p-3">
            <form id="updateNoticeForm" method="post" enctype="multipart/form-data"
                onSubmit="document.getElementById('updateNotice').disabled=true;">
                @csrf
                @method('PUT')

                <input type="hidden" id="noticeId" name="noticeId">
                <div class="mb-3">
                    <label for="title" class="form-label">শিরোনাম <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="noticeTitle" name="title">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">বিবরণ <span class="text-danger">*</span></label>
                    <textarea id="summernoteUpdate" class="form-control" name="description" rows="3"></textarea>
                </div>

                <div class="custom-form-section">
                    <h2 class="section-heading">
                        অবস্থান নির্বাচন করুন
                        <small class="text-muted ml-2">
                            <i class="fa fa-info-circle" data-toggle="tooltip" 
                               title="আপনি পুরো বাংলাদেশ জুড়ে বা নির্দিষ্ট অঞ্চল নির্বাচন করতে পারবেন।"></i>
                        </small>
                    </h2>

                    <div class="row">
                        <!-- Division Dropdown -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="update_division_ids">বিভাগ</label>
                                <div class="select-box">
                                    <select name="division_ids[]" id="update_division_ids" 
                                        class="form-control js-select2" multiple>
                                        <option value="all">সমস্ত বিভাগ</option>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="update_district_ids">জেলা</label>
                                <div class="select-box">
                                    <select name="district_ids[]" id="update_district_ids" 
                                        class="form-control js-select2" multiple>
                                        <option value="all">সমস্ত জেলা</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Upazila Dropdown -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="update_upazila_ids">উপজেলা</label>
                                <div class="select-box">
                                    <select name="upazila_ids[]" id="update_upazila_ids" 
                                        class="form-control js-select2" multiple>
                                        <option value="all">সমস্ত উপজেলা</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Union Dropdown -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="update_union_ids">ইউনিয়ন</label>
                                <div class="select-box">
                                    <select name="union_ids[]" id="update_union_ids" 
                                        class="form-control js-select2" multiple>
                                        <option value="all">সমস্ত ইউনিয়ন</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="file_paths" class="form-label">ফাইল সংযুক্তি</label>
                    <input type="file" class="form-control" id="file_paths" name="file_paths[]" multiple>
                    <small>PDF অথবা Word</small>
                    <div id="currentFiles" class="mt-2"></div>
                </div>

                <div class="mb-3 d-flex flex-wrap gap-3">
                    <div class="flex-fill col-md-4">
                        <label for="status" class="form-label">স্ট্যাটাস <span class="text-danger">*</span></label>
                        <select class="form-control" id="noticeStatus" name="status">
                            <option value="draft">খসড়া</option>
                            <option value="publish">প্রকাশ</option>
                        </select>
                    </div>
                    <div class="flex-fill col-md-4">
                        <label for="email_send" class="form-label">ইমেইল পাঠানো হবে কি? <span class="text-danger">*</span></label>
                        <select class="form-control" id="noticeEmail_send" name="email_send">
                            <option value="no">না</option>
                            <option value="yes">হ্যাঁ</option>
                        </select>
                    </div>
                    <div class="flex-fill col-md-4">
                        <label for="pinned" class="form-label">পিন করুন</label>
                        <select class="form-control" id="noticePinned" name="pinned">
                            <option value="0">না</option>
                            <option value="1">হ্যাঁ</option>
                        </select>
                    </div>
                </div>

                <div class="block-content block-content-full text-right border-top p-3">
                    <button type="button" class="btn btn-alt-info mr-1" data-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" id="updateNotice" class="btn btn-info">নোটিশ আপডেট করুন</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- View Notice Modal -->
    <x-modal id="viewNoticeModal" aria-labelledby="viewNoticeModalLabel" title="নোটিশ বিস্তারিত" size="lg">
        <div class="p-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title" id="viewNoticeTitle"></h5>
                </div>
                <div class="card-body">
                    <p id="viewNoticeDescription" class="card-text"></p>

                    <div class="mb-3">
                        <small class="text-muted">নিয়োজিত অবস্থান</small>
                        <div id="viewNoticeLocations" class="mb-0">
                            <div id="viewDivisions" class="mb-2"></div>
                            <div id="viewDistricts" class="mb-2"></div>
                            <div id="viewUpazilas" class="mb-2"></div>
                            <div id="viewUnions" class="mb-2"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">সংযুক্তি</small>
                        <ul id="viewFilePaths" class="list-unstyled mb-0">
                        </ul>
                    </div>

                    <div class="mb-3" id='file_attach' style="display: none;">
                        <iframe id="iframeNoticeFile" style="width: 100%; height: 400px;" src=""></iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ করুন</button>
        </div>
    </x-modal>
@endsection



@section('scripts_after')


<script>

    $(document).ready(function () {
        $('.js-select2').select2({
            placeholder: "Select Departments",
            allowClear: true
        });
    });


    $(document).ready(function () {
        $('#summernoteUpdate').summernote({
            height: 'auto',
            minHeight: 100,
            maxHeight: 500,
            placeholder: 'Write your content here...',
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
    $(document).ready(function () {
        $('#summernoteAddNotice').summernote({
            height: 'auto',
            minHeight: 100,
            maxHeight: 500,
            placeholder: 'Write your content here...',
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


    function viewNoticeDetails(notice_id) {
    $.ajax({
        url: "{{ route('notices.show', ':id') }}".replace(':id', notice_id),
        method: 'GET',
        success: function (response) {
            if (response.status === 200) {
                $('#viewNoticeTitle').text(response.notice.title);
                $('#viewNoticeDescription').html(response.notice.description);

                // লোকেশন চেক করে তারপর দেখানো
                if (response.locations) {
                    let locationText = '';
                    if (response.locations.divisions && response.locations.divisions.length > 0) {
                        locationText += '<strong>বিভাগ:</strong> ' + response.locations.divisions.join(', ') + '<br>';
                    }
                    if (response.locations.districts && response.locations.districts.length > 0) {
                        locationText += '<strong>জেলা:</strong> ' + response.locations.districts.join(', ') + '<br>';
                    }
                    if (response.locations.upazilas && response.locations.upazilas.length > 0) {
                        locationText += '<strong>উপজেলা:</strong> ' + response.locations.upazilas.join(', ') + '<br>';
                    }
                    if (response.locations.unions && response.locations.unions.length > 0) {
                        locationText += '<strong>ইউনিয়ন:</strong> ' + response.locations.unions.join(', ');
                    }
                    $('#viewNoticeDepartments').html(locationText);
                } else {
                    $('#viewNoticeDepartments').html('<p>লোকেশন তথ্য পাওয়া যায়নি।</p>');
                }

                // ফাইল সংযুক্তি হ্যান্ডেল করা
                const filePathsContainer = $('#viewFilePaths');
                filePathsContainer.empty();

                if (response.notice.file_paths && response.notice.file_paths.length > 0) {
                    response.notice.file_paths.forEach(function (file) {
                        const fileDownloadUrl = "{{ asset('notice/files') }}/" + encodeURIComponent(file.trim());
                        const fileLink = `
                            <li style="margin-bottom: 5px;">
                                <a href="${fileDownloadUrl}" target="_blank" style="text-decoration: none; color: blue;">
                                    📄 ${file}
                                </a>
                            </li>`;
                        filePathsContainer.append(fileLink);
                    });
                } else {
                    filePathsContainer.append('<li>কোনো ফাইল সংযুক্ত নেই।</li>');
                }

                // iframe বাদ, modal show
                $('#viewNoticeModal').modal('show');
            } else {
                alert(response.message);
            }
        },
        error: function (xhr) {
            console.error('Error fetching notice details:', xhr.responseText);
            alert('নোটিশ লোড করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।');
        }
    });
}


function editNoticeDetails(notice_id) {
    $.ajax({
        url: "{{ route('notices.edit', ':id') }}".replace(':id', notice_id),
        method: 'GET',
        success: function (response) {
            if (response.status === 200) {
                // Update form action
                $('#updateNoticeForm').attr('action', "{{ route('notices.update', ':id') }}".replace(':id', response.notice.id));

                // Fill in notice details
                $('#noticeId').val(response.notice.id);
                $('#noticeTitle').val(response.notice.title);
                $('#summernoteUpdate').summernote('code', response.notice.description);

                // Set status and other settings
                $('#noticeStatus').val(response.notice.status);
                $('#noticeEmail_send').val(response.notice.email_send);
                $('#noticePinned').val(response.notice.pinned);

                // Handle location selection
                if (response.locations) {
                    // Populate and select divisions
                    if (response.locations.division_ids && response.locations.division_ids.length > 0) {
                        $('#division_ids').val(response.locations.division_ids).trigger('change');
                    }

                    // Populate and select districts
                    if (response.locations.district_ids && response.locations.district_ids.length > 0) {
                        $('#district_ids').empty();
                        $.get('/get-districts/' + response.locations.division_ids.join(','), function(data) {
                            data.forEach(function(district) {
                                $('#district_ids').append(
                                    `<option value="${district.id}">${district.bn_name}</option>`
                                );
                            });
                            $('#district_ids').val(response.locations.district_ids).trigger('change');
                        });
                    }

                    // Populate and select upazilas
                    if (response.locations.upazila_ids && response.locations.upazila_ids.length > 0) {
                        $('#upazila_ids').empty();
                        $.get('/get-upazilas/' + response.locations.district_ids.join(','), function(data) {
                            data.forEach(function(upazila) {
                                $('#upazila_ids').append(
                                    `<option value="${upazila.id}">${upazila.bn_name}</option>`
                                );
                            });
                            $('#upazila_ids').val(response.locations.upazila_ids).trigger('change');
                        });
                    }

                    // Populate and select unions
                    if (response.locations.union_ids && response.locations.union_ids.length > 0) {
                        $('#union_ids').empty();
                        $.get('/get-unions/' + response.locations.upazila_ids.join(','), function(data) {
                            data.forEach(function(union) {
                                $('#union_ids').append(
                                    `<option value="${union.id}">${union.bn_name}</option>`
                                );
                            });
                            $('#union_ids').val(response.locations.union_ids).trigger('change');
                        });
                    }
                }

                // Handle file attachments
                const filePathsContainer = $('#currentFiles');
                filePathsContainer.empty();

                if (response.file_paths && response.file_paths.length > 0) {
                    response.file_paths.forEach(function (filePath) {
                        const fileName = filePath.split('/').pop();
                        const fileUrl = "{{ asset('notice/files') }}/" + filePath;
                        filePathsContainer.append(`<li><a href="${fileUrl}" target="_blank">${fileName}</a></li>`);
                    });
                } else {
                    filePathsContainer.append('<p>কোনো ফাইল সংযুক্ত নেই।</p>');
                }

                $('#updateNoticeModal').modal('show');
            } else {
                alert(response.message);
            }
        },
        error: function (xhr) {
            console.error('Error fetching notice details:', xhr.responseText);
        }
    });
}

    function DeleteNotice(notice_id) {
        if (confirm('Are you sure want to delete this Notice?')) {
            $.ajax({
                url: "{{ route('notices.destroy', ':id') }}".replace(':id', notice_id),
                method: 'delete',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.status === 200) {
                        alert(response.message);
                        location.reload();
                    }
                },
            });
        }
    }

</script>
@endsection