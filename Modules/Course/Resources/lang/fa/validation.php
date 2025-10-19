<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    "accepted"         => ":attribute باید پذیرفته شده باشد.",
    "active_url"       => "آدرس :attribute معتبر نیست",
    "after"            => ":attribute باید تاریخی بعد از :date باشد.",
    "alpha"            => ":attribute باید شامل حروف الفبا باشد.",
    "alpha_dash"       => ":attribute باید شامل حروف الفبا و عدد و خظ تیره(-) باشد.",
    "alpha_num"        => ":attribute باید شامل حروف الفبا و عدد باشد.",
    "array"            => ":attribute باید شامل آرایه باشد.",
    "before"           => ":attribute باید تاریخی قبل از :date باشد.",
    "between"          => array(
        "numeric" => ":attribute باید بین :min و :max باشد.",
        "file"    => ":attribute باید بین :min و :max کیلوبایت باشد.",
        "string"  => ":attribute باید بین :min و :max کاراکتر باشد.",
        "array"   => ":attribute باید بین :min و :max آیتم باشد.",
    ),
    "boolean"          => "The :attribute field must be true or false",
    "confirmed"        => ":attribute با تاییدیه مطابقت ندارد.",
    "date"             => ":attribute یک تاریخ معتبر نیست.",
    "date_format"      => ":attribute با الگوی :format مطاقبت ندارد.",
    "different"        => ":attribute و :other باید متفاوت باشند.",
    "digits"           => ":attribute باید :digits رقم باشد.",
    "digits_between"   => ":attribute باید بین :min و :max رقم باشد.",
    'dimensions' => ' :attribute تصویر پروفایل باید حداقل 100*100 px و حداکثر 500*500 px باشد.',
    "email"            => "فرمت :attribute معتبر نیست.",
    "exists"           => ":attribute انتخاب شده، معتبر نیست.",
    "image"            => ":attribute باید تصویر باشد.",
    "in"               => ":attribute انتخاب شده، معتبر نیست.",
    "integer"          => ":attribute باید نوع داده ای عددی (integer) باشد.",
    "ip"               => ":attribute باید IP آدرس معتبر باشد.",
    "max"              => array(
        "numeric" => ":attribute نباید بزرگتر از :max باشد.",
        "file"    => ":attribute نباید بزرگتر از :max کیلوبایت باشد.",
        "string"  => ":attribute نباید بیشتر از :max کاراکتر باشد.",
        "array"   => ":attribute نباید بیشتر از :max آیتم باشد.",
    ),
    "mimes"            => ":attribute باید یکی از فرمت های :values باشد.",
    "min"              => array(
        "numeric" => ":attribute نباید کوچکتر از :min باشد.",
        "file"    => ":attribute نباید کوچکتر از :min کیلوبایت باشد.",
        "string"  => ":attribute نباید کمتر از :min کاراکتر باشد.",
        "array"   => ":attribute نباید کمتر از :min آیتم باشد.",
    ),
    "not_in"           => ":attribute انتخاب شده، معتبر نیست.",
    "numeric"          => ":attribute باید شامل عدد باشد.",
    "regex"            => ":attribute یک فرمت معتبر نیست",
    "required"         => "فیلد :attribute الزامی است",
    "required_if"      => "فیلد :attribute هنگامی که :other برابر با :value است، الزامیست.",
    "required_with"    => ":attribute الزامی است زمانی که :values موجود است.",
    "required_with_all" => ":attribute الزامی است زمانی که :values موجود است.",
    "required_without" => ":attribute الزامی است زمانی که :values موجود نیست.",
    "required_without_all" => ":attribute الزامی است زمانی که :values موجود نیست.",
    "same"             => ":attribute و :other باید مانند هم باشند.",
    "size"             => array(
        "numeric" => ":attribute باید برابر با :size باشد.",
        "file"    => ":attribute باید برابر با :size کیلوبایت باشد.",
        "string"  => ":attribute باید برابر با :size کاراکتر باشد.",
        "array"   => ":attribute باسد شامل :size آیتم باشد.",
    ),
    "timezone"         => "The :attribute must be a valid zone.",
    "unique"           => ":attribute قبلا انتخاب شده است.",
    "url"              => "فرمت آدرس :attribute اشتباه است.",
    "exists_code"      => "کد ارسالی در سیستم وجود ندارد",
    "expire_code"      => "اعتبار کد ارسالی به پایان رسیده است",
    "used"             => "این کد قبلا مورد استفاده قرار گرفته است",
    "exists_phone"     => "چنین شماره ای در سیستم ثبت نشده است",
    "recaptcha"        => "کپچا اعتبار لازم را ندارد",
    "string" => ":attribute باید رشته باشد.",

    ##############

    'answer_added'    => 'پاسخ شما با موفقیت ثبت شد',
    'answer_notFound'    => 'پاسخ مورد نظر یافت نشد',
    'access_editAnswer'    => 'شما مجاز به ویرایش این پاسخ نیستید.',
    'access_deleteAnswer'    => 'شما مجاز به حذف این پاسخ نیستید.',
    'answer_edited'    => 'پاسخ شما با موفقیت ویرایش شد',
    'answer_deleted'    => 'پاسخ شما با موفقیت حذف شد',


    ##############

    'course_added'    => 'دوره با موفقیت ایجاد شد',
    'course_notFound'    => 'دوره مورد نظر پیدا نشد',
    'course_edited'    => 'دوره با موفقیت ویرایش شد',
    'course_editError'    => 'خطا در عملیات ویرایش دوره',
    'course_deleted'    => 'خطا در عملیات ویرایش دوره',
    'access_editCourse'    => 'شما مجاز به ویرایش این دوره نیستید',

    ##############

    'season_notFound'    => 'فصل مورد نظر پیدا نشد',
    'season_added'    => 'فصل با موفقیت ایجاد شد',
    'season_edited'    => 'فصل با موفقیت ویرایش شد',
    'season_deleted'    => 'فصل با موفقیت ویرایش شد',


    ##############

    'episode_added'    => 'قسمت جدید با موفقیت آپلود شد',
    'episode_notFound'    => 'قسمت مورد نظر پیدا نشد',
    'episode_edited'    => 'قسمت جدید با موفقیت ویرایش شد',
    'episode_deleted'    => 'قسمت با موفقیت حذف شد',
    'episode_noneQuestion'    => 'برای این قسمت پرسشی ثبت نشده است',


    ##############

    'question_notFound' => 'پرسش مورد نظر پیدا نشد',
    'question_added' => 'سوال شما با موفقیت ثبت شد.',
    'question_lockEdit' => 'این سوال دارای پاسخ است و قابل ویرایش نمی‌باشد.',
    'question_lockdelete' => 'این سوال دارای پاسخ است و قابل حذف نمی‌باشد.',
    'question_edited' => 'سوال با موفقیت ویرایش شد.',
    'question_deleted' => 'سوال با موفقیت حذف شد.',


    ##############


    'vote_deleted' => 'رای شما حذف شد.',
    'vote_updated' => 'رای شما به‌روزرسانی شد.',
    'vote_added' => 'رای شما ثبت شد',
    'vote_addError' => 'خطایی در ثبت رخ داد. لطفاً دوباره تلاش کنید.',

    ###############

    'login_error' => 'لطفا ابتدا وارد شوید',
    'access_showCourse'    => 'شما در این دوره ثبت نام نکرده اید و اجازه مشاهده آن را ندارید',
    'spaced'    => 'پیوند شما نباید دارای فاصله باشد لطفا از - استفاده کنید',






    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => array(
        'password' => [

            'regex' => 'در پسورد شما باید حداقل یک حرف بزرگ و یک حرف کوچک و یک عدد و یک کاراکتر خاص مثل (?,@,$,%,...) باشد',

        ],
        'userName' => [

            'regex' => 'نام کاربری باید انگیلیسی باشد',

        ],
    ),

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => array(
        "name" => "نام",
        "username" => "نام کاربری",
        "email" => "ایمیل",
        "first_name" => "نام",
        "last_name" => "نام خانوادگی",
        "password" => "رمز عبور",
        "password_confirmation" => "تاییدیه ی رمز عبور",
        "city" => "شهر",
        "country" => "کشور",
        "address" => "نشانی",
        "phone" => "تلفن",
        "mobile" => "تلفن همراه",
        "age" => "سن",
        "sex" => "جنسیت",
        "gender" => "جنسیت",
        "day" => "روز",
        "month" => "ماه",
        "year" => "سال",
        "hour" => "ساعت",
        "minute" => "دقیقه",
        "second" => "ثانیه",
        "title" => "عنوان",
        "text" => "متن",
        "content" => "محتوا",
        "description" => "توضیحات",
        "excerpt" => "گلچین کردن",
        "date" => "تاریخ",
        "time" => "زمان",
        "available" => "موجود",
        "size" => "اندازه",
        "body" => "متن",
        "imageUrl" => "تصویر",
        "videoUrl" => "آدرس ویدیو",
        "slug" => "نامک",
        "tags" => "تگ ها",
        "category" => "دسته",
        "story" => "داستان",
        'number' => 'شماره قسمت',
        'price' => 'قیمت دوره',
        'course_id' => 'دوره مورد نظر',
        'fileUrl' => 'آدرس فایل',
        'enSlug' => 'نامک انگلیسی',
        'percent' => 'درصد',
        'images' => 'تصویر',
        'userName' => 'نام کاربری',
        'comment' => 'متن نظرات شما',
        'coupon' => 'کد تخفیف',
        'amount' => 'مقدار تخفیف',
        'expire' => 'زمان تخفیف',
        'avatar' => 'تصویر پروفایل',
        'priority' => 'الویت',
        'message' => 'متن پیام'
    ),
);
