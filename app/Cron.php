<?php

namespace Richardds\ServerAdmin;

use Illuminate\Database\Eloquent\Model;
use Richardds\ServerAdmin\Core\Tasks\CronInterval;
use Richardds\ServerAdmin\Scopes\Local\Toggles;

/**
 * Richardds\ServerAdmin\Cron
 *
 * @property int $id
 * @property string $minute
 * @property string $hour
 * @property string $day
 * @property string $month
 * @property string $weekday
 * @property string $command
 * @property int $uid
 * @property string|null $description
 * @property bool $enabled
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron enabled()
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron whereCommand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron whereHour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron whereMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Richardds\ServerAdmin\Cron whereWeekday($value)
 * @mixin \Eloquent
 */
class Cron extends Model
{
    use Toggles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'minute',
        'hour',
        'day',
        'month',
        'weekday',
        'command',
        'uid',
        'enabled',
    ];

    /**
     * The attributes that should be visible in serialization.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'minute',
        'hour',
        'day',
        'month',
        'weekday',
        'command',
        'uid',
        'enabled',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'minute' => 'string',
        'hour' => 'string',
        'day' => 'string',
        'month' => 'string',
        'weekday' => 'string',
        'command' => 'string',
        'uid' => 'integer',
        'enabled' => 'boolean',
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    protected function parseFieldInterval(string $fieldInterval): ?string
    {
        if ('*' == $fieldInterval) {
            return null;
        }

        return trim($fieldInterval);
    }

    /**
     * @param string $minute
     */
    protected function setMinuteAttribute(string $minute): void
    {
        $this->attributes['minute'] = $this->parseFieldInterval($minute);
    }

    /**
     * @param $value
     * @return string
     */
    protected function getMinuteAttribute($value): string
    {
        return $value ?? '*';
    }

    /**
     * @param string $hour
     */
    protected function setHourAttribute(string $hour): void
    {
        $this->attributes['hour'] = $this->parseFieldInterval($hour);
    }

    /**
     * @param $value
     * @return string
     */
    protected function getHourAttribute($value): string
    {
        return $value ?? '*';
    }

    /**
     * @param string $day
     */
    protected function setDayAttribute(string $day): void
    {
        $this->attributes['day'] = $this->parseFieldInterval($day);
    }

    /**
     * @param $value
     * @return string
     */
    protected function getDayAttribute($value): string
    {
        return $value ?? '*';
    }

    /**
     * @param string $month
     */
    protected function setMonthAttribute(string $month): void
    {
        $this->attributes['month'] = $this->parseFieldInterval($month);
    }

    /**
     * @param $value
     * @return string
     */
    protected function getMonthAttribute($value): string
    {
        return $value ?? '*';
    }

    /**
     * @param string $weekday
     */
    protected function setWeekdayAttribute(string $weekday): void
    {
        $this->attributes['weekday'] = $this->parseFieldInterval($weekday);
    }

    /**
     * @param $value
     * @return string
     */
    protected function getWeekdayAttribute($value): string
    {
        return $value ?? '*';
    }

    /**
     * @param CronInterval $interval
     */
    public function setInterval(CronInterval $interval): void
    {
        $this->day = $interval->getDay();
        $this->minute = $interval->getMinute();
        $this->hour = $interval->getHour();
        $this->day = $interval->getDay();
        $this->weekday = $interval->getWeekday();
    }

    /**
     * @return CronInterval
     */
    public function getInterval(): CronInterval
    {
        return new CronInterval(
            $this->minute ?? '*',
            $this->hour ?? '*',
            $this->day ?? '*',
            $this->month ?? '*',
            $this->weekday ?? '*'
        );
    }
}
