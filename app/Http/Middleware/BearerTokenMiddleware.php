<?php
namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BearerTokenMiddleware
{
    private $validToken = '2BH52wAHrAymR7wP3CASt'; // Geçerli token
    private $maxAttempts = 10; // Maksimum hatalı deneme
    private $blockDuration = 10; // Bloklama Süresi

    public function handle(Request $request, Closure $next)
    {
        $ipAddress = $request->ip(); // İstek yapan kullanıcıdan alınan IP adresi
        $token = $request->bearerToken(); // İstek başlığından alınan Bearer token

        if ($this->isBlocked($ipAddress)) {
            $this->logToDatabase($ipAddress, 'Çok fazla istek gönderildi. 10 dakika süreyle bloklandı.');
            return response()->json(['error' => 'Çok fazla istek.'], 429); // IP adresi engellendiğinde hata mesajı döner.
        }

        if ($token === $this->validToken) {
            $this->resetAttempts($ipAddress); // Token geçerliyse, IP adresinin deneme sayısını sıfırlar.
            return $next($request);
        }

        $this->incrementAttempts($ipAddress); // Token geçersizse, deneme sayısını artırır.
        $this->logToDatabase($ipAddress, 'Geçersiz token kullanımı.');
        return response()->json(['error' => 'Yetki hatası, geçersiz token.'], 401); // Geçersiz token olduğunda hata mesajı atar.
    }
    
    // Veritabanında blacklist tablosunda IP ve süre kontrolü işlemi
    private function isBlocked($ipAddress)
    {
        $blacklist = DB::table('blacklist')
            ->where('ip_address', $ipAddress)
            ->where('blocked_until', '>', now())
            ->exists();
        return $blacklist;
    }
    
    // IP adresi için toplam deneme sayısını kontrol işlemi
    private function incrementAttempts($ipAddress)
    {
        $cacheKey = 'attempts_' . $ipAddress;
        $attempts = Cache::get($cacheKey, 0);

        if ($attempts >= $this->maxAttempts - 1) {
            DB::table('blacklist')->updateOrInsert(
                ['ip_address' => $ipAddress],
                ['blocked_until' => now()->addMinutes($this->blockDuration)]
            );
            Cache::forget($cacheKey);
            $this->logToDatabase($ipAddress, 'Hatalı deneme sınırına ulaşıldı. 10 dakika süreyle bloklandı.');
        } else {
            Cache::put($cacheKey, $attempts + 1, now()->addMinutes($this->blockDuration));
        }
    }

    // Deneme sayısını sıfırlama işlemi
    private function resetAttempts($ipAddress)
    {
        Cache::forget('attempts_' . $ipAddress);
    }

    // Veritabanına log kaydetme işlemi
    private function logToDatabase($ipAddress, $message)
    {
        Log::create([
            'ip_address' => $ipAddress,
            'message' => $message,
        ]);
    }
}
