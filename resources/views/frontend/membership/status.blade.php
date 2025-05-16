@extends('frontend.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">সদস্য আবেদনের অবস্থা</div>

                    <div class="card-body">
                        @if($application)
                            <div class="alert alert-info">
                                আপনার আবেদনের বর্তমান অবস্থা:
                                @php
                                    $status = $application->membershipStatus; // Using the accessor here
                                @endphp

                                <strong>
                                    @if($status === 'pending')
                                        বিচারাধীন
                                    @elseif($status === 'approved')
                                        অনুমোদিত
                                    @elseif($status === 'rejected')
                                        প্রত্যাখ্যাত
                                    @elseif($status === 'active' || $application->is_active == 1)
                                        সক্রিয়
                                    @else
                                        <span class="text-warning">অজানা অবস্থা</span>
                                    @endif
                                </strong>


                            </div>

                            <div class="mb-4">
                                <h5>আবেদনকারীর তথ্য</h5>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="30%">নাম</th>
                                            <td>{{ $application->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>মোবাইল</th>
                                            <td>{{ $application->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>ঠিকানা</th>
                                            <td>
                                                @if($application->division)
                                                    {{ $application->division->bn_name }},
                                                @endif
                                                @if($application->district)
                                                    {{ $application->district->bn_name }},
                                                @endif
                                                @if($application->upazila)
                                                    {{ $application->upazila->bn_name }},
                                                @endif
                                                @if($application->union)
                                                    {{ $application->union->bn_name }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>আবেদনের তারিখ</th>
                                            <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d/m/Y') ?? 'তারিখ নেই' }}
                                            </td>
                                        </tr>
                                        @if($status === 'rejected')
                                            <tr>
                                                <th>প্রত্যাখ্যানের কারণ</th>
                                                <td>{{ $application->rejection_reason ?? 'কোন কারণ উল্লেখ করা হয়নি' }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            @if($status === 'pending')
                                <div class="alert alert-warning">
                                    আপনার আবেদনটি বর্তমানে পর্যালোচনা করা হচ্ছে। অনুমোদন প্রক্রিয়া সম্পন্ন হলে আপনাকে জানানো হবে।
                                </div>
                            @elseif($status === 'approved' || $status === 'active' || $application->is_active == 1)
                                <div class="alert alert-success">
                                    অভিনন্দন! আপনার সদস্য আবেদন অনুমোদিত হয়েছে। আপনি এখন সদস্য হিসেবে সকল সুবিধা ভোগ করতে পারবেন।
                                </div>
                            @elseif($status === 'rejected')
                                <div class="alert alert-danger">
                                    দুঃখিত, আপনার আবেদন প্রত্যাখ্যান করা হয়েছে। আপনি চাইলে পুনরায় আবেদন করতে পারেন।
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('membership.apply') }}" class="btn btn-primary">পুনরায় আবেদন করুন</a>
                                </div>
                            @else
                                <div class="alert alert-secondary">
                                    আপনার আবেদনের অবস্থা নির্ধারণ করা যায়নি। অনুগ্রহ করে প্রশাসকের সাথে যোগাযোগ করুন।
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                আপনার কোন সদস্য আবেদন পাওয়া যায়নি। নতুন আবেদন করতে নীচের বাটন ক্লিক করুন।
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('membership.apply') }}" class="btn btn-primary">আবেদন করুন</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection