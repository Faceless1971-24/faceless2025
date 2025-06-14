@extends('frontend.layouts.master')

@section('title', 'সদস্য')

@section('content')

    <!-- Project Purpose Section -->
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <h2 class="mb-3" style="color:#006633;position:relative;display:inline-block;padding-bottom:10px;">
                    আমাদের লক্ষ্য
                    <span style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:80px;height:3px;background:#006633;"></span>
                </h2>
                <p class="lead mb-4" style="color:#333;">এই প্ল্যাটফর্মটি তৈরি করা হয়েছে আওয়ামী লীগের ঘরোয়া নেতৃত্বকে আরও দৃশ্যমান, সংযুক্ত এবং জনবান্ধব করে তোলার জন্য। 
                আমাদের উদ্দেশ্য শুধুমাত্র সদস্য সংগ্রহ নয়, বরং একটি স্বচ্ছ, আধুনিক ও শক্তিশালী সংগঠন গড়ে তোলা যেখানে নেতৃত্ব জনগণের আরও কাছে থাকবে।</p>
                <p style="color:#555;font-size:1rem;">
                    আপনার এলাকার নেতা কে? কীভাবে যোগাযোগ করবেন? কীভাবে সদস্য হবেন এবং দেশের জন্য কাজ করবেন — সব উত্তর এক জায়গায়।<br>
                    আসুন, আমরা সবাই একসাথে ভবিষ্যতের বাংলাদেশ গড়ে তুলি।
                </p>
            </div>
        </div>
    </div>

    <!-- Include your existing Hero Section here -->

    <!-- Admin Directory Section -->
    {{-- [Unchanged directory section remains here] --}}
    {{-- You already provided it perfectly. Keep as-is. --}}

    <!-- Call to Action -->
    <div style="background:linear-gradient(135deg,#006633,#004d26);color:white;padding:70px 0;position:relative;margin:80px 0;overflow:hidden;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h2 class="mb-4">দলের সদস্য হয়ে দেশের জন্য কিছু করুন</h2>
                    <p class="lead mb-4">নেতৃত্বের প্রথম ধাপ হল অংশগ্রহণ। আওয়ামী লীগের সদস্য হয়ে জনগণের পাশে দাঁড়ানোর সুযোগ নিন।</p>
                    @auth
                        <a href="{{ route('membership.apply') }}" class="btn btn-warning btn-lg px-5"
                           style="transition:all 0.3s;box-shadow:0 4px 15px rgba(0,0,0,0.2);"
                           onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.25)';"
                           onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)';">
                            <i class="fas fa-user-plus me-1"></i> সদস্য আবেদন করুন
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-5"
                           style="transition:all 0.3s;box-shadow:0 4px 15px rgba(0,0,0,0.2);"
                           onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.25)';"
                           onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)';">
                            <i class="fas fa-user-plus me-1"></i> রেজিস্ট্রেশন করুন
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    {{-- [Keep your Benefits section as-is. Already on point.] --}}

    <!-- FAQ Section -->
    <div class="container my-5">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 text-center">
                <h2 class="mb-4" style="position:relative;display:inline-block;padding-bottom:10px;">সাধারণ জিজ্ঞাসা
                    <span style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:80px;height:3px;background:#006633;"></span>
                </h2>
                <p class="lead">যে প্রশ্নগুলো আপনার মনে আসতেই পারে, সেগুলোর উত্তর এখানেই থাকলো।</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach([
                    ['question' => 'সদস্যতার জন্য কী কী ডকুমেন্ট দরকার?', 'answer' => '<ul><li>জাতীয় পরিচয়পত্রের কপি</li><li>এক কপি পাসপোর্ট সাইজ ছবি</li><li>আপনার ঠিকানা, ফোন ও ইমেইল</li></ul>'],
                    ['question' => 'সদস্যতা ফি দিতে হয়?', 'answer' => 'না, এটি সম্পূর্ণ বিনামূল্যে। আমরা চাই দেশের প্রত্যেক সচেতন নাগরিক সহজেই যুক্ত হতে পারেন।'],
                    ['question' => 'সদস্য হয়ে কী লাভ?', 'answer' => '<ul><li>আপনার এলাকার সমস্যা নিয়ে সরাসরি কথা বলার সুযোগ</li><li>দলের প্রশিক্ষণ ও উন্নয়ন প্রোগ্রামে অংশগ্রহণ</li><li>ভোটাধিকার ও নেতৃত্বে আসার পথ</li><li>আপনার অবদান অনুযায়ী স্বীকৃতি</li></ul>'],
                    ['question' => 'আমি কি একজন সাধারণ মানুষ হয়েও সদস্য হতে পারি?', 'answer' => 'অবশ্যই! আওয়ামী লীগ জনগণের দল। শিক্ষক, ছাত্র, কৃষক, কর্মচারী — সবাই আমাদের অংশ হতে পারেন। যোগ্যতা একটাই: দেশের প্রতি ভালোবাসা।']
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