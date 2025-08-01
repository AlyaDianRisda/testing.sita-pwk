<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nim',
        'nip',
        'wa_dos',
        'tipe_dos',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Interact with the user's first name.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function type(): Attribute
    {
        $types = ["user", "admin", "dosen"];
        return new Attribute(
            get: fn($value) => ["user", "admin", "dosen"][$value],
            set: fn($value) => is_string($value) ? array_search($value, $types, true) : $value
        );
    }

    public function kuotaBimbingan()
    {
        return $this->hasMany(ProposalKuotaDosen::class, 'dosen_id');
    }

    public function kuotaBimbinganPeriode($periodId) // hapus jika tidak perlu
    {
        return $this->hasOne(ProposalKuotaDosen::class, 'dosen_id')
            ->where('period_id', $periodId);

        /*
            // Ambil kuota_tersisa dari relasi ProposalKuotaDosen
            $kuotaBerjalan = 0;
            if ($item->dosen && $item->period) {
                $kuotaModel    = $item->dosen->kuotaBimbinganPeriode($item->period->id)->first();
                $kuotaBerjalan = $kuotaModel?->kuota_tersisa ?? 0;
            }
         */
    }


    public static function getTableColumns() // Fix ordering
    {
        return Schema::getColumnListing((new self)->getTable());
    }

}
