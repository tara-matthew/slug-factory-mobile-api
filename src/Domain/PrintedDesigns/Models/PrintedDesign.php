<?php

namespace Domain\PrintedDesigns\Models;

use App\PrintedDesigns\Resources\PrintedDesignResource;
use Domain\Favourites\Models\Favourite;
use Domain\Filaments\Brands\Models\FilamentBrand;
use Domain\Filaments\Colours\Models\FilamentColour;
use Domain\Filaments\Materials\Models\FilamentMaterial;
use Domain\Images\Models\PrintedDesignMasterImage;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Domain\PrintedDesigns\Models\PrintedDesign
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Database\Factories\PrintedDesignFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintedDesign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintedDesign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintedDesign query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintedDesign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintedDesign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintedDesign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintedDesign whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintedDesign whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintedDesign whereUserId($value)
 * @method static PrintedDesign create($array = [])
 *
 * @property-read User $user
 *
 * @mixin Builder
 */
class PrintedDesign extends Model
{
    use HasFactory;

    protected $with = ['images'];

    protected $fillable = [
        'title',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PrintedDesignMasterImage::class);
    }

    public function filamentBrand(): BelongsTo
    {
        return $this->belongsTo(FilamentBrand::class);
    }

    public function filamentColour(): BelongsTo
    {
        return $this->belongsTo(FilamentColour::class);
    }

    public function filamentMaterial(): BelongsTo
    {
        return $this->belongsTo(FilamentMaterial::class);
    }

    public function favourites(): MorphMany
    {
        return $this->morphMany(Favourite::class, 'favouritable');
    }

    public function toResource(): PrintedDesignResource
    {
        return new PrintedDesignResource($this);
    }

    public function isFavourite(): bool // todo put into a trait
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        if (! $user) {
            return false;
        }

        return $this->favourites()->whereBelongsTo($user)->exists();
    }
}
