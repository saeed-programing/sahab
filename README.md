<div dir="rtl">

# سامانه دبیرستان سحاب - قم

برای استفاده از این سامانه، باید دستورات زیر را در **Command Prompt** سیستم یا هاست وارد کنید:
</div>

```
git clone https://github.com/saeed-programing/sahab.git
cd sahab
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```
<div dir="rtl">بعد از این دستورات، میتوانید با اطلاعات زیر وارد سامانه شوید:</div>

```
1. user: admin - pass: admin
2. user: test - pass: test
3. usre: teacher - pass: teacher
```
<div dir="rtl">به صورت فیک، تعداد 3 استاد و 3 کلاس و 250 دانش آموز ایجاد شده اند. فرمت کد ملی دانش آموزان به این صورت می باشد: 1111111111 - 1111111112 - 1111111113 - و... . (با این کد ملی می توانید در صفحه مربوط به حضور غیاب، تست خود را انجام دهید.
<br> باقی موارد (موارد انضباطی، روز های درسی و...) باید به صورت دستی و توسط خودتان ایجاد شود.</div>
