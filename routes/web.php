<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Middleware\CheckRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

// Route::get('/test-mail', function (Request $request) {
//     $to = $request->query('to');
//     $cc = $request->query('cc');
//     $bcc = $request->query('bcc');

//     abort_unless(is_string($to) && filter_var($to, FILTER_VALIDATE_EMAIL), 422, 'Parameter `to` wajib berupa email yang valid.');

//     $ccList = collect(preg_split('/[\s,;]+/', (string) $cc) ?: [])
//         ->filter()
//         ->values()
//         ->all();

//     $bccList = collect(preg_split('/[\s,;]+/', (string) $bcc) ?: [])
//         ->filter()
//         ->values()
//         ->all();

//     foreach (array_merge($ccList, $bccList) as $email) {
//         abort_unless(filter_var($email, FILTER_VALIDATE_EMAIL), 422, "Email tidak valid: {$email}");
//     }

//     try {
//         Mail::send([], [], function ($message) use ($to, $ccList, $bccList): void {
//             $message
//                 ->to($to)
//                 ->subject('Test Email - ' . config('app.name'))
//                 ->html(
//                     '<p>Ini adalah email test dari route Laravel.</p>' .
//                         '<p>Dikirim pada: ' . now()->format('d M Y H:i:s') . ' WIB</p>'
//                 );

//             if ($ccList !== []) {
//                 $message->cc($ccList);
//             }

//             if ($bccList !== []) {
//                 $message->bcc($bccList);
//             }
//         });
//     } catch (Throwable $exception) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Gagal mengirim email.',
//             'error' => $exception->getMessage(),
//             'mailer' => config('mail.default'),
//             'host' => config('mail.mailers.smtp.host'),
//             'port' => config('mail.mailers.smtp.port'),
//             'username' => config('mail.mailers.smtp.username'),
//         ], 500);
//     }

//     return response()->json([
//         'success' => true,
//         'message' => 'Email test berhasil diproses.',
//         'mailer' => config('mail.default'),
//         'host' => config('mail.mailers.smtp.host'),
//         'port' => config('mail.mailers.smtp.port'),
//         'to' => $to,
//         'cc' => $ccList,
//         'bcc' => $bccList,
//     ]);
// });
