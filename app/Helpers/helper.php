<?php

use Morilog\Jalali\Jalalian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

if (!function_exists('toJalali')) {
    function toJalali($date)
    {
        if (!$date)
            return null;
        return Jalalian::fromCarbon(Carbon::parse($date))->format('Y/m/d');
    }
}

if (!function_exists('toGregorian')) {
    function toGregorian($jalaliDate)
    {
        if (!$jalaliDate)
            return null;

        [$y, $m, $d] = explode('/', $jalaliDate);
        $gDate = \Morilog\Jalali\CalendarUtils::toGregorian($y, $m, $d);

        // این خط فرمت رو دقیق می‌کنه:
        return sprintf('%04d-%02d-%02d', $gDate[0], $gDate[1], $gDate[2]);
    }
}

if (!function_exists('sendMessageByEitaa')) {
    function sendMessageByEitaa($title, $text, $disable_notification = 0, $reply_to_message_id = null, $pin = 0, $date = null, $viewCountForDelete = null)
    {
        $url = "https://eitaayar.ir/api/bot976:3f848dd5-d514-4aef-a600-0b2fb61899fe/sendMessage";

        $response = Http::post($url, [
            'chat_id' => '10945259',
            'title' => $title,
            'text' => $text,
            'disable_notification' => $disable_notification,
            'reply_to_message_id' => $reply_to_message_id,
            'pin' => $pin,
            'date' => $date,
            'viewCountForDelete' => $viewCountForDelete
        ]);

        return $response->json();
    }
}
