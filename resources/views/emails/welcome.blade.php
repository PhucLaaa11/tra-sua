@component('mail::message')
# Xin chào {{ $user->name }} 👋

Cảm ơn bạn đã đăng ký tại **Trà Sữa Ngon** 🍹.  
Chúc bạn có trải nghiệm tuyệt vời cùng hệ thống quản lý sản phẩm của chúng tôi.

@component('mail::button', ['url' => url('/')])
Bắt đầu ngay
@endcomponent

Thân ái,<br>
{{ config('app.name') }}
@endcomponent
