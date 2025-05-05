@extends('frontend.layouts.master')

@section('title', 'সদস্যতা')

@section('content')
    <!-- Hero Section with minimal styling -->
    <div style="background:linear-gradient(rgba(30,58,138,0.85),rgba(15,34,89,0.9)),url('/images/membership-banner.jpg');background-size:cover;background-position:center;color:white;padding:90px 0;position:relative;overflow:hidden;">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-3">আমাদের সদস্য হোন</h1>
                    <p class="lead mb-4">আমাদের দলের সদস্য হয়ে বাংলাদেশের পরিবর্তনের অংশীদার হোন।</p>
                    @auth
                        <div class="mt-4">
                            <a href="{{ route('membership.apply') }}" class="btn btn-warning btn-lg px-4 me-2" style="transition:all 0.3s;box-shadow:0 4px 8px rgba(0,0,0,0.1);">
                                <i class="fas fa-user-plus me-1"></i> সদস্যতার আবেদন করুন
                            </a>
                            <a href="{{ route('membership.status') }}" class="btn btn-light btn-lg px-4" style="transition:all 0.3s;">
                                <i class="fas fa-search me-1"></i> আবেদনের স্ট্যাটাস দেখুন
                            </a>
                        </div>
                    @else
                        <div class="mt-4">
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 me-2" style="transition:all 0.3s;">
                                <i class="fas fa-sign-in-alt me-1"></i> লগইন করুন
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4" style="transition:all 0.3s;box-shadow:0 4px 8px rgba(0,0,0,0.1);">
                                <i class="fas fa-user-plus me-1"></i> রেজিস্ট্রেশন করুন
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
        <!-- Diagonal edge at bottom -->
        <div style="position:absolute;bottom:0;left:0;width:100%;height:70px;background:linear-gradient(135deg,transparent 49%,white 50%);"></div>
    </div>

    <!-- Benefits Section -->
    <div class="container my-5 py-3">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 text-center">
                <h2 class="mb-4" style="position:relative;display:inline-block;padding-bottom:10px;">সদস্য হওয়ার সুবিধাসমূহ
                    <span style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:80px;height:3px;background:#F59E0B;"></span>
                </h2>
                <p class="lead">আমাদের দলের সদস্য হিসেবে আপনি পাবেন অনেক সুবিধা ও সুযোগ, যা আপনাকে সমাজের পরিবর্তনে অবদান রাখতে সাহায্য করবে।</p>
            </div>
        </div>

        <div class="row">
            <!-- Benefits cards with simplified styling -->
            @foreach([
                ['icon' => 'fa-bullhorn', 'title' => 'আপনার কণ্ঠস্বর শোনাতে পারবেন', 'desc' => 'দলীয় সিদ্ধান্তে আপনার মতামত, পরামর্শ ও চিন্তাভাবনা শেয়ার করার সুযোগ পাবেন।'],
                ['icon' => 'fa-handshake', 'title' => 'নেটওয়ার্কিং সুযোগ', 'desc' => 'বিভিন্ন পেশা, অভিজ্ঞতা ও দক্ষতার মানুষের সাথে পরিচিত হওয়ার সুযোগ পাবেন।'],
                ['icon' => 'fa-graduation-cap', 'title' => 'প্রশিক্ষণ ও বিকাশ', 'desc' => 'নেতৃত্ব, যোগাযোগ, সংগঠন ও অন্যান্য দক্ষতা বিকাশের জন্য প্রশিক্ষণ পাবেন।'],
                ['icon' => 'fa-users', 'title' => 'অনুষ্ঠানে অংশগ্রহণ', 'desc' => 'দলের বিভিন্ন অনুষ্ঠান, সম্মেলন ও মিটিংয়ে অংশগ্রহণ করার সুযোগ পাবেন।'],
                ['icon' => 'fa-vote-yea', 'title' => 'ভোটাধিকার', 'desc' => 'দলের বিভিন্ন নির্বাচনে ভোট দেওয়ার অধিকার পাবেন।'],
                ['icon' => 'fa-award', 'title' => 'বিশেষ সুযোগ ও স্বীকৃতি', 'desc' => 'অবদানের জন্য বিশেষ স্বীকৃতি ও সম্মান পাওয়ার সুযোগ পাবেন।']
            ] as $benefit)
            <div class="col-md-4 mb-4">
                <div style="background:white;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,0.05);padding:25px;height:100%;transition:all 0.3s;border-top:4px solid #F59E0B;" onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 3px 15px rgba(0,0,0,0.05)';">
                    <div style="display:inline-flex;align-items:center;justify-content:center;width:60px;height:60px;border-radius:50%;background:#f8f9fa;color:#1E3A8A;font-size:1.5rem;margin-bottom:20px;">
                        <i class="fas {{ $benefit['icon'] }}"></i>
                    </div>
                    <h4 style="margin-bottom:12px;color:#1E3A8A;">{{ $benefit['title'] }}</h4>
                    <p style="color:#555;">{{ $benefit['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Call to Action -->
    <div style="background:linear-gradient(135deg,#1E3A8A,#0F2259);color:white;padding:70px 0;position:relative;margin:80px 0;overflow:hidden;">
        <!-- Top wave decoration -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h2 class="mb-4">আজই আমাদের সাথে যোগ দিন</h2>
                    <p class="lead mb-4">একসাথে আমরা আমাদের দেশের জন্য পরিবর্তন আনতে পারি।</p>
                    @auth
                        <a href="{{ route('membership.apply') }}" class="btn btn-warning btn-lg px-5" style="transition:all 0.3s;box-shadow:0 4px 15px rgba(0,0,0,0.2);" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.25)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)';">
                            <i class="fas fa-user-plus me-1"></i> সদস্যতার আবেদন করুন
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-5" style="transition:all 0.3s;box-shadow:0 4px 15px rgba(0,0,0,0.2);" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.25)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)';">
                            <i class="fas fa-user-plus me-1"></i> রেজিস্ট্রেশন করুন
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        <!-- Bottom wave decoration -->
    </div>

    <!-- FAQ Section -->
    <div class="container my-5">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 text-center">
                <h2 class="mb-4" style="position:relative;display:inline-block;padding-bottom:10px;">সাধারণ জিজ্ঞাসা
                    <span style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:80px;height:3px;background:#F59E0B;"></span>
                </h2>
                <p class="lead">সদস্যতা সম্পর্কে আপনার যেসব প্রশ্ন থাকতে পারে</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- FAQ items with simplified styling -->
                @foreach([
                    ['question' => 'কীভাবে সদস্য হওয়া যায়?', 'answer' => 'সদস্য হওয়ার জন্য প্রথমে ওয়েবসাইটে রেজিস্ট্রেশন করুন, তারপর সদস্যতা পেইজে গিয়ে আবেদন ফর্ম পূরণ করুন। আবেদন যাচাই-বাছাই করে অনুমোদন দেওয়া হবে এবং আপনাকে ই-মেইল বা ফোনে জানানো হবে।'],
                    ['question' => 'কী কী ডকুমেন্ট প্রয়োজন?', 'answer' => '<p>সদস্যতার আবেদনের জন্য নিম্নলিখিত ডকুমেন্ট প্রয়োজন:</p><ul><li>জাতীয় পরিচয়পত্রের স্ক্যান কপি</li><li>সাম্প্রতিক পাসপোর্ট সাইজের ছবি</li><li>যোগাযোগের তথ্য (ইমেইল, ফোন নম্বর, ঠিকানা)</li></ul>'],
                    ['question' => 'সদস্যতা ফি পেমেন্ট করতে হবে?', 'answer' => 'সদস্যতা সম্পূর্ণ বিনামূল্যে। কোনো ফি প্রদানের প্রয়োজন নেই।'],
                    ['question' => 'সদস্য হিসেবে আমার দায়িত্ব কী?', 'answer' => '<p>সদস্য হিসেবে আপনার দায়িত্ব:</p><ul><li>দলের নীতি ও আদর্শ মেনে চলা</li><li>দলের কার্যক্রমে সক্রিয় অংশগ্রহণ</li><li>দলের ভাবমূর্তি রক্ষায় সচেতন থাকা</li><li>অন্য সদস্যদের সাথে সৌহার্দ্যপূর্ণ আচরণ করা</li><li>নিয়মিত মিটিং ও অনুষ্ঠানে উপস্থিত থাকা</li></ul>']
                ] as $index => $faq)
                <div style="background:white;margin-bottom:15px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.05);overflow:hidden;">
                    <div class="faq-question" style="padding:15px 20px;font-weight:600;cursor:pointer;border-left:4px solid transparent;transition:all 0.3s;" onclick="toggleFaq({{ $index }})">
                        <i class="fas fa-chevron-right me-2" style="transition:transform 0.3s;" id="icon-{{ $index }}"></i> {{ $faq['question'] }}
                    </div>
                    <div class="faq-answer" id="answer-{{ $index }}" style="max-height:0;overflow:hidden;transition:max-height 0.3s ease-out;padding:0 20px;">
                        <div style="padding:15px 0;">{!! $faq['answer'] !!}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function toggleFaq(index) {
        const answer = document.getElementById('answer-' + index);
        const icon = document.getElementById('icon-' + index);
        const question = icon.parentElement;
        
        // Close all other FAQs
        document.querySelectorAll('.faq-answer').forEach((el, i) => {
            if (i !== index) {
                el.style.maxHeight = '0';
                document.getElementById('icon-' + i).style.transform = 'rotate(0)';
                document.getElementById('icon-' + i).parentElement.style.borderLeftColor = 'transparent';
            }
        });
        
        // Toggle current FAQ
        if (answer.style.maxHeight === '0px' || !answer.style.maxHeight) {
            answer.style.maxHeight = '500px';
            icon.style.transform = 'rotate(90deg)';
            question.style.borderLeftColor = '#F59E0B';
        } else {
            answer.style.maxHeight = '0';
            icon.style.transform = 'rotate(0)';
            question.style.borderLeftColor = 'transparent';
        }
    }
    
    // Auto-scroll to sections from URL hash
    if (window.location.hash) {
        const targetSection = document.querySelector(window.location.hash);
        if (targetSection) {
            window.scrollTo({
                top: targetSection.offsetTop - 100,
                behavior: 'smooth'
            });
        }
    }
</script>
@endsection