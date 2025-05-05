@extends('layouts.base')

@section('title', 'Company Settings')

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Settings</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Company Settings</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection


@section('content')

    <div class="row">
        <div class="col-xl-8 offset-xl-2 col-sm-12">
            <x-alert />

            <form action="{{ route('company_setting.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Company Settings</h3>
                    </div>

                    <div class="block-content block-content-full">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="company_name">Name</label>
                                    <input type="text" name="company_name" id="company_name" class="form-control"
                                           value="{{ $company ? $company->company_name: '' }}" placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_address">Address</label>
                            <input type="text" name="company_address" id="company_address" value="{{$company? $company->company_address : '' }}" class="form-control"/>
                        </div>

                        <div class="row">
                           
                       
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="emails">Email</label>
                                    <input type="text" name="emails" id="emails" class="form-control" value="{{ $company ? $company->emails : '' }}" />
                                </div>
                            </div>
                           
                        </div>
                        <input type="hidden" name="id" value="{{$company? $company->id : '' }}"/>

                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>

                </div>

            </form>
        </div>
    </div>

@endsection


@section('styles_before')
<link rel="stylesheet" href="{{ asset('theme/js/plugins/select2/css/select2.min.css') }}">
<style>
.select2-container{
    width: 100% !important;
}
</style>

@endsection

@section('scripts')
    <script src="{{ asset('theme/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('theme/js/plugins/select2/js/select2.full.min.js') }}"></script>
    {{-- <script src="{{ asset('theme/js/plugins/moment/moment.js') }}"></script> --}}
    <script>
        jQuery(function(){One.helpers(['select2']);});
        
        $(document).ready(function() {
            checkSupervisor();
        })
        function toggleOtherSupervisor(){
            const supervisorDropDown = document.getElementById('supervisor_id_main');
            const otherSupervisorDiv = document.getElementById('other_supervisor_div');
            if(supervisorDropDown.value === '2'){
                otherSupervisorDiv.style.display = 'block';
            }
            else{
                otherSupervisorDiv.style.display = 'none';
            }
        }
        function checkSupervisor() {
            console.log('hello');
            
            const supervisorDropDown = document.getElementById('supervisor_id_main'); 
            const otherSupervisorDiv = document.getElementById('other_supervisor_div');
            if(supervisorDropDown.value == '2'){
                otherSupervisorDiv.style.display = 'block';
            }
        }
</script>
@endsection
