<div>
    <table class="{{ $class ? $class : 'table table-bordered table-striped table-vcenter custom-datatable' }}"
        id="{{ $id }}" style="width: 100%;">

        <thead>
            <tr>
                @foreach ($columns as $td)
                    <th {{ isset($td['visible']) && $td['visible'] === false ? 'hidden' : '' }}
                        class="{{ isset($td['class']) ? $td['class'] : 'text-nowrap align-middle fw-semibold text-capitalize' }}"
                        style="{{ isset($td['style']) ? $td['style'] : 'white-space: nowrap;' }}">
                        {{ $td['th'] ?? $td['data'] }}
                    </th>
                @endforeach
            </tr>
        </thead>

    </table>

    @section('styles_before')
        <link rel="stylesheet" href="{{ asset('theme/js/plugins/datatables/dataTables.bootstrap4.css') }}">
        <link rel="stylesheet" href="{{ asset('theme/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
        {{-- datatables button css --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

        <style>
            /* General Table Styling */
            .custom-datatable {
                width: 100%;
                border-collapse: collapse;
                font-family: 'Roboto', sans-serif;
                background-color: #f9f9f9;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            /* Table Header Styling */
            .custom-datatable thead {
                background-color: #007bff;
                color: white;
                font-size: 1rem;
                font-weight: 600;
            }

            .custom-datatable thead th {
                padding: 16px 20px;
                text-align: left;
                text-transform: uppercase;
                border-bottom: 2px solid #ccc;
            }

            .custom-datatable thead th:hover {
                background-color: #0056b3;
            }

            /* Table Body Styling */
            .custom-datatable tbody tr {
                transition: background-color 0.3s ease;
            }

            .custom-datatable tbody tr:hover {
                background-color: #f1f1f1;
            }

            .custom-datatable tbody td {
                padding: 14px 20px;
                text-align: left;
                font-size: 0.875rem;
                border: 1px solid #e1e4e8;
                vertical-align: middle;
                color: #495057;
            }

            .custom-datatable tbody td.text-right {
                text-align: right;
            }

            .custom-datatable tbody tr:nth-child(even) {
                background-color: #fafafa;
            }

            .custom-datatable tbody tr:nth-child(odd) {
                background-color: #fff;
            }

            /* Button Styling */



            .dataTables_paginate .paginate_button:hover {
                background-color: #0056b3;
                border-color: #0056b3;
            }

            .dataTables_paginate .paginate_button.disabled {
                background-color: #e0e0e0;
                cursor: not-allowed;
            }

            /* Datatable Export Button Styling */
            .dt-buttons {
                margin-bottom: 15px;
            }

            .dt-button {
                background-color: #28a745;
                color: white;
                border: 1px solid #28a745;
                padding: 8px 12px;
                margin: 0 5px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 0.875rem;
            }

            .dt-button:hover {
                background-color: #218838;
                border-color: #218838;
            }

            /* Search input box */
            .dataTables_filter input {
                padding: 8px 12px;
                margin: 0 10px;
                font-size: 0.875rem;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
        </style>

    @endsection

    @section('scripts')
        <script src="{{ asset('theme/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <!-- Page JS Code -->
        <script src="{{ asset('theme/js/pages/be_tables_datatables.min.js') }}"></script>

        @if ($buttons)

            <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
        @endif
    @endsection

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                var columns = @json($columns);
                var buttonsShow = '{{ $buttons == true ? 'true' : 'false' }}';
                var searching = '{{ $searching == true ? 'true' : 'false' }}';
                var paging = '{{ $paging == true ? 'true' : 'false' }}';
                var noDataInit = '{{ $noDataInit == true ? 'true' : 'false' }}';

                var dtTableInitializeObjectValues = {
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    searchDelay: 1000,
                    ajax: '{{ $url }}',
                    columns: columns,
                    async: true,
                    "order": [[0, "desc"]],
                    "columnDefs": [{
                        "targets": [-1],
                        "searchable": false,
                        "sortable": false,
                        className: 'text-right'
                    }]
                };

                if (buttonsShow == 'true') {
                    dtTableInitializeObjectValues.dom = "lBfrtip";
                    dtTableInitializeObjectValues.buttons = [{
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }];
                }

                if (searching == 'false') {
                    dtTableInitializeObjectValues.searching = false;
                }

                if (paging == 'false') {
                    dtTableInitializeObjectValues.paging = false;
                    dtTableInitializeObjectValues.info = false;
                }

                if (noDataInit == 'true') {
                    dtTableInitializeObjectValues.deferLoading = 0;
                }

                var dtable = $('#{!! $id !!}').DataTable(dtTableInitializeObjectValues);

                $('#{!! $id !!}_filter input')
                    .unbind()
                    .bind("input", function (e) {
                        if (this.value.length >= 3 || e.keyCode == 13) {
                            dtable.search(this.value).draw();
                        }
                        if (this.value == "") {
                            dtable.search("").draw();
                        }
                        return;
                    });

            })
        </script>
    @endpush
</div>