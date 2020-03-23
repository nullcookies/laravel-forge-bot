<?php

namespace App;

use App\Contracts\DialogContract;
use App\Exceptions\Dialogs\DialogClassNotFoundException;
use App\Exceptions\Dialogs\DialogClassWrongContractException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Dialog extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Returns last unfinished dialog.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeCurrent(Builder $query)
    {
        return $query->whereNull('finished_at')->latest();
    }

    /**
     * Finishes the dialog.
     *
     * @return Dialog
     */
    public function finish(): Dialog
    {
        $this->update([
            'finished_at' => Carbon::now(),
        ]);

        return $this;
    }

    /**
     * Determines if the dialog is finished.
     *
     * @return bool
     */
    public function finished(): bool
    {
        return $this->finished_at !== null;
    }

    /**
     * Checks class name of the dialog.
     *
     * @return bool
     * @throws DialogClassNotFoundException
     * @throws DialogClassWrongContractException
     */
    private function nameIsValid(): bool
    {
        if (!class_exists($this->name)) {
            throw new DialogClassNotFoundException($this->name);
        }

        $interfaces = class_implements($this->name);

        if (empty($interfaces) || !in_array(DialogContract::class, $interfaces)) {
            throw new DialogClassWrongContractException($this->name);
        }

        return true;
    }

    /**
     * Runs next step of the dialog.
     *
     * @param string $message
     *
     * @return void
     */
    public function nextStep(string $message): void
    {
        if ($this->nameIsValid()) {
            $this->name::next($this, $message);
        }
    }

    /**
     * User.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
